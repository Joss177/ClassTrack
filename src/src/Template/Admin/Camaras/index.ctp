<?= $this->Html->css('camaras', ['block' => true]) ?>

    <?= $this->Flash->render('camara', ['escape' => false]) ?>
<div class="camaras-container">

    <div class="header-camaras">
        <h1 class="title">Cámaras</h1>



        <button type="button" class="btn-agregar" onclick="abrirModalRegistro()">
            Agregar
        </button>
    </div>



    <div class="cards">

        <?php foreach ($camaras as $cam): ?>
            <div class="camera-card"
                 onclick="openModal(
                    '<?= h($cam->aula->nombre) ?>',
                    '<?= h($cam->aula->edificio ?? '') ?>',
                    '<?= h($cam->aula->piso ?? '') ?>',
                    '<?= h($cam->estado) ?>',
                    '<?= h($cam->aula->capacidad ?? '-') ?>',
                    '<?= $cam->ultima_deteccion ? $cam->ultima_deteccion->format('H:i') : 'Sin registro' ?>'
                )">



                <div class="image-container">

                    <?= $this->Html->image('salon.jpg', ['alt' => h($cam->aula->nombre)]) ?>

                    <?php if ($cam->estado === 'activa'): ?>
                        <div class="live-badge">
                            <span class="dot"></span>
                            EN VIVO
                        </div>
                    <?php endif; ?>

                    <button class="edit-badge"
                        onclick="event.stopPropagation(); abrirModalEditar(
                            '<?= $cam->id ?>',
                            '<?= $cam->aula_id ?>',
                            '<?= h($cam->estado) ?>',
                            '<?= h($cam->aula->capacidad ?? '') ?>'
                        )">

                        <svg viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 20h9"/>
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                        </svg>

                    </button>

                    <button class="delete-badge"
                        onclick="event.stopPropagation(); abrirModalEliminar('<?= $cam->id ?>')">

                        <svg viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-2 14H7L5 6"/>
                            <path d="M10 11v6"/>
                            <path d="M14 11v6"/>
                            <path d="M9 6V4h6v2"/>
                        </svg>
                    </button>

                </div>

                <div class="card-body">
                    <div class="card-header">
                        <h3><?= h($cam->aula->nombre) ?></h3>
                        <span class="status <?= $cam->estado === 'activa' ? 'active' : 'inactive' ?>">
                            <?= ucfirst($cam->estado) ?>
                        </span>
                    </div>

                    <p>Edificio: <?= h($cam->aula->edificio ?? '-') ?></p>
                    <p>Piso: <?= h($cam->aula->piso ?? '-') ?></p>
                    <p>Capacidad: <?= h($cam->aula->capacidad ?? '-') ?> estudiantes</p>
                </div>

            </div>
        <?php endforeach; ?>

    </div>


    <!-- MODAL DE VISTA CAMARA -->
    <div id="cameraModal" class="modal">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h2 id="modalAula"></h2>
                    <span id="modalUbicacion"></span>
                </div>
                <button class="close-btn" onclick="closeModal()">×</button>
            </div>

            <div class="modal-image">
                <?= $this->Html->image('salon.jpg') ?>
                <div class="time-badge" id="modalHora"></div>
            </div>

            <div class="modal-info">
                <div>
                    <label>Estado</label>
                    <p id="modalEstado" class="status"></p>
                </div>

                <div>
                    <label>Última detección</label>
                    <p id="modalDeteccion"></p>
                </div>

                <div>
                    <label>Capacidad</label>
                    <p id="modalCapacidad"></p>
                </div>

                <div>
                    <label>Número de Aula</label>
                    <p id="modalNumero"></p>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL REGISTRO CAMARA -->
    <div class="modal-registro-overlay" id="modalRegistroCamara">
        <div class="modal-registro">

            <div class="modal-registro-header">
                <h3>Agregar Cámara</h3>
                <span class="modal-registro-close" onclick="cerrarModalRegistro()">×</span>
            </div>

            <?= $this->Form->create($camara, [
                'url' => ['action' => 'add'],
                'method' => 'post'
            ]) ?>

            <div class="modal-registro-body">

                <div class="form-group">
                    <label>Aula <span class="required">*Requerido</span></label>

                    <select id="aulaSelect" name="aula_id" required>
                        <option value="">Seleccione un aula</option>

                        <?php foreach ($aulas as $aula): ?>
                            <option
                                value="<?= $aula->id ?>"
                                data-capacidad="<?= $aula->capacidad ?>">
                                <?= h($aula->nombre) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label>Estado <span class="required">*Requerido</span></label>
                    <?= $this->Form->control('estado', [
                        'type' => 'select',
                        'options' => [
                            'activa' => 'Activa',
                            'inactiva' => 'Inactiva',
                            'mantenimiento' => 'Mantenimiento'
                        ],
                        'label' => false,
                        'class' => 'form-control'
                    ]) ?>
                </div>

                <div class="form-group">
                    <label>Capacidad</label>

                    <input type="text" id="capacidadInput" readonly>

                    <?= $this->Form->hidden('capacidad', [
                        'id' => 'capacidadHidden'
                    ]) ?>
                </div>

            </div>

            <div class="modal-registro-footer">
                <button type="button" class="btn-cancelar" onclick="cerrarModalRegistro()">Cancelar</button>
                <button type="submit" class="btn-agregar" id="submitCamara">
                    Agregar Cámara
                </button>
            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>

    <!-- MODAL ELIMINAR CAMARA -->
    <div class="cam-delete-overlay" id="modalEliminar">
        <div class="cam-delete-modal">

            <div class="cam-delete-header">
                <h3>Confirmar Eliminación</h3>
                <span class="cam-delete-close" onclick="cerrarModalEliminar()">×</span>
            </div>

            <?= $this->Form->create(null, [
                'id' => 'formEliminar',
                'method' => 'post'
            ]) ?>

            <div class="cam-delete-body">
                <p>
                    ¿Estás seguro de que deseas eliminar esta cámara?
                    Esta acción no se puede deshacer.
                </p>
            </div>

            <div class="cam-delete-footer">
                <button type="button"
                        class="cam-btn-cancelar"
                        onclick="cerrarModalEliminar()">
                    Cancelar
                </button>

                <?= $this->Form->button('Eliminar', [
                    'class' => 'cam-btn-danger'
                ]) ?>
            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    /* =========================
       MODAL VISTA CÁMARA
    ========================== */
    window.openModal = function(aula, edificio, piso, estado, capacidad, deteccion) {

        document.getElementById('modalAula').innerText = aula;
        document.getElementById('modalUbicacion').innerText = edificio + ' - Piso ' + piso;
        document.getElementById('modalHora').innerText = deteccion;

        document.getElementById('modalEstado').innerText =
            estado.charAt(0).toUpperCase() + estado.slice(1);

        document.getElementById('modalDeteccion').innerText = deteccion;
        document.getElementById('modalCapacidad').innerText = capacidad;
        document.getElementById('modalNumero').innerText = aula;

        document.getElementById('cameraModal').style.display = 'flex';
    };

    window.closeModal = function() {
        document.getElementById('cameraModal').style.display = 'none';
    };


    /* =========================
       MODAL REGISTRO - AGREGAR
    ========================== */
    window.abrirModalRegistro = function() {

        const modal = document.getElementById('modalRegistroCamara');
        const form = modal.querySelector('form');

        form.action = "<?= $this->Url->build(['action' => 'add']) ?>";

        modal.querySelector('.modal-registro-header h3').innerText = "Agregar Cámara";
        document.getElementById('submitCamara').innerText = "Agregar Cámara";

        form.reset();

        document.getElementById('capacidadInput').value = '';
        document.getElementById('capacidadHidden').value = '';

        modal.style.display = 'flex';
    };


    /* =========================
       MODAL REGISTRO - EDITAR
    ========================== */
    window.abrirModalEditar = function(id, aulaId, estado, capacidad) {

        const modal = document.getElementById('modalRegistroCamara');
        const form = modal.querySelector('form');

        form.action = "<?= $this->Url->build(['action' => 'edit']) ?>/" + id;

        modal.querySelector('.modal-registro-header h3').innerText = "Editar Cámara";
        document.getElementById('submitCamara').innerText = "Editar Cámara";

        const aulaSelect = document.getElementById('aulaSelect');
        aulaSelect.value = aulaId;

        const estadoSelect = document.querySelector('[name="estado"]');
        estadoSelect.value = estado;

        document.getElementById('capacidadInput').value = capacidad;
        document.getElementById('capacidadHidden').value = capacidad;

        modal.style.display = 'flex';
    };

    window.cerrarModalRegistro = function() {
        document.getElementById('modalRegistroCamara').style.display = 'none';
    };


    /* =========================
       MODAL ELIMINAR
    ========================== */
    window.abrirModalEliminar = function(id) {

        document.getElementById("formEliminar").action =
            "<?= $this->Url->build(['action' => 'delete']) ?>/" + id;

        document.getElementById("modalEliminar").style.display = "flex";
    };

    window.cerrarModalEliminar = function() {
        document.getElementById("modalEliminar").style.display = "none";
    };


    /* =========================
       AUTO CAPACIDAD AL CAMBIAR AULA
    ========================== */
    const aulaSelect = document.getElementById('aulaSelect');

    if (aulaSelect) {
        aulaSelect.addEventListener('change', function() {

            const selectedOption = this.options[this.selectedIndex];
            const capacidad = selectedOption.dataset.capacidad;

            document.getElementById('capacidadInput').value = capacidad || '';
            document.getElementById('capacidadHidden').value = capacidad || '';
        });
    }


    /* =========================
       CERRAR MODALES AL HACER CLICK FUERA
    ========================== */
    window.addEventListener('click', function(event) {

        const cameraModal = document.getElementById('cameraModal');
        const registroModal = document.getElementById('modalRegistroCamara');
        const modalEliminar = document.getElementById('modalEliminar');

        if (event.target === cameraModal) {
            cameraModal.style.display = 'none';
        }

        if (event.target === registroModal) {
            registroModal.style.display = 'none';
        }

        if (event.target === modalEliminar) {
            modalEliminar.style.display = 'none';
        }

    });

});


/* =========================
   AUTO CERRAR FLASH
========================= */
document.addEventListener("DOMContentLoaded", function () {
    const mensajes = document.querySelectorAll(".message");

    mensajes.forEach(function(msg) {
        setTimeout(function() {
            msg.style.opacity = "0";
            msg.style.transition = "opacity 0.5s";

            setTimeout(function() {
                msg.remove();
            }, 500);

        }, 3000);
    });
});
</script>

