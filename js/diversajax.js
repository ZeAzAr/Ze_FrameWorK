function loadData(namedata, iddata,lien,funcreturn) {
     // Cr�ation de l'objet
     var XHR = new XHRConnection();
     var XHREval;

     XHR.appendData(namedata, document.getElementById(iddata).value);
     // On soumet la requ�te
     // Signification des param�tres:
     //      + On indique � l'objet qu'il faut appeler le fichier search.php
     //      + On utilise la m�thode POST, adapt�e l'envoi d'information
     //      + On indique quelle fonction appeler lorsque l'op�ration a �t� effectu�e
  //   XHR.sendAndLoad("search.php?base=<?php echo $Base; ?>&langh=<?php echo $Langh; ?>&langs=<?php echo $Langs; ?>", "POST", afficherResultatsRecherche);
     XHREval = 'XHR.sendAndLoad(lien, "POST", '+funcreturn+');';
     eval(XHREval);
}

function loadDataFiltre(namedata, iddata,lien,funcreturn) {
     // Cr�ation de l'objet
     var XHR = new XHRConnection();
     var XHREval;

   document.getElementById(iddata).value = nettoieString(document.getElementById(iddata).value);


     XHR.appendData(namedata, document.getElementById(iddata).value);
     // On soumet la requ�te
     // Signification des param�tres:
     //      + On indique � l'objet qu'il faut appeler le fichier search.php
     //      + On utilise la m�thode POST, adapt�e l'envoi d'information
     //      + On indique quelle fonction appeler lorsque l'op�ration a �t� effectu�e
  //   XHR.sendAndLoad("search.php?base=<?php echo $Base; ?>&langh=<?php echo $Langh; ?>&langs=<?php echo $Langs; ?>", "POST", afficherResultatsRecherche);
     XHREval = 'XHR.sendAndLoad(lien, "POST", '+funcreturn+');';
     eval(XHREval);
}

// Ce code est tres utile pour executer un script js inclue dans le r�sultat de l'ajax :D
function evalJS(obj) {
	 var JSResult = obj.responseText;
	 if (JSResult.length > 0) {
	  	eval(JSResult);
	 }
}

function loadAjaxJS(lien) {
	var XHRJS = new XHRConnection();
    var XHRJSEval;
    XHRJSEval = 'XHRJS.sendAndLoad(lien, "GET", evalJS);';
    eval(XHRJSEval);
    var obj = "location.reload();"
		setTimeout(obj,300);
}

function loadURL(lien) {
	var obj = "location.reload();"
	setTimeout(obj,200);
}

function retourneFormTableauvirgule(objTab) {
	var inputresult = "";
	var taille = objTab.length;
	for (i=0; i< taille; i++) {
		if (i>0) {
			inputresult = inputresult +',';
		}
		inputresult = inputresult + objTab[i].value;
	}

	return inputresult;
}

function retourneFormTableauarray(objTab) {
	var inputresult = new Array();
	var taille = objTab.length;
	for (i=0; i< taille; i++) {
		inputresult[i] = objTab[i].value;
	}
	return inputresult;
}