
function soloNumeros(e){
    var key = window.Event ? e.which : e.keyCode
    return ((key >= 48 && key <= 57) || (key==8))
}
$(document).ready(function () {
	var pathname = window.location.pathname;
	path = pathname.split("/");

	if(typeof(path[2]) == "undefined"){
		$(".menuBar li").removeClass("active");
		$(".menuBar li.panel").addClass("active");
	}else{
		$(".menuBar li").removeClass("active");
		$(".menuBar li."+path[2]).addClass("active");
	}

	var cont = 0;
	$('.barrasMenu').on('click',function(){
		if(cont == 0){
			$("#fondo").toggle();
			$('.menuBar').css('left', '0px');
			$('.barrasMenu img').attr('src', '/img/admin/arrow-forward.png');
			$('.barrasMenu img').css('width', '29px');
			cont = 1;
		}else{
			$("#fondo").toggle();
			$('.menuBar').css('left', '-100%');
			$('.barrasMenu img').attr('src', '/img/admin/menu-mob.png');
			$('.barrasMenu img').css('width', '35px');
			cont = 0;
		}

	});
});
