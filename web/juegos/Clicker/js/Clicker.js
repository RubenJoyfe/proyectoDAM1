let puntos = 0;
let mult = 1;

function myFun(){
	puntos += mult;
	counter.innerHTML = puntos;
}

function reset(){
	puntos=0;
	mult = 1;
	counter.innerHTML = puntos;
	mult_counter.innerHTML = "X" + mult;
	mult_button.innerHTML = 10*mult*mult + " puntos"
}

function multiplicador() {
	if (puntos>=10*mult*mult) {
		puntos -= 10*mult*mult;
		counter.innerHTML = puntos;
		mult *= 2;
		mult_counter.innerHTML = "X" + mult;
		mult_button.innerHTML = 10*mult*mult + " puntos"
	}
}
