document.addEventListener("DOMContentLoaded", function(event){
	desplegable.addEventListener("click", mostrarDesbloqueables);
	fondos();
});


function mostrarDesbloqueables() {
	console.log('ola')
	desbloqueables.classList.toggle("oculto");
}

function fifgame() {
	const squares =  $("iFrame").contents().find(".square");
	for (var i=0; i<squares.length; i++) {
		squares[i].style["background-image"] = 'url(img/1.jpg)';
	}
}

function fondos() {
	const options = document.getElementsByClassName("foto");
	for (var i = 0; i < options.length; i++) {
		options[i].style.backgroundImage = "url('15game/img/"+i+".jpg')";
		options[i].style.backgroundImage;
		options[i].addEventListener("click", fifgame);
	}
}