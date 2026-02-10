<?php
    use Cake\Http\Client;
    use Cake\Core\Configure;
    $http = new Client();
    $url = Configure::read('App.fullBaseUrl') . $this->Url->build(["controller" => "Servicios","action" => "getList"]);
    $servicios = $http->get($url)->getJson();
?>
<section id="servicios">
    <div class="content">
        <h3 class="title">Services</h3>
        <p class="description">In business for nearly 40 Years, Clean Environments has a solid reputation for providing detail oriented cleaning services. Our strict standards and unwavering work ethic are part of what makes us a favored company. Whatever your cleaning needs, you can rest assured our team can handle it.
            <br><br>
            Everyone on our cleaning staff is experienced and professionally trained, ensuring you receive the finest services possible.
            <br><br>
            We understand the scope of work for your construction project may vary from project to project, and we can customize our written proposal based on the project specifications.  
            <br><br>
            Traditionally, we review the project plans as provided digitally or made availabe for review by your estimator or project manager.  We review floorplans, elevations, and building materials and discuss the scope of work needed including any special instructions for varying finish materials.
        </p>
        <ul class="list">
            <li>Wipe all surfaces</li>
            <li>Vacuum all carpets</li>
            <li>Hard Floors - Mopping, Stripping & Waxing</li>
            <li>Window Cleaning</li>
            <li>Carpets can be spot cleaned or hot water extraction cleaned if necessary</li>
        </ul>
    </div>
</section>