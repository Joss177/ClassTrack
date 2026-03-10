<h3>Confirmar contraseña</h3>

<?= $this->Form->create() ?>

<?= $this->Form->control('password', [
    'type' => 'password',
    'label' => 'Ingresa tu contraseña'
]) ?>

<?= $this->Form->button('Verificar') ?>

<?= $this->Form->end() ?>
