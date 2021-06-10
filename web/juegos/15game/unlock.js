function unlockThis(img) {
	const squares =  document.getElementsByClassName("square");
	for (var i=0; i<squares.length; i++) {
		if (img==0) {
			squares[i].innerHTML = i+1;
			squares[i].style.border = "0.1vh solid rgba(0,0,0, 0.1)";
			squares[i].style["background-image"] = "";
		}
		else {
			squares[i].style["background-image"] = "url("+src+"/img/"+img+".jpg)";
			squares[i].innerHTML = '';
			squares[i].style.border = '';
		}
	}
}