import json
import sys

DIAS = {1: "Lunes", 2: "Martes", 3: "Miércoles", 4: "Jueves", 5: "Viernes"}

def mostrar_bonito(json_file):
    with open(json_file, 'r', encoding='utf-8') as f:
        data = json.load(f)
        
    for grupo in data.get("grupos", []):
        print(f"\n{'='*50}")
        print(f"📘 GRUPO: {grupo['nombre']}")
        print(f"{'='*50}")
        
        print("\n👨‍🏫 DICCIONARIO DE MATERIAS:")
        print(f"{'CÓDIGO':<12} | {'DOCENTE':<35} | ASIGNATURA")
        print("-" * 80)
        for mat in grupo.get("materias", []):
            nombre = mat['nombre'][:30] + "..." if len(mat['nombre']) > 30 else mat['nombre']
            print(f"{mat['codigo']:<12} | {mat['docente']:<35} | {nombre}")
            
        print("\n🕒 HORARIO DE CLASES:")
        horarios = grupo.get("horarios", [])
        
        for dia_num in range(1, 6):
            clases_dia = [h for h in horarios if h["dia_semana"] == dia_num]
            if clases_dia:
                print(f"\n  📅 {DIAS[dia_num]}")
                for c in clases_dia:
                    print(f"    [{c['hora_inicio']} - {c['hora_fin']}] {c['codigo']:<10} ➔ Aula: {c['aula']}")

if __name__ == "__main__":
    if len(sys.argv) > 1:
        mostrar_bonito(sys.argv[1])
    else:
        print("Pasa el archivo JSON: python visor.py res.json")