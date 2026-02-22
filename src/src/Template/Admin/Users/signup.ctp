<!DOCTYPE html>
<html lang="es">
<head>
    <?= $this->Html->charset() ?>
    <title>ClassTrack - Crear Cuenta</title>
    <?= $this->Html->css('signup.css') ?>
</head>

<body>

<div class="login-container">

    <!-- ICONO -->
    <div class="login-icon">
        <?= $this->Html->image('LOGOCLASSTRACK.png') ?>
    </div>

    <h2 class="brand-title">ClassTrack</h2>
    <p class="subtitle">Crear Cuenta</p>

    <?= $this->Form->create($user, ['class' => 'login-form']) ?>

<div class="form-group">
    <label>Nombre Completo</label>
    <?= $this->Form->text('nombre_completo', [
        'placeholder' => 'Juan Pérez'
    ]) ?>
    <?= $this->Form->error('nombre_completo') ?>
</div>

<div class="form-group">
    <label>Correo Electrónico</label>
    <?= $this->Form->email('correo', [
        'placeholder' => 'tu@email.com'
    ]) ?>
    <?= $this->Form->error('correo') ?>
</div>

<div class="form-group password-field">
    <label>Contraseña</label>
    <?= $this->Form->password('password', [
        'placeholder' => 'Mínimo 6 caracteres'
    ]) ?>
    <span class="eye">👁</span>
    <?= $this->Form->error('password') ?>
</div>

<div class="form-group password-field">
    <label>Confirmar Contraseña</label>
    <?= $this->Form->password('confirm_password', [
                'placeholder' => 'Repite tu contraseña'
            ]) ?>
        </div>

        <button type="submit" class="btn-primary">
            Registrarse
        </button>

    <?= $this->Form->end() ?>

    <p class="login-link">
        ¿Ya tienes una cuenta?
        <?= $this->Html->link('Inicia Sesión', ['controller' => 'Users', 'action' => 'login']) ?>
    </p>

</div>

</body>
</html>
