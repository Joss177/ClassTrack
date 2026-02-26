import cv2
import numpy as np
from insightface.app import FaceAnalysis
import json
import os


# estas son nuestras bases de datos
pathNombresJson = 'data/nombres.json'
pathMatrizEmb = 'data/numeros.npy'

#aqui checamos si existen. si si solo se carga su info
if os.path.exists(pathNombresJson) and os.path.exists(pathMatrizEmb):
    
    with open(pathNombresJson, 'r') as file:
        listaNombres = json.load(file)

    matrizEmbeddings = np.load(pathMatrizEmb)

else:
    #si no existen los archivos se empieaza con nada XD
    listaNombres = []
    matrizEmbeddings = np.empty((0, 512))


app = FaceAnalysis(name='buffalo_s', providers=['CPUExecutionProvider'])

app.prepare(ctx_id=0, det_size=(640, 640))

video=cv2.VideoCapture(0)

if not video:
    exit()


while True:
    ret,frame=video.read()

    if not ret:
        break
    

        



    cv2.imshow('papu ventan',frame)

    #esta es una foram de tener varias teclas responsivas
    tecla = cv2.waitKey(1) & 0xFF
    if tecla == ord('q'):
        break
    elif tecla == ord('s'):
        frame_resultado = frame.copy()
        caras = app.get(frame_resultado)
        if  caras:
            for cara in caras:
                #primero cargo la info de la cara
                matriz=cara.embedding
                nombre=input('nombre de la persona detectada\n')
                listaNombres.append(nombre)

                #esto lo que hace es que con la info que tenga matrizEmbeddings le anade vertivalmeten la matriz de la cara que analizamos
                matrizEmbeddings = np.vstack([matrizEmbeddings, matriz])

                #aqui sobreescribo la info de los archivos con la nueva info
                with open(pathNombresJson, 'w') as file:
                    json.dump(listaNombres, file)
                np.save(pathMatrizEmb, matrizEmbeddings)

                #para luego volver a leerlos para que se vuelva a cargar la info
                with open(pathNombresJson, 'r') as file:
                    listaNombres = json.load(file)

                matrizEmbeddings = np.load(pathMatrizEmb)
    
    elif tecla==ord('o'):
        frame_resultado = frame.copy()
        caras = app.get(frame_resultado)

        for cara in caras:
            
            x1, y1, x2, y2 = cara.bbox.astype(int)
            nombre='Desconocido'
            
            if listaNombres:
                persona=cara.embedding
                encontrarPersona=np.dot(matrizEmbeddings,persona) #evalua la cara de la persona con toda lainfo que tenemos y da un np array de cual es el mas cercabno
                certezaPersona=np.max(encontrarPersona) #da el valor de 0 a 1 de similitud
                print(certezaPersona)
                if  certezaPersona>230: # el rango con el que debo de jugar es de 200 a 250. menos y da falso postivo mas y es muy estricto a la hora de juzgar a la persona

                    personaMasCercana=np.argmax(encontrarPersona) #devuelve el indice del np array de ese valor
                    nombre=listaNombres[personaMasCercana]
                    #cv2.putText(frame,str(certezaPersona),(x1,y2+10),1,1,(165,45,85),2)
            
            
            cv2.rectangle(frame_resultado,(x1,y1),(x2,y2),(255,0,0),1)
            cv2.putText(frame_resultado,nombre,(x1,y1-10),1,1,(165,45,85),2)
            cv2.imshow('Resultado Reconocimiento', frame_resultado)



video.release()
cv2.destroyAllWindows()