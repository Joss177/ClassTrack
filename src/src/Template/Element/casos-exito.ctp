<?php
use Cake\Http\Client;
  use Cake\Core\Configure;
  $http = new Client();
  $url = Configure::read('App.fullBaseUrl') . $this->Url->build(["controller" => 'Emprendedores',"action" => "getList"]);
  $emprendedores = $http->get($url)->getJson();
  
?>
<section id="casos-exito">
    <h5 class="sectionTitle">Casos de éxito</h5>
    <div class="slider swiper-container">
        <ul id="slider" class="swiper-wrapper">
          <?php if(!empty($emprendedores)){ ?>
            <?php foreach ($emprendedores as $key => $emp):?>
            <li class="slide swiper-slide" style="background-image: url('<?='/files/Emprendedores/img/'.$emp['img'];?>');"></li>
            <?php endforeach; ?>
            <?php } ?>
        </ul>
        <div class="swiper-pagination"></div>
    </div>
</section>
