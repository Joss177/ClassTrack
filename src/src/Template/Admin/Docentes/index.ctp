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

        <!-- Docente 1 -->
        <?php foreach ($docentes as $docente): ?>
            <div class="docente-card">
                <div class="docente-info">
                    <h4><?= h($docente->nombre . ' ' . $docente->apellido) ?></h4>
                    <p class="correo"><?= h($docente->email) ?></p>
                    <p class="materia">Sin materia</p>
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
            <label>Nombre</label>
            <input type="text" name="nombre" required>

            <label>Apellido</label>
            <input type="text" name="apellido" required>

            <label>Email</label>
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
            <label>Nombre</label>
            <input type="text" name="nombre" id="editNombre" required>

            <label>Apellido</label>
            <input type="text" name="apellido" id="editApellido" required>

            <label>Email</label>
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
            <button type="submit" class="btn-confirmar-eliminar">Eliminar</button>
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

/* Cards docentes */
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

/* Línea */
hr {
    border: none;
    border-top: 1px solid #e5e7eb;
    margin: 15px 0;
}

/* Acciones */
.docente-actions {
    display: flex;
    gap: 15px;
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
}

/* Editar */
.btn-editar {
    color: #2563eb;
}

.btn-editar:hover {
    background-color: #eff6ff;
}

/* Eliminar */
.btn-eliminar {
    color: #dc2626;
}

.btn-eliminar:hover {
    background-color: #fee2e2;
}
</style>

<!-- MODAL -->

<style>
    /* PARA ELIMINAR AUN USUARIO */

    /* Modal específico eliminar */
    .modal-eliminar {
        width: 420px;
    }

    /* Texto */
    .texto-eliminar {
        color: #374151;
        font-size: 14px;
        line-height: 1.6;
        margin: 10px 0 20px 0;
    }

    /* Botón rojo fuerte */
    .btn-confirmar-eliminar {
        padding: 8px 18px;
        border-radius: 6px;
        border: none;
        background: #e50914;
        color: #ffffff;
        cursor: pointer;
    }

    .btn-confirmar-eliminar:hover {
        background: #c40811;
    }


    /* Overlay oscuro */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.75);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

/* Modal */
.modal {
    background: #ffffff;
    width: 420px;
    border-radius: 10px;
    padding: 25px;
    animation: fadeIn 0.2s ease-in-out;
}

/* Header */
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
}

.close {
    font-size: 22px;
    cursor: pointer;
    color: #374151;
}

/* Body */
.modal-body {
    display: flex;
    flex-direction: column;
}

.modal-body label {
    margin-bottom: 5px;
    font-size: 14px;
    color: #374151;
}

.modal-body input {
    margin-bottom: 15px;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    font-size: 14px;
}

.modal-body input:focus {
    outline: none;
    border-color: #1e3a5f;
}

/* Footer */
.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 15px;
}

/* Botones */
.btn-cancelar {
    padding: 8px 18px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background: #ffffff;
    cursor: pointer;
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

/* Animación */
@keyframes fadeIn {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>

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



</script>
