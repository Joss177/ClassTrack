<?= $this->Html->css('gestion', ['block' => true]) ?>
<div class="gestion-container">

    <h2 class="titulo">Gestión</h2>

    <?= $this->Flash->render('docente', ['escape' => false]) ?>

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

        <!-- Docente 1 -->
        <?php foreach ($docentes as $docente): ?>
            <div class="docente-card">
                <div class="docente-info">
                    <h4><?= h($docente->nombre . ' ' . $docente->apellido) ?></h4>
                    <p class="correo"><?= h($docente->email) ?></p>
                </div>

                <hr>

                <div class="docente-actions">
                    <button class="btn-editar"
                        onclick="abrirModalEditar(
                            '<?= h($docente->nombre) ?>',
                            '<?= h($docente->apellido) ?>',
                            '<?= h($docente->email) ?>',
                            <?= $docente->id ?>
                        )">
                        ✏ Editar
                    </button>

                    <button class="btn-eliminar"
                        onclick="abrirModalEliminar(<?= $docente->id ?>)">
                        🗑 Eliminar
                    </button>
                </div>
            </div>
        <?php endforeach; ?>



    </div>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="modalDocente">
    <div class="modal">

        <div class="modal-header">
            <h3>Agregar Docente</h3>
            <span class="close" onclick="cerrarModal()">×</span>
        </div>

        <?= $this->Form->create(null, [
            'url' => ['action' => 'add'],
            'method' => 'post'
        ]) ?>

        <div class="modal-body">

            <label>Nombre <span class="required">*Requerido</span></label>
            <input type="text" name="nombre" required>

            <label>Apellido <span class="required">*Requerido</span></label>
            <input type="text" name="apellido" required>

            <label>Email <span class="required">*Requerido</span></label>
            <input type="email" name="email" required>

        </div>

        <hr>

        <div class="modal-footer">
            <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
            <button type="submit" class="btn-guardar">Agregar</button>
        </div>

        <?= $this->Form->end() ?>

    </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal-overlay" id="modalEditar">
    <div class="modal">

        <div class="modal-header">
            <h3>Editar Docente</h3>
            <span class="close" onclick="cerrarModalEditar()">×</span>
        </div>

        <?= $this->Form->create(null, [
            'id' => 'formEditar',
            'method' => 'post'
        ]) ?>

        <div class="modal-body">
            <label>Nombre <span class="required">*Requerido</span></label>
            <input type="text" name="nombre" id="editNombre" required>

            <label>Apellido <span class="required">*Requerido</span></label>
            <input type="text" name="apellido" id="editApellido" required>

            <label>Email <span class="required">*Requerido</span></label>
            <input type="email" name="email" id="editEmail" required>
        </div>

        <hr>

        <div class="modal-footer">
            <button type="button" class="btn-cancelar" onclick="cerrarModalEditar()">Cancelar</button>
            <button type="submit" class="btn-guardar">Guardar</button>
        </div>

        <?= $this->Form->end() ?>

    </div>
</div>

<!-- MODAL ELIMINAR -->
<div class="modal-overlay" id="modalEliminar">
    <div class="modal modal-eliminar">

        <div class="modal-header">
            <h3>Confirmar Eliminación</h3>
        </div>

        <?= $this->Form->create(null, [
            'id' => 'formEliminar',
            'method' => 'post'
        ]) ?>

        <div class="modal-body">
            <p class="texto-eliminar">
                ¿Estás seguro de que deseas eliminar este elemento?
                Esta acción no se puede deshacer.
            </p>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-cancelar" onclick="cerrarModalEliminar()">Cancelar</button>
            <?= $this->Form->button('Eliminar', ['class' => 'btn-danger']) ?>
        </div>

        <?= $this->Form->end() ?>

    </div>
</div>




<!-- Script del modal -->

<script>

    //PARA CREAR UN DOCENTE
    function abrirModal() {
        document.getElementById("modalDocente").style.display = "flex";
    }

    function cerrarModal() {
        document.getElementById("modalDocente").style.display = "none";
    }

    /* Cerrar si se hace click fuera del modal */
    window.onclick = function(event) {
        const modal = document.getElementById("modalDocente");
        if (event.target === modal) {
            cerrarModal();
        }
    }

    //PARA EDITAR UN DOCENTE

    function abrirModalEditar(nombre, apellido, email, id) {

        document.getElementById("editNombre").value = nombre;
        document.getElementById("editApellido").value = apellido;
        document.getElementById("editEmail").value = email;

        document.getElementById("formEditar").action =
            "<?= $this->Url->build(['action' => 'edit']) ?>/" + id;

        document.getElementById("modalEditar").style.display = "flex";
    }

    function cerrarModalEditar() {
        document.getElementById("modalEditar").style.display = "none";
    }

    /* Cerrar al hacer click fuera */
    window.addEventListener("click", function(event) {
        const modalEditar = document.getElementById("modalEditar");
        if (event.target === modalEditar) {
            cerrarModalEditar();
        }
    });

    //PARA ELIMINAR UN DOCENTE

    function abrirModalEliminar(id) {

        document.getElementById("formEliminar").action =
            "<?= $this->Url->build(['action' => 'delete']) ?>/" + id;

        document.getElementById("modalEliminar").style.display = "flex";
    }

    function cerrarModalEliminar() {
        document.getElementById("modalEliminar").style.display = "none";
    }

    /* Cerrar si se hace click fuera */
    window.addEventListener("click", function(event) {
        const modalEliminar = document.getElementById("modalEliminar");
        if (event.target === modalEliminar) {
            cerrarModalEliminar();
        }
    });

    /*Para cerrar el flash despues de 3 segundos */

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
