<?php
use Cake\Http\Client;
  use Cake\Core\Configure;
  $http = new Client();
  $url = Configure::read('App.fullBaseUrl') . $this->Url->build(["controller" => 'Galerias',"action" => "getList"]);
  $galerias = $http->get($url)->getJson();
?>
<section id="galeria">
    <div class="content">
        <h5 class="sectionTitle">Galería de fotos</h3>
        <div class="slider swiper-container">
            <ul id="slider" class="swiper-wrapper">
                <?php if(!empty($galerias)){ ?>
                  <?php foreach ($galerias as $key => $galeria):?>
                  <li class="slide swiper-slide" style="background-image: url('<?='/files/Galerias/img/'.$galeria['img'];?>');"></li>
                  <?php endforeach; ?>
                  <?php } ?>
            </ul>
            <div class="paginate-content">
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>
</section>
