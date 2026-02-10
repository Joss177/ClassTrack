<?php $this->layout = 'empty';
$this->assign('title', 'Panel de administrador | OnePage');
?>
<section id="login">
  <div class="loginContainer">
    <?= $this->Html->image('admin/logo_admin.png', ['alt' => 'Logo', 'class'=>'logoAdmin']) ?>
    <?php
      $this->Form->setTemplates([
        'inputContainer' => '<div class="input{{required}}">{{content}}</div>',
        'input' => '<input type="{{type}}" name="{{name}}" class="form-control is-invalid" {{attrs}} />',
        'inputContainerError' => '<div class="input has-danger {{required}} error">{{content}}{{error}}</div>',
        'error'=>'<div class="text-danger">{{content}}</div>'
      ]);
      ?>
    <?= $this->Form->create($login, ['class'=>'loginForm']) ?>
      <legend>Panel de administrador</legend>
      <?= $this->Flash->render();?>
      <?php
        echo $this->Form->control('email',['required'=>false, 'type'=>'text','label'=>'Usuario','placeholder'=>'example@email.com']);
        echo $this->Form->control('password',['required'=>false,'label'=>'Contraseña','placeholder'=>'Contraseña']);
        echo $this->Form->button('Iniciar sesión',['class'=>'btn btn-success btn-block formButton']);
      ?>
    <?= $this->Form->end(); ?>
  </div>
</section>
