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
    <?= $this->Form->create($editUser, ['id'=>'newUser']) ?>
      <?php
        echo $this->Form->control('password',['required'=>false,'label'=>['text'=>'Contraseña'],'placeholder'=>'Contraseña']);
        echo $this->Form->control('confirm_pass',['type'=>'password','required'=>false,'label'=>['text'=>'Confirmar contraseña'],'placeholder'=>'Confirmar contraseña', 'onkeyup'=>'validPass(this)']);
        echo "<div class='errorDisplay confirmError text-danger' style='display:none;'>Las contraseñas no coinciden</div>";
        echo "<div class='buttons'>";
          echo $this->Html->link($this->Form->button('Cancelar',['class'=>'btn btnDanger', 'type'=>'button']), [ 'action' => 'index'], ['escape' => false]);
          echo $this->Form->button('Editar usuario',['class'=>'btn btnSuccess']);
        echo "</div>";
      ?>
    <?= $this->Form->end(); ?>
  </div>
</section>
<script type="text/javascript">
  $('#password').val('');
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
