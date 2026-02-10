<section id="userList" class="moduleContent">
  <div class="title">
      <h2>Lista de Usuarios</h2>
      <?= $this->Html->link($this->Form->button('Agregar usuario',['class'=>'btn addButton', 'type'=>'button']), ['controller' => 'Users', 'action' => 'signup'], ['escape' => false]); ?>
  </div>
  <?= $this->Flash->render('message');?>
  <?php if(empty(isMobile)){ ?>
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col"><?= $this->Paginator->sort('name','Nombre') ?></th>
          <th scope="col"><?= $this->Paginator->sort('email', 'Email') ?></th>
          <th scope="col"><?= $this->Paginator->sort('created', 'Fecha de creación') ?></th>
          <th scope="col">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user->name ?></td>
            <td><?= $user->email ?></td>
            <td><?= $user->created->Format(DATE_RFC850) ?></td>
            <td class="accionesList">
              <?php
                $status = ($user->status == 1) ? 'activeButton' : 'publishButton' ;
                echo $this->Form->postLink('', ['action' => 'publish', $user->id],['class'=>'statusButtonList '.$status ,'escape'=>false]);
                echo $this->Html->link('', ['action' => 'edit', $user->id],['class'=>'editButtonList','escape'=>false]);
                echo $this->Html->link('<i class="fa fa-unlock-alt"></i>', ['action' => 'editPass', $user->id],['class'=>'editPassButton','escape'=>false]);
                echo $this->Form->postLink('', ['action' => 'delete', $user->id],['confirm' => '¿Desea eliminar el user?', 'class'=>'deleteButtonList','escape'=>false]);
              ?>
            </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php }else{ ?>
    <?php foreach ($users as $user): ?>
    <table class="table table-hover">
      <tbody>
        <tr style="border-bottom:none;">
          <th scope="col" style="padding-bottom:0px;"><?= $this->Paginator->sort('name','Nombre') ?></th>
        </tr>
        <tr>
            <td><?= $user->name ?></td>
        </tr>
        <tr style="border-bottom:none;">
          <th scope="col" style="padding-bottom:0px;"><?= $this->Paginator->sort('email', 'Email') ?></th>
        </tr>
        <tr>
            <td><?= $user->email ?></td>
        </tr>
        <tr style="border-bottom:none;">
          <th scope="col" style="padding-bottom:0px;"><?= $this->Paginator->sort('created', 'Fecha de creación') ?></th>
        </tr>
        <tr>
            <td><?= $user->created->Format(DATE_RFC850) ?></td>
        </tr>
        <tr style="border-bottom:none;">
          <th scope="col" style="padding-bottom:0px;">Acciones</th>
        </tr>
        <tr>
            <td class="accionesList">
              <?php
                $status = ($user->status == 1) ? 'activeButton' : 'publishButton' ;
                #echo $this->Form->postLink('<i class="fa fa-check-circle"></i>', ['action' => 'publish', $user->id],['class'=>'editButtonList '.$status ,'escape'=>false]);
                echo $this->Html->link('', ['action' => 'edit', $user->id],['class'=>'editButtonList','escape'=>false]);
                echo $this->Html->link('<i class="fa fa-unlock-alt"></i>', ['action' => 'editPass', $user->id],['class'=>'editPassButton','escape'=>false]);
                echo $this->Form->postLink('', ['action' => 'delete', $user->id],['confirm' => '¿Desea eliminar el user?', 'class'=>'deleteButtonList','escape'=>false]);
              ?>
            </td>
        </tr>
      </tbody>
    </table>
    <?php endforeach; ?>
  <?php } ?>
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
