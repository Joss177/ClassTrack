import cv2
import numpy as np
from insightface.app import FaceAnalysis
import json
import os

'''Aqui abajo es la base de datos del reconocimiento facila. NO TOCAR'''

#======================NO TOQUEN XD :V========================================
# estas son nuestras bases de datos
pathNombresJson = 'camaraView/data/nombres.json'
pathMatrizEmb = 'camaraView/data/numeros.npy'
    #aqui checamos si existen. si si solo se carga su info
if os.path.exists(pathNombresJson) and os.path.exists(pathMatrizEmb):
    
    with open(pathNombresJson, 'r') as file:
        listaNombres = json.load(file)

    matrizEmbeddings = np.load(pathMatrizEmb)

else:
    #si no existen los archivos se empieaza con nada XD
    listaNombres = []
    matrizEmbeddings = np.empty((0, 512))
#======================NO TOQUEN XD :V========================================

'''Aqui arriba es la base de datos del reconocimiento facila. NO TOCAR'''

# funcion para prender camara y llamar a las otras funciones
def videoCara():
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
        
        elif tecla == ord('s'):#guardar cara
            frame_resultado = frame.copy()
            caras = app.get(frame_resultado)
            caraNuevaGuarda(caras)
        
        elif tecla==ord('o'): #pasar lista
            frame_resultado = frame.copy()
            caras = app.get(frame_resultado)
            listaAsistencia=carasDetectadaas(frame_resultado,caras)
            print(listaAsistencia)
            '''
            Aqui cano podrias ya a empezar a hacer la logica del pase de asistencia. 
            de preferencia haslo en otro archivo y aqui solo importa la funcion y 
            pasa los argumentos aqui para que tengamos orden
            '''


            
    video.release()
    cv2.destroyAllWindows()

#A esta no le meuvas cano. Es como detecto las caras
def carasDetectadaas(frame,carasDetctadas):
    listaAsistencia=[]
    for cara in carasDetctadas:
                
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
                        listaAsistencia.append(nombre)#Esto proximamente se cambiara por matricula
                        #cv2.putText(frame,str(certezaPersona),(x1,y2+10),1,1,(165,45,85),2)
                
                
                cv2.rectangle(frame,(x1,y1),(x2,y2),(255,0,0),1)
                cv2.putText(frame,nombre,(x1,y1-10),1,1,(165,45,85),2)

    cv2.imshow('Resultado Reconocimiento', frame)
    return listaAsistencia

# por facor que de preferencia solo este una cara a la vez cuando se vaya a guardar una nueva cara
def caraNuevaGuarda(CaraDetectada):
    if  CaraDetectada:
        for cara in CaraDetectada:
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

videoCara()