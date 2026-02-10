<section class="moduleContent" id="ProcesosList">
  <?= $this->Flash->render('message_procesos');?>
  <div class="title">
          <h2>Lista de Incubación de la empresa</h2>
    </div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col"><?= $this->Paginator->sort('tittle1','Titulo 1') ?></th>
        <th scope="col"><?= $this->Paginator->sort('description1','Descripcion 1') ?></th>
        <th scope="col"><?= $this->Paginator->sort('tittle2','Titulo 2') ?></th>
        <th scope="col"><?= $this->Paginator->sort('description2','Descripcion 2') ?></th>
        <th scope="col"><?= $this->Paginator->sort('created', 'Fecha de creación') ?></th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($procesos as $proceso): ?>
    <tr>
        <td><?= mb_strimwidth($proceso->tittle1,0,40,'...') ?></td>
        <td><?= $proceso->description1?></td>
        <td><?= mb_strimwidth($proceso->tittle2,0,40,'...') ?></td>
        <td><?= $proceso->description2?></td>
        <td><?= $proceso->created->Format(DATE_RFC850) ?></td>
        <td class="accionesList">
          <?php
            echo $this->Html->link('', ['action' => 'edit', $proceso->id],['class'=>'editButtonList','escape'=>false]);
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
