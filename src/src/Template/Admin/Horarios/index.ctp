<div class="container">

    <h1 class="title">Horarios</h1>

    <div class="top-bar">
        <div class="left">
            <label>Horarios</label>
            <select>
                <option>Todas las aulas</option>
            </select>
        </div>

        <button class="btn-primary" onclick="openModal()">Agregar Horario</button>
    </div>

    <div class="card">

        <h2 class="aula-title">Aula 101</h2>

        <div class="schedule">

            <!-- Encabezados -->
            <div class="header empty"></div>
            <div class="header">Lunes</div>
            <div class="header">Martes</div>
            <div class="header">Miércoles</div>
            <div class="header">Jueves</div>
            <div class="header">Viernes</div>

            <!-- 07:00 -->
            <div class="time">07:00</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <!-- 07:50 -->
            <div class="time">07:50</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <!-- 08:40 -->
            <div class="time">08:40</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <!-- 09:30 -->
            <div class="time">09:30</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <!-- 10:20 RECESO -->
            <div class="receso">RECESO</div>

            <!-- 11:10 -->
            <div class="time">11:10</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <!-- 12:00 -->
            <div class="time">12:00</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <!-- 12:50 -->
            <div class="time">12:50</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <!-- 13:40 -->
            <div class="time">13:40</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <!-- 14:30 -->
            <div class="time">14:30</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <!-- 15:20 -->
            <div class="time">15:20</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <!-- 15:50 RECESO -->
            <div class="receso">RECESO</div>

            <!-- 16:40 -->
            <div class="time">16:20</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <!-- 16:40 -->
            <div class="time">17:10</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

        </div>
    </div>
</div>

<!-- MODAL AGREGAR HORARIO-->
<div id="modalHorario" class="modal-overlay">

    <div class="modal">

        <div class="modal-header">
            <h3>Agregar Horario</h3>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>

        <div class="modal-body">

            <div class="form-group">
                <label>Docente</label>
                <select>
                    <option>Seleccionar docente</option>
                </select>
            </div>

            <div class="form-group">
                <label>Materia</label>
                <select>
                    <option>Seleccionar materia</option>
                </select>
            </div>

            <div class="form-group">
                <label>Grupo</label>
                <select>
                    <option>Seleccionar grupo</option>
                </select>
            </div>

            <div class="form-group">
                <label>Aula</label>
                <select>
                    <option>Seleccionar aula</option>
                </select>
            </div>

            <div class="form-group">
                <label>Día de la semana</label>
                <select>
                    <option>Lunes</option>
                    <option>Martes</option>
                    <option>Miércoles</option>
                    <option>Jueves</option>
                    <option>Viernes</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Hora Inicio</label>
                    <select id="horaInicio"></select>
                </div>

                <div class="form-group">
                    <label>Hora Fin</label>
                    <select id="horaFin"></select>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeModal()">Cancelar</button>
            <button class="btn-primary">Agregar</button>
        </div>

    </div>

</div>


<style>
    #horaInicio,
    #horaFin {
        height: 140px;
        overflow-y: auto;
    }
    body {
    margin: 0;
    background-color: #f5f6f8;
    font-family: Arial, Helvetica, sans-serif;
    color: #1f2d3d;
}

.container {
    padding: 30px;
}

.title {
    margin-bottom: 20px;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.top-bar .left {
    display: flex;
    align-items: center;
    gap: 10px;
}

select {
    padding: 6px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

.btn-primary {
    background-color: #1e3a5f;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    cursor: pointer;
}

.card {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #e0e0e0;
}

.aula-title {
    margin-bottom: 20px;
}

/* GRID HORARIO */

.schedule {
    display: grid;
    grid-template-columns: 80px repeat(5, 1fr);
    gap: 10px;
    align-items: center;
}

.header {
    font-weight: bold;
    text-align: center;
}

.time {
    font-size: 14px;
    color: #555;
}

.cell {
    height: 55px;
    background-color: #f8f9fb;
    border: 1px solid #dcdfe6;
    border-radius: 6px;
}

/* Barra de receso */

.receso {
    grid-column: 1 / -1;
    background-color: #eef1f5;
    border: 1px solid #dcdfe6;
    text-align: center;
    padding: 10px 0;
    font-weight: bold;
    color: #555;
    border-radius: 6px;
}

/* ================= MODAL AGREGAR HORARIO ================= */

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.75);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 999;
}

.modal {
    background: #f5f6f8;
    width: 420px;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.modal-header h3 {
    margin: 0;
}

.close {
    cursor: pointer;
    font-size: 22px;
    color: #555;
}

.modal-body {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
    font-size: 14px;
}

.form-group label {
    margin-bottom: 5px;
    font-weight: 500;
}

.form-group select {
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    background: white;
}

/* ===== CORREGIR ALTURA DE SELECTS ===== */

.form-row {
    display: flex;
    gap: 15px;
    align-items: flex-start; /* IMPORTANTE */
}

.form-row .form-group {
    flex: 1;
}

.form-group select {
    height: 38px;        /* Altura correcta tipo sistema */
    padding: 6px 10px;
    font-size: 14px;
}

/* Evita que el select crezca verticalmente */
select {
    max-height: 38px;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 25px;
}

.btn-secondary {
    background: #e0e3e8;
    border: 1px solid #c9ccd3;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
}
</style>


<script>
    //MODAL AGREGAR HORARIO
    function openModal() {
        document.getElementById('modalHorario').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalHorario').style.display = 'none';
    }

    //FUNCIONALIDAD DE LAS HORAS


document.addEventListener("DOMContentLoaded", function () {

    const horaInicio = document.getElementById("horaInicio");
    const horaFin = document.getElementById("horaFin");

    const inicioDia = 7 * 60;      // 07:00
    const finDia = 17 * 60 + 10;   // 17:10
    const intervalo = 50;

    const recesos = [
        10 * 60 + 20, // 10:20
        15 * 60 + 50  // 15:50
    ];

    function formatHora(min) {
        let h = Math.floor(min / 60);
        let m = min % 60;
        return String(h).padStart(2, '0') + ":" + String(m).padStart(2, '0');
    }

    for (let tiempo = inicioDia; tiempo < finDia; tiempo += intervalo) {

        // Saltar si el bloque inicia en receso
        if (recesos.includes(tiempo)) continue;

        let finBloque = tiempo + intervalo;

        // No permitir que se pase de 17:10
        if (finBloque > finDia) break;

        let optInicio = document.createElement("option");
        optInicio.value = tiempo;
        optInicio.textContent = formatHora(tiempo);
        horaInicio.appendChild(optInicio);

        let optFin = document.createElement("option");
        optFin.value = finBloque;
        optFin.textContent = formatHora(finBloque);
        horaFin.appendChild(optFin);
    }

    // Sincroniza fin automáticamente
    horaInicio.addEventListener("change", function () {
        horaFin.selectedIndex = this.selectedIndex;
    });

});



</script>
