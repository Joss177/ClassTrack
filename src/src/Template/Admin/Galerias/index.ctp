<section class="moduleContent" id="GaleriasList">
  <?= $this->Flash->render('message_galerias');?>
  <div class="title">
          <h2>Lista de imágenes</h2>
          <?= $this->Html->link($this->Form->button('Agregar imágenes',['class'=>'btn btn-success btn-block addButton', 'type'=>'button']), ['controller' => 'Galerias', 'action' => 'add'], ['escape' => false]); ?>
    </div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col"><?= $this->Paginator->sort('img','Nombre Imágen') ?></th>
        <th scope="col"><?= $this->Paginator->sort('created', 'Fecha de creación') ?></th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($galerias as $galeria): ?>
    <tr>
        <td><?= mb_strimwidth($galeria->img,0,40,'...') ?></td>
        <td><?= $galeria->created->Format(DATE_RFC850) ?></td>
        <td class="accionesList">
          <?php
          $status = ($galeria->status == 1) ? 'activeButton' : 'publishButton' ;
            echo $this->Form->postLink('', ['action' => 'publish', $galeria->id],['class'=>'statusButtonList '.$status ,'escape'=>false]);
            echo $this->Html->link('', ['action' => 'edit', $galeria->id],['class'=>'editButtonList','escape'=>false]);
            echo $this->Form->postLink('', ['action' => 'delete', $galeria->id],['confirm' => '¿Desea eliminar el registro?', 'class'=>'deleteButtonList','escape'=>false]);
          ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <div class="paginator">
    <div class="controlPrev">
      <?= $this->Paginator->prev('', ['escape'=>false]) ?>
    </div>
    <div class="pagesPaginator">
      <?= $this->Paginator->numbers() ?>
    </div>
    <div class="controlNext">
      <?= $this->Paginator->next('', ['escape'=>false]) ?>
    </div>
  </div>
</section>
