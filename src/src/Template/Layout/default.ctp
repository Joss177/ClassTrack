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

$cakeDescription = 'OnePage';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?= $this->Html->charset() ?>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $cakeDescription ?></title>
        <?= $this->Html->meta('icon') ?>
        <meta property="og:title" content="<?= $cakeDescription ?>" />
        <meta property="og:type" content="website"/>
        <!-- <meta property="og:url" content="https://mohadito.com" />
        <meta property="og:description" content="Mohadito es un servicio de autolavado a domicilio en Mazatlán. Entra haz tu reservacion."/>
        <meta name="description" content="Mohadito es un servicio de autolavado a domicilio en Mazatlán. Entra haz tu reservacion.">
        <meta name="keywords" content="Mohadito, Autolavado"> -->

        <?= $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'); ?>
        <?= $this->Html->script('https://cdn.jsdelivr.net/npm/sweetalert2@11'); ?>

        <?= $this->Html->css('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css') ?>
        <?= $this->Html->css('style'.MIN) ?>
        <?= $this->Html->css('responsive'.MIN) ?>

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
    </head>
    <body>
        <?= $this->Flash->render() ?>
        <header>
            <?= $this->element('header') ?>
        </header>
        <div class="container clearfix">
            <?= $this->fetch('content') ?>
        </div>
        <div id="fondo"></div>
        <footer>
            <?= $this->element('footer') ?>
        </footer>
        <?= $this->Html->script('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js') ?>
        <?= $this->Html->script('main'.MIN) ?>
    </body>
</html>
