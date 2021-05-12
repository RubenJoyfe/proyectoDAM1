const left = document.querySelector('.left');
const section = document.querySelector('.sect');
const ul = document.querySelector('.menu');
const content = document.querySelector('.content');
document.querySelector('.toggle').onclick = function(){
	this.classList.toggle('Tactive');
	ul.classList.toggle('Tactive');
	content.classList.toggle('Tactive');
	left.classList.toggle('shide2');
	section.classList.toggle('shide');
}


/*BUSQUEDA*/



document.querySelector('#busqueda').addEventListener('keypress', keyPressed);


function keyPressed(e) {
	var code = (e.keyCode ? e.keyCode : e.which);
	if (code == 13) { //Enter keycode
		document.querySelector('#formSearch').submit();
    }
};