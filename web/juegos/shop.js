document.addEventListener("DOMContentLoaded", function(event){
	desplegable.addEventListener("click", mostrarDesbloqueables);
	fondos();
});


function mostrarDesbloqueables() {
	if (desbloqueables.style.display!='flex') {
		desbloqueables.style.display = 'flex';
	}
	else {
		desbloqueables.style.display = 'none';
	}
}

function fifgame(img) {
	const squares =  $("iFrame").contents().find(".square");
	for (var i=0; i<squares.length; i++) {
		if (img==0) {
			squares[i].innerHTML = i+1;
			squares[i].style.border = "0.1vh solid rgba(0,0,0, 0.1)";
			squares[i].style["background-image"] = "";
		}
		else {
			squares[i].style["background-image"] = "url(img/"+img+".jpg)";
			squares[i].innerHTML = '';
			squares[i].style.border = '';
		}
	}
}

function fondos() {
	const options = document.getElementsByClassName("foto");
	for (let i = 0; i < options.length; i++) {
		options[i].style.backgroundImage = "url('15game/img/"+i+".jpg')";
		options[i].style.backgroundImage;
		options[i].addEventListener("click", function(){
			fifgame(i);
		});
	}
}