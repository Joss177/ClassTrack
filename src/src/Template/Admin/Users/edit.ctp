<section id="editUser" class="moduleContent">
  <div class="title borderBottom">
      <h2>Editar usuario</h2>
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
        echo $this->Form->control('name',['required'=>false,'label'=>['class'=>'form-control-label', 'text'=>'Nombre completo'],'placeholder'=>'Juan Pérez']);
        echo $this->Form->control('email',['required'=>false,'label'=>['class'=>'form-control-label', 'text'=>'Usuario'],'placeholder'=>'example@email.com']);
        echo $this->Form->control('group_id',['class'=>'form-control', 'label'=>'Grupo', 'type'=>'select', 'options'=>$groups]);
        echo "<div class='buttons'>";
          echo $this->Html->link($this->Form->button('Cancelar',['class'=>'btn btnDanger', 'type'=>'button']), ['action' => 'index'], ['escape' => false]);
          echo $this->Form->button('Editar usuario',['class'=>'btn btnSuccess']);
        echo "</div>";
      ?>
    <?= $this->Form->end(); ?>
  </div>
</section>
