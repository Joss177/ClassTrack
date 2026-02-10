<section class="moduleContent" id="EmprendedoresList">
  <?= $this->Flash->render('message_emprendedores');?>
  <div class="title">
          <h2>Lista de Emprendedores</h2>
          <?= $this->Html->link($this->Form->button('Agregar emprendedores',['class'=>'btn btn-success btn-block addButton', 'type'=>'button']), ['controller' => 'Emprendedores', 'action' => 'add'], ['escape' => false]); ?>
    </div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col"><?= $this->Paginator->sort('name','Nombre') ?></th>
        <th scope="col"><?= $this->Paginator->sort('created', 'Fecha de creación') ?></th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($emprendedores as $emprendedor): ?>
    <tr>
        <td><?= mb_strimwidth($emprendedor->name,0,40,'...') ?></td>
        <td><?= $emprendedor->created->Format(DATE_RFC850) ?></td>
        <td class="accionesList">
          <?php
          $status = ($emprendedor->status == 1) ? 'activeButton' : 'publishButton' ;
            echo $this->Form->postLink('', ['action' => 'publish', $emprendedor->id],['class'=>'statusButtonList '.$status ,'escape'=>false]);
            echo $this->Html->link('', ['action' => 'edit', $emprendedor->id],['class'=>'editButtonList','escape'=>false]);
            echo $this->Form->postLink('', ['action' => 'delete', $emprendedor->id],['confirm' => '¿Desea eliminar el registro?', 'class'=>'deleteButtonList','escape'=>false]);
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
