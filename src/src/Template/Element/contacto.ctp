<section id="contacto">
    <div class="content">
        <div class="contacto-form">
            <h5 class="sectionTitle">Contáctanos</h5>
            <div class="resultsMessage"></div>
            <?php
                echo $this->Form->create(null, array('url' => '', 'class' => 'validation_form form'));
                    echo $this->Form->control('name',array('label'=>false, 'placeholder'=>'Nombre','class'=>'validation fullnameInput','onKeyPress'=>'return soloLetras(event)'));
                    echo $this->Form->control('email', array('label'=>false, 'placeholder'=>'Correo Electrónico','class'=>'validation emailInput', 'id'=>'emailContact'));
                    echo $this->Form->control('phone',array('label'=>false, 'placeholder'=>'Teléfono', 'class'=>'validation phoneInput', 'onKeyPress'=>'return soloNumeros(event)'));
                    echo $this->Form->control('message',array('type' => 'textarea','label'=>false, 'placeholder'=>'Mensaje', 'class'=>'validation messageInput'));
                    echo $this->Form->button('Enviar', ['type' => 'submit', 'class'=>'btn', 'id'=>'btnContacto']);
                echo $this->Form->end();
            ?>
        </div>
    </div>
    <img src="/img/idea.png" alt="imagen de bombilla" class="idea">
</section>
