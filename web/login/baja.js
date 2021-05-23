 document.addEventListener("DOMContentLoaded", function(event){

	const loader = document.getElementsByClassName("circle-loader");
	var time=Math.random()*3 * 1000;
	setTimeout(comprobarBaja, time);

	function comprobarBaja() { //EXTRAE EL CODIGO DE BAJA DEL JSON
	var code;
	var xhr = new XMLHttpRequest();
	xhr.open('GET','comprobarBaja.php');
	xhr.onload = function() {
	  if(xhr.status == 200) {
	    const response = xhr.responseText;
	    var json = JSON.parse(response);
	    code = parseInt(Object.values(json));
	    loaderSuccess(code);
	    console.log(code);
	    return code;
	  }else {
	    console.log("Existe un error de tipo: "+xhr.status);
	  }
	}
	xhr.send();    
	}

	function loaderSuccess(codigoBaja) { //CAMBIA ESTILOS DEPENDEINDO DEL CODIGO DE BAJA
		const msg = document.getElementsByClassName("msg");
		loader[0].classList.add("showmsg");
		msg[0].classList.remove("hd");
		c_msg.classList.add("ap");

		if (codigoBaja==0) {
		  loader[0].classList.add("success");
		  c_msg.innerHTML="Se ha dado de baja exitosamente, ¡Te echaremos de menos!";
		  setTimeout(redireccion, 10000);
		}
		else {
		  loader[0].classList.add("failed");
		   c_msg.innerHTML="Ups!... Algo ha salido mal.";
		}
	}

	function redireccion() {
		window.location="../index.php";
	}

	$('#borrar').remove();

});