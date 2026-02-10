<section class="moduleContent" id="bannerList">
  <?= $this->Flash->render('message_banner');?>
  <div class="title">
          <h2>Lista de banners</h2>
          <?= $this->Html->link($this->Form->button('Agregar banner',['class'=>'btn btn-success btn-block addButton', 'type'=>'button']), ['controller' => 'Banners', 'action' => 'add'], ['escape' => false]); ?>
    </div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col"><?= $this->Paginator->sort('tittle','Titulo') ?></th>
        <th scope="col"><?= $this->Paginator->sort('img', 'Nombre Imágen') ?></th>
        <th scope="col"><?= $this->Paginator->sort('created', 'Fecha de creación') ?></th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($banners as $article): ?>
    <tr>
        <td><?= mb_strimwidth($article->tittle,0,40,'...') ?></td>
        <td><?= $article->img?></td>
        <td><?= $article->created->Format(DATE_RFC850) ?></td>
        <td class="accionesList">
          <?php
          $status = ($article->status == 1) ? 'activeButton' : 'publishButton' ;
            echo $this->Form->postLink('', ['action' => 'publish', $article->id],['class'=>'statusButtonList '.$status ,'escape'=>false]);
            echo $this->Html->link('', ['action' => 'edit', $article->id],['class'=>'editButtonList','escape'=>false]);
            echo $this->Form->postLink('', ['action' => 'delete', $article->id],['confirm' => '¿Desea eliminar el banner?', 'class'=>'deleteButtonList','escape'=>false]);
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
