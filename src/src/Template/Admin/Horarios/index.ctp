

<?= $this->Html->css('horario', ['block' => true]) ?>

<?php
// BLOQUES EXACTOS DEL DÍA
$bloques = [
    ['inicio' => '07:00', 'fin' => '07:50'],
    ['inicio' => '07:50', 'fin' => '08:40'],
    ['inicio' => '08:40', 'fin' => '09:30'],
    ['inicio' => '09:30', 'fin' => '10:20'],
    ['inicio' => '10:20', 'fin' => '10:50', 'receso' => true],
    ['inicio' => '10:50', 'fin' => '11:40'],
    ['inicio' => '11:40', 'fin' => '12:30'],
    ['inicio' => '12:30', 'fin' => '13:20'],
    ['inicio' => '13:20', 'fin' => '14:10'],
    ['inicio' => '14:10', 'fin' => '15:00'],
    ['inicio' => '15:00', 'fin' => '15:50'],
    ['inicio' => '15:50', 'fin' => '16:20', 'receso' => true],
    ['inicio' => '16:20', 'fin' => '17:10'],
    ['inicio' => '17:10', 'fin' => '18:00'],
];

// Organizar horarios por aula/día/hora_inicio
$horariosMap = [];

// Organizar horarios por aula/día/bloque
$horariosMap = [];

foreach ($horarios as $h) {

    $inicioHorario = strtotime($h->hora_inicio);
    $finHorario    = strtotime($h->hora_fin);

    foreach ($bloques as $bloque) {

        if (!empty($bloque['receso'])) {
            continue;
        }

        $inicioBloque = strtotime($bloque['inicio']);

        // Si el bloque está dentro del rango del horario
        if ($inicioBloque >= $inicioHorario && $inicioBloque < $finHorario) {
            $horariosMap[$h->aula_id][$h->dia_semana][$bloque['inicio']] = $h;
        }
    }
}
?>

<div class="container">

    <h1 class="title">Horarios</h1>

    <div class="top-bar">
        <div>
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
                <?php foreach (['Lunes','Martes','Miércoles','Jueves','Viernes'] as $diaNombre): ?>
                    <div class="header"><?= $diaNombre ?></div>
                <?php endforeach; ?>

                <!-- Bloques del día -->
                <?php foreach ($bloques as $bloque): ?>

                    <?php if (!empty($bloque['receso'])): ?>
                        <div class="receso">
                            <?= $bloque['inicio'] ?> - <?= $bloque['fin'] ?> RECESO
                        </div>
                        <?php continue; ?>
                    <?php endif; ?>

                    <div class="time">
                        <?= $bloque['inicio'] ?> - <?= $bloque['fin'] ?>
                    </div>

                    <?php for ($dia = 1; $dia <= 5; $dia++): ?>
                        <div class="cell">

                            <?php if (!empty($horariosMap[$aulaId][$dia][$bloque['inicio']])):
                                $h = $horariosMap[$aulaId][$dia][$bloque['inicio']];
                            ?>
                                <div class="materia-bloque abrir-detalle"
                                    style="background: <?= h($h->materia->color) ?>"
                                    data-id="<?= $h->id ?>"
                                    data-materia="<?= h($h->materia->nombre) ?>"
                                    data-codigo="<?= h($h->materia->codigo ?? '') ?>"
                                    data-docente="<?= h($h->docente->nombre ?? '') ?>"
                                    data-grupo="<?= h($h->grupo->nombre ?? '') ?>"
                                    data-aula="<?= h($aulaNombre) ?>"
                                    data-dia="<?= $dia ?>"
                                    data-hora="<?= substr($h->hora_inicio,0,5) ?> - <?= substr($h->hora_fin,0,5) ?>">

                                    <strong><?= h($h->materia->nombre) ?></strong><br>
                                    <?= h($h->grupo->nombre ?? '') ?><br>
                                    <?= h($h->docente->nombre ?? '') ?><br>
                                    <?= h($h->hora_inicio) ?> - <?= h($h->hora_fin) ?>

                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endfor; ?>

                <?php endforeach; ?>

            </div>

        </div>

    <?php endforeach; ?>

</div>

<?= $this->Form->create(null, [
    'url' => ['action' => 'add']
]) ?>

<div id="modalAgregar" class="modal-overlay">

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
                        <option value="<?= $id ?>">
                            <?= h($nombre) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- MATERIA -->
            <div class="form-group">
                <label>Materia</label>
                <select name="materia_id" required>
                    <option value="">Seleccionar materia</option>
                    <?php foreach ($materias as $id => $nombre): ?>
                        <option value="<?= $id ?>">
                            <?= h($nombre) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- GRUPO -->
            <div class="form-group">
                <label>Grupo</label>
                <select name="grupo_id" required>
                    <option value="">Seleccionar grupo</option>
                    <?php foreach ($grupos as $id => $nombre): ?>
                        <option value="<?= $id ?>">
                            <?= h($nombre) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- AULA -->
            <div class="form-group">
                <label>Aula</label>
                <select name="aula_id" required>
                    <option value="">Seleccionar aula</option>
                    <?php foreach ($aulas as $id => $nombre): ?>
                        <option value="<?= $id ?>">
                            <?= h($nombre) ?>
                        </option>
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
            <button type="button" class="btn-secondary" onclick="closeModal()">
                Cancelar
            </button>

            <button type="submit" class="btn-primary">
                Agregar
            </button>
        </div>

    </div>

</div>

<?= $this->Form->end() ?>

<!-- MODAL DE DETALLES -->
<div id="modalHorario" class="modal-overlay">
    <div class="modal-container">

        <div class="modal-header">
            <h3>Detalles del Horario</h3>
            <span class="cerrar" onclick="cerrarModal()">×</span>
        </div>

        <div class="modal-body">

            <div class="materia-box">
                <div class="materia-dot"></div>
                <div>
                    <strong id="m_materia"></strong>
                    <div class="codigo" id="m_codigo"></div>
                </div>
            </div>

            <div class="grid-info">
                <div>
                    <label>Docente</label>
                    <p id="m_docente"></p>
                </div>
                <div>
                    <label>Grupo</label>
                    <p id="m_grupo"></p>
                </div>
                <div>
                    <label>Aula</label>
                    <p id="m_aula"></p>
                </div>
                <div>
                    <label>Día</label>
                    <p id="m_dia"></p>
                </div>
            </div>

            <div class="horario-info">
                <label>Horario</label>
                <p id="m_hora"></p>
            </div>

        </div>

        <div class="modal-footer">

            <button type="button" id="btnEditar" class="btn-editar">
                Editar
            </button>

            <form id="formEliminar" method="post" style="display:inline;">
                <button type="submit" class="btn-eliminar">
                    Eliminar
                </button>
            </form>

        </div>

    </div>
</div>

<script>
const horariosExistentes = <?= json_encode($horarios) ?>;

document.addEventListener("DOMContentLoaded", function () {

    const horaInicio = document.getElementById("horaInicio");
    const horaFin = document.getElementById("horaFin");
    const aulaSelect = document.querySelector("select[name='aula_id']");
    const diaSelect = document.querySelector("select[name='dia_semana']");

    if (!horaInicio || !horaFin) return;

    const horasInicio = [
        "07:00","07:50","08:40","09:30",
        "10:50","11:40","12:30","13:20",
        "14:10","15:00","16:20","17:10"
    ];

    const horasFin = [
        "07:50","08:40","09:30","10:20",
        "10:50","11:40","12:30","13:20",
        "14:10","15:00","15:50","16:20",
        "17:10","18:00"
    ];

    function limpiarSelect(select) {
        select.innerHTML = "";
    }

    function horaToMin(h) {
        const partes = h.split(":");
        return parseInt(partes[0]) * 60 + parseInt(partes[1]);
    }

    function rangoOcupado(aulaId, dia, inicioNuevo, finNuevo) {

        const minInicioNuevo = horaToMin(inicioNuevo);
        const minFinNuevo = horaToMin(finNuevo);

        return horariosExistentes.some(h => {

            if (h.aula_id != aulaId) return false;
            if (h.dia_semana != dia) return false;

            const inicio = horaToMin(h.hora_inicio.substring(0,5));
            const fin = horaToMin(h.hora_fin.substring(0,5));

            return (minInicioNuevo < fin && minFinNuevo > inicio);
        });
    }

    function cargarHorasInicio() {

        limpiarSelect(horaInicio);
        limpiarSelect(horaFin);

        const aula = aulaSelect.value;
        const dia = diaSelect.value;

        if (!aula || !dia) return;

        horasInicio.forEach(h => {

            // Verificamos que iniciar en esa hora no esté dentro de otro rango
            const ocupado = horariosExistentes.some(existente => {

                if (existente.aula_id != aula) return false;
                if (existente.dia_semana != dia) return false;

                const inicio = horaToMin(existente.hora_inicio.substring(0,5));
                const fin = horaToMin(existente.hora_fin.substring(0,5));
                const horaMin = horaToMin(h);

                return (horaMin >= inicio && horaMin < fin);
            });

            if (!ocupado) {
                let opt = document.createElement("option");
                opt.value = h;
                opt.textContent = h;
                horaInicio.appendChild(opt);
            }

        });

        actualizarHoraFin();
    }

    function actualizarHoraFin() {

        limpiarSelect(horaFin);

        const inicioSeleccionado = horaInicio.value;
        const aula = aulaSelect.value;
        const dia = diaSelect.value;

        if (!inicioSeleccionado || !aula || !dia) return;

        horasFin.forEach(h => {

            if (h > inicioSeleccionado) {

                if (!rangoOcupado(aula, dia, inicioSeleccionado, h)) {

                    let opt = document.createElement("option");
                    opt.value = h;
                    opt.textContent = h;
                    horaFin.appendChild(opt);

                }

            }

        });

    }

    aulaSelect.addEventListener("change", cargarHorasInicio);
    diaSelect.addEventListener("change", cargarHorasInicio);
    horaInicio.addEventListener("change", actualizarHoraFin);

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    document.querySelectorAll(".abrir-detalle").forEach(bloque => {

        bloque.addEventListener("click", function() {

            const id = this.dataset.id;

            document.getElementById("m_materia").innerText = this.dataset.materia;
            document.getElementById("m_codigo").innerText = this.dataset.codigo;
            document.getElementById("m_docente").innerText = this.dataset.docente;
            document.getElementById("m_grupo").innerText = this.dataset.grupo;
            document.getElementById("m_aula").innerText = this.dataset.aula;
            document.getElementById("m_dia").innerText = nombreDia(this.dataset.dia);
            document.getElementById("m_hora").innerText = this.dataset.hora;

            document.getElementById("btnEditar").href =
                "/admin/horarios/edit/" + id;

            document.getElementById("formEliminar").innerHTML =
                '<input type="hidden" name="_method" value="POST">' +
                '<button type="submit" formaction="/admin/horarios/delete/' + id + '" class="btn-eliminar">Eliminar</button>';

            document.getElementById("modalHorario").style.display = "flex";
        });

    });

});

function cerrarModal() {
    document.getElementById("modalHorario").style.display = "none";
}

function nombreDia(num) {
    const dias = {
        1: 'Lunes',
        2: 'Martes',
        3: 'Miércoles',
        4: 'Jueves',
        5: 'Viernes'
    };
    return dias[num] ?? '';
}

window.onclick = function(e) {
    const modal = document.getElementById("modalHorario");
    if (e.target === modal) {
        cerrarModal();
    }
}
</script>

<script>
function openModal() {
    document.getElementById('modalAgregar').style.display = 'flex';
}

function closeModal() {
    document.getElementById('modalAgregar').style.display = 'none';
}

window.onclick = function(e) {

    const modalAgregar = document.getElementById("modalAgregar");
    const modalDetalle = document.getElementById("modalHorario");

    if (e.target === modalAgregar) {
        modalAgregar.style.display = "none";
    }

    if (e.target === modalDetalle) {
        modalDetalle.style.display = "none";
    }
};
</script>
