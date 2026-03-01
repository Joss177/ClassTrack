<?= $this->Html->css('gestion', ['block' => true]) ?>
<div class="gestion-container">

    <h2 class="titulo">Gestión</h2>

    <div class="card-gestion">

        <!-- Tabs -->
        <div class="tabs-header">
            <?php $controller = $this->request->getParam('controller'); ?>

            <div class="tabs">

                <?= $this->Html->link(
                    'Docentes',
                    ['prefix' => 'admin', 'controller' => 'Docentes', 'action' => 'index'],
                    ['class' => 'tab ' . ($controller === 'Docentes' ? 'active' : '')]
                ) ?>

                <?= $this->Html->link(
                    'Materias',
                    ['prefix' => 'admin', 'controller' => 'Materias', 'action' => 'index'],
                    ['class' => 'tab ' . ($controller === 'Materias' ? 'active' : '')]
                ) ?>

                <?= $this->Html->link(
                    'Grupos',
                    ['prefix' => 'admin', 'controller' => 'Grupos', 'action' => 'index'],
                    ['class' => 'tab ' . ($controller === 'Grupos' ? 'active' : '')]
                ) ?>

                <?= $this->Html->link(
                    'Aulas',
                    ['prefix' => 'admin', 'controller' => 'Aulas', 'action' => 'index'],
                    ['class' => 'tab ' . ($controller === 'Aulas' ? 'active' : '')]
                ) ?>

            </div>

            <div class="acciones-header">
                <button class="btn-agregar" onclick="abrirModal()">Agregar</button>
            </div>
        </div>

        <div class="aulas-list">

            <?php foreach ($aulas as $aula): ?>

                <div class="aula-card">
                    <div class="aula-info">
                        <h4><?= h($aula->nombre) ?></h4>
                        <p>Capacidad: <?= h($aula->capacidad) ?></p>
                        <p>Edificio: <?= h($aula->edificio) ?></p>
                        <p>Piso: <?= h($aula->piso) ?></p>
                        <p>
                            Cámara:
                            <?= $aula->tiene_camara ? 'Sí' : 'No' ?>
                        </p>
                    </div>

                    <hr>

                    <div class="aula-actions">

                        <!-- BOTÓN EDITAR -->
                        <button type="button"
                            class="btn-editar"
                            onclick="abrirModalEditar(
                                <?= $aula->id ?>,
                                '<?= h($aula->nombre) ?>',
                                <?= $aula->capacidad ?>,
                                <?= $aula->piso ?>,
                                '<?= h($aula->edificio) ?>',
                                <?= $aula->tiene_camara ? 1 : 0 ?>
                            )">
                            Editar
                        </button>

                        <!-- BOTÓN ELIMINAR -->
                        <button type="button"
                            class="btn-eliminar"
                            onclick="abrirModalEliminar(<?= $aula->id ?>)">
                            Eliminar
                        </button>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>



    </div>
</div>

<!-- MODAL AGREGAR -->
<div class="modal-overlay" id="modalAula">
    <div class="modal">

        <div class="modal-header">
            <h3>Agregar Aula</h3>
            <span class="modal-close" onclick="cerrarModal()">&times;</span>
        </div>

        <?= $this->Form->create(null, ['url' => ['action' => 'add']]) ?>

        <div class="modal-body">

            <div class="form-group">
                <?= $this->Form->label('nombre', 'Nombre') ?>
                <?= $this->Form->control('nombre', [
                    'label' => false,
                    'placeholder' => 'Aula 1',
                    'required' => true
                ]) ?>
            </div>

            <div class="form-group">
                <?= $this->Form->label('capacidad', 'Capacidad') ?>
                <?= $this->Form->control('capacidad', [
                    'label' => false,
                    'type' => 'number',
                    'required' => true
                ]) ?>
            </div>

            <div class="form-group">
                <?= $this->Form->label('piso', 'Piso') ?>
                <?= $this->Form->control('piso', [
                    'label' => false,
                    'type' => 'number',
                    'required' => true
                ]) ?>
            </div>

            <div class="form-group">
                <?= $this->Form->label('edificio', 'Edificio') ?>
                <?= $this->Form->control('edificio', [
                    'label' => false,
                    'required' => true
                ]) ?>
            </div>

            <div class="checkbox-group">
                <?= $this->Form->hidden('tiene_camara', ['value' => 0]) ?>
                <?= $this->Form->checkbox('tiene_camara', ['value' => 1]) ?>
                <label>Tiene cámara</label>
            </div>

            <hr>

            <div class="modal-actions">
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-guardar">Agregar</button>
            </div>

        </div>

        <?= $this->Form->end() ?>

    </div>
</div>>

<!-- MODAL EDITAR -->
<div class="modal-overlay" id="modalEditarAula">
    <div class="modal">

        <div class="modal-header">
            <h3>Editar Aula</h3>
            <span class="modal-close" onclick="cerrarModalEditar()">&times;</span>
        </div>

        <?= $this->Form->create(null, [
            'id' => 'formEditarAula',
            'url' => [
                'prefix' => 'admin',
                'controller' => 'Aulas',
                'action' => 'edit'
            ]
        ]) ?>

        <div class="modal-body">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" id="editNombre" required>
            </div>

            <div class="form-group">
                <label>Capacidad</label>
                <input type="number" name="capacidad" id="editCapacidad" required>
            </div>

            <div class="form-group">
                <label>Piso</label>
                <input type="number" name="piso" id="editPiso" required>
            </div>

            <div class="form-group">
                <label>Edificio</label>
                <input type="text" name="edificio" id="editEdificio" required>
            </div>

            <div class="checkbox-group">
                <input type="hidden" name="tiene_camara" value="0">
                <input type="checkbox" name="tiene_camara" id="editCamara" value="1">
                <label for="editCamara">Tiene cámara</label>
            </div>

            <hr>

            <div class="modal-actions">
                <button type="button" class="btn-cancelar" onclick="cerrarModalEditar()">Cancelar</button>
                <button type="submit" class="btn-guardar">Guardar</button>
            </div>

        </div>

        <?= $this->Form->end() ?>

    </div>
</div>

<!-- MODAL ELIMINAR -->
<div class="modal-overlay" id="modalEliminarAula">
    <div class="modal-confirm">

        <?= $this->Form->create(null, [
            'id' => 'formEliminarAula',
            'url' => [
                'prefix' => 'admin',
                'controller' => 'Aulas',
                'action' => 'delete'
            ]
        ]) ?>

        <div class="modal-header">
            <h3>Confirmar Eliminación</h3>
        </div>

        <div class="modal-body">
            <p>
                ¿Estás seguro de que deseas eliminar este elemento?
                Esta acción no se puede deshacer.
            </p>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn-cancelar" onclick="cerrarModalEliminar()">Cancelar</button>
            <button type="submit" class="btn-danger">Eliminar</button>
        </div>

        <?= $this->Form->end() ?>

    </div>
</div>

<script>

    // ===== AGREGAR =====
    function abrirModal() {
        document.getElementById("modalAula").style.display = "flex";
    }

    function cerrarModal() {
        document.getElementById("modalAula").style.display = "none";
    }


    // ===== EDITAR =====
    function abrirModalEditar(id, nombre, capacidad, piso, edificio, camara) {

        // Construir URL correctamente con ID
        var urlEditar = "<?= $this->Url->build(['prefix' => 'admin', 'controller' => 'Aulas', 'action' => 'edit']) ?>/" + id;
        document.getElementById("formEditarAula").action = urlEditar;

        // Asignar valores al formulario
        document.getElementById("editNombre").value = nombre;
        document.getElementById("editCapacidad").value = capacidad;
        document.getElementById("editPiso").value = piso;
        document.getElementById("editEdificio").value = edificio;
        document.getElementById("editCamara").checked = (camara == 1);

        document.getElementById("modalEditarAula").style.display = "flex";
    }

    function cerrarModalEditar() {
        document.getElementById("modalEditarAula").style.display = "none";
    }


    // ===== ELIMINAR =====
    function abrirModalEliminar(id) {

        // Construir URL correctamente con ID
        var urlEliminar = "<?= $this->Url->build(['prefix' => 'admin', 'controller' => 'Aulas', 'action' => 'delete']) ?>/" + id;
        document.getElementById("formEliminarAula").action = urlEliminar;

        document.getElementById("modalEliminarAula").style.display = "flex";
    }

    function cerrarModalEliminar() {
        document.getElementById("modalEliminarAula").style.display = "none";
    }


    // ===== CERRAR AL HACER CLICK FUERA =====
    window.onclick = function(event) {

        const modales = [
            'modalAula',
            'modalEditarAula',
            'modalEliminarAula'
        ];

        modales.forEach(function(id) {
            const modal = document.getElementById(id);
            if (modal && event.target === modal) {
                modal.style.display = 'none';
            }
        });
    };

</script>

