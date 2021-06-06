var tiles=[];
var lastTile;
var fondo = '';

let firstMove=false;
let playing = false;

let hour = 0;
let minute = 0;
let second = 0;
let millisecond = 0;

let cron;

window.onresize = function(event){
	fondo = document.getElementsByClassName("square")[0].style.backgroundImage;
	squares(option.value);
};


document.addEventListener("DOMContentLoaded", function(event){
	option.disabled = true;
	loadOptions();
	squares(option.value);
	fondos();
	solucion.addEventListener("click", solve);

	var slider = document.getElementsByClassName("slider")[0];
	slider.oninput = function() {
	  for(let tile of tiles){
	  	tile.square.style.transition = "top "+(100-this.value)/50+"s, left "+(100-this.value)/50+"s";
	  }
	}
	desbloqueable.style.display = 'none';
});

function loadOptions(){
	mas.addEventListener("click", function() {
		if (parseInt(option.value)<10) {
			fondo = document.getElementsByClassName("square")[0].style.backgroundImage;
			option.value=parseInt(option.value)+1;
			squares(option.value);
		}
	});
	menos.addEventListener("click", function() {
		if (parseInt(option.value)>3) {
			fondo = document.getElementsByClassName("square")[0].style.backgroundImage;
			option.value=parseInt(option.value)-1;
			squares(option.value);
		}
	});
	rand.addEventListener("click", function(){
		shuffle(option.value);
		reset();
		firstMove = true;
		playing = true;
	});
}

function squares(choice){
	tiles = [];
	game.innerHTML = '';
	const sz = parseInt(choice);
	var parentSize = document.getElementById("game").offsetHeight;
	// if (parentSize%210!==0) {
	// 	parentSize += 2520-parentSize%2520;
	// }
	// while(parentSize%210!==0){
	// 	parentSize++;
	// }
	// document.getElementById("game").style.width = parentSize+"px";
	// document.getElementById("game").style.height = parentSize+"px";
	
	// console.log("Father: "+document.getElementById("game").offsetHeight+" - "+document.getElementById("game").offsetWidth);

	const widheig = Math.round(parentSize/sz);
	
	// console.log("wh: "+widheig);
	const totalSize = sz*sz; //Casillas totales
	for(var i = 0; i < totalSize; i++){
		//------------------------------------------
		const Tile = {
			startX: i%sz,
			startY: parseInt(i/sz),
			currentX: i%sz,
			currentY: parseInt(i/sz),
			size: widheig,
			move: function(x, y){
				this.currentX = x;
				this.currentY = y;
				this.square.style.left = x*this.size+'px';
				this.square.style.top = y*this.size+'px';
			}
		};
		// console.log("size: "+Tile.size);
		//------------------------------------------
		Tile.square = document.createElement("div");
		if (fondo==='') {
			Tile.square.innerHTML = i+1;
			Tile.square.style.border = "0.1vh solid rgba(0,0,0, 0.1)";
		}
		if (i+1===totalSize) {
			// Tile.square.innerHTML = '';
			Tile.square.classList.add("last");
			lastTile = Tile;
		}
		Tile.square.classList.add("square");
		//Establecer tamaño y posición
		Tile.square.style.width = widheig+'px';
		Tile.square.style.height = widheig+'px';
		Tile.square.style["background-image"] = fondo;
		Tile.square.style["font-size"] = 100/sz+"%";
		const sliderValue = document.getElementsByClassName("slider")[0].value;
		Tile.square.style.transition = "top "+(100-sliderValue)/50+"s, left "+(100-sliderValue)/50+"s";
		Tile.move(i%sz,parseInt(i/sz));
		game.appendChild(Tile.square);
		tiles.push(Tile);
		// console.log("dafukkkk: "+Tile.size*document.getElementById("game").offsetHeight);
		Tile.square.addEventListener("mousedown", function(event){
			if (movement(Tile)&&firstMove) {
				firstMove=false;
				start();
			}
		});
		Tile.square.style["background-size"] = sz+"00% "+sz+"00%";
		Tile.square.style["background-position"] = -1*Tile.startX*widheig+"px "+-1*Tile.startY*widheig+"px";

		reset();
		playing = false;
		firstMove = false;

		// if (i===totalSize) {
			// console.log("child: "+Tile.square.offsetWidth+" - "+Tile.square.offsetHeight);
		// }
	}
}


function resize(tile, parentSize){
	Tile.square.style.width = widheig+'px';
	Tile.square.style.height = widheig+'px';
	Tile.square.style["font-size"] = 100/sz+"%";
}

/*............SHUFFLE............*/

	function shuffle(size) {
	    size-=1;
	    var n;
	    var lastN=-1;
	    for (var i = 0; i < 10*size*size;) {
	        n = parseInt(Math.random()*4);
	        if (n!==lastN) {
	            switch(n){
	                case 0:
	                {
	                    if (lastTile.currentX>0) {
	                        swap(getTileAt(lastTile.currentX-1,lastTile.currentY));
	                        lastN = 2;
	                        i++;
	                    }
	                    break;
	                }
	                case 1:
	                {
	                    if (lastTile.currentY>0) {
	                        swap(getTileAt(lastTile.currentX,lastTile.currentY-1));
	                        lastN = 3;
	                        i++;
	                    }
	                    break;
	                }
	                case 2:
	                {
	                    if (lastTile.currentX<size) {
	                        swap(getTileAt(lastTile.currentX+1,lastTile.currentY));
	                        lastN = 0;
	                        i++;
	                    }
	                    break;
	                }
	                default:
	                {
	                    if (lastTile.currentY<size) {
	                        swap(getTileAt(lastTile.currentX,lastTile.currentY+1));
	                        lastN = 1;
	                        i++;
	                    }
	                    break;
	                }
	            }
	        }
	    }
	}

	function getTileAt(x,y){
	    for (let tile of tiles) {
	        if (x === tile.currentX && y === tile.currentY) {
	            return tile;
	        }
	    }
	    return 0;
	}

/*..........FIN-SHUFFLE..........*/

function checkWin() {
	for (let tile of tiles) {
		if (tile.startX !== tile.currentX || tile.startY !== tile.currentY) {
			return false;
		}
	}
	return true;
}


function movement(myTile) {
	const x = myTile.currentX;
	const y = myTile.currentY;
	let moved=false;
	if (lastTile.currentY===y) {
		if (lastTile.currentX>x) {
			do {
				swap(getTileAt(lastTile.currentX-1,lastTile.currentY));
			}while (lastTile.currentX!==x);
		} else {
			do {
				swap(getTileAt(lastTile.currentX+1,lastTile.currentY));
			}while (lastTile.currentX!==x);
		}
		moved = true;
	} else if (lastTile.currentX===x) {
		if (lastTile.currentY>y) {
			do {
				swap(getTileAt(lastTile.currentX,lastTile.currentY-1));
			}while (lastTile.currentY!==y);
		} else {
			do {
				swap(getTileAt(lastTile.currentX,lastTile.currentY+1));
			}while (lastTile.currentY!==y);
		}
		moved = true;
	}
	if (playing && checkWin()) {
		victory();
	}
	return moved;
}

function swap(myTile){
	const x = myTile.currentX;
	const y = myTile.currentY;
	myTile.move(lastTile.currentX,lastTile.currentY);
	lastTile.move(x,y);
}

function victory() {
	console.log("Victoria");
	pause();
	playing = false;
}

function fondos() {
	const options = document.getElementsByClassName("foto");
	for (var i = 0; i < options.length; i++) {
		options[i].style.backgroundImage = "url('img/"+i+".jpg')";
		options[i].addEventListener("click", changeFondo);
	}
}

function changeFondo() {
	fondo = this.style.backgroundImage;
	if (fondo==='url("img/0.jpg")') {
		fondo = '';
	}
	let i=0;
	for (let tile of tiles) {
		i++;
		tile.square.style.backgroundImage = fondo;
		if (fondo==='') {
			tile.square.innerHTML = i;
			tile.square.style.border = "0.1vh solid rgba(0,0,0, 0.1)";
		}
		else {
			tile.square.innerHTML = '';
			tile.square.style.border = '';
		}
	}
	// solve();
}

function solve() {
	for (let tile of tiles) {
		tile.move(tile.startX, tile.startY);
	}
	reset();
	playing = false;
	firstMove = false;
}


//**********************************Timer******************************************
function start() {
  pause();
  cron = setInterval(() => { timer(); }, 10);
}

function pause() {
  clearInterval(cron);
}

function reset() {
	pause();
	hour = 0;
	minute = 0;
	second = 0;
	millisecond = 0;
	document.getElementById('hour').innerText = '00';
	document.getElementById('minute').innerText = '00';
	document.getElementById('second').innerText = '00';
	document.getElementById('millisecond').innerText = '000';
}

function timer() {
  if ((millisecond += 10) == 1000) {
    millisecond = 0;
    second++;
  }
  if (second == 60) {
    second = 0;
    minute++;
  }
  if (minute == 60) {
    minute = 0;
    hour++;
  }
  document.getElementById('hour').innerText = returnData(hour);
  document.getElementById('minute').innerText = returnData(minute);
  document.getElementById('second').innerText = returnData(second);
  document.getElementById('millisecond').innerText = returnData(millisecond);
}

function returnData(input) {
  return input > 10 ? input : `0${input}`
}