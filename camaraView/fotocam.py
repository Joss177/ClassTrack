import cv2
import time
import os

output_path = r"C:\xampp\htdocs\ClassTrack\src\webroot\img\testImg\fotocam.jpg"

# Verificar que la carpeta existe
folder = os.path.dirname(output_path)
print(f"Carpeta existe: {os.path.exists(folder)}")
print(f"Ruta: {folder}")

# Usar directo el índice de Iriun (índice 1)
cap = cv2.VideoCapture(1)
ret, frame = cap.read()

if not ret:
    print("ERROR: No se encontró Iriun en índice 1, probando índice 2...")
    cap.release()
    cap = cv2.VideoCapture(2)
    ret, frame = cap.read()
    if not ret:
        print("ERROR: Iriun no encontrado. Asegúrate de que esté conectado.")
        cap.release()
        exit()

print("Iriun conectado, iniciando captura...")

while True:
    ret, frame = cap.read()
    if ret:
        resultado = cv2.imwrite(output_path, frame)
        print(f"Foto guardada: {resultado} -> {output_path}")
    else:
        print("ERROR: No se pudo leer el frame")
    time.sleep(2)  # Cada 5 segundos

cap.release()