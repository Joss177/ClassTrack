import numpy as np

# Simulamos: 1000 personas guardadas, cada una con 512 números
# (En la vida real, estos serían los embeddings que generó InsightFace)
base_de_datos = np.random.rand(1000, 512) 

# Simulamos: La cara que está viendo la cámara ahora mismo
cara_en_vivo = np.random.rand(512)

# --- LA MAGIA DE NUMPY ---
# np.dot multiplica la cara en vivo por TODA la base de datos a la vez.
# El resultado es una lista de 1000 puntajes de similitud.
puntajes = np.dot(base_de_datos, cara_en_vivo)

# Ahora solo buscamos quién tiene el puntaje más alto
indice_del_ganador = np.argmax(puntajes)
puntaje_maximo = puntajes[indice_del_ganador]

print(f"La cara coincide con la persona #{indice_del_ganador} con un puntaje de {puntaje_maximo:.2f}")
print(ord('s'))