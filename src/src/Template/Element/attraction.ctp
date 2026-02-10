<?php
    use Cake\Http\Client;
    use Cake\Core\Configure;
    $http = new Client();
    $url = Configure::read('App.fullBaseUrl') . $this->Url->build(["controller" => "Banners","action" => "getList"]);
    $banners = $http->get($url)->getJson();
?>
<section id="attraction">
    <div class="container">
        <div class="slider swiper-container">
            <ul id="slider" class="swiper-wrapper">
                <?php if(!empty($banners)){ ?>
                    <?php foreach ($banners as $key => $banner): ?>
                        <li class="slide swiper-slide" style="background-image:url('<?='/files/Banners/img/'.$banner['img'];?>');">
                            <h2 class="title"><?=$banner['tittle']?></h2>
                        </li>
                    <?php endforeach; ?>
                <?php }else{ ?>
                    <li class="slide swiper-slide" style="background-image:url('/img/attraction.png');">
                        <h2 class="title">Es tiempo de emprender</h2>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="description">
        <p>Somos un centro de desarrollo empresarial que brinda de manera profesional la guía y la capacitación necesaria para que un emprendedor inicie de manera planeada y eficiente su empresa, por medio de: Planeación y validación de proyectos y empresas, Tramitología y desarrollo comercial, Capacitación y adiestramientos, Vinculación a fondos y financiamientos y Desarrollo de competencias y habilidades empresariales.</p>
    </div>
    <img src="/img/idea.png" alt="imagen de bombilla" class="idea">
</section>
