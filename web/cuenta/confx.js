darksw.onchange = cambiarTema;
toogleDark();
function cambiarTema(event) {
	
	const options = {
				method: 'POST',
				body: JSON.stringify({value: darksw.checked ? 1 : 0})
			}
	fetch('conf.php', options)
	// .then(response => response.json())
	// .then(data => console.log(data));
	toogleDark();
}

function toogleDark(){
	const conf = document.getElementsByClassName("conf");
	const topmenu = document.getElementsByClassName("top-menu");
	const perfil = document.getElementById("perfil").elements;
	const hrs = document.getElementsByTagName("hr");
	const fotoPerfil = document.getElementsByClassName("fotoPerfil");
	const busqueda = document.getElementById("busqueda");
	const divbq = document.getElementById("divbq");
	const toggle = document.getElementsByClassName("toggle");
	const sect = document.getElementsByClassName("sect");

	if (darksw.checked) {
		document.body.classList.add("darkbg");
		topmenu[0].classList.add("dartkbgtop");
		fotoPerfil[0].classList.add("darkftedit");
		busqueda.classList.add("darkSearch");
		divbq.classList.add("darkSearch");
		toggle[0].classList.add("toogleDark");
		sect[0].classList.add("leftDark");

		for (var i = 0 ; i < conf.length; i++) {
			conf[i].classList.add("darkbg1");
		}
		for (var i = 0; i < perfil.length; i++) {
			perfil[i].classList.add("darkinputbg");
		}
		for (var i = 0; i < hrs.length; i++) {
			hrs[i].classList.add("darkhr");
		}
	}
	else {
		document.body.classList.remove("darkbg");
		topmenu[0].classList.remove("dartkbgtop");
		fotoPerfil[0].classList.remove("darkftedit");
		busqueda.classList.remove("darkSearch");
		divbq.classList.remove("darkSearch");
		toggle[0].classList.remove("toogleDark");
		sect[0].classList.remove("leftDark");

		for (var i = 0 ; i < conf.length; i++) {
			conf[i].classList.remove("darkbg1");
		}
		for (var i = 0; i < perfil.length; i++) {
			perfil[i].classList.remove("darkinputbg");
		}
		for (var i = 0; i < hrs.length; i++) {
			hrs[i].classList.remove("darkhr");
		}
	}
}


btnBaja.addEventListener("click", alertBaja);
	cancelar.addEventListener("click", cancelarBaja);
	confirmar.addEventListener("click", confirmarBaja);

function alertBaja() {
	bgalerta.style.display = 'flex';
}

function cancelarBaja() {
	bgalerta.style.display = 'none';
}

function confirmarBaja() {
	const darBaja = {
				method: 'POST',
				body: JSON.stringify({baja: 1})
			}
	fetch('conf.php', darBaja);
	eliminar.submit();

}