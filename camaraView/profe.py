import cv2
import numpy as np

cap = cv2.VideoCapture(0)
contador=0
while True:
    contador=contador+1
    ret, frame = cap.read()
    frameMostrar=cv2.cvtColor(frame,cv2.COLOR_BGR2GRAY)
    _,frameGries=cv2.threshold(frameMostrar,0,255,cv2.THRESH_BINARY+cv2.THRESH_OTSU)
    frameSuaveGris=cv2.GaussianBlur(frameGries,[7,7],0)
    _,frameGriesInv=cv2.threshold(frameMostrar,0,255,cv2.THRESH_BINARY_INV+cv2.THRESH_OTSU)
    contornoGris, _ = cv2.findContours(frameGries, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    contrnoInvGri,_s=cv2.findContours(frameGriesInv,cv2.RETR_EXTERNAL,cv2.CHAIN_APPROX_SIMPLE)
    y,x=frameGries.shape

    fondoBlanco=np.ones((y,x,3),dtype=np.uint8)*255
    fondoNegro=np.zeros((y,x,3),dtype=np.uint8)*255
    for contorno in contornoGris:
        area=cv2.contourArea(contorno)
        if area>1000:
            x,y,w,h=cv2.boundingRect(contorno)
            cv2.drawContours(fondoBlanco,contornoGris, -1, (255, 65, 89), 2)
            #cv2.rectangle(fondo,(x,y),(x+w,y+h),(89,125,21),2)
            cv2.drawContours(fondoNegro, contrnoInvGri, -1, (0, 89, 230), 2)
    #if contador%2==1:
    #    frameMostrar=frame
    #else:
    #    frameMostrar=cv2.cvtColor(frame,cv2.COLOR_BGR2GRAY)
    cv2.imshow('Negro',fondoNegro)
    cv2.imshow('Blanco',fondoBlanco)
    #cv2.imshow("Gris", frameGries)
    #cv2.imshow("lol", frame)
    #cv2.imshow("Gris Invertido", frameGriesInv)
    #cv2.imshow('Suave Gris',frameSuaveGris)


    tecla = cv2.waitKey(1) & 0xFF
    if tecla == ord('q'):
        break


cap.release()
cv2.destroyAllWindows()