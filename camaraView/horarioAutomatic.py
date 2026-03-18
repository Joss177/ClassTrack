import pdfplumber
import re
import sys
import json

sys.stdout.reconfigure(encoding="utf-8")

DIAS = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"]
# Índices reales de cada día en la tabla del horario
INDICES_DIAS = [2, 4, 6, 8, 10]

COLORES = [
    "#f87171", "#34d399", "#fbbf24", "#60a5fa",
    "#a78bfa", "#f472b6", "#22d3ee", "#0ea5e9",
    "#10b981", "#ef4444", "#d97706", "#4b5563",
    "#16a34a", "#3b82f6", "#e879f9", "#f97316",
]

# Títulos extendidos para limpieza
TITULOS = r"(?:Lic\.|MC\.|Ing\.|Lcda\.|Lcd\.|Dra?\.|MEC\.|M\.|MTIC\.|Dr\.)"


def limpiar(texto):
    if not texto: return ""
    return re.sub(r"\s+", " ", texto.strip())


def normalizar_hora(hora):
    hora = hora.strip().replace(".", ":")
    partes = hora.split(":")
    if len(partes) == 2:
        return partes[0].zfill(2) + ":" + partes[1].zfill(2)
    return hora


def normalizar_docente(nombre):
    """Limpia títulos al inicio y números basura al final."""
    if not nombre: return "Sin asignar"
    nombre = re.sub(r'\s+\d+$', '', nombre)
    nombre = re.sub(r"^" + TITULOS + r"\s*", "", nombre, flags=re.IGNORECASE)
    return re.sub(r"\s+", " ", nombre.strip())


def es_aula_valida(texto):
    """Regla estricta: Una letra, un guion, tres números. Ej. B-310"""
    return bool(re.match(r"^[A-Z]-\d{3}$", texto.strip()))


def extraer_nombre_grupo(texto):
    m = re.search(r"([A-Z0-9][A-Z0-9\-]{3,})\s*\n?\s*GRUPO/GRADO:", texto)
    if m: return m.group(1).strip()
    m = re.search(r"GRUPO/GRADO:\s*\n?\s*([A-Z0-9][A-Z0-9\-]{3,})", texto)
    if m: return m.group(1).strip()
    return "SIN_GRUPO"


def extraer_pagina(page):
    texto = page.extract_text() or ""
    nombre_grupo = extraer_nombre_grupo(texto)

    all_tables = page.extract_tables()
    materias_raw = {}
    bloques_raw = []

    for table in all_tables:
        for fila in table:
            if not fila or not fila[0]: continue
            
            celda_0 = limpiar(fila[0])
            
           
            hm = re.match(r"(\d{1,2}:\d{2})\s*[-–]\s*(\d{1,2}:\d{2})", celda_0)
            if hm and "RECESO" not in celda_0.upper():
                h_ini = normalizar_hora(hm.group(1))
                h_fin = normalizar_hora(hm.group(2))
                
                for dia_num, idx in enumerate(INDICES_DIAS, start=1):
                    celda_clase = limpiar(fila[idx]) if idx < len(fila) else ""
                    if not celda_clase: continue
                    
                    partes = celda_clase.replace("\n", " ").split()
                    if not partes: continue
                    
                    codigo = partes[0].strip()
                    aula = partes[1].strip() if len(partes) > 1 else "SIN_AULA"
                    
                    bloques_raw.append({
                        "codigo": codigo,
                        "aula": aula,
                        "dia_semana": dia_num,
                        "hora_inicio": h_ini,
                        "hora_fin": h_fin,
                    })
                continue 

            
            if "CLAVE" in celda_0.upper(): continue # Ignorar encabezado
            
            m_clave = re.match(r"^([A-Z][A-Z0-9]*-[A-Z0-9\-]+|Tutoría)$", celda_0, re.IGNORECASE)
            if m_clave and len(fila) >= 3:
                clave = m_clave.group(1)
                
                # FILTROS DE BASURA: Ignorar códigos de doc y aulas
                if "DPE-RG" in clave.upper() or es_aula_valida(clave): 
                    continue
                
                if clave.upper() == "TUTORÍA" or clave.upper() == "TUTORIA": clave = "Tutoría" 
                
                nombre_mat = limpiar(fila[1])
                docente_mat = normalizar_docente(fila[2])
                
                # Omitir si nombre o docente están vacíos
                if not nombre_mat or "Sin asignar" in docente_mat:
                    continue
                    
                materias_raw[clave] = {"nombre": nombre_mat, "docente": docente_mat}

    # ── 2. Fallback por Texto (Por si alguna materia no estaba en tabla) ──
    patron_materia = re.compile(r"^([A-Z][A-Z0-9]*-[A-Z0-9\-]+|Tutoría)\s+(.+)", re.IGNORECASE)
    for linea in texto.split("\n"):
        linea = limpiar(linea)
        m = patron_materia.search(linea)
        if m:
            clave = limpiar(m.group(1))
            if "DPE-RG" in clave or es_aula_valida(clave): continue
            
            if clave in materias_raw: continue # Ya se extrajo perfecta desde la tabla
            
            resto_linea = m.group(2)
            m_titulo = re.search(TITULOS, resto_linea, re.IGNORECASE)
            
            if m_titulo:
                inicio_titulo = m_titulo.start()
                nombre = limpiar(resto_linea[:inicio_titulo])
                docente = normalizar_docente(resto_linea[inicio_titulo:])
            else:
                m_transicion = re.search(r"([A-Z\s]+?)\s+([A-Z][a-z].*)", resto_linea)
                if m_transicion:
                    nombre = limpiar(m_transicion.group(1))
                    docente = normalizar_docente(m_transicion.group(2))
                else:
                    nombre = limpiar(resto_linea)
                    docente = "Sin asignar"
            
            materias_raw[clave] = {"nombre": nombre, "docente": docente}

    # ── 3. Corrección: Herencia de Aulas ──
    for i in range(len(bloques_raw)):
        if bloques_raw[i]["aula"] == "SIN_AULA":
            for j in range(len(bloques_raw)):
                if bloques_raw[i]["codigo"] == bloques_raw[j]["codigo"] and bloques_raw[j]["aula"] != "SIN_AULA":
                    bloques_raw[i]["aula"] = bloques_raw[j]["aula"]
                    break

    bloques_raw.sort(key=lambda x: (x["dia_semana"], x["hora_inicio"]))
    horarios_final = []
    for b in bloques_raw:
        if horarios_final:
            ult = horarios_final[-1]
            if (ult["codigo"] == b["codigo"] and ult["aula"] == b["aula"] and 
                ult["dia_semana"] == b["dia_semana"] and ult["hora_fin"] == b["hora_inicio"]):
                ult["hora_fin"] = b["hora_fin"]
                continue
        horarios_final.append(b)

    # ── 5. Seguro contra Materias Huérfanas (Protege CakePHP) ──
    codigos_en_horario = set(b["codigo"] for b in horarios_final)
    for cod in codigos_en_horario:
        if cod not in materias_raw:
            materias_raw[cod] = {"nombre": "MATERIA NO REGISTRADA", "docente": "Sin asignar"}

    materias_lista = []
    for idx, (clave, info) in enumerate(materias_raw.items()):
        materias_lista.append({
            "codigo":  clave,
            "nombre":  info["nombre"],
            "color":   COLORES[idx % len(COLORES)],
            "docente": info["docente"],
        })

    return {
        "nombre":   nombre_grupo,
        "materias": materias_lista,
        "horarios": horarios_final,
    }


def main():
    if len(sys.argv) < 2:
        print("Error: No se recibió la ruta del PDF.")
        sys.exit(1)

    pdf_path = sys.argv[1]
    resultado = {"grupos": []}

    try:
        with pdfplumber.open(pdf_path) as pdf:
            for num, page in enumerate(pdf.pages, start=1):
                try:
                    datos = extraer_pagina(page)
                    resultado["grupos"].append(datos)
                except Exception as e:
                    resultado["grupos"].append({
                        "nombre":   f"ERROR_PAGINA_{num}",
                        "materias": [],
                        "horarios": [],
                        "error":    str(e),
                    })

        with open("res.json", "w", encoding="utf-8") as f:
            json.dump(resultado, f, ensure_ascii=False, indent=4)
            
        print("¡Éxito! El archivo 'res.json' ha sido generado correctamente.")

    except Exception as e:
        print(f"Error al abrir el PDF: {str(e)}")

if __name__ == "__main__":
    main()