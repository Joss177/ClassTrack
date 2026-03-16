# ============================================================
# horarioAutomatic.py
# Llamado por CakePHP — recibe ruta del PDF, devuelve JSON.
# NO toca la base de datos. La inserción la hace CakePHP.
#
# BUGS CORREGIDOS (confirmados con pruebas sobre el PDF real):
#   1. El grupo viene ANTES del label "GRUPO/GRADO:" en el texto
#      extraído, no después. El regex original nunca encontraba nada.
#   2. La tabla tiene columnas dobles (cada día ocupa 2 columnas).
#      Los datos reales están en índices 2,4,6,8,10 — no en 1,2,3,4,5.
#
# pip install pdfplumber
# ============================================================

import pdfplumber
import re
import sys
import json

sys.stdout.reconfigure(encoding="utf-8")

DIAS = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"]
# Índices reales de cada día en la tabla (columnas dobles)
INDICES_DIAS = [2, 4, 6, 8, 10]

COLORES = [
    "#f87171", "#34d399", "#fbbf24", "#60a5fa",
    "#a78bfa", "#f472b6", "#22d3ee", "#0ea5e9",
    "#10b981", "#ef4444", "#d97706", "#4b5563",
    "#16a34a", "#3b82f6", "#e879f9", "#f97316",
]

TITULOS = r"(?:Lic\.|MC\.|Ing\.|Lcda\.|Dra?\.|MEC\.|M\.|MTIC\.|Dr\.)"


def limpiar(texto):
    if not texto:
        return ""
    return re.sub(r"\s+", " ", texto.strip())


def normalizar_hora(hora):
    """Garantiza HH:MM con cero inicial."""
    hora = hora.strip()
    partes = hora.split(":")
    if len(partes) == 2:
        return partes[0].zfill(2) + ":" + partes[1].zfill(2)
    return hora


def normalizar_docente(nombre):
    """Colapsa espacios múltiples para evitar duplicados en BD."""
    if not nombre:
        return "Sin asignar"
    return re.sub(r"\s+", " ", nombre.strip())


def extraer_nombre_grupo(texto):
    """
    FIX: En este PDF el nombre del grupo viene ANTES del label.
    Texto real extraído: '...TIID2-1\\nGRUPO/GRADO:\\n...'
    Se busca en ambos sentidos para cubrir cualquier variación.
    """
    # Caso real: nombre ANTES del label
    m = re.search(
        r"([A-Z0-9][A-Z0-9\-]{3,})\s*\n?\s*GRUPO/GRADO:",
        texto
    )
    if m:
        return m.group(1).strip()

    # Fallback: nombre DESPUÉS del label (otros PDFs)
    m = re.search(
        r"GRUPO/GRADO:\s*\n?\s*([A-Z0-9][A-Z0-9\-]{3,})",
        texto
    )
    if m:
        return m.group(1).strip()

    return "SIN_GRUPO"


def extraer_pagina(page):
    texto = page.extract_text() or ""

    # ── Nombre del grupo ──────────────────────
    nombre_grupo = extraer_nombre_grupo(texto)

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
            docente = normalizar_docente(m.group(3))
            if clave not in materias_raw:
                materias_raw[clave] = {"nombre": nombre, "docente": docente}

    # ── Tabla del horario ─────────────────────
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

            h_ini = normalizar_hora(hm.group(1))
            h_fin = normalizar_hora(hm.group(2))

            # FIX: usar índices 2,4,6,8,10 — no 1,2,3,4,5
            for dia_num, idx in enumerate(INDICES_DIAS, start=1):
                celda = limpiar(fila[idx]) if idx < len(fila) else ""
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
                    "dia_semana":  dia_num,   # 1=Lunes … 5=Viernes
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

        print(json.dumps(resultado, ensure_ascii=False))

    except Exception as e:
        print(json.dumps({"error": str(e)}, ensure_ascii=False))


if __name__ == "__main__":
    main()
