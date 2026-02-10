<section id="signup" class="moduleContent">
  <div class="title borderBottom">
      <h2>Agregar usuario</h2>
  </div>
  <?= $this->Flash->render('message');?>
  <div>
    <?php
      $this->Form->setTemplates([
        'inputContainer' => '<div class="input {{required}}">{{content}}</div>',
        'input' => '<input type="{{type}}" name="{{name}}" class="form-control is-invalid" {{attrs}} />',
        'inputContainerError' => '<div class="input has-danger {{required}} error">{{content}}{{error}}</div>',
        'error'=>'<div class="text-danger">{{content}}</div>'
      ]);
      ?>
    <?= $this->Form->create($sign_up, ['id'=>'newUser']) ?>
      <?php
        echo $this->Form->control('name',['required'=>false,'label'=>['text'=>'Nombre completo'],'placeholder'=>'Juan Pérez']);
        echo $this->Form->control('email',['required'=>false,'label'=>['text'=>'Usuario'],'placeholder'=>'example@email.com']);
        echo $this->Form->control('password',['required'=>false,'label'=>['text'=>'Contraseña'],'placeholder'=>'Contraseña']);
        echo $this->Form->control('confirm_pass',['type'=>'password','required'=>false,'label'=>['text'=>'Confirmar contraseña'],'placeholder'=>'Confirmar contraseña', 'onkeyup'=>'validPass(this)']);
        echo "<div class='errorDisplay confirmError text-danger' style='display:none;'>Las contraseñas no coinciden</div>";
        echo $this->Form->control('group_id',['class'=>'form-control', 'label'=>'Grupo', 'type'=>'select', 'options'=>$groups]);
        echo "<div class='buttons'>";
          echo $this->Html->link($this->Form->button('Cancelar',['class'=>'btn btnDanger', 'type'=>'button']), [ 'action' => 'index'], ['escape' => false]);
          echo $this->Form->button('Crear usuario',['class'=>'btn btnSuccess saveButton']);
        echo "</div>";
      ?>
    <?= $this->Form->end(); ?>
  </div>
</section>

<script type="text/javascript">

  var submit = false;

  function validPass(val){
    var pass = $('#password').val();
    if(pass == val.value){
      submit = true;
      $('.confirmError').css('display','none');
    }else{
      submit = false;
      $('.confirmError').css('display','block');
    }
  }

  $('#newUser').submit(function(e){
    return submit;
  });

</script>
