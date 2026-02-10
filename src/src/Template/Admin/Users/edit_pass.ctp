<section id="editUser" class="moduleContent">
  <div class="title borderBottom">
      <h2>Editar contraseña</h2>
  </div>

  <?= $this->Flash->render('message');?>

  <div class="">
    <?php
      $this->Form->setTemplates([
        'inputContainer' => '<div class="input {{required}}">{{content}}</div>',
        'input' => '<input type="{{type}}" name="{{name}}" class="form-control is-invalid" {{attrs}} />',
        'inputContainerError' => '<div class="input has-danger {{required}} error">{{content}}{{error}}</div>',
        'error'=>'<div class="text-danger">{{content}}</div>'
      ]);
    ?>

    <?= $this->Form->create($editUser, ['id'=>'editPasswordForm']) ?>

      <?php
        echo $this->Form->control('password', [
            'type' => 'password',
            'required' => false,
            'label' => ['text'=>'Contraseña'],
            'value' => ''
        ]);

        echo $this->Form->control('confirm_pass', [
            'type'=>'password',
            'required'=>false,
            'label'=>['text'=>'Confirmar contraseña'],
            'value'=>''
        ]);

        echo "<div class='errorDisplay confirmError text-danger' style='display:none;'>Las contraseñas no coinciden</div>";

        echo "<div class='buttons'>";
          echo $this->Html->link(
              $this->Form->button('Cancelar',['class'=>'btn btnDanger','type'=>'button']),
              ['action' => 'index'],
              ['escape' => false]
          );
          echo $this->Form->button('Actualizar contraseña',['class'=>'btn btnSuccess']);
        echo "</div>";
      ?>

    <?= $this->Form->end(); ?>
  </div>
</section>

<script>
(function(){
  let valid = false;

  $('#confirm-pass').on('keyup', function(){
    if ($('#password').val() === $(this).val()) {
      valid = true;
      $('.confirmError').hide();
    } else {
      valid = false;
      $('.confirmError').show();
    }
  });

  $('#editPasswordForm').on('submit', function(){
    return valid;
  });
})();
</script>
