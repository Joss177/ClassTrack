import cv2
import numpy as np
from insightface.app import FaceAnalysis


'''La libreria que decidi usar para reconocer la caras fue insightface porque fue la que mas me gusto del video tutorial XD

Primero se inicia la app que vamos a usar aka: que modelo de reconocimiento facial. decidi usar entre buffalo_l o buffalo_s
la diferencia que tienen es que uno es lento y el otro rapido. Pero pues tambien tiene que ver que tan bueno es el modelo
mas adelante en el proyecto tengo que ver que priorizo precicion o rapidez
'''
# 'name' define el modlo que vamos a usar y providers no se que hace pero tiene que estar ahiu
app = FaceAnalysis(name='buffalo_s', providers=['CPUExecutionProvider'])

#prepare hace que el motor del modelo se configure. el primero es basicamente que vamos a usar si cpu o gpu o lo primerop que este disponible
#el otro es como va a redimensionar la imagen el modelo para procesarla. solo acepta numeros multiplos de 32
'''det_size
Tamaño (ejemplos),  Velocidad (FPS),     Sensibilidad,Notas
"(320, 320)",       Muy Rápido           ,Baja,Solo detectará caras que estén cerca de la cámara. Caras al fondo del cuarto serán invisibles.
"(640, 640)",       Equilibrado          ,Media,El estándar. Funciona bien para la mayoría de webcams y detecta caras a distancias normales.
"(1280, 1280)",     Lento                ,Detectará hasta al vecino que se asoma por la ventana al fondo. Consume mucha CPU/GPU.
'''
app.prepare(ctx_id=0, det_size=(640, 640))

video = cv2.VideoCapture(0)

if not video.isOpened():
    print("Error: No se pudo acceder a la cámara.")
    exit()

while True:

    ret, frame = video.read()
    
    if not ret: 
        break
    
    #esto lo que gace es devolver una lista de objetos de caras que tiene los sieguentes atributos
    '''
    bbox:      es un np array con 4 valores [x_min, y_min, x_max, y_max] esto es lo que delimita donde esta una cara
    kps:       es una lista con las cordenas de puntos claves de la cara y estan en este orden 0 Ojo Izquierdo, 1 Ojo Derecho 2 Punta de la Nariz, 3 Comisura izquierda de la boca, 4 Comisura derecha de la boca
    det_score: que tan seguro esta el modelo que lo que esta detectando es una cara es un float de 0 a 1
    gender:    da	0 o 1 (Hombre/Mujer)	
    age:	   da una estiamcion de la edad de la cara
    embedding: da nn vector de 512 números. Esto es lo que hace que vea si cara 1 y 2 son las mismass. con esto es lo que voy a hacer el detector de caras
    pose:	   da angulos (Pitch, Yaw, Roll)	Para saber si la persona está mirando hacia arriba, abajo o a los lados.
    landmark_3d_68:	68 puntos en 3D	Para hacer filtros tipo Instagram o snap. Con esto puedo hacer pendejadas
    '''#si es que hay mas nada mas has face.key() y gg te salen todos los datos accsibless
    faces = app.get(frame)

    #un for de todas las caras que se detectan en el frame
    for face in faces:

        #se almacena la lista de los puntos de la cara
        puntos_claveCara = face.kps 
        
        #agarro los ojos
        ojoIz_x, ojoIz_y = puntos_claveCara[0].astype(int)
        ojoDe_x, ojoDe_y = puntos_claveCara[1].astype(int)

        #la nariz
        nariz_x, nariz_y = puntos_claveCara[2].astype(int)

        #la boca
        bocaIz_x, bocaIz_y = puntos_claveCara[3].astype(int)
        bocaDe_x, bocaDe_y = puntos_claveCara[4].astype(int)

        #aqui dibujo cada uno de los puntos de la cara con openCGV
        cv2.circle(frame, (ojoIz_x, ojoIz_y), 3, (70, 167, 26), )
        cv2.circle(frame, (ojoDe_x, ojoDe_y), 3, (70, 167, 26), )
        cv2.circle(frame, (nariz_x, nariz_y), 3, (0, 0, 255),-1 )# -1 rellena el círculo
        cv2.circle(frame, (bocaIz_x, bocaIz_y), 3, (222, 230, 56), )
        cv2.circle(frame, (bocaDe_x, bocaDe_y), 3, (222, 230, 56),)

        #esto lo que hace es devolver la cordenas de donde esta la cara para luego dibujar el rectangulo
        x1, y1, x2, y2 = face.bbox.astype(int)
        cv2.rectangle(frame, (x1, y1), (x2, y2), (255, 0, 0), 2)

        if (face.gender).astype(int):
            sexo='hombre'
        else:
            sexo='mujer'
        
        seguridad=(face.det_score*100)
        edad=face.age
        cv2.putText(frame,sexo,(x1, y2+20),1,1,(255, 40, 180),2)
        cv2.putText(frame,(str(seguridad)+'%'),(x1-50, y1),1,1,(255, 40, 180),2)
        cv2.putText(frame,(str(edad)),(x2, y1),1,1,(255, 40, 180),2)

    cv2.imshow('Rastreador de Nariz', frame)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

video.release()
cv2.destroyAllWindows()