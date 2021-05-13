darksw.onchange = cambiarTema;
toogleDark();
function cambiarTema(event) {
	console.log(darksw.checked);
	
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

	if (darksw.checked) {
		document.body.classList.add("darkbg");
		topmenu[0].classList.add("dartkbgtop");

		for (var i = 0 ; i < conf.length; i++) {
			conf[i].classList.add("darkbg1");
		}
	}
	else {
		document.body.classList.remove("darkbg");
		topmenu[0].classList.remove("dartkbgtop");

		for (var i = 0 ; i < conf.length; i++) {
			conf[i].classList.remove("darkbg1");
		}

		
	}
}