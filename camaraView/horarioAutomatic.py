# ============================================================
# horarioAutomatic.py  —  Procesa TODOS los grupos del PDF
# Devuelve JSON UTF-8 compatible con controller CakePHP 3.8
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
# El PDF tiene caracteres intercalados en ciertos docentes.
# Clave: fragmento único del texto corrupto → Valor: nombre real.
DOCENTES_CORRUPTOS = {
    "ESMC.":       "MC.",                         # E-CTER-1: prefijo extra
    "NEJSOe":      "Selene Elizabeth García Gamez", # T-HSMC-1 grupos 1-2
    "NEJMOC D. LE": "MC. Lizette Peralta Partida",  # T-HSMC-1 grupo 3
    "NEJMOC D. CE": "MC. Carlos Zamudio Togo",      # T-HSMC-1 grupos 4-5
    "PEÑMOC.":     "MC. Lizette Peralta Partida",   # T-LEAD-2 grupos 5-8
    "RRODLrLaO.":  "Dra. Ismaylia Saucedo Ugalde",  # E-EMDS-2 grupos 5-8
    "RROMLCL.O":   "MC. Iliana Amabely Silva Hernández", # E-EMDS-2 grupo 9
    "APT-FT":      "Dr. Luis Javier Mena Camare",   # APT-FT texto fusionado
    "TAI-FT":      "Ing. Jose Cruz Paredes Magaña",  # TAI-FT texto fusionado
    "ICIMOCS.":    "MC. Roberto Antonio Martínez Thompson",  # E-AWOS-2 grupos 5-6
    "ICIDOr.S":    "Dr. Ramón Patricio Velázquez Cuadras",   # E-AWOS-2 grupos 7-8
    "ERNDEr.T":    "Dr. Ramón Patricio Velázquez Cuadras",   # TAI-FT grupo 13
    "ERNInEgT.":   "Ing. Jose Cruz Paredes Magaña",          # TAI-FT grupos 10-12
    "NODLrOaG.":   "Dra. Vanessa Guadalupe Félix Aviña",     # APT-FT grupos 10-11
    "NODLrO.":     "Dr. Luis Javier Mena Camare",            # APT-FT grupos 12-13
}

def corregir_docente(texto):
    """Corrige docentes con texto corrupto del PDF."""
    if not texto:
        return "Sin asignar"
    for patron, correcto in DOCENTES_CORRUPTOS.items():
        if patron in texto:
            # Caso especial ESMC.: solo reemplazar el prefijo
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
    """Elimina número de horas al final y colapsa espacios."""
    if not nombre:
        return "Sin asignar"
    nombre = re.sub(r"\s+\d+$", "", limpiar(nombre))
    return limpiar(nombre)


def extraer_nombre_grupo(texto):
    # Nombre ANTES del label (confirmado en este PDF)
    m = re.search(r"([A-Z0-9][A-Z0-9\-]{2,})\s*\n\s*GRUPO/GRADO:", texto)
    if m:
        return m.group(1).strip()
    # Fallback: nombre DESPUÉS del label
    m = re.search(r"GRUPO/GRADO:\s*\n?\s*([A-Z0-9][A-Z0-9\-]{2,})", texto)
    if m:
        return m.group(1).strip()
    return "SIN_GRUPO"


def extraer_periodo(texto):
    m = re.search(r"PERIODO:\s*(.+)", texto, re.IGNORECASE)
    if m:
        return limpiar(m.group(1))
    return "SIN_PERIODO"


def extraer_tutor(texto):
    m = re.search(r"Nombre del Tutor:\s*(.+)", texto)
    if m:
        return limpiar(m.group(1))
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
                    # Tec. Móvil ocupa dos tokens
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
            # Formato confirmado: [codigo, nombre, docente, horas]
            if len(fila) >= 3:
                codigo_raw  = limpiar(fila[0])
                nombre_raw  = limpiar(fila[1])
                docente_raw = normalizar_docente(limpiar(fila[2]) if fila[2] else "")

                # Solo filas que parecen código de materia
                if not re.match(r"^([A-Z][A-Z0-9]*-[A-Z0-9\-]+|Tutoría)$",
                                 codigo_raw, re.IGNORECASE):
                    continue
                if not nombre_raw or nombre_raw.lower() in ("asignatura", "clave"):
                    continue

                # Normalizar Tutoría
                if codigo_raw.upper() in ("TUTORÍA", "TUTORIA"):
                    codigo_raw = "Tutoría"

                # Nombre distinto al código para evitar UNIQUE KEY doble en BD
                if codigo_raw == "Tutoría" and nombre_raw.upper() in ("TUTORÍA", "TUTORIA"):
                    nombre_raw = "Tutoría Grupal"

                # Corregir texto corrupto del PDF usando el mapa global
                docente_raw = corregir_docente(docente_raw)

                materias_raw[codigo_raw] = {
                    "nombre":  nombre_raw,
                    "docente": docente_raw,
                }

    # ── Corrección: "Tec." suelto → "Tec. Móvil" ─────────────
    for b in bloques_raw:
        if b["aula"] == "Tec.":
            b["aula"] = "Tec. Móvil"

    # ── Herencia de aulas para celdas sin aula ────────────────
    for i in range(len(bloques_raw)):
        if bloques_raw[i]["aula"] == "SIN_AULA":
            for j in range(len(bloques_raw)):
                if (bloques_raw[i]["codigo"] == bloques_raw[j]["codigo"]
                        and bloques_raw[j]["aula"] != "SIN_AULA"):
                    bloques_raw[i]["aula"] = bloques_raw[j]["aula"]
                    break

    # ── Fusionar bloques consecutivos ─────────────────────────
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

    # ── Añadir docente a cada bloque ──────────────────────────
    for bloque in horarios_final:
        info = materias_raw.get(bloque["codigo"], {})
        bloque["docente"] = info.get("docente", "Sin asignar")

    # ── Seguro: materias huérfanas ────────────────────────────
    for cod in set(b["codigo"] for b in horarios_final):
        if cod not in materias_raw:
            materias_raw[cod] = {
                "nombre":  "MATERIA NO REGISTRADA",
                "docente": "Sin asignar",
            }

    # ── Lista final de materias con color ─────────────────────
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
    resultado = {"grupos": []}

    try:
        with pdfplumber.open(pdf_path) as pdf:
            for num, page in enumerate(pdf.pages, start=1):
                try:
                    datos = extraer_pagina(page)
                    if datos and datos["nombre"] != "SIN_GRUPO":
                        resultado["grupos"].append(datos)
                except Exception as e:
                    resultado["grupos"].append({
                        "nombre":   f"ERROR_PAGINA_{num}",
                        "materias": [],
                        "horarios": [],
                        "error":    str(e),
                    })

        # Salida para CakePHP — UTF-8 sin escapes unicode
        print(json.dumps(resultado, ensure_ascii=False))

        # Archivo de debug local
        with open("res.json", "w", encoding="utf-8") as f:
            json.dump(resultado, f, ensure_ascii=False, indent=4)

    except Exception as e:
        print(json.dumps({"error": str(e)}, ensure_ascii=False))
        sys.exit(1)


if __name__ == "__main__":
    main()
