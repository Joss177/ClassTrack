<?php
use Cake\Http\Client;
  use Cake\Core\Configure;
  $http = new Client();
  $url = Configure::read('App.fullBaseUrl') . $this->Url->build(["controller" => 'Comunidades',"action" => "getList"]);
  $comunidades = $http->get($url)->getJson();

?>
<section id="comunidad">
    <div class="content">
        <div class="flex-space">
            <?php foreach ($comunidades as $key => $comunidad): ?>
            <div class="image" style="background-image:url('<?='/files/Comunidades/img/'.$comunidad['img'];?>');"></div>
            <div class="text">
                <h5 class="sectionTitle"><?=$comunidad['tittle']?></h5>
                <div class="description"><?=$comunidad['description']?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
