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


