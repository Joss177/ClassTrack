<?php
use Cake\Http\Client;
  use Cake\Core\Configure;
  $http = new Client();
  $url = Configure::read('App.fullBaseUrl') . $this->Url->build(["controller" =>'Procesos',"action" => "getList"]);
  $procesos = $http->get($url)->getJson();
?>
<section id="proceso">
    <h3 class="sectionTitle">El proceso de incubación de Emprendamos Juntos es de la siguiente manera:</h3>
    <div class="flex-space">
        <div class="block">
            <h4 class="title"><?=$procesos[0]['tittle1'] ?></h4>
            <div class="description"><?=$procesos[0]['description1'] ?></div>
        </div>
        <div class="block">
            <h4 class="title"><?=$procesos[0]['tittle2'] ?></h4>
            <?=$procesos[0]['description2'] ?>
        </div>
    </div>
</section>
