let dineros = 0;
let mult = 1;

function myFun(){
	dineros += mult;
	counter.innerHTML = dineros;
}

function reset(){
	dineros=0;
	mult = 1;
	counter.innerHTML = dineros;
	mult_counter.innerHTML = "X" + mult;
	mult_button.innerHTML = 10*mult*mult + " Dineros"
}

function multiplicador() {
	if (dineros>=10*mult*mult) {
		dineros -= 10*mult*mult;
		counter.innerHTML = dineros;
		mult *= 2;
		mult_counter.innerHTML = "X" + mult;
		mult_button.innerHTML = 10*mult*mult + " Dineros"
	}
}
