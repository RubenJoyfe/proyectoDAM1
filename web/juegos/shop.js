document.addEventListener("DOMContentLoaded", function(event){
	desplegable.addEventListener("click", mostrarDesbloqueables);
});


function mostrarDesbloqueables() {
	console.log('ola')
	desbloqueables.classList.toggle("oculto");
}