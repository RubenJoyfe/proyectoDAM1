darksw.onchange = cambiarTema;

function cambiarTema(event) {
	console.log(darksw.checked);
	
	const options = {
				method: 'POST',
				body: JSON.stringify({value: darksw.checked ? 1 : 0})
			}
	fetch('conf.php', options)
	// .then(response => response.json())
	// .then(data => console.log(data));
}

