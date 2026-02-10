<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

 $session = $this->getRequest()->getSession();
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php $this->assign('title', 'Admin | CMS'); ?>
        <title><?= h($this->fetch('title')) ?></title>
        <?= $this->Html->meta('icon') ?>

        <?= $this->Html->css('admin') ?>
        <?= $this->Html->css('fontawesome') ?>
        <?= $this->Html->css('all') ?>

        
        <?= $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'); ?>

        <?= $this->Html->css('jquery.datetimepicker.min.css') ?>
        <?= $this->Html->script('jquery.datetimepicker.js'); ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <?= $this->Html->script('admin'); ?>
        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
    </head>
    <body>
        <div class="topBar">
            <div class="menuLogo">
                <span class="barrasMenu">
                    <img src="/img/admin/menu-mob.png" alt="Menu mobile">
                </span>
                <?= $this->Html->image('admin/logo_admin.png', ['alt' => 'Logo', 'class'=>'logoAdminCMS']) ?>
            </div>
            <div class="userInfo">
                <span class="user">
                    <?= "Bienvenido, ". $this->request->getSession()->read('Auth.Admin.name') ?>
                </span>
                <?= $this->Html->link('Cerrar sesión', ['controller' => 'Users', 'action' => 'logout'],['escape'=>false, 'class'=>'logoutButton']) ?>
            </div>
        </div>
        <div class="adminContentLayout">
            <nav class="menuBar">
                <ul>
                    <li class="panel"><?= $this->Html->link('Panel', ['controller' => 'Admin', 'action' => 'index']) ?></li>
                    <?php if ($session->read('Auth.Admin.group_id')==1 || $session->read('Auth.Admin.group_id')==2): ?>
                      <li class="users"><?= $this->Html->link('Usuarios', ['controller' => 'Users', 'action' => 'index']) ?></li>
                    <?php endif; ?>
                    <?php if ($session->read('Auth.Admin.group_id')==1 || $session->read('Auth.Admin.group_id')==2): ?>
                      <li class="banners"><?= $this->Html->link('Banners', ['controller' => 'Banners', 'action' => 'index']) ?></li>
                    <?php endif; ?>
                    <?php if ($session->read('Auth.Admin.group_id')==1 || $session->read('Auth.Admin.group_id')==2): ?>
                      <li class="comunidades"><?= $this->Html->link('Comunidades', ['controller' => 'Comunidades', 'action' => 'index']) ?></li>
                    <?php endif; ?>
                    <?php if ($session->read('Auth.Admin.group_id')==1 || $session->read('Auth.Admin.group_id')==2): ?>
                      <li class="emprendedores"><?= $this->Html->link('Casos Éxito', ['controller' => 'Emprendedores', 'action' => 'index']) ?></li>
                    <?php endif; ?>
                    <?php if ($session->read('Auth.Admin.group_id')==1 || $session->read('Auth.Admin.group_id')==2): ?>
                      <li class="filosofias"><?= $this->Html->link('Filosofias Emprendamos Juntos', ['controller' => 'Filosofias', 'action' => 'index']) ?></li>
                    <?php endif; ?>
                    <?php if ($session->read('Auth.Admin.group_id')==1 || $session->read('Auth.Admin.group_id')==2): ?>
                      <li class="galerias"><?= $this->Html->link('Galeria Fotos', ['controller' => 'Galerias', 'action' => 'index']) ?></li>
                    <?php endif; ?>
                    <?php if ($session->read('Auth.Admin.group_id')==1 || $session->read('Auth.Admin.group_id')==2): ?>
                      <li class="procesos"><?= $this->Html->link('Proceso de Incubación', ['controller' => 'Procesos', 'action' => 'index']) ?></li>
                    <?php endif; ?>
                    <?php if ($session->read('Auth.Admin.group_id')==1 || $session->read('Auth.Admin.group_id')==2): ?>
                      <li class="redes"><?= $this->Html->link('Redes Sociales', ['controller' => 'Redes', 'action' => 'index']) ?></li>
                    <?php endif; ?>
                    <?php if ($session->read('Auth.Admin.group_id')==1 || $session->read('Auth.Admin.group_id')==2): ?>
                      <li class="contacts"><?= $this->Html->link('Contactos', ['controller' => 'Contactos', 'action' => 'index']) ?></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?= $this->fetch('content') ?>
        </div>
        <div id="fondo"></div>
    </body>
</html>
