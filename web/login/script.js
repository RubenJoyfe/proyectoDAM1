
function validarEmail(email) { //valida la cadena que se le pase para comprobar si es un correo o no
  const cadena = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return cadena.test(email);
}


function validar() {
  const correo = document.getElementById("emailAddress").value; //recoge la cadena
  if (validarEmail(correo)) { //si es un correo...
    console.log(correo);
    this.style.borderColor = 'green';
    this.style.color = 'green';
    document.forms[1].children[4].style.background = '#1BA453';
    document.forms[1].children[1].children[0].classList.remove("bad-email");
    document.forms[1].children[4].disabled=false;
  }
  else { //si no es un correo...
    this.style.borderColor = 'red';
    this.style.color = 'red';
    //console.log(document.forms[1].children[1].children[0]);
    document.forms[1].children[1].children[0].classList.add("bad-email");
    document.forms[1].children[4].disabled=true;
    document.forms[1].children[4].style.background = 'grey';
    // console.log(document.forms[1].children[4]);
    console.log("incorrecto");
  }
}

document.addEventListener("change", function a(){ //en CUALQUIER cambio que se haga en la web
  if (document.getElementById("emailAddress")) { //compruebo si existe la id asignada al correo
    emailAddress.addEventListener("blur", validar); //si existe lo valido
  }
  else {
    location.reload(); //si no existe recargo la página
  }
});
