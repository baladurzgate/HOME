//---SUBMIT--
function submit($req,$send,$formId,$feedbackId,$header){
    var elem,params,xmlhttp,feedback;
	params ='request='+$req+'&';
	feedback=document.getElementById($feedbackId);
	if($req!=='logout'){
		elem = document.getElementById($formId).elements;
		for(var i = 0; i < elem.length; i++){
			if (elem[i].tagName == "SELECT"){
				params += elem[i].name + "=" + encodeURIComponent(elem[i].options[elem[i].selectedIndex].value) + "&";
			}else{
				params += elem[i].name + "=" + encodeURIComponent(elem[i].value) + "&";
			}
		} 
	}
	var xmlhttp;
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.open("POST",$send,false);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
				console.log(xmlhttp.responseText);
				console.log($header);
            if ( xmlhttp.responseText.trim()=="valid"){
				if($header!=''){
					window.location.href=$header;
				}
            }
            else{
				if($req!=='logout'){
					feedback.innerHTML=xmlhttp.responseText;
				}
            }
        }

    }
	
	xmlhttp.send(params);
}