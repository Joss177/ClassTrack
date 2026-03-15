import cv2
import numpy as np

'''
Para empezar con lo de openCV primero se tiene que entender que las imagenes son un np array de pixeles con valores. 
las cordenadas en un np array es y,x,z. y & x  altura y la anchura de nuestro canvas respectivamente y z que colores va a tener.
1 siendo escala de grises y 3 es BGR(RGB pero asi esta acomodado porque xd )
'''

# dtype=np.uint8 asegura que los valores sean de 0 a 255 (8 bits). Si quiro mas resolucion solo cambio el tipo de bits 
imagen = np.zeros((300, 500, 3), dtype=np.uint8)


'''
Si queremos acceder a cierto rango de pixeles en np lo hacemos con slicing 
y pasamos en rango de 8 bits que color queremos mostrar. seguimos usando (y,x)
'''

# aqui basicamente estamos diceindo que pinte de azul esa area
imagen[0:100, 0:100] = [255, 0, 0] 

'''
cunado usemos alguna funcion de openCV ahi si se usa (x,y) no como en un np array que es (y,x)
A esta funcion se le pasa el np array que habimaos creado. La misma funcion dice que hace
'''
cv2.line(imagen, (0, 0), (500, 300), (0, 255, 0), 5)
cv2.circle(imagen,(250,150),10,(0,0,255),3) #esto solo dibuja un circulo. lafuncion se explica por su sola


'''importante
En Funciones de OpenCV (cv2.circle, cv2.rectangle): Usamos lógica de geometría (x,y).

En Acceso Directo (Numpy) (imagen[y, x]): Usamos lógica de matriz (Fila, Columna).
'''
cv2.imshow("Papu ventana", imagen)
cv2.waitKey(0) # Espera a que presiones cualquier tecla para cerrar
cv2.destroyAllWindows()