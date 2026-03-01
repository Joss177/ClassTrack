
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
            <h2>Iniciar Sesión</h2>
        </section>

        <!-- FORMULARIO -->
        <section class="login-form">

            <?= $this->Flash->render() ?>

            <?= $this->Form->create(null, ['class' => 'form']) ?>

                <section class="form-group">
                    <?= $this->Form->label('correo', 'Correo Electrónico') ?>
                    <?= $this->Form->control('correo', [
                        'label' => false,
                        'placeholder' => 'tu@email.com',
                        'required' => true
                    ]) ?>
                </section>

                <section class="form-group">
                    <?= $this->Form->label('password', 'Contraseña') ?>
                    <?= $this->Form->control('password', [
                        'label' => false,
                        'required' => true
                    ]) ?>
                </section>

                <section class="form-extra">
                    <a href="#">¿Olvidaste tu contraseña?</a>
                </section>

                <section class="form-actions">
                    <?= $this->Form->button('Ingresar', ['class' => 'btn-login']) ?>
                </section>

            <?= $this->Form->end() ?>
        </section>

        <!-- FOOTER -->
        <section class="login-footer">
            <p>
                ¿No tienes una cuenta?
                <?= $this->Html->link('Regístrate', ['controller' => 'Users', 'action' => 'signup']) ?>
            </p>
        </section>

    </section>
</section>

<style>
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
</script>
