# ============================================================
# horarioAutomatic.py
# Llamado por CakePHP — recibe ruta del PDF, devuelve JSON.
# NO toca la base de datos. La inserción la hace CakePHP.
#
# COMPORTAMIENTO CONFIRMADO CON EL PDF REAL:
#   1. El nombre del grupo viene ANTES del label "GRUPO/GRADO:"
#      Ej: "TI8-3\nGRUPO/GRADO:\n"
#   2. La tabla tiene columnas dobles (color + dato por día).
#      Los datos reales están en índices 2,4,6,8,10.
#   3. Cada celda tiene formato "CODIGO\nAULA" separado por \n.
#   4. Solo procesa el grupo TI8-3 y sale al encontrarlo.
#
# Uso: python3 horarioAutomatic.py /ruta/del/archivo.pdf
# Salida: JSON por stdout, CakePHP lo lee con proc_open/shell_exec
#
# pip install pdfplumber
# ============================================================

import pdfplumber
import re
import sys
import json

sys.stdout.reconfigure(encoding="utf-8")

# ── Constantes ────────────────────────────────────────────────
GRUPO_OBJETIVO = "TI8-3"

INDICES_DIAS = [2, 4, 6, 8, 10]   # columnas reales en la tabla (doble columna)

COLORES = [
    "#f87171", "#34d399", "#fbbf24", "#60a5fa",
    "#a78bfa", "#f472b6", "#22d3ee", "#0ea5e9",
    "#10b981", "#ef4444", "#d97706", "#4b5563",
    "#16a34a", "#3b82f6", "#e879f9", "#f97316",
]

TITULOS = r"(?:Lic\.|MC\.|Ing\.|Lcda\.|Dra?\.|MEC\.|M\.|MTIC\.|Dr\.)"


# ── Helpers ───────────────────────────────────────────────────
def limpiar(texto):
    if not texto:
        return ""
    return re.sub(r"\s+", " ", texto.strip())


def normalizar_hora(hora):
    """Garantiza formato HH:MM con cero inicial."""
    hora = hora.strip()
    partes = hora.split(":")
    if len(partes) == 2:
        return partes[0].zfill(2) + ":" + partes[1].zfill(2)
    return hora


def normalizar_docente(nombre):
    """Colapsa espacios múltiples y elimina el número de horas al final."""
    if not nombre:
        return "Sin asignar"
    nombre = re.sub(r"\s+", " ", nombre.strip())
    # Eliminar el número de horas totales que aparece al final: " 5", " 6", etc.
    nombre = re.sub(r"\s+\d+$", "", nombre)
    return nombre


def extraer_nombre_grupo(texto):
    """
    El nombre del grupo viene ANTES del label en este PDF.
    Texto real: "...TI8-3\nGRUPO/GRADO:\n..."
    También cubre el caso inverso por si cambia el formato.
    """
    # Caso confirmado: nombre ANTES del label
    m = re.search(r"([A-Z0-9][A-Z0-9\-]{2,})\s*\n\s*GRUPO/GRADO:", texto)
    if m:
        return m.group(1).strip()

    # Fallback: nombre DESPUÉS del label
    m = re.search(r"GRUPO/GRADO:\s*\n?\s*([A-Z0-9][A-Z0-9\-]{2,})", texto)
    if m:
        return m.group(1).strip()

    return "SIN_GRUPO"


# ── Extracción principal ───────────────────────────────────────
def extraer_pagina_ti83(page):
    texto = page.extract_text() or ""

    # ── 1. Verificar que es TI8-3 ────────────────────────────
    nombre_grupo = extraer_nombre_grupo(texto)
    if nombre_grupo != GRUPO_OBJETIVO:
        return None

    # ── 2. Tutor y período ───────────────────────────────────
    tutor = ""
    m = re.search(r"Nombre del Tutor:\s*(.+)", texto)
    if m:
        tutor = normalizar_docente(m.group(1))

    periodo = ""
    m = re.search(r"PERIODO:\s*(.+)", texto)
    if m:
        periodo = limpiar(m.group(1))

    # ── 3. Materias y docentes desde el catálogo al pie ──────
    #    Líneas limpias: "CODIGO  NOMBRE COMPLETO  Dr./MC./... Apellido N"
    #    Líneas corruptas (APT-FT, TAI-FT): texto fusionado del PDF.
    #    Estrategia: primero intentar el regex estricto línea a línea;
    #    si falla, buscar el título académico EN CUALQUIER POSICIÓN de la línea
    #    y tomar todo lo que va después como docente.
    TITULOS_RE = re.compile(
        r"((?:Lic|MC|Ing|Lcda|Dra?|MEC|MTIC|Dr)\.\s+[A-ZÁÉÍÓÚÜÑ][^\d\n]{5,})",
        re.IGNORECASE
    )

    # Códigos válidos de materias (solo los que aparecen en la tabla)
    CODIGOS_VALIDOS = re.compile(
        r"^([A-Z][A-Z0-9]*-[A-Z0-9\-]+|Tutoría)$"
    )

    materias_raw = {}
    lineas = texto.split("\n")
    for linea in lineas:
        linea = limpiar(linea)
        # La primera palabra debe ser un código válido de materia
        partes = linea.split()
        if not partes:
            continue
        codigo = partes[0]
        if not CODIGOS_VALIDOS.match(codigo):
            continue
        # Buscar el título académico en la línea
        m_doc = TITULOS_RE.search(linea)
        if m_doc:
            docente = normalizar_docente(m_doc.group(1))
            # El nombre de la materia es lo que queda entre el código y el docente
            nombre = limpiar(linea[len(codigo):m_doc.start()])
            # Limpiar basura de caracteres mezclados (letras minúsculas intercaladas)
            nombre = re.sub(r"[a-záéíóúüñ]", "", nombre).strip()
            nombre = re.sub(r"\s{2,}", " ", nombre).strip()
            if codigo not in materias_raw and len(nombre) > 2:
                materias_raw[codigo] = {"nombre": nombre, "docente": docente}

    # ── Fallback para materias cuyo texto en el PDF está corrompido ──
    # APT-FT y TAI-FT tienen caracteres mezclados en este PDF concreto.
    # Tutoría no lleva título académico así que no la captura el regex.
    FALLBACK = {
        "Tutoría": {"nombre": "Tutoría",
                    "docente": "Dra. Ismaylia Saucedo Ugalde"},
        "APT-FT":  {"nombre": "Administración de Proyectos de TI",
                    "docente": "Dr. Luis Javier Mena Camare"},
        "TAI-FT":  {"nombre": "Tecnologías de Aplicaciones en Internet",
                    "docente": "Ing. Jose Cruz Paredes Magaña"},
    }
    for clave, info in FALLBACK.items():
        if clave not in materias_raw:
            materias_raw[clave] = info
    table = page.extract_table()
    bloques_raw = []
    aulas_set   = set()

    if table:
        for fila in table[1:]:          # fila[0] = encabezado "Horario/Lunes/..."
            if not fila or not fila[0]:
                continue

            hora = limpiar(fila[0])
            if not hora or "RECESO" in hora.upper():
                continue

            hm = re.match(r"(\d{1,2}:\d{2})\s*[-–]\s*(\d{1,2}:\d{2})", hora)
            if not hm:
                continue

            h_ini = normalizar_hora(hm.group(1))
            h_fin = normalizar_hora(hm.group(2))

            # Índices 2,4,6,8,10 = Lunes,Martes,Miércoles,Jueves,Viernes
            for dia_num, idx in enumerate(INDICES_DIAS, start=1):
                celda = fila[idx] if idx < len(fila) else None
                celda = limpiar(celda) if celda else ""
                if not celda:
                    continue

                # Celda tiene formato "CODIGO\nAULA" o "CODIGO AULA"
                partes = celda.replace("\n", " ").split()
                if not partes:
                    continue

                codigo = partes[0].strip()
                aula   = partes[1].strip() if len(partes) > 1 else "SIN_AULA"

                # Aulas con dos palabras: "Tec. Móvil"
                if len(partes) > 2 and partes[1] in ("Tec.", "B-", "D-", "CC"):
                    aula = partes[1] + " " + partes[2]

                aulas_set.add(aula)

                bloques_raw.append({
                    "codigo":      codigo,
                    "aula":        aula,
                    "dia_semana":  dia_num,   # 1=Lunes … 5=Viernes
                    "hora_inicio": h_ini,
                    "hora_fin":    h_fin,
                })

    # ── 5. Fusionar bloques consecutivos ─────────────────────
    #    Si misma materia, aula y día y hora_fin == hora_inicio siguiente → fusionar
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
        horarios_final.append(dict(b))

    # ── 6. Añadir docente a cada bloque del horario ──────────
    for bloque in horarios_final:
        info = materias_raw.get(bloque["codigo"], {})
        bloque["docente"] = info.get("docente", "Sin asignar")

    # ── 7. Lista final de materias con color ─────────────────
    materias_lista = []
    for idx, (clave, info) in enumerate(materias_raw.items()):
        materias_lista.append({
            "codigo":  clave,
            "nombre":  info["nombre"],
            "color":   COLORES[idx % len(COLORES)],
            "docente": info["docente"],
        })

    # ── 8. Lista de aulas únicas ─────────────────────────────
    aulas_lista = [{"nombre": a} for a in sorted(aulas_set)]

    # ── 9. Lista de docentes únicos ──────────────────────────
    docentes_set = set()
    for mat in materias_lista:
        if mat["docente"] and mat["docente"] != "Sin asignar":
            docentes_set.add(mat["docente"])
    docentes_lista = [{"nombre": d} for d in sorted(docentes_set)]

    # ── 10. JSON final ───────────────────────────────────────
    return {
        "nombre":   nombre_grupo,   # llave "nombre" — igual que $grupoData['nombre'] en CakePHP
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

    pdf_path = sys.argv[1]

    try:
        with pdfplumber.open(pdf_path) as pdf:
            for num, page in enumerate(pdf.pages, start=1):
                try:
                    datos = extraer_pagina_ti83(page)
                    if datos is not None:
                        # Encontrado — imprimir JSON con estructura {"grupos": [...]} que espera CakePHP
                        print(json.dumps({"grupos": [datos]}, ensure_ascii=False))
                        sys.exit(0)
                except Exception as e:
                    print(json.dumps(
                        {"error": f"Error procesando página {num}: {str(e)}"},
                        ensure_ascii=False
                    ))
                    sys.exit(1)

        # No se encontró el grupo en ninguna página
        print(json.dumps(
            {"error": f"Grupo {GRUPO_OBJETIVO} no encontrado en el PDF"},
            ensure_ascii=False
        ))
        sys.exit(1)

    except Exception as e:
        print(json.dumps({"error": str(e)}, ensure_ascii=False))
        sys.exit(1)


if __name__ == "__main__":
    main()
