<section class="moduleContent" id="ContactosList">
  <?= $this->Flash->render('contactos_contactos');?>
  <div class="title">
          <h2>Lista de contactos</h2>
    </div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col"><?= $this->Paginator->sort('name','Nombre') ?></th>
        <th scope="col"><?= $this->Paginator->sort('email','Email') ?></th>
        <th scope="col"><?= $this->Paginator->sort('phone','Telefono') ?></th>
        <th scope="col"><?= $this->Paginator->sort('message','Mensaje') ?></th>
        <th scope="col"><?= $this->Paginator->sort('created', 'Fecha de creación') ?></th>

      </tr>
    </thead>
    <tbody>
      <?php foreach($contactos as $contacto): ?>
    <tr>
        <td><?= mb_strimwidth($contacto->name,0,40,'...') ?></td>
        <td><?= mb_strimwidth($contacto->email,0,40,'...') ?></td>
        <td><?= mb_strimwidth($contacto->phone,0,40,'...') ?></td>
        <td><?= mb_strimwidth($contacto->message,0,40,'...') ?></td>
        <td><?= $contacto->created->Format(DATE_RFC850) ?></td>

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
