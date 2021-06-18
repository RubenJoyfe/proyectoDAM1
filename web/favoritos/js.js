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
		// console.log("background-image:url('./juegos/" + name + "/img/portada.png')");
		juego.style.cssText = "background-image:url('../juegos/" + name + "/img/portada.png')";
		juego.addEventListener("mouseenter", function( event ) {
			juego.style.cssText = "background-image:url('../juegos/" + name + "/img/portada.gif')";
		});
		juego.addEventListener("mouseout", function( event ) {
			juego.style.cssText = "background-image:url('../juegos/" + name + "/img/portada.png')";
		});
	}

/*Favoritos*/
const stars = document.getElementsByClassName("star");
for (let star of stars){
	star.addEventListener("click", function(){
		const myFav = {
			method: 'POST',
			body: JSON.stringify({nombreJuego: this.value, favorito: this.checked? 1 : 0})
		}
		fetch('../favorito.php', myFav).then(response => {
			if(response.ok) {
				return response.json()
			}
			throw new Exception("Error");
		})
			.then(data => addFav(data.cod_error, this.checked? 1 : 0))
			.catch(function(error) {
				console.log('There has been a problem with your fetch operation: ' + error.message);
				alertify.error('Unexpected error: ' + error.message);
		});
	});
}


});


function addFav(code, fav) {
	console.log(code)
	switch (code) {
		case "0":
			alertify.set('notifier','position', 'bottom-right');
			if (fav==1) {
				alertify.notify("Añadido a favoritos.");
			}
			else {
				alertify.notify("Eliminado de favoritos.");
			}
			
			break;
		case "-1", "-2":
			alertify.error('Sesion no iniciada, no se pudo añadir a favoritos.');
			break;
		default:
			alertify.error('Ha ocurrido un error, intentelo más tarde');
			break;
	}
}