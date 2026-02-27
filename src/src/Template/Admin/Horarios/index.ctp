<?php
$horariosOrganizados = [];

foreach ($horarios as $h) {

    $inicio = strtotime($h->hora_inicio);
    $fin = strtotime($h->hora_fin);

    for ($t = $inicio; $t < $fin; $t += 50 * 60) {

        $horaBloque = date('H:i', $t);

        $horariosOrganizados[$h->aula_id][$h->dia_semana][$horaBloque] = $h;
    }
}

$inicioDia = 7 * 60;
$finDia = 17 * 60 + 10;
$intervalo = 50;

$recesos = [
    10 * 60 + 20,
    15 * 60 + 50
];

function formatHora($min) {
    $h = floor($min / 60);
    $m = $min % 60;
    return str_pad($h, 2, '0', STR_PAD_LEFT) . ':' .
           str_pad($m, 2, '0', STR_PAD_LEFT);
}
?>
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

    <?php foreach ($aulas as $aulaId => $aulaNombre): ?>

    <div class="card">

        <h2 class="aula-title"><?= h($aulaNombre) ?></h2>

        <div class="schedule">

            <!-- Encabezados -->
            <div class="header empty"></div>
            <div class="header">Lunes</div>
            <div class="header">Martes</div>
            <div class="header">Miércoles</div>
            <div class="header">Jueves</div>
            <div class="header">Viernes</div>

            <?php
            for ($tiempo = $inicioDia; $tiempo < $finDia; $tiempo += $intervalo):

                if (in_array($tiempo, $recesos)):
            ?>
                    <div class="receso">RECESO</div>
            <?php
                    continue;
                endif;

                $horaActual = formatHora($tiempo);
            ?>

                <div class="time"><?= $horaActual ?></div>

                <?php for ($dia = 1; $dia <= 5; $dia++): ?>

                    <div class="cell">
                        <?php if (
                            isset($horariosOrganizados[$aulaId][$dia][$horaActual]) &&
                            !empty($horariosOrganizados[$aulaId][$dia][$horaActual]->materia)
                        ):
                            $h = $horariosOrganizados[$aulaId][$dia][$horaActual];
                        ?>

                        <div class="materia-bloque"
                            style="background: <?= h($h->materia->color) ?>; color:#fff;">

                            <strong><?= h($h->materia->nombre) ?></strong><br>
                            <?= h($h->grupo->nombre ?? '') ?><br>
                            <?= h($h->docente->nombre ?? '') ?><br>
                            <?= h($h->hora_inicio) ?> - <?= h($h->hora_fin) ?>

                        </div>

                        <?php endif; ?>
                    </div>

                <?php endfor; ?>

            <?php endfor; ?>

        </div>
    </div>

    <?php endforeach; ?>

</div>


<?= $this->Form->create(null, [
    'url' => ['action' => 'add']
]) ?>

    <div id="modalHorario" class="modal-overlay">

        <div class="modal">

            <div class="modal-header">
                <h3>Agregar Horario</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>

            <div class="modal-body">

                <!-- DOCENTE -->
                <div class="form-group">
                    <label>Docente</label>
                    <select name="docente_id" required>
                        <option value="">Seleccionar docente</option>
                        <?php foreach ($docentes as $id => $nombre): ?>
                            <option value="<?= $id ?>"><?= h($nombre) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- MATERIA -->
                <div class="form-group">
                    <label>Materia</label>
                    <select name="materia_id" required>
                        <option value="">Seleccionar materia</option>
                        <?php foreach ($materias as $id => $nombre): ?>
                            <option value="<?= $id ?>"><?= h($nombre) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- GRUPO -->
                <div class="form-group">
                    <label>Grupo</label>
                    <select name="grupo_id" required>
                        <option value="">Seleccionar grupo</option>
                        <?php foreach ($grupos as $id => $nombre): ?>
                            <option value="<?= $id ?>"><?= h($nombre) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- AULA -->
                <div class="form-group">
                    <label>Aula</label>
                    <select name="aula_id" required>
                        <option value="">Seleccionar aula</option>
                        <?php foreach ($aulas as $id => $nombre): ?>
                            <option value="<?= $id ?>"><?= h($nombre) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- DIA -->
                <div class="form-group">
                    <label>Día de la semana</label>
                    <select name="dia_semana" required>
                        <option value="">Seleccionar día</option>
                        <option value="1">Lunes</option>
                        <option value="2">Martes</option>
                        <option value="3">Miércoles</option>
                        <option value="4">Jueves</option>
                        <option value="5">Viernes</option>
                    </select>
                </div>

                <!-- HORAS -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Hora Inicio</label>
                        <select id="horaInicio" name="hora_inicio" required></select>
                    </div>
                    <div class="form-group">
                        <label>Hora Fin</label>
                        <select id="horaFin" name="hora_fin" required></select>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn-primary">Agregar</button>
            </div>

        </div>

    </div>
<?= $this->Form->end() ?>

<script>
    //MODAL AGREGAR HORARIO
    function openModal() {
        document.getElementById('modalHorario').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalHorario').style.display = 'none';
    }



    // Cerrar al hacer clic fuera
    window.onclick = function(event) {
        const modales = [
            'modalHorario'
        ];

        modales.forEach(function(id) {
            const modal = document.getElementById(id);
            if (modal && event.target === modal) {
                modal.style.display = 'none';
            }
        });
    };

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
        optInicio.value = formatHora(tiempo);
        optInicio.textContent = formatHora(tiempo);
        horaInicio.appendChild(optInicio);

        let optFin = document.createElement("option");
        optFin.value = formatHora(finBloque);
        optFin.textContent = formatHora(finBloque);
        horaFin.appendChild(optFin);
    }

    // Sincroniza fin automáticamente
    horaInicio.addEventListener("change", function () {
        horaFin.selectedIndex = this.selectedIndex;
    });

});



</script>

<style>

    .schedule {
    display: grid;
    grid-template-columns: 80px repeat(5, 1fr);
    gap: 8px;
}

.header {
    font-weight: 600;
    text-align: center;
    padding: 8px 0;
}

.time {
    font-size: 14px;
    color: #555;
    display: flex;
    align-items: center;
}

.cell {
    background: #f8f9fb;
    border: 1px solid #e2e6ea;
    border-radius: 6px;
    min-height: 70px;
    padding: 4px;
}

.materia-bloque {
    background: #3b82f6;
    color: white;
    padding: 6px;
    border-radius: 6px;
    font-size: 12px;
    line-height: 1.3;
}

.receso {
    grid-column: 1 / -1;
    text-align: center;
    background: #e9ecef;
    padding: 6px;
    font-weight: 600;
    border-radius: 6px;
}

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



