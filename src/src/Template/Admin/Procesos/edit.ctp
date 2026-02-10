<?= $this->Html->script('tinymce.min'); ?>
<section id="editProcesos" class="moduleContent">
  <div class="title borderBottom">
      <h2>Editar Procesos de Incubación</h2>
  </div>
  <?= $this->Flash->render('message_procesos');?>
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
    <?= $this->Form->create($proceso, ['class'=>'addBanner','type' => 'file']) ?>
      <?php
      echo $this->Form->control('tittle1',['required'=>false,'label'=>['class'=>'form-control-label', 'text'=>'Título 1'],'placeholder'=>'Título']);
      echo $this->Form->control('description1',['required'=>false,'label'=>['class'=>'form-control-label', 'text'=>'Descripción 1'],'placeholder'=>'Descripción']);
      echo $this->Form->control('tittle2',['required'=>false,'label'=>['class'=>'form-control-label', 'text'=>'Título 2'],'placeholder'=>'Título']);
      echo $this->Form->control('description2',['required'=>false,'label'=>['class'=>'form-control-label', 'text'=>'Descripción 2'],'placeholder'=>'Descripción']);

        echo "<div class='buttons'>";
          echo $this->Html->link($this->Form->button('Cancelar',['class'=>'btn btnDanger', 'type'=>'button']), ['controller' => 'Emprendedores', 'action' => 'index'], ['escape' => false]);
          echo $this->Form->button('Guardar',['class'=>'btn btnSuccess']);
        echo "</div>";
      ?>
    <?= $this->Form->end(); ?>
  </div>
</section>
<script>
  tinymce.init({
  selector: '#description1',
  plugins: 'paste searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
  menubar: 'edit view insert format tools table help',
  toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
  });
  tinymce.init({
  selector: '#description2',
  plugins: 'paste searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
  menubar: 'edit view insert format tools table help',
  toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
  });


</script>
