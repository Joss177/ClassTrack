<?php
use Cake\Http\Client;
  use Cake\Core\Configure;
  $http = new Client();
  $url = Configure::read('App.fullBaseUrl') . $this->Url->build(["controller" =>'Redes',"action" => "getList"]);
  $redes = $http->get($url)->getJson();

?>
<div class="top">
    <div class="container">
        <div class="logoHeader">
            <?=
                $this->Html->image("/img/logo.png", [
                    "alt" => "Logo emprendamos juntos",
                    'url' => ['controller' => 'Pages', 'action' => 'display', 'home']
                ]);
            ?>
        </div>
        <div class="redes">
            <a href="<?= $redes[0]['facebook'] ?>" class="facebook">
                <img src="/img/facebook.png" alt="Icono de facebook">
            </a>
            <a href="<?=$redes[0]['instagram'] ?>" class="instagram">
                <img src="/img/instagram.png" alt="Icono de instagram">
            </a>
            <a href="https://api.whatsapp.com/send?phone=+52<?=$redes[0]['whatsapp']?>&text=Hola,%20tengo%20una%20duda%20" class="whatsapp" target="_blank" rel="noopener" >
                <img src="/img/whatsapp.png" alt="Icono de whatsapp">
            </a>
        </div>
        <span id="barras-menu">
            <span></span>
        </span>
    </div>
</div>
<div class="bottom">
    <div class="container">
        <nav>
            <div></div>
            <ul class="menu">
                <li class="go">
                    <a href="#" data-go="sobre-nosotros">Quienes somos</a>
                </li>
                <li class="go">
                    <a href="#" data-go="casos-exito">Casos de éxito</a>
                </li>
                <li class="go">
                    <a href="#" data-go="galeria">Galería</a>
                </li>
                <li class="go">
                    <a href="#" data-go="contacto">Contáctanos</a>
                </li>
            </ul>
            <img src="/img/fondo-menu.png" alt="Figura de fondo" class="fondoNav">
        </nav>
    </div>
</div>
