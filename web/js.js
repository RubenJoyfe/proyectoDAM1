document.addEventListener("DOMContentLoaded", function(event){
/*Ocultar-Mostrar Menu*/
	const left = document.querySelector('.left');
	const section = document.querySelector('.sect');
	const ul = document.querySelector('.menu');
	const content = document.querySelector('.content');
	document.querySelector('.toggle').onclick = function(){
		this.classList.toggle('Tactive');
		ul.classList.toggle('Tactive');
		content.classList.toggle('Tactive');
		left.classList.toggle('shide2');
		section.classList.toggle('shide');
	}

/*BUSQUEDA*/

	document.querySelector('#busqueda').addEventListener('keypress', keyPressed);

	function keyPressed(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) { //Enter keycode
			document.querySelector('#formSearch').submit();
	    }
	};

/*Portadas*/
	const juegos = document.getElementsByClassName("juego");
	for(let juego of juegos){
		const name = juego.dataset.juego;
		console.log("background-image:url('./juegos/" + name + "/img/portada.png')");
		juego.style.cssText = "background-image:url('./juegos/" + name + "/img/portada.png')";
		juego.addEventListener("mouseenter", function( event ) {
			juego.style.cssText = "background-image:url('./juegos/" + name + "/img/portada.gif')";
		});
		juego.addEventListener("mouseout", function( event ) {
			juego.style.cssText = "background-image:url('./juegos/" + name + "/img/portada.png')";
		});
	}
});