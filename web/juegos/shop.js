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
		const bloq = options[i].childNodes;
		// const precio = bloq[1].childNodes[1].childNodes[2].innerHTML;
		const precio = document.getElementsByClassName("precio")[i].innerHTML;
		options[i].style.backgroundImage = "url('15game/img/"+i+".jpg')";
		options[i].style.backgroundImage;
		// let pos;
		// for (var f = 0; f < bloq.length; f++) {
		// 	if (bloq[f].tagName == 'DIV') {
		// 		pos=f;
		// 	}		
		// }
		options[i].addEventListener("click", function(){
			if (!bloq[1].classList.contains("bloqueado")) {
				fifgame(i);
			}
			else {
				const imgsrc = this.style.backgroundImage;

				const divbg = document.createElement("div");
				const msg = document.createElement("div");
				const h2 = document.createElement("h2");
				const img = document.createElement("img");
				const p = document.createElement("p");
				const conf = document.createElement("input");
				const canc = document.createElement("input");

				conf.setAttribute("id", "confirmar");
				canc.setAttribute("id", "cancelar");
				conf.setAttribute("value", "Confirmar");
				canc.setAttribute("value", "Cancelar");
				conf.setAttribute("type", "submit");
				canc.setAttribute("type", "button");

				img.classList.add("buyImg");
				divbg.classList.add("blackbg");
				msg.classList.add("buyAlert");
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
				cancelar.addEventListener("click", function(){
					document.getElementsByClassName("blackbg")[0].remove();
				});
			}
		});
	}

	//necesario para que funcione, 0 == desbloqueado
	const desbloqueado = document.getElementsByClassName("bloqueado");
	for (var i = desbloqueado.length-1; i >= 0 ; i--) {
		const precio = document.getElementsByClassName("precio")[i].innerHTML;
		if (precio==0) {
			desbloqueado[i].innerHTML = "";
			desbloqueado[i].classList.remove("bloqueado");
		}
	}
}