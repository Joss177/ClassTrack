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

        <!-- LISTADO DE MATERIAS -->
        <div class="materias-list">

        <?php foreach ($materias as $m): ?>

            <div class="materia-card">

                <div class="materia-info">
                    <h4><?= h($m->nombre) ?></h4>
                    <p class="clave"><?= h($m->codigo) ?></p>

                    <div class="color-box">
                        <span class="color-preview"
                            style="background:<?= h($m->color) ?>;">
                        </span>
                        <span class="color-code"><?= h($m->color) ?></span>
                    </div>
                </div>

                <hr>

                <div class="materia-actions">


                    <button
                        class="btn-editar"
                        data-id="<?= $m->id ?>"
                        data-nombre="<?= h($m->nombre) ?>"
                        data-codigo="<?= h($m->codigo) ?>"
                        data-descripcion="<?= h($m->descripcion) ?>"
                        data-color="<?= h($m->color) ?>"
                        onclick="abrirModalEditar(this)">
                        ✏ Editar
                    </button>

                    <button
                        class="btn-eliminar"
                        onclick="abrirModalEliminar(<?= $m->id ?>)">
                        🗑 Eliminar
                    </button>

                </div>
            </div>

        <?php endforeach; ?>

        </div>
    </div>
</div>

<!-- MODAL AGREGAR MATERIA -->
<div class="modal-overlay" id="modalMateria">

    <div class="modal">

        <div class="modal-header">
            <h3>Agregar Materia</h3>
            <span class="modal-close" onclick="cerrarModal()">×</span>
        </div>

        <?= $this->Form->create($materia) ?>

        <div class="modal-body">

            <div class="form-group">
                <?= $this->Form->label('nombre', 'Nombre') ?>
                <?= $this->Form->control('nombre', [
                    'label' => false,
                    'class' => 'form-input'
                ]) ?>
            </div>

            <div class="form-group">
                <?= $this->Form->label('codigo', 'Código') ?>
                <?= $this->Form->control('codigo', [
                    'label' => false,
                    'class' => 'form-input'
                ]) ?>
            </div>

            <div class="form-group">
                <?= $this->Form->label('descripcion', 'Descripción') ?>
                <?= $this->Form->control('descripcion', [
                    'type' => 'textarea',
                    'rows' => 4,
                    'label' => false,
                    'class' => 'form-input'
                ]) ?>
            </div>

            <div class="form-group">
                <?= $this->Form->label('color', 'Color') ?>
                <?= $this->Form->control('color', [
                    'type' => 'color',
                    'label' => false,
                    'value' => '#1e3a5f',
                    'class' => 'form-input'
                ]) ?>
            </div>

        </div>

        <hr>

        <div class="modal-footer">
            <button type="button" class="btn-cancelar" onclick="cerrarModal()">
                Cancelar
            </button>

            <?= $this->Form->button('Agregar', [
                'class' => 'btn-guardar'
            ]) ?>
        </div>

        <?= $this->Form->end() ?>

    </div>

</div>

<!-- MODAL EDITAR MATERIA -->
<div class="modal-overlay" id="modalEditarMateria">

    <div class="modal">

        <div class="modal-header">
            <h3>Editar Materia</h3>
            <span class="modal-close" onclick="cerrarModalEditar()">×</span>
        </div>

        <?= $this->Form->create(null, [
            'id' => 'formEditarMateria',
            'method' => 'post'
        ]) ?>

        <?= $this->Form->hidden('id', ['id' => 'edit-id']) ?>

        <div class="modal-body">

            <div class="form-group">
                <?= $this->Form->label('nombre', 'Nombre') ?>
                <?= $this->Form->control('nombre', [
                    'label' => false,
                    'class' => 'form-input',
                    'id' => 'edit-nombre'
                ]) ?>
            </div>

            <div class="form-group">
                <?= $this->Form->label('codigo', 'Código') ?>
                <?= $this->Form->control('codigo', [
                    'label' => false,
                    'class' => 'form-input',
                    'id' => 'edit-codigo'
                ]) ?>
            </div>

            <div class="form-group">
                <?= $this->Form->label('descripcion', 'Descripción') ?>
                <?= $this->Form->control('descripcion', [
                    'type' => 'textarea',
                    'rows' => 4,
                    'label' => false,
                    'class' => 'form-input',
                    'id' => 'edit-descripcion'
                ]) ?>
            </div>

            <div class="form-group">
                <?= $this->Form->label('color', 'Color') ?>
                <?= $this->Form->control('color', [
                    'type' => 'color',
                    'label' => false,
                    'class' => 'form-input',
                    'id' => 'edit-color'
                ]) ?>
            </div>

        </div>

        <hr>

        <div class="modal-footer">
            <button type="button" class="btn-cancelar" onclick="cerrarModalEditar()">
                Cancelar
            </button>

            <?= $this->Form->button('Guardar', [
                'class' => 'btn-guardar'
            ]) ?>
        </div>

        <?= $this->Form->end() ?>

    </div>

</div>

<!-- MODAL ELIMINAR -->
<div class="modal-overlay" id="modalEliminar">
    <div class="modal-confirm">

        <h3>Confirmar Eliminación</h3>

        <p>
            ¿Estás seguro de que deseas eliminar este elemento?
            Esta acción no se puede deshacer.
        </p>

        <?= $this->Form->create(null, [
            'id' => 'formEliminar',
            'method' => 'post'
        ]) ?>

        <div class="modal-actions">
            <button type="button" class="btn-cancelar" onclick="cerrarModalEliminar()">
                Cancelar
            </button>

            <?= $this->Form->button('Eliminar', ['class' => 'btn-danger']) ?>
        </div>

        <?= $this->Form->end() ?>

    </div>
</div>

<script>
    /* MODAL AGREGAR */
    function abrirModal() {
        document.getElementById('modalMateria').style.display = 'flex';
    }

    function cerrarModal() {
        document.getElementById('modalMateria').style.display = 'none';
    }

    /* MODAL EDITAR */

   function abrirModalEditar(btn) {

        let id = btn.dataset.id;
        let nombre = btn.dataset.nombre;
        let codigo = btn.dataset.codigo;
        let descripcion = btn.dataset.descripcion;
        let color = btn.dataset.color;

        document.getElementById('edit-id').value = id;
        document.getElementById('edit-nombre').value = nombre;
        document.getElementById('edit-codigo').value = codigo;
        document.getElementById('edit-descripcion').value = descripcion;
        document.getElementById('edit-color').value = color;

        document.getElementById('formEditarMateria').action =
            "<?= $this->Url->build(['action' => 'edit']) ?>/" + id;

        document.getElementById('modalEditarMateria').style.display = 'flex';
    }

    //MODAL ELIMINAR

    function abrirModalEliminar(id) {

        document.getElementById('formEliminar').action =
            "<?= $this->Url->build(['action' => 'delete']) ?>/" + id;

        document.getElementById('modalEliminar').style.display = 'flex';
    }

    function cerrarModalEliminar() {
        document.getElementById('modalEliminar').style.display = 'none';
    }

    // Cerrar al hacer clic fuera del contenido (overlay)
    window.onclick = function(event) {

        const modales = [
            'modalMateria',
            'modalEditarMateria',
            'modalEliminar'
        ];

        modales.forEach(function(id) {
            const modal = document.getElementById(id);
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

    };


</script>

<style>
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

/* Card principal */
.card-gestion {
    background: #ffffff;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

/* Tabs */
.tabs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
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
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
}

.tab:hover {
    color: #1e3a5f;
    transform: scale(1.05);
}

.tab.active:hover {
    transform: none;
    background-color: #1e3a5f;
}

.tab.active {
    background-color: #1e3a5f;
    color: #ffffff;
}

/* Botón agregar */
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

/* Línea divisoria */
hr {
    border: none;
    border-top: 1px solid #e5e7eb;
    margin: 15px 0;
}

/* ===== CARDS DOCENTES ===== */
.docente-card {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 18px;
    margin-bottom: 20px;
}

.docente-info h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #111827;
}

.correo {
    margin: 5px 0;
    color: #374151;
    font-size: 14px;
}

.materia {
    color: #6b7280;
    font-size: 14px;
}

.docente-actions {
    display: flex;
    gap: 15px;
}

/* ===== CARDS MATERIAS ===== */
.materias-list {
    margin-top: 10px;
}

.materia-card {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    transition: 0.2s ease;
}

.materia-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.materia-info h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #111827;
}

.clave {
    margin: 6px 0 10px 0;
    font-size: 14px;
    color: #4b5563;
}

/* Color preview */
.color-box {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 5px;
}

.color-preview {
    width: 22px;
    height: 22px;
    border-radius: 4px;
}

.color-code {
    font-size: 14px;
    color: #6b7280;
}

/* ===== BOTONES GENERALES ===== */
.btn-editar,
.btn-eliminar {
    flex: 1;
    padding: 10px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid #d1d5db;
    background: #ffffff;
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

.materia-actions {
    display: flex;
    gap: 15px;
}

/* CSS MODAL AGREGAR */

/* ===== MODAL ===== */

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 999;
}

.modal {
    background: #ffffff;
    width: 420px;
    border-radius: 10px;
    padding: 22px 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from {
        transform: translateY(-10px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.modal-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
}

.modal-close {
    cursor: pointer;
    font-size: 18px;
    color: #6b7280;
}

.modal-close:hover {
    color: #111827;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-size: 14px;
    margin-bottom: 6px;
    color: #374151;
}

.form-group input[type="text"],
.form-group textarea {
    width: 100%;
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    font-size: 14px;
}

.form-group input[type="text"]:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #1e3a5f;
    box-shadow: 0 0 0 1px #1e3a5f;
}

.form-group input[type="color"] {
    width: 100%;
    height: 38px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    padding: 2px;
    cursor: pointer;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 15px;
}

.btn-cancelar {
    padding: 8px 16px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background: #ffffff;
    cursor: pointer;
}

.btn-cancelar:hover {
    background: #f3f4f6;
}

.btn-guardar {
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    background: #1e3a5f;
    color: #ffffff;
    cursor: pointer;
}

.btn-guardar:hover {
    background: #162d49;
}

/* MODAL ELIMINAR */

/* Fondo oscuro */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Caja modal */
.modal-confirm {
    background: #f3f4f6;
    width: 420px;
    border-radius: 10px;
    padding: 25px 30px;
    font-family: Arial, sans-serif;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Título */
.modal-confirm h3 {
    margin: 0 0 15px 0;
    font-size: 18px;
    color: #1f2937;
}

/* Texto */
.modal-confirm p {
    font-size: 14px;
    color: #374151;
    line-height: 1.5;
    margin-bottom: 25px;
}

/* Botones alineados a la derecha */
.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* Botón cancelar */
.btn-cancelar {
    padding: 8px 18px;
    border-radius: 5px;
    border: 1px solid #cbd5e1;
    background: #e5e7eb;
    cursor: pointer;
}

/* Botón eliminar rojo */
.btn-danger {
    padding: 8px 18px;
    border-radius: 5px;
    border: none;
    background: #ef4444;
    color: white;
    cursor: pointer;
}

/* Hover */
.btn-danger:hover {
    background: #dc2626;
}

.btn-cancelar:hover {
    background: #d1d5db;
}
</style>
