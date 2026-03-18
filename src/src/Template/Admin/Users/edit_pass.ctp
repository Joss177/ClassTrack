
<?= $this->Html->css('login') ?>

<section class="login-page">

    <section class="login-container">

        <!-- ICONO -->
        <section class="login-icon">
            <img src="<?= $this->Url->image('LOGOCLASSTRACK.png') ?>" alt="Login Icon">
        </section>

        <!-- TITULO -->
        <section class="login-header">
            <h1>ClassTrack</h1>
            <h2>Restablecer Contraseña</h2>
        </section>

        <!-- FORMULARIO -->
        <section class="login-form">

            <?= $this->Flash->render() ?>

            <?= $this->Form->create(null, ['class' => 'form']) ?>

                <section class="form-group">
                    <?= $this->Form->label('password', 'Nueva Contraseña') ?>
                    <?= $this->Form->control('password', [
                        'label' => false,
                        'type' => 'password',
                        'placeholder' => 'Nueva contraseña',
                        'required' => true
                    ]) ?>
                </section>

                <section class="form-group">
                    <?= $this->Form->label('confirm_password', 'Confirmar Contraseña') ?>
                    <?= $this->Form->control('confirm_password', [
                        'label' => false,
                        'type' => 'password',
                        'placeholder' => 'Repite la contraseña',
                        'required' => true
                    ]) ?>
                </section>

                <section class="form-actions">
                    <?= $this->Form->button('Guardar nueva contraseña', ['class' => 'btn-login']) ?>
                </section>

            <?= $this->Form->end() ?>
        </section>

        <!-- FOOTER -->
        <section class="login-footer">
            <p>
                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login']) ?>">
                    Volver al login
                </a>
            </p>
        </section>

    </section>
</section>



<style>



    .btn-agregar,
.btn-guardar {
    background-color: #1e3a5f;
    color: #ffffff;
    border: none;
    padding: 8px 18px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-agregar:hover,
.btn-guardar:hover {
    background-color: #162d49;
}

.btn-cancelar {
    padding: 8px 18px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background: #ffffff;
    cursor: pointer;
}

.btn-cancelar:hover {
    background: #f3f4f6;
}

    .modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.75);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.modal,
.modal-confirm {
    background: #ffffff;
    width: 420px;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    animation: fadeIn 0.2s ease;
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

.modal-close,
.close {
    cursor: pointer;
    font-size: 18px;
    color: #6b7280;
}

.modal-close:hover,
.close:hover {
    color: #111827;
}

.modal-body {
    display: flex;
    flex-direction: column;
}

.modal-body label {
    margin-bottom: 5px;
    font-size: 14px;
    color: #374151;
}

.modal-body input,
.form-group input,
.form-group textarea {
    width: 100%;
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    margin-bottom: 15px;
}

.modal-body input:focus,
.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #1e3a5f;
}

.modal-footer,
.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 15px;
}


    .message {
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    text-align: center;
    font-size: 14px;
    animation: fadeIn 0.3s ease-in-out;
}

.message.error {
    background-color: #e6f0ff;
    color: #1a3d7c;
    border: 1px solid #99c2ff;
}

.message.success {
    background-color: #e0f7f4;
    color: #0f4d4a;
    border: 1px solid #66d9cc;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const message = document.querySelector('.message');
    if (message) {
        setTimeout(function () {
            message.style.transition = "opacity 0.5s ease";
            message.style.opacity = "0";
            setTimeout(function () {
                message.remove();
            }, 500);
        }, 2000); // 3 segundos
    }
});

    // AGREGAR
function abrirModal() {
    document.getElementById("modalGrupo").style.display = "flex";
}
function cerrarModal() {
    document.getElementById("modalGrupo").style.display = "none";
}

// Cerrar al hacer clic fuera
window.onclick = function(event) {
    const modales = [
        'modalGrupo',
    ];

    modales.forEach(function(id) {
        const modal = document.getElementById(id);
        if (modal && event.target === modal) {
            modal.style.display = 'none';
        }
    });
};
</script>
