<?= $this->Html->css('configuracion') ?>
<div class="config-container">

    <h2 class="title">Configuración</h2>

    <!-- Flash de configuración -->
    <?= $this->Flash->render('configuracion', [
        'escape' => false,
        'class' => 'message' // clase base para todos los flashes
    ]) ?>

    <!-- CUENTA -->
    <div class="card">
        <div class="card-title">
            <span class="icon">👤</span>
            Cuenta / Perfil
        </div>

        <!-- FORMULARIO NOMBRE / CORREO -->
        <?= $this->Form->create($user) ?>
        <?= $this->Form->hidden('action', ['value' => 'update_info']) ?>

        <div class="form-group">
            <label>Nombre Completo</label>
            <?= $this->Form->control('nombre_completo', ['type' => 'text', 'class' => 'form-control', 'label' => false]) ?>
        </div>

        <div class="form-group">
            <label>Correo Institucional</label>
            <?= $this->Form->control('correo', ['type' => 'email', 'class' => 'form-control', 'label' => false]) ?>
        </div>

        <div class="form-buttons" style="display:flex; gap:10px; margin-top:15px;">
            <?= $this->Form->button('Guardar', ['class' => 'btn']) ?>
            <button type="button" id="openModal" class="btn">Cambiar Contraseña</button>
        </div>
        <?= $this->Form->end() ?>
    </div>

    <!-- MODAL -->
    <div id="passwordModal" class="modal" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Cambiar Contraseña</h3>
                <span class="close">&times;</span>
            </div>

            <div class="modal-body">
                <?= $this->Form->create($user) ?>
                <?= $this->Form->hidden('action', ['value' => 'update_password']) ?>

                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <div class="input-icon">
                        <?= $this->Form->control('password1', ['type' => 'password', 'id' => 'pass1', 'label' => false, 'required' => true]) ?>
                        <span class="eye" onclick="togglePassword('pass1')">👁</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Confirmar Contraseña</label>
                    <div class="input-icon">
                        <?= $this->Form->control('password2', ['type' => 'password', 'id' => 'pass2', 'label' => false, 'required' => true]) ?>
                        <span class="eye" onclick="togglePassword('pass2')">👁</span>
                    </div>
                </div>

                <?= $this->Form->button('Cambiar Contraseña', ['class' => 'btn']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>

    <!-- TEMA -->
    <div class="card">
        <div class="card-title">
            <span class="icon">🎨</span>
            Tema
        </div>

        <p class="subtext">Selecciona el tema de la aplicación</p>

        <label class="radio">
            <input type="radio" name="tema" checked>
            Claro
        </label>

        <label class="radio">
            <input type="radio" name="tema">
            Oscuro
        </label>
    </div>


    <!-- VERSION -->
    <div class="card">
        <div class="card-title">
            <span class="icon">ℹ️</span>
            Versión del Sistema
        </div>

        <div class="info-row">
            <span>Nombre del Sistema</span>
            <strong>ClassTrack</strong>
        </div>

        <div class="info-row">
            <span>Versión</span>
            <strong>1.0.0</strong>
        </div>

        <div class="info-row">
            <span>Última actualización</span>
            <strong>Febrero 2026</strong>
        </div>
    </div>

</div>

<script>
const modal = document.getElementById("passwordModal");
const btn = document.getElementById("openModal");
const close = document.querySelector(".close");

/* ABRIR MODAL */
btn.onclick = function() {
    modal.style.display = "flex";
}

/* CERRAR MODAL */
close.onclick = function() {
    modal.style.display = "none";
}

/* CERRAR SI CLICK FUERA */
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

/* MOSTRAR / OCULTAR PASSWORD */
function togglePassword(id){
    const input = document.getElementById(id);
    input.type = (input.type === "password") ? "text" : "password";
}

/* Mantener modal abierto si hay error en password */
<?php if (!empty($openPasswordModal) && $openPasswordModal === true): ?>
modal.style.display = "flex";
<?php endif; ?>

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

<style>
    /* Base del flash */
.message {
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    text-align: center;
    font-size: 14px;
    animation: fadeIn 0.3s ease-in-out;
}

/* Éxito */
.message.success {
    background-color: #e0f7f4;
    color: #0f4d4a;
    border: 1px solid #66d9cc;
}

/* Error */
.message.error {
    background-color: #e6f0ff;
    color: #1a3d7c;
    border: 1px solid #99c2ff;
}

/* Delete u otros */
.message.delete {
    background-color: #ffeaea;
    color: #7c1a1a;
    border: 1px solid #ff9999;
}
</style>



