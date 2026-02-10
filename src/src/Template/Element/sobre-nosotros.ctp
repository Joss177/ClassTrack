<?php
use Cake\Http\Client;
  use Cake\Core\Configure;
  $http = new Client();
  $url = Configure::read('App.fullBaseUrl') . $this->Url->build(["controller" =>'Filosofias',"action" => "getList"]);
  $filosofias = $http->get($url)->getJson();
?>
<section id="sobre-nosotros">
    <div class="content">
        <h3 class="sectionTitle">Filosofia emprendamos juntos</h3>
        <div class="flex-space">
            <div class="block">
                <h4 class="title">Mision</h4>
                <div class="description"><?= $filosofias[0]['mision'] ?></div>
            </div>
            <div class="block">
                <h4 class="title">Vision</h4>
                <div class="description"><?= $filosofias[0]['vision'] ?></div>
            </div>
            <div class="block">
                <h4 class="title">Valores</h4>
                <div class="flex-space">
                  <?= $filosofias[0]['valores'] ?>
                </div>
            </div>
        </div>
    </div>
</section>
