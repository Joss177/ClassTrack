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
<style>
    /* ===== ESTILO GENERAL ===== */

body {
    background-color: #f5f6fa;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.gestion-container {
    width: 90%;
    margin: 30px auto;
}

.titulo {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #1f2937;
}

/* ===== CARD PRINCIPAL ===== */

.card-gestion {
    background: #ffffff;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

/* ===== TABS ===== */

.tabs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.tabs {
    display: flex;
    gap: 10px;
}

.tab {
    padding: 8px 18px;
    border-radius: 6px;
    font-weight: 500;
    color: #4b5563;
    text-decoration: none;
    transition: all 0.2s ease;
}

.tab:hover {
    color: #1e3a5f;
    transform: scale(1.05);
}

.tab.active {
    background-color: #1e3a5f;
    color: #ffffff;
}

.tab.active:hover {
    transform: none;
}

/* ===== BOTÓN AGREGAR ===== */

.btn-agregar {
    background-color: #1e3a5f;
    color: #ffffff;
    border: none;
    padding: 8px 18px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
}

.btn-agregar:hover {
    background-color: #162d49;
}

/* ===== AULAS ===== */

.aulas-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.aula-card {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 22px;
    transition: 0.2s ease;
}

.aula-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.aula-info h4 {
    margin: 0 0 8px 0;
    font-size: 18px;
    font-weight: 600;
    color: #111827;
}

.aula-info p {
    margin: 4px 0;
    font-size: 14px;
    color: #4b5563;
}

/* Línea divisoria */
.aula-card hr {
    border: none;
    border-top: 1px solid #e5e7eb;
    margin: 18px 0;
}

/* ===== BOTONES AULA ===== */

.aula-actions {
    display: flex;
    gap: 15px;
}

.aula-actions button {
    flex: 1;
    padding: 10px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid #d1d5db;
    background: #ffffff;
    transition: 0.2s ease;
}

.btn-editar {
    color: #2563eb;
}

.btn-editar:hover {
    background-color: #eff6ff;
}

.btn-eliminar {
    color: #dc2626;
}

.btn-eliminar:hover {
    background-color: #fee2e2;
}

/* ===== MODAL AGREGAR ===== */

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    display: flex; /* cambiar a none si quieres ocultarlo */
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* ===== CAJA MODAL ===== */

.modal {
    background: #f3f4f6;
    width: 420px;
    border-radius: 10px;
    padding: 25px 28px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    animation: fadeIn 0.2s ease;
}

/* Animación */
@keyframes fadeIn {
    from {
        transform: translateY(-8px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* ===== HEADER ===== */

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.modal-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
}

.modal-close {
    font-size: 18px;
    cursor: pointer;
    color: #374151;
}

.modal-close:hover {
    color: #111827;
}

/* ===== FORM ===== */

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    font-size: 14px;
    margin-bottom: 6px;
    color: #374151;
}

.form-group input {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    font-size: 14px;
    background: #ffffff;
}

.form-group input:focus {
    outline: none;
    border-color: #1e3a5f;
    box-shadow: 0 0 0 1px #1e3a5f;
}

/* ===== CHECKBOX ===== */

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 15px;
}

.checkbox-group label {
    font-size: 14px;
    color: #374151;
}

/* ===== DIVISOR ===== */

.modal hr {
    border: none;
    border-top: 1px solid #d1d5db;
    margin: 18px 0;
}

/* ===== BOTONES ===== */

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.btn-cancelar {
    padding: 8px 18px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    background: #e5e7eb;
    cursor: pointer;
}

.btn-cancelar:hover {
    background: #d1d5db;
}

.btn-guardar {
    padding: 8px 18px;
    border-radius: 6px;
    border: none;
    background: #1e3a5f;
    color: #ffffff;
    cursor: pointer;
}

.btn-guardar:hover {
    background: #162d49;
}

.modal-overlay {
    display: none;
}

/* ===== MODAL ELIMINAR ===== */

.modal-overlay {
    display: none;
}

.modal-confirm {
    background: #f3f4f6;
    width: 420px;
    border-radius: 10px;
    padding: 25px 30px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    animation: fadeIn 0.2s ease;
}

.modal-body p {
    font-size: 14px;
    color: #374151;
    line-height: 1.6;
    margin: 15px 0 25px 0;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.btn-danger {
    padding: 8px 18px;
    border-radius: 6px;
    border: none;
    background: #ef4444;
    color: #ffffff;
    cursor: pointer;
}

.btn-danger:hover {
    background: #dc2626;
}
</style>
