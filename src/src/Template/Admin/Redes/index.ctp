<section class="moduleContent" id="RedesList">
  <?= $this->Flash->render('message_redes');?>
  <div class="title">
          <h2>Lista de redes sociales</h2>
    </div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col"><?= $this->Paginator->sort('facebook','Nombre Facebook') ?></th>
        <th scope="col"><?= $this->Paginator->sort('whatsapp','Nombre whatsapp') ?></th>
        <th scope="col"><?= $this->Paginator->sort('instagram','Nombre Instagram') ?></th>
        <th scope="col"><?= $this->Paginator->sort('created', 'Fecha de creación') ?></th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($redes as $red): ?>
    <tr>
        <td><?= mb_strimwidth($red->facebook,0,40,'...') ?></td>
        <td><?= mb_strimwidth($red->whatsapp,0,40,'...') ?></td>
        <td><?= mb_strimwidth($red->instagram,0,40,'...') ?></td>

        <td><?= $red->created->Format(DATE_RFC850) ?></td>
        <td class="accionesList">
          <?php
          $status = ($red->status == 1) ? 'activeButton' : 'publishButton' ;
            echo $this->Form->postLink('', ['action' => 'publish', $red->id],['class'=>'statusButtonList '.$status ,'escape'=>false]);
            echo $this->Html->link('', ['action' => 'edit', $red->id],['class'=>'editButtonList','escape'=>false]);
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
