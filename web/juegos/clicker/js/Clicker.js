let n = 0;

function myFun(){
	n++;
	counter.innerHTML = n;
}

function reset(){
	n=0;
	counter.innerHTML = n;
}


/*
const aviones = [];
let lastIdAvion = 0;
let avionEditar;
aviones.push(crearAvion("IB3456 ", "Aterrizando" ));
aviones.push(crearAvion("AA7654 ", "En tierra" ));

document.addEventListener("DOMContentLoaded", function(event){
	console.log("cargada");
	crearTablaAviones();
	const formEditarAvion = divEditarAvion.getElementsByTagName("form")[0];
	formEditarAvion.onsubmit = editarAvion;
});



function crearAvion(vuelo, estado){
		lastIdAvion++;
		const avion = {
			id : lastIdAvion,
			vuelo:vuelo,
			estado:estado, 
			hora:"En hora",
			actualizarCeldasTablas: function() {
				const celdaEstado = this.filaTabla.getElementsByClassName("estado")[0];
				const celdaHora = this.filaTabla.getElementsByClassName("hora")[0];
				celdaEstado.innerHTML = this.estado;
				celdaHora.innerHTML = this.hora;
				console.log(this.filaTabla);
			}
		};
		return avion;
	
	}

function crearTablaAviones(){
	for ( let i = 0; i < aviones.length; i++) {
		crearFilaTablaAvion(aviones[i]);
	} 
}



function crearFilaTablaAvion(avion){
	const newTr = document.createElement("tr");
	avion.filaTabla = newTr;


	const newTdVuelo = document.createElement("td");
	const newTdEstado = document.createElement("td");
	const newTdHora = document.createElement("td");
	const newTdEditar = document.createElement("td");
	const newBtnEditar = document.createElement( "button");


	newTr.dataset.idAvion = avion.id;
	newTr.appendChild(newTdVuelo);
		newTr.appendChild(newTdEstado);
			newTr.appendChild(newTdHora);
				newTr.appendChild(newTdEditar);
				newTdEditar.appendChild(newBtnEditar);
				
				newTdVuelo.innerHTML = avion.vuelo;
				newTdEstado.innerHTML = avion.estado;
				newTdEstado.classList.add("estado");
				newTdHora.classList.add("hora");
				newTdHora.innerHTML = avion.hora;
				newBtnEditar.onclick = habilitarEditarAvion;
				newBtnEditar.innerHTML = "Editar";
				
	TablaAviones.appendChild(newTr);
}

function habilitarEditarAvion(event){
	const idAvion = parseInt(this.parentNode.parentNode.dataset.idAvion);
	avionEditar = getAvionById(idAvion);
	const h3 = divEditarAvion.getElementsByTagName("h3")[0];
	h3.innerHTML = avionEditar.vuelo;
	inputEstadoAvion.value = avionEditar.estado;
	inputHoraAvion.value = avionEditar.hora;
	divEditarAvion.classList.toggle("oculto");
}

function editarAvion(event) {
	avionEditar.estado = this.elements.estado.value;
	avionEditar.hora = this.elements.hora.value;
	divEditarAvion.classList.toggle("oculto");
	avionEditar.actualizarCeldasTablas();
	event.preventDefault();
}

function getAvionById(id) {
	//Mantener el < aviones.lenght
	for(let i = 0; i < aviones.length; i++) {
		if(aviones[i].id === id ){
			return aviones[i];

		}
	}
	return null;
}
*/