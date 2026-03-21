# ============================================================
# horarioAutomatic.py  —  Procesa TODOS los grupos del PDF
# Devuelve JSON UTF-8 compatible con controller CakePHP 3.8
# Estructurado por AULAS en lugar de grupos.
# pip install pdfplumber
# ============================================================
import pdfplumber
import re
import sys
import json
import io

# Forzar UTF-8 en stdout — necesario para Windows (Anaconda/XAMPP) y Linux
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding="utf-8", errors="replace")

INDICES_DIAS = [2, 4, 6, 8, 10]   # columnas reales en tabla doble

COLORES = [
    "#f87171", "#34d399", "#fbbf24", "#60a5fa",
    "#a78bfa", "#f472b6", "#22d3ee", "#0ea5e9",
    "#10b981", "#ef4444", "#d97706", "#4b5563",
    "#16a34a", "#3b82f6", "#e879f9", "#f97316",
]

# ── Mapa de corrección de docentes corruptos en el PDF ───────
DOCENTES_CORRUPTOS = {
    "ESMC.":       "MC.",                         
    "NEJSOe":      "Selene Elizabeth García Gamez", 
    "NEJMOC D. LE": "MC. Lizette Peralta Partida",  
    "NEJMOC D. CE": "MC. Carlos Zamudio Togo",      
    "PEÑMOC.":     "MC. Lizette Peralta Partida",   
    "RRODLrLaO.":  "Dra. Ismaylia Saucedo Ugalde",  
    "RROMLCL.O":   "MC. Iliana Amabely Silva Hernández", 
    "APT-FT":      "Dr. Luis Javier Mena Camare",   
    "TAI-FT":      "Ing. Jose Cruz Paredes Magaña",  
    "ICIMOCS.":    "MC. Roberto Antonio Martínez Thompson",  
    "ICIDOr.S":    "Dr. Ramón Patricio Velázquez Cuadras",   
    "ERNDEr.T":    "Dr. Ramón Patricio Velázquez Cuadras",   
    "ERNInEgT.":   "Ing. Jose Cruz Paredes Magaña",          
    "NODLrOaG.":   "Dra. Vanessa Guadalupe Félix Aviña",     
    "NODLrO.":     "Dr. Luis Javier Mena Camare",            
}

def corregir_docente(texto):
    """Corrige docentes con texto corrupto del PDF."""
    if not texto:
        return "Sin asignar"
    for patron, correcto in DOCENTES_CORRUPTOS.items():
        if patron in texto:
            if patron == "ESMC.":
                return texto.replace("ESMC.", "MC.").strip()
            return correcto
    return texto

# ── Helpers ───────────────────────────────────────────────────
def limpiar(texto):
    if not texto:
        return ""
    return re.sub(r"\s+", " ", texto.strip())

def normalizar_hora(hora):
    hora = hora.strip().replace(".", ":")
    partes = hora.split(":")
    if len(partes) == 2:
        return partes[0].zfill(2) + ":" + partes[1].zfill(2)
    return hora

def normalizar_docente(nombre):
    if not nombre:
        return "Sin asignar"
    nombre = re.sub(r"\s+\d+$", "", limpiar(nombre))
    return limpiar(nombre)

def extraer_nombre_grupo(texto):
    m = re.search(r"([A-Z0-9][A-Z0-9\-]{2,})\s*\n\s*GRUPO/GRADO:", texto)
    if m: return m.group(1).strip()
    m = re.search(r"GRUPO/GRADO:\s*\n?\s*([A-Z0-9][A-Z0-9\-]{2,})", texto)
    if m: return m.group(1).strip()
    return "SIN_GRUPO"

def extraer_periodo(texto):
    m = re.search(r"PERIODO:\s*(.+)", texto, re.IGNORECASE)
    if m: return limpiar(m.group(1))
    return "SIN_PERIODO"

def extraer_tutor(texto):
    m = re.search(r"Nombre del Tutor:\s*(.+)", texto)
    if m: return limpiar(m.group(1))
    return ""

# ── Extracción por página ─────────────────────────────────────
def extraer_pagina(page):
    texto      = page.extract_text() or ""
    nombre_grupo = extraer_nombre_grupo(texto)
    periodo      = extraer_periodo(texto)
    tutor        = extraer_tutor(texto)

    all_tables   = page.extract_tables()
    materias_raw = {}   # {codigo: {nombre, docente}}
    bloques_raw  = []
    aulas_set    = set()

    for table in all_tables:
        for fila in table:
            if not fila or not fila[0]:
                continue

            celda_0 = limpiar(fila[0])

            # ── Tabla 0: filas de horario ──────────────────────
            hm = re.match(r"(\d{1,2}:\d{2})\s*[-–]\s*(\d{1,2}:\d{2})", celda_0)
            if hm and "RECESO" not in celda_0.upper():
                h_ini = normalizar_hora(hm.group(1))
                h_fin = normalizar_hora(hm.group(2))

                for dia_num, idx in enumerate(INDICES_DIAS, start=1):
                    celda = limpiar(fila[idx]) if idx < len(fila) else ""
                    if not celda:
                        continue
                    partes = celda.replace("\n", " ").split()
                    if not partes:
                        continue

                    codigo = partes[0].strip()
                    if len(partes) >= 3 and partes[1] == "Tec.":
                        aula = "Tec. Móvil"
                    else:
                        aula = partes[1].strip() if len(partes) > 1 else "SIN_AULA"

                    bloques_raw.append({
                        "codigo":      codigo,
                        "aula":        aula,
                        "dia_semana":  dia_num,
                        "hora_inicio": h_ini,
                        "hora_fin":    h_fin,
                    })
                continue

            # ── Tabla 1: catálogo de materias ──────────────────
            if len(fila) >= 3:
                codigo_raw  = limpiar(fila[0])
                nombre_raw  = limpiar(fila[1])
                docente_raw = normalizar_docente(limpiar(fila[2]) if fila[2] else "")

                if not re.match(r"^([A-Z][A-Z0-9]*-[A-Z0-9\-]+|Tutoría)$", codigo_raw, re.IGNORECASE):
                    continue
                if not nombre_raw or nombre_raw.lower() in ("asignatura", "clave"):
                    continue

                if codigo_raw.upper() in ("TUTORÍA", "TUTORIA"):
                    codigo_raw = "Tutoría"
                if codigo_raw == "Tutoría" and nombre_raw.upper() in ("TUTORÍA", "TUTORIA"):
                    nombre_raw = "Tutoría Grupal"

                docente_raw = corregir_docente(docente_raw)

                materias_raw[codigo_raw] = {
                    "nombre":  nombre_raw,
                    "docente": docente_raw,
                }

    for b in bloques_raw:
        if b["aula"] == "Tec.":
            b["aula"] = "Tec. Móvil"

    for i in range(len(bloques_raw)):
        if bloques_raw[i]["aula"] == "SIN_AULA":
            for j in range(len(bloques_raw)):
                if (bloques_raw[i]["codigo"] == bloques_raw[j]["codigo"]
                        and bloques_raw[j]["aula"] != "SIN_AULA"):
                    bloques_raw[i]["aula"] = bloques_raw[j]["aula"]
                    break

    bloques_raw.sort(key=lambda x: (x["dia_semana"], x["hora_inicio"]))
    horarios_final = []
    for b in bloques_raw:
        if horarios_final:
            ult = horarios_final[-1]
            if (ult["codigo"]      == b["codigo"]
                    and ult["aula"]       == b["aula"]
                    and ult["dia_semana"] == b["dia_semana"]
                    and ult["hora_fin"]   == b["hora_inicio"]):
                ult["hora_fin"] = b["hora_fin"]
                continue
        if b["aula"] != "SIN_AULA":
            aulas_set.add(b["aula"])
        horarios_final.append(dict(b))

    for bloque in horarios_final:
        info = materias_raw.get(bloque["codigo"], {})
        bloque["docente"] = info.get("docente", "Sin asignar")

    for cod in set(b["codigo"] for b in horarios_final):
        if cod not in materias_raw:
            materias_raw[cod] = {
                "nombre":  "MATERIA NO REGISTRADA",
                "docente": "Sin asignar",
            }

    docentes_set   = set()
    materias_lista = []
    for idx, (clave, info) in enumerate(materias_raw.items()):
        doc = info["docente"]
        materias_lista.append({
            "codigo":  clave,
            "nombre":  info["nombre"],
            "color":   COLORES[idx % len(COLORES)],
            "docente": doc,
        })
        if doc and doc != "Sin asignar":
            docentes_set.add(doc)

    aulas_lista    = [{"nombre": a} for a in sorted(aulas_set)]
    docentes_lista = [{"nombre": d} for d in sorted(docentes_set)]

    return {
        "nombre":   nombre_grupo,
        "tutor":    tutor,
        "periodo":  periodo,
        "aulas":    aulas_lista,
        "docentes": docentes_lista,
        "materias": materias_lista,
        "horarios": horarios_final,
    }


# ── Entry point ───────────────────────────────────────────────
def main():
    if len(sys.argv) < 2:
        print(json.dumps(
            {"error": "Uso: python3 horarioAutomatic.py /ruta/archivo.pdf"},
            ensure_ascii=False
        ))
        sys.exit(1)

    pdf_path  = sys.argv[1]
    
    # Aquí guardamos los datos originales por grupo
    datos_grupos = []

    try:
        with pdfplumber.open(pdf_path) as pdf:
            for num, page in enumerate(pdf.pages, start=1):
                try:
                    datos = extraer_pagina(page)
                    if datos and datos["nombre"] != "SIN_GRUPO":
                        datos_grupos.append(datos)
                except Exception as e:
                    # En caso de error de lectura, lo ignoramos para que no rompa el resto del PDF
                    pass

        # =====================================================================
        # TRANSFORMADOR MÁGICO: Convertir de Grupos a Aulas
        # =====================================================================
        aulas_dict = {}

        for grupo in datos_grupos:
            nombre_grupo = grupo["nombre"]
            
            # Mapas rápidos para obtener el nombre de la materia y el docente por su código
            mapa_materias = {m["codigo"]: m["nombre"] for m in grupo["materias"]}
            mapa_docentes = {m["codigo"]: m["docente"] for m in grupo["materias"]}

            for h in grupo["horarios"]:
                nombre_aula = h["aula"]
                
                # Omitimos si el aula es SIN_AULA, ya que no representa un espacio físico
                if nombre_aula == "SIN_AULA":
                    continue
                
                # Si el aula no existe en el diccionario general, la inicializamos
                if nombre_aula not in aulas_dict:
                    aulas_dict[nombre_aula] = {
                        "nombre": nombre_aula,
                        "horarios": []
                    }
                
                # Agregamos la clase a esa aula, incluyendo el nombre del grupo al que pertenece
                aulas_dict[nombre_aula]["horarios"].append({
                    "dia_semana": h["dia_semana"],
                    "hora_inicio": h["hora_inicio"],
                    "hora_fin": h["hora_fin"],
                    "grupo": nombre_grupo,
                    "codigo": h["codigo"],
                    "materia": mapa_materias.get(h["codigo"], "MATERIA NO REGISTRADA"),
                    "docente": mapa_docentes.get(h["codigo"], "Sin asignar")
                })

        # Estructura final que espera CakePHP y el archivo res.json
        resultado_final = {"aulas": []}
        
        # Ordenamos las aulas alfabéticamente (B-201, B-202, etc.)
        for aula_nombre in sorted(aulas_dict.keys()):
            aula_data = aulas_dict[aula_nombre]
            
            # Ordenamos los horarios de esa aula por día de la semana y hora de inicio
            aula_data["horarios"].sort(key=lambda x: (x["dia_semana"], x["hora_inicio"]))
            
            resultado_final["aulas"].append(aula_data)
        # =====================================================================

        # Salida por consola para CakePHP — UTF-8 sin escapes unicode
        print(json.dumps(resultado_final, ensure_ascii=False))

        # Archivo de debug local
        with open("res.json", "w", encoding="utf-8") as f:
            json.dump(resultado_final, f, ensure_ascii=False, indent=4)

    except Exception as e:
        print(json.dumps({"error": str(e)}, ensure_ascii=False))
        sys.exit(1)


if __name__ == "__main__":
    main()