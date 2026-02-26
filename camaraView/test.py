import cv2
import numpy as np

#Esto inicializa la camara. segun la camara que se quiera prender es el numero que se le pasa. 
# tecnicamente correcto esto crea un buffer de imagenes y si se llena va lageado
video = cv2.VideoCapture(0)

# Eto es solo por si no  se abre la camara
if not video.isOpened():
    print("Error: No se pudo acceder a la cámara.")
    exit()

while True:
    '''
    la funcion read() da 2 valores. el priermo es un booleno si se capturo el frame (es lo que hace que haya movimento)
    el segundo son los colres de ese frame da una matriz de colores para ser mas exacto.
    '''
    # 2. .read() nos da:
    # 'ret': Un booleano (True si capturó el frame, False si falló)
    # 'frame': La matriz BGR (píxeles) del momento exacto
    ret, frame = video.read()
    
    #para esto sirve saber si se captiuro el frame o no. para si hay una sobrecarga de trabajo se cierre el programa 
    if not ret:
        break
    


    
    alto, ancho, colores = frame.shape # .shape lo que hace es que nos da el np array de la imagen. y como ya sabemos se maneja (y,x,z)
    
    #para saber el centro de la imagen
    #el operador // es una divison normal pero que redondea hacia abajo el numero resultante. no podemos pasar medios numeros en cordenadas de pixeles
    centro_x= ancho // 2
    centro_y = alto // 2
    
    cv2.circle(frame, (centro_x, centro_y), 50, (0, 255, 0), 2)

    cv2.imshow('El papu misterioso', frame)

    #esto es como la manera estandar de poner que se cierre la vista
    #el 1 es que espera un milisengundo para ver si detecta un teclado y si presionas la q se sale del programa. q es 0xFF pero su valor en ord(q)
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

#se libera la camara 
video.release()
cv2.destroyAllWindows()