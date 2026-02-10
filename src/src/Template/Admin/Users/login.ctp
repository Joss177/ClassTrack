
<?= $this->Html->css('login') ?>

<section class="login-page">

    <section class="login-container">

        <!-- ICONO -->
        <section class="login-icon">
            <img src="<?= $this->Url->image('login-icon.png') ?>" alt="Login Icon">
        </section>

        <!-- TITULO -->
        <section class="login-header">
            <h1>ClassTrack</h1>
            <h2>Iniciar Sesión</h2>
        </section>

        <!-- FORMULARIO -->
        <section class="login-form">
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
            <p>¿No tienes una cuenta? <a href="#">Regístrate</a></p>
        </section>

    </section>
</section>

<!-- BOTÓN PROVISIONAL EDIT -->
<section class="login-footer" style="margin-top: 20px; text-align: center;">
    <?= $this->Html->link(
        'Editar usuario (temporal)',
        [
            'prefix' => 'admin',
            'controller' => 'Users',
            'action' => 'edit_pass',
            1 // ID del usuario a editar
        ],
        [
            'class' => 'btn-login',
            'style' => 'display: inline-block; text-decoration: none;'
        ]
    ) ?>
</section>
