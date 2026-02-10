<section class="moduleContent" id="FilosofiasList">
  <?= $this->Flash->render('message_filosofias');?>
  <div class="title">
          <h2>Lista de Filosofias de la empresa</h2>
    </div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col"><?= $this->Paginator->sort('mision','Misión') ?></th>
        <th scope="col"><?= $this->Paginator->sort('vision','Visión') ?></th>
        <th scope="col"><?= $this->Paginator->sort('valores','Valores') ?></th>
        <th scope="col"><?= $this->Paginator->sort('created', 'Fecha de creación') ?></th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($filosofias as $filosofia): ?>
    <tr>
        <td><?= mb_strimwidth($filosofia->mision,0,40,'...') ?></td>
        <td><?= mb_strimwidth($filosofia->vision,0,40,'...') ?></td>
        <td><?= mb_strimwidth($filosofia->valores,0,40,'...') ?></td>
        <td><?= $filosofia->created->Format(DATE_RFC850) ?></td>
        <td class="accionesList">
          <?php
            echo $this->Html->link('', ['action' => 'edit', $filosofia->id],['class'=>'editButtonList','escape'=>false]);
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
