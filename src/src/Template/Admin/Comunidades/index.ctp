<section class="moduleContent" id="ComunidadesList">
  <?= $this->Flash->render('message');?>
  <div class="title">
          <h2>Lista de Comunidades</h2>
          <?php if(count($comunidades)==0){ ?>
          <?= $this->Html->link($this->Form->button('Agregar comunidad',['class'=>'btn btn-success btn-block addButton', 'type'=>'button']), ['controller' => 'Comunidades', 'action' => 'add'], ['escape' => false]); ?>
        <?php } ?>
    </div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col"><?= $this->Paginator->sort('tittle','Titulo') ?></th>
        <th scope="col"><?= $this->Paginator->sort('description', 'Descripción') ?></th>
        <th scope="col"><?= $this->Paginator->sort('created', 'Fecha de creación') ?></th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($comunidades as $comunidad): ?>
    <tr>
        <td><?= mb_strimwidth($comunidad->tittle,0,40,'...') ?></td>
        <td><?= $comunidad->description?></td>
        <td><?= $comunidad->created->Format(DATE_RFC850) ?></td>
        <td class="accionesList">
          <?php
            echo $this->Html->link('', ['action' => 'edit', $comunidad->id],['class'=>'editButtonList','escape'=>false]);
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
