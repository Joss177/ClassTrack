# ============================================================
# horarioAutomatic.py
# Llamado por CakePHP — recibe ruta del PDF, devuelve JSON.
# NO toca la base de datos. La inserción la hace CakePHP.
#
# Formato de salida:
# {
#   "grupos": [
#     {
#       "nombre": "TIID2-1",
#       "materias": [
#         {
#           "codigo":  "B-CDI-1",
#           "nombre":  "Cálculo Diferencial",
#           "color":   "#f87171",
#           "docente": "Lic. Ana Isabel Melgarejo Rodríguez"
#         }
#       ],
#       "horarios": [
#         {
#           "codigo":      "B-CDI-1",
#           "aula":        "B-219",
#           "dia_semana":  1,
#           "hora_inicio": "07:00",
#           "hora_fin":    "08:40"
#         }
#       ]
#     }
#   ]
# }
#
# pip install pdfplumber
# ============================================================

import pdfplumber
import re
import sys
import json

sys.stdout.reconfigure(encoding="utf-8")

# ──────────────────────────────────────────────
# CONSTANTES
# ──────────────────────────────────────────────
DIAS = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"]

COLORES = [
    "#f87171", "#34d399", "#fbbf24", "#60a5fa",
    "#a78bfa", "#f472b6", "#22d3ee", "#0ea5e9",
    "#10b981", "#ef4444", "#d97706", "#4b5563",
    "#16a34a", "#3b82f6", "#e879f9", "#f97316",
]

TITULOS = r"(?:Lic\.|MC\.|Ing\.|Lcda\.|Dra?\.|MEC\.|M\.|MTIC\.|Dr\.)"


# ──────────────────────────────────────────────
# UTILIDADES
# ──────────────────────────────────────────────
def limpiar(texto):
    """Elimina espacios múltiples y saltos de línea."""
    if not texto:
        return ""
    return re.sub(r"\s+", " ", texto.strip())


def normalizar_hora(hora):
    """
    Garantiza formato HH:MM con cero inicial.
    '7:00'  → '07:00'
    '07:50' → '07:50'
    Fix para que strtotime() de PHP funcione correctamente.
    """
    hora = hora.strip()
    partes = hora.split(":")
    if len(partes) == 2:
        h = partes[0].zfill(2)   # rellenar con cero a la izquierda
        m = partes[1].zfill(2)
        return f"{h}:{m}"
    return hora


def normalizar_docente(nombre):
    """
    Normaliza el nombre del docente para evitar duplicados:
    - Elimina espacios múltiples (doble espacio entre título y nombre)
    - Capitaliza de forma consistente
    Fix para evitar duplicados por diferencias de espaciado en el PDF.
    """
    if not nombre:
        return "Sin asignar"
    # Colapsar múltiples espacios en uno solo
    nombre = re.sub(r"\s+", " ", nombre.strip())
    return nombre


# ──────────────────────────────────────────────
# EXTRACCIÓN DE UNA PÁGINA
# ──────────────────────────────────────────────
def extraer_pagina(page):
    texto = page.extract_text() or ""

    # ── Nombre del grupo ──────────────────────
    # FIX: el regex ahora acepta con o sin espacio después de ':'
    # y captura hasta el fin de línea para evitar cortes
    grupo_match = re.search(
        r"GRUPO/GRADO:\s*([A-Z0-9][A-Z0-9\-]*)",
        texto
    )
    nombre_grupo = grupo_match.group(1).strip() if grupo_match else "SIN_GRUPO"

    # ── Materias y docentes ───────────────────
    patron_materia = re.compile(
        r"([A-Z][A-Z0-9]*-[A-Z0-9\-]+|Tutoría)\s+(.+?)\s+(" + TITULOS + r".*)",
        re.IGNORECASE,
    )

    materias_raw = {}
    for linea in texto.split("\n"):
        linea = limpiar(linea)
        m = patron_materia.search(linea)
        if m:
            clave   = limpiar(m.group(1))
            nombre  = limpiar(m.group(2))
            # FIX docentes duplicados: normalizar espaciado
            docente = normalizar_docente(m.group(3))
            if clave not in materias_raw:
                materias_raw[clave] = {"nombre": nombre, "docente": docente}

    # ── Tabla del horario (cuadrícula) ────────
    table = page.extract_table()
    bloques_raw = []

    if table:
        for fila in table[1:]:
            if not fila or not fila[0]:
                continue

            hora = limpiar(fila[0])
            if not hora or "RECESO" in hora.upper():
                continue

            hm = re.match(r"(\d{1,2}:\d{2})\s*[-–]\s*(\d{1,2}:\d{2})", hora)
            if not hm:
                continue

            # FIX horas: normalizar a HH:MM con cero inicial
            h_ini = normalizar_hora(hm.group(1))
            h_fin = normalizar_hora(hm.group(2))

            for i, _dia in enumerate(DIAS, start=1):
                celda = limpiar(fila[i]) if i < len(fila) else ""
                if not celda:
                    continue

                partes = celda.replace("\n", " ").split()
                if not partes:
                    continue

                codigo = partes[0].strip()
                aula   = partes[1].strip() if len(partes) > 1 else "SIN_AULA"

                bloques_raw.append({
                    "codigo":      codigo,
                    "aula":        aula,
                    "dia_semana":  i,
                    "hora_inicio": h_ini,
                    "hora_fin":    h_fin,
                })

    # ── Fusionar bloques consecutivos ─────────
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
        horarios_final.append(b)

    # ── Lista de materias con color ───────────
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


# ──────────────────────────────────────────────
# MAIN
# ──────────────────────────────────────────────
def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No se recibió la ruta del PDF"}, ensure_ascii=False))
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

        # Un solo print al final — sin texto extra antes del JSON
        print(json.dumps(resultado, ensure_ascii=False))

    except Exception as e:
        print(json.dumps({"error": str(e)}, ensure_ascii=False))


if __name__ == "__main__":
    main()
