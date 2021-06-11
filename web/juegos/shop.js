let dineros;
let src;

document.addEventListener("DOMContentLoaded", function(event){
	try {
		dineros = parseInt(document.getElementsByClassName("dineros")[0].childNodes[1].textContent);
	} catch(e) {

	}
	gmdesplegable.addEventListener("click", mostrarDesbloqueables);
	cargarTienda();
});


function mostrarDesbloqueables() {
	if (gmdesbloqueables.style.display!='flex') {
		gmdesbloqueables.style.display = 'flex';
	}
	else {
		gmdesbloqueables.style.display = 'none';
	}
}

function cargarTienda() {
	const options = document.getElementsByClassName("foto");
	for (let i = 0; i < options.length; i++) {
		const bloq = options[i].childNodes;
		// const precio = bloq[1].childNodes[1].childNodes[2].innerHTML;
		const precio = parseInt(document.getElementsByClassName("precio")[i].innerHTML);
		const gm = options[i].id;
		src = gm;
		
		options[i].style.backgroundImage = "url('"+gm+"/img/"+i+".jpg')";
		options[i].style.backgroundImage;
		// let pos;
		// for (var f = 0; f < bloq.length; f++) {
		// 	if (bloq[f].tagName == 'DIV') {
		// 		pos=f;
		// 	}		
		// }
		options[i].addEventListener("click", function(){
			if (!bloq[1].classList.contains("gmbloqueado")) {
				unlockThis(i);
			}
			else {
				const imgsrc = this.style.backgroundImage;
				console.log(imgsrc)

				const divbg = document.createElement("div");
				const msg = document.createElement("div");
				const h2 = document.createElement("h2");
				const img = document.createElement("img");
				const p = document.createElement("p");
				const conf = document.createElement("input");
				const canc = document.createElement("input");

				conf.setAttribute("id", "gmconfirmar");
				canc.setAttribute("id", "gmcancelar");
				conf.setAttribute("value", "Confirmar");
				canc.setAttribute("value", "Cancelar");
				conf.setAttribute("type", "submit");
				canc.setAttribute("type", "button");

				img.classList.add("gmbuyImg");
				divbg.classList.add("gmblackbg");
				msg.classList.add("gmbuyAlert");
				h2.innerHTML = "Comprar desbloqueable";
				p.innerHTML = "Precio: "+precio;
				img.style.backgroundImage = imgsrc;

				msg.appendChild(h2);
				msg.appendChild(img);
				msg.appendChild(p);
				divbg.appendChild(msg);
				msg.appendChild(canc);
				msg.appendChild(conf);
				document.body.appendChild(divbg);

				gmcancelar.addEventListener("click", function(){
					document.getElementsByClassName("gmblackbg")[0].remove();
				});
				gmconfirmar.addEventListener("click", function(){
					if (dineros<precio) {
						alertify.error("Dinero insuficiente, faltan " + (precio-dineros) + " dineros"); 
					}
					else {
						const desblo = options[i].getAttribute('value');
						
						const desbloquear = {
								method: 'POST',
								body: JSON.stringify({id_des: desblo, dinero: (dineros-precio)})
							}
						fetch('buy.php', desbloquear).then(response => {
							if(response.ok) {
								return response.json()
							}
							throw new Exception("Error");
						})
  						.then(data => errorsw(data.cod_error, precio, options[i]))
  						.catch(function(error) {
  							console.log('There has been a problem with your fetch operation: ' + error.message);
							alertify.error('There has been a problem with your fetch operation: ' + error.message); 
						});
					}
				});
			}
		});
	}

	function errorsw(code, precio, myBlock) {
		switch (code) {
			case "0":
				dineros-=precio;
				document.getElementsByClassName("dineros")[0].childNodes[1].textContent = dineros;
				alertify.set('notifier','position', 'bottom-right');
				alertify.notify("Compra realizada! Dinero restante: " + dineros, 'success', 5);
				//Borrar opciones de compra
				document.getElementsByClassName("gmblackbg")[0].remove();
				myBlock.childNodes[1].classList.remove("gmbloqueado");
				myBlock.childNodes[1].textContent="";
				break;
			case "-1": {
				alertify.error("No se ha podido tramitar la compra. ("+code+")");
				// window.location="../login/login.php";
			}
			default:
				alertify.error("No se ha podido tramitar la compra. ("+code+")");
				break;
		}
	}

	//necesario para que funcione, 0 == desbloqueado
	const desbloqueado = document.getElementsByClassName("gmbloqueado");
	for (var i = desbloqueado.length-1; i >= 0 ; i--) {
		const precio = document.getElementsByClassName("precio")[i].innerHTML;
		if (precio==0) {
			desbloqueado[i].innerHTML = "";
			desbloqueado[i].classList.remove("gmbloqueado");
		}
	}
}

function cashUpdate(cash, errr) {
	console.log(errr + " - " + cash);
	document.getElementsByClassName("dineros")[0].childNodes[1].textContent = cash;
}
