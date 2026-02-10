<section id="editRedes" class="moduleContent">
  <div class="title borderBottom">
      <h2>Editar Redes Sociales</h2>
  </div>
  <?= $this->Flash->render('message_redes');?>
  <div class="bannerContainer">
    <?php
      $this->Form->setTemplates([
        'inputContainer' => '<div class="input {{required}}">{{content}}</div>',
        'input' => '<input type="{{type}}" name="{{name}}" class="form-control is-invalid" {{attrs}} />',
        'inputContainerError' => '<div class="form-group has-danger col-xl-6 col2 {{required}} error">{{content}}{{error}}</div>',
        'checkboxContainer' => '<div class="input checkbox-container">{{content}}</div>',
        'nestingLabel' => '{{hidden}}<label {{attrs}}>{{input}}{{text}}<span class="content-check"></span></label>',
        'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
        'error'=>'<div class="text-danger">{{content}}</div>'
      ]);
      ?>
    <?= $this->Form->create($rede, ['class'=>'editRedes','type' => 'file']) ?>
      <?php
      echo $this->Form->control('facebook',['required'=>false,'label'=>['class'=>'form-control-label', 'text'=>'Facebook'],'placeholder'=>'Link']);
      echo $this->Form->control('whatsapp',['required'=>false,'label'=>['class'=>'form-control-label', 'text'=>'WhatsApp'],'onKeyPress'=>'return soloNumeros(event)','maxlength'=>'10','placeholder'=>'Numero']);
      echo $this->Form->control('instagram',['required'=>false,'label'=>['class'=>'form-control-label', 'text'=>'Instagram'],'placeholder'=>'Link']);
        echo "<div class='buttons'>";
          echo $this->Html->link($this->Form->button('Cancelar',['class'=>'btn btnDanger', 'type'=>'button']), ['controller' => 'Emprendedores', 'action' => 'index'], ['escape' => false]);
          echo $this->Form->button('Guardar',['class'=>'btn btnSuccess']);
        echo "</div>";
      ?>
    <?= $this->Form->end(); ?>
  </div>
</section>
