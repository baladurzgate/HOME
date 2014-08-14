<!---HOME--->
<div id ="output"></div>
<input id="url" type="hidden" value="request.php?c=mouseHouse&t=mouseHouse&p=request_usermap"> </div>
<script>
	var mousePos={
		x:0,
		y:0
	};
	var IE = document.all?true:false;
	if(!IE)document.captureEvents(Event.MOUSEMOVE);
	document.onmousemove = getMouseXY;
	var tempX = 0;
	var tempY = 0;
	function getMouseXY(e) {
		if (IE) { 
			tempX = event.clientX + document.body.scrollLeft;
			tempY = event.clientY + document.body.scrollTop;
		} 
		else{  
			tempX = e.pageX+document.body.style.left;
			tempY = e.pageY+document.body.style.top;
		}  
		mousePos={
			x:tempX,
			y:tempY
		}
		return true;
	};
	var message='';
	var tab=new Array();
	var url=document.getElementById("url").value;
	var output = document.getElementById('output');
	var inc=0;
	var color ='#'+Math.floor(Math.random()*16777215).toString(16);
	var xmlhttp;
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	function ask_server($send){
		xmlhttp.open("POST",$send,false);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		var params ='x='+mousePos.x+'&y='+mousePos.y+'&color='+color;
		xmlhttp.send(params);
	}
	xmlhttp.onreadystatechange = function(){
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
			tab = stringToArray(xmlhttp.responseText,"*","\n","=");
			var output = document.getElementById('output');
			output.innerHTML="";
			for (var i=0;i<tab.length;i++){
				var btn=document.createElement("div");
				btn.className="online_user";
				var mx = tab[i]['x'];
				var my = tab[i]['y'];
				if(mx<0){mx=0};
				if(my<0){my=0};
				btn.style.top=my-10;
				btn.style.left=mx-10;
				btn.style.backgroundColor=tab[i]['color'];
				output.appendChild(btn);
			}
		}
	}
	function stringToArray($str,$split1,$split2,$split3){
		var output=new Array();
		var firstSplit = $str.split($split1);
		for(var i = 0;i<firstSplit.length;i++){
			var object=new Array();
			var secondSplit = firstSplit[i].split($split2);
			for(var j = 0;j<secondSplit.length;j++){
				var thirdSplit= secondSplit[j].split($split3);
				if(thirdSplit.length>1){
					object[thirdSplit[0]]=thirdSplit[1]
				}else{
					object[thirdSplit[0]]="";
				}
			}
			output.push( object);
		}
		return output;
	}
	var mainLoop = setInterval(function(){
		ask_server(url);
	}, 100);
</script>

