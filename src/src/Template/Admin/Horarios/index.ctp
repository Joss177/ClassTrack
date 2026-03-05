

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
            $horariosMap[$h->grupo_id][$h->dia_semana][$bloque['inicio']] = $h;
        }
    }
}
?>

<div class="container">

    <h1 class="title">Horarios</h1>

    <div class="top-bar">
        <?= $this->Form->create(null, ['type' => 'get', 'style' => 'display:inline;']) ?>

            <div>
                <label>Grupo:</label>
                <select name="grupo_id" onchange="this.form.submit()">
                    <option value="">Todas las aulas</option>

                    <?php foreach ($grupos as $id => $nombre): ?>
                        <option value="<?= $id ?>"
                            <?= ($this->request->getQuery('grupo_id') == $id) ? 'selected' : '' ?>>
                            <?= h($nombre) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

        <?= $this->Form->end() ?>

        <button class="btn-primary" onclick="openModal()">Agregar Horario</button>
    </div>

    <?php foreach ($grupos as $grupoId => $grupoNombre): ?>

        <div class="card">

            <h2 class="aula-title"><?= h($grupoNombre) ?></h2>

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
                        <div class="cell"
                            data-grupo="<?= $grupoId ?>"
                            data-dia="<?= $dia ?>"
                            data-hora="<?= $bloque['inicio'] ?>">

                        <?php if (!empty($horariosMap[$grupoId][$dia][$bloque['inicio']])):
                                $h = $horariosMap[$grupoId][$dia][$bloque['inicio']];
                                $duracion = (strtotime($h->hora_fin) - strtotime($h->hora_inicio)) / 60;
                            ?>
                                <div class="materia-bloque abrir-detalle"
                                    style="background: <?= h($h->materia->color) ?>"

                                    data-id="<?= $h->id ?>"

                                    data-materia="<?= h($h->materia->nombre ?? '') ?>"
                                    data-codigo="<?= h($h->materia->codigo ?? '') ?>"
                                    data-docente="<?= h($h->docente->nombre ?? '') ?>"
                                    data-grupo="<?= h($h->grupo->nombre ?? '') ?>"
                                    data-aula="<?= h($h->aula->nombre ?? '') ?>"

                                    data-dia="<?= $h->dia_semana ?>"
                                    data-hora="<?= $h->hora_inicio ?> - <?= $h->hora_fin ?>"

                                    data-duracion="<?= $duracion ?>"
                                >

                                <strong><?= h($h->materia->codigo ?? '') ?></strong><br>
                                <?= h($h->aula->nombre ?? '') ?>

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

        <?= $this->Form->create(null, [
            'id' => 'formEliminar',
            'method' => 'post'
        ]) ?>

            <button type="submit" class="btn-eliminar">
                Eliminar
            </button>

        <?= $this->Form->end() ?>

    </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    if (typeof Sortable === "undefined") {
        console.error("SortableJS no se cargó");
        return;
    }

    const celdas = document.querySelectorAll(".cell");

    celdas.forEach(function(cell) {

        new Sortable(cell, {
            group: "horarios",
            animation: 150,
            draggable: ".materia-bloque",

            onMove: function (evt) {

                const destino = evt.to;

                if (!destino) {
                    return false;
                }

                // 🔒 Solo una materia por celda
                if (destino.children.length > 0) {
                    return false;
                }

                return true;
            },

            onEnd: function (evt) {

                const item = evt.item;

                if (!item) return;

                const id = item.dataset.id;
                const duracion = parseInt(item.dataset.duracion) || 0;

                const destino = evt.to;

                if (!destino) return;

                const nuevoGrupo = destino.dataset.grupo;
                const nuevoDia = destino.dataset.dia;
                const nuevaHoraInicio = destino.dataset.hora;

                if (!id || !nuevoGrupo || !nuevoDia || !nuevaHoraInicio) {
                    console.error("Datos incompletos");
                    location.reload();
                    return;
                }

                moverHorario(
                    id,
                    nuevoGrupo,
                    nuevoDia,
                    nuevaHoraInicio,
                    duracion
                );
            }

        });

    });

    function horaToMin(h) {

        if (!h) return 0;

        const partes = h.split(":");

        const horas = parseInt(partes[0]) || 0;
        const minutos = parseInt(partes[1]) || 0;

        return (horas * 60) + minutos;
    }

    function minToHora(min) {

        const h = Math.floor(min / 60);
        const m = min % 60;

        const hh = ("0" + h).slice(-2);
        const mm = ("0" + m).slice(-2);

        return hh + ":" + mm;
    }

    function moverHorario(id, grupo, dia, horaInicio, duracion) {

        const minInicio = horaToMin(horaInicio);
        const minFin = minInicio + duracion;
        const horaFin = minToHora(minFin);

        fetch("<?= $this->Url->build(['action' => 'mover']) ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": "<?= $this->request->getParam('_csrfToken') ?>"
            },
            body: JSON.stringify({
                id: id,
                grupo_id: grupo,
                dia_semana: dia,
                hora_inicio: horaInicio,
                hora_fin: horaFin
            })
        })
        .then(function(res) {

            if (!res.ok) {
                throw new Error("Error HTTP");
            }

            return res.json();

        })
        .then(function(data) {

            if (!data.success) {
                alert("Conflicto de horario");
                location.reload();
            }

        })
        .catch(function(error) {

            console.error(error);
            alert("Error del servidor");
            location.reload();

        });

    }

});
</script>


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

    // llenar datos del modal
    document.getElementById("m_materia").innerText = this.dataset.materia;
    document.getElementById("m_codigo").innerText = this.dataset.codigo;
    document.getElementById("m_docente").innerText = this.dataset.docente;
    document.getElementById("m_grupo").innerText = this.dataset.grupo;
    document.getElementById("m_aula").innerText = this.dataset.aula;
    document.getElementById("m_dia").innerText = nombreDia(this.dataset.dia);
    document.getElementById("m_hora").innerText = this.dataset.hora;

    // botón editar
    document.getElementById("btnEditar").href =
        "/admin/horarios/edit/" + id;

    // formulario eliminar (solo cambiamos action)
    document.getElementById("formEliminar").action =
        "/admin/horarios/delete/" + id;

    // mostrar modal
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


<style>
    .materia-bloque {
    background: #3b82f6;
    color: white;
    border-radius: 6px;
    font-weight: 600;
    font-size: 12px;
    line-height: 1.0;
    cursor: pointer;

    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 8px;
}

.materia-bloque {
    color: rgb(58, 58, 58);
    padding: 6px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: transform .15s ease, box-shadow .15s ease;
}
</style>
