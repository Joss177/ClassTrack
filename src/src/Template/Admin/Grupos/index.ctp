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

        <div class="grupos-list">

            <?php foreach ($grupos as $grupo): ?>

                <div class="grupo-card">
                    <div class="grupo-info">
                        <h4><?= h($grupo->nombre) ?></h4>
                        <p class="grupo-estudiantes">
                            Estudiantes: <?= h($grupo->cantidad_estudiantes) ?>
                        </p>
                    </div>

                    <div class="grupo-actions">
                        <button
                            class="btn-editar"
                            onclick="abrirModalEditar(
                                <?= $grupo->id ?>,
                                '<?= h($grupo->nombre) ?>',
                                <?= $grupo->cantidad_estudiantes ?>
                            )">
                            Editar
                        </button>

                        <button
                            class="btn-eliminar"
                            onclick="abrirModalEliminar(<?= $grupo->id ?>)">
                            Eliminar
                        </button>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

    </div>
</div>


<!-- MODAL AGREGAR GRUPO -->
<div class="modal-overlay" id="modalGrupo">
    <div class="modal">

        <div class="modal-header">
            <h3>Agregar Grupo</h3>
            <span class="modal-close" onclick="cerrarModal()">×</span>
        </div>

        <?= $this->Form->create(null, [
            'url' => ['action' => 'add']
        ]) ?>

        <div class="modal-body">

            <div class="form-group">
                <label>Nombre</label>
                <?= $this->Form->control('nombre', [
                    'label' => false,
                    'class' => 'form-control'
                ]) ?>
            </div>

            <div class="form-group">
                <label>Cantidad de Estudiantes</label>
                <?= $this->Form->control('cantidad_estudiantes', [
                    'label' => false,
                    'type' => 'number'
                ]) ?>
            </div>

        </div>

        <hr>

        <div class="modal-footer">
            <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
            <button type="submit" class="btn-guardar">Agregar</button>
        </div>

        <?= $this->Form->end() ?>

    </div>
</div>

<!-- MODAL EDITAR GRUPO -->
<div class="modal-overlay" id="modalEditarGrupo">
    <div class="modal">

        <div class="modal-header">
            <h3>Editar Grupo</h3>
            <span class="modal-close" onclick="cerrarModalEditar()">×</span>
        </div>

        <?= $this->Form->create(null, [
            'id' => 'formEditar',
            'url' => ['action' => 'edit']
        ]) ?>

        <?= $this->Form->hidden('id', ['id' => 'editar-id']) ?>

        <div class="modal-body">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" id="editar-nombre">
            </div>

            <div class="form-group">
                <label>Cantidad de Estudiantes</label>
                <input type="number" name="cantidad_estudiantes" id="editar-cantidad">
            </div>

        </div>

        <hr>

        <div class="modal-footer">
            <button type="button" class="btn-cancelar" onclick="cerrarModalEditar()">Cancelar</button>
            <button type="submit" class="btn-guardar">Guardar</button>
        </div>

        <?= $this->Form->end() ?>

    </div>
</div>


<!-- MODAL CONFIRMAR ELIMINACIÓN -->
<div class="modal-overlay" id="modalEliminar">

    <div class="modal-confirm">

        <div class="modal-header">
            <h3>Confirmar Eliminación</h3>
        </div>

        <div class="modal-body">
            <p>
                ¿Estás seguro de que deseas eliminar este elemento?
                Esta acción no se puede deshacer.
            </p>
        </div>

        <?= $this->Form->create(null, [
            'id' => 'formEliminar',
            'url' => ['action' => 'delete']
        ]) ?>

        <?= $this->Form->hidden('id', ['id' => 'eliminar-id']) ?>

        <div class="modal-actions">
            <button type="button" class="btn-cancelar" onclick="cerrarModalEliminar()">Cancelar</button>
            <button type="submit" class="btn-danger">Eliminar</button>
        </div>

        <?= $this->Form->end() ?>

    </div>
</div>

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
    margin: 20px 0;
}

/* ===== GRUPOS ===== */

.grupos-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.grupo-card {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
    transition: 0.2s ease;
}

.grupo-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.grupo-info h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #111827;
}

.grupo-estudiantes {
    margin-top: 6px;
    font-size: 14px;
    color: #4b5563;
}

/* Acciones */
.grupo-actions {
    display: flex;
    gap: 15px;
    margin-top: 15px;
}

.btn-editar,
.btn-eliminar {
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

/* MODAL AGREGAR */

/* ===== OVERLAY ===== */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.75);
    display: none; /* se muestra con JS */
    justify-content: center;
    align-items: center;
    z-index: 999;
}

/* ===== CAJA MODAL ===== */
.modal {
    background: #ffffff;
    width: 420px;
    border-radius: 10px;
    padding: 22px 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
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

/* Header */
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
    font-size: 18px;
    cursor: pointer;
    color: #6b7280;
}

.modal-close:hover {
    color: #111827;
}

/* Body */
.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-size: 14px;
    margin-bottom: 6px;
    color: #374151;
}

.form-group input {
    width: 100%;
    padding: 9px 10px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    background: #f9fafb;
}

.form-group input:focus {
    outline: none;
    border-color: #1e3a5f;
    box-shadow: 0 0 0 1px #1e3a5f;
    background: #ffffff;
}

/* Línea divisoria */
.modal hr {
    border: none;
    border-top: 1px solid #e5e7eb;
    margin: 15px 0;
}

/* Footer */
.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

/* Botones */
.btn-cancelar {
    padding: 8px 16px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background: #f3f4f6;
    cursor: pointer;
}

.btn-cancelar:hover {
    background: #e5e7eb;
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

/*MODAL ELIMINAR */

/* Overlay */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Caja */
.modal-confirm {
    background: #f3f4f6;
    width: 420px;
    border-radius: 10px;
    padding: 25px 30px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    animation: fadeIn 0.2s ease;
}

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

.modal-header h3 {
    margin: 0 0 15px 0;
    font-size: 18px;
    color: #1f2937;
}

.modal-body p {
    font-size: 14px;
    color: #374151;
    line-height: 1.5;
    margin-bottom: 25px;
}

/* Botones */
.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
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

<script>
    // AGREGAR
function abrirModal() {
    document.getElementById("modalGrupo").style.display = "flex";
}
function cerrarModal() {
    document.getElementById("modalGrupo").style.display = "none";
}

// EDITAR
function abrirModalEditar(id, nombre, cantidad) {
    document.getElementById("modalEditarGrupo").style.display = "flex";

    document.getElementById("editar-id").value = id;
    document.getElementById("editar-nombre").value = nombre;
    document.getElementById("editar-cantidad").value = cantidad;

    document.getElementById("formEditar").action = "/admin/grupos/edit/" + id;
}

function cerrarModalEditar() {
    document.getElementById("modalEditarGrupo").style.display = "none";
}

// ELIMINAR
function abrirModalEliminar(id) {
    document.getElementById("modalEliminar").style.display = "flex";

    document.getElementById("eliminar-id").value = id;
    document.getElementById("formEliminar").action = "/admin/grupos/delete/" + id;
}

function cerrarModalEliminar() {
    document.getElementById("modalEliminar").style.display = "none";
}

// Cerrar al hacer clic fuera
window.onclick = function(event) {
    const modales = [
        'modalGrupo',
        'modalEditarGrupo',
        'modalEliminar'
    ];

    modales.forEach(function(id) {
        const modal = document.getElementById(id);
        if (modal && event.target === modal) {
            modal.style.display = 'none';
        }
    });
};
</script>
