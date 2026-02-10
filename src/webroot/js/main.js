$(document).ready(function () {
    $(".soloNumeros").on('input', function(e){
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    $(".soloLetras").on('input', function(e){
        $(this).val($(this).val().replace(/[^a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s]/g, ''));
    });

    // var swiperAttraction = new Swiper("#attraction .swiper-container", {
    //     pagination: {
    //         el: '#attraction .swiper-pagination',
    //         clickable: true,
    //     },
    //     autoplay: {
    //         delay: 5000,
    //     },
    //     fadeEffect: {
    //         crossFade: true
    //     },
    //     effect: 'fade',
    //     speed: 1000,
    //     loop: true
    // });

    var space = 38;
    var numRows = 2;
    var slides = 5;
    if($(window).width() < 900){
        space = 24;
    }
    if($(window).width() < 500){
        space = 18;
        numRows = 4;
        slides = 3;
    }

    var swiperCasos = new Swiper("#casos-exito .swiper-container", {
        slidesPerView: slides,
        grid: {
          rows: numRows,
        },
        spaceBetween: space,
        pagination: {
          el: "#casos-exito .swiper-pagination",
          clickable: true,
        },
        autoplay: {
            delay: 5000,
        },
        loop: true
    });

    var space2 = 60;
    if($(window).width() < 900){
        space2 = 40;
    }
    if($(window).width() < 500){
        space2 = 20;
    }

    var swiperGaleria = new Swiper("#galeria .swiper-container", {
        slidesPerView: 'auto',
        spaceBetween: space2,
        autoplay: {
          delay: 5000,
        },
        speed: 1000,
        loop: true,
        navigation: {
          nextEl: "#galeria .swiper-button-next",
          prevEl: "#galeria .swiper-button-prev",
        },
    });

    $('#barras-menu').on('click',function(){
        $(this).toggleClass('close');
        $('#fondo').toggle();
        $('nav').toggleClass('show');
    });

    $("#fondo").click(function(e){
        e.preventDefault();
        $('#fondo').toggle();
        $('#barras-menu').toggleClass('close');
        $('nav').toggleClass('show');
    });

    $(".menu a").click(function(e){
        e.preventDefault();
        var go = $(this).attr("data-go");
        $('html, body').animate({
            scrollTop: $("section#"+go).offset().top
        }, 1000);
        if($(window).width() < 900){
            $('#fondo').toggle();
            $('#barras-menu').toggleClass('close');
            $('nav').toggleClass('show');
        }
    });

    $('.arrow-start').on('click',function(e){
        e.preventDefault();
        $(this).toggleClass('close');
        $('html, body').animate({
            scrollTop: $("header").offset().top
        }, 1000);
    });

    $(document).on('click', '#btnContacto', function(e) {
        var submit=false;
        var validado=0;
        $('#contacto .validation').each(function(index){
            if($(this).val()==""){
                $(this).addClass('error');
                validado=1;
            }
            else{
                $(this).removeClass('error');
            }
        });
  
        if(validado==1){
            e.preventDefault();
            Swal.fire({
                title: "Error",
                text: "Favor de llenar todos los campos",
                icon: "error"
            });
        }else{
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "/contactos/add",
                data: $('.validation_form').serialize(),
                success: function(response){
                    if(response == 'true'){
                        Swal.fire({
                            title: "Exito",
                            text: "Gracias por ponerte en contacto con nosotros",
                            icon: "success"
                        });
                        $(".validation_form")[0].reset();
                    }
                }
            });
        }
  
    });

    $(".aviso-privacidad").click(function(e){
        e.preventDefault();
        $('#fondo').toggle();
        $("#modalPrivacidad").toggle();
    });

    $(".terminos-condiciones").click(function(e){
        e.preventDefault();
        $('#fondo').toggle();
        $("#modalTerminos").toggle();
    });

    $(".closeModal").click(function(e){
        e.preventDefault();
        $('#fondo').toggle();
        if($("#modalPrivacidad").css("display") == "block"){
            $("#modalPrivacidad").toggle();
        }
        if($("#modalTerminos").css("display") == "block"){
            $("#modalTerminos").toggle();
        }
    });

});
