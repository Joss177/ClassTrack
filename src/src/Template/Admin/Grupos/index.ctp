<?= $this->Html->css('gestion', ['block' => true]) ?>
<div class="gestion-container">

    <h2 class="titulo">Gestión</h2>

    <?= $this->Flash->render('grupo') ?>

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
                <label>
                    Nombre
                    <span class="required">*Requerido</span>
                </label>

                <?= $this->Form->control('nombre', [
                    'label' => false,
                    'class' => 'form-control',
                    'required' => true
                ]) ?>
            </div>

            <div class="form-group">
                <label>
                    Cantidad de Estudiantes
                    <span class="required">*Requerido</span>
                </label>

                <?= $this->Form->control('cantidad_estudiantes', [
                    'label' => false,
                    'type' => 'number',
                    'class' => 'form-control',
                    'required' => true
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
                <label>Nombre <span class="required">*Requerido</span></label>
                <input type="text" name="nombre" id="editar-nombre">
            </div>

            <div class="form-group">
                <label>Cantidad de Estudiantes <span class="required">*Requerido</span></label>
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

document.addEventListener("DOMContentLoaded", function () {
        const mensajes = document.querySelectorAll(".message");

        mensajes.forEach(function(msg) {
            setTimeout(function() {
                msg.style.opacity = "0";
                msg.style.transition = "opacity 0.5s";

                setTimeout(function() {
                    msg.remove();
                }, 500);

            }, 3000); // 3 segundos
        });
    });
</script>
