import gspread
from google.oauth2.service_account import Credentials
import os
from datetime import datetime


SCOPES = [
    "https://www.googleapis.com/auth/spreadsheets",
    "https://www.googleapis.com/auth/drive"
]
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
RUTA_CREDENCIALES = os.path.join(BASE_DIR, "pasedelista-488923-6b6f15fe036d.json")

credenciales = Credentials.from_service_account_file(
    RUTA_CREDENCIALES,
    scopes=SCOPES
)


cliente = gspread.authorize(credenciales)

hoja = cliente.open("asistencia").sheet1


def registrar_asistencia(nombre_detectado=None, minutos=0, fechaAmanuela=None, finalizar_clase=False):

    # -------- Fecha --------
    if fechaAmanuela:
        fecha_hoy = fechaAmanuela
    else:
        fecha_hoy = datetime.now().strftime("%d-%m-%Y")

    encabezados = hoja.row_values(1)

    # Crear encabezado si no existe
    if not encabezados:
        hoja.update_cell(1, 1, "Nombre")
        encabezados = hoja.row_values(1)

    # Crear columnas de fecha si no existen
    if fecha_hoy not in encabezados:
        hoja.update_cell(1, len(encabezados) + 1, fecha_hoy)
        hoja.update_cell(1, len(encabezados) + 2, "Min")
        encabezados = hoja.row_values(1)

    ajustar_columnas()

    col_fecha = encabezados.index(fecha_hoy) + 1

    # -------------------------------------------------
    #    Registrar asistencia (si hay nombre)
    # -------------------------------------------------
    if nombre_detectado and not finalizar_clase:

        try:
            celda = hoja.find(nombre_detectado)
            fila_alumno = celda.row
        except:
            hoja.append_row([nombre_detectado])
            celda = hoja.find(nombre_detectado)
            fila_alumno = celda.row

        valor_actual = hoja.cell(fila_alumno, col_fecha).value

        if not valor_actual:
            hoja.update_cell(fila_alumno, col_fecha, "A")
            hoja.update_cell(fila_alumno, col_fecha + 1, minutos)
            colorear_celda(fila_alumno, col_fecha, "A")

    # -------------------------------------------------
    #         Marcar faltas automáticamente
    # -------------------------------------------------
    if finalizar_clase:

        alumnos = hoja.col_values(1)

        for fila in range(2, len(alumnos) + 1):

            valor = hoja.cell(fila, col_fecha).value

            if not valor:
                hoja.update_cell(fila, col_fecha, "F")
                colorear_celda(fila, col_fecha, "F")

def ajustar_columnas():
    sheet_id = hoja._properties['sheetId']

    requests = [
        #columna 1 (nombre) mas ancha
        {
         "updateDimensionProperties": {
             "range":{
                 "sheetId": sheet_id,
                 "dimension": "COLUMNS",
                 "startIndex": 0,
                 "endIndex": 1 
             },
             "properties": {
                 "pixelSize": 300
             },
             "fields": "pixelSize"
         }
        },
        #columnas de la 2 en andelante mas pequenitatatata
        {
            "updateDimensionProperties": {
             "range":{
                 "sheetId": sheet_id,
                 "dimension": "COLUMNS",
                 "startIndex": 1,
                 "endIndex": 50
             },
             "properties": {
                 "pixelSize": 20
             },
             "fields": "pixelSize"
         }

        }
    ]
    hoja.spreadsheet.batch_update({"requests": requests})

def colorear_celda(fila, columna, tipo):

    sheet_id = hoja._properties['sheetId']

    if tipo == "A":
        color = {"red": 0.6, "green": 0.9, "blue": 0.6}
    elif tipo == 'F':
        color = {"red": 1, "green": 0.6, "blue": 0.6}
    else:
        return

    request = {
        "repeatCell": {
            "range": {
                "sheetId": sheet_id,
                "startRowIndex": fila - 1,
                "endRowIndex": fila,
                "startColumnIndex": columna - 1,
                "endColumnIndex": columna
            },
            "cell": {
                "userEnteredFormat": {
                    "backgroundColor": color
                }
            },
            "fields": "userEnteredFormat.backgroundColor"
        }
    }

    hoja.spreadsheet.batch_update({"requests": [request]})
