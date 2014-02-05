function cacheListFeed_toggle_div() {
  var divmenubar = document.getElementById('menuBar');
  var Elem_article = document.querySelector('#main article');
  
  if(divmenubar.style.display=="none") {
  	// affichage de la menu bar
    divmenubar.style.display = "block";
	if (Elem_article.style.position!="fixed") {
		// On supprime la valeur pour utiliser celle de la CSS afin d'avoir un affichage dynamique
		Elem_article.style.width="";
	}
    document.getElementById('cacheListFeed_divbut_return').parentNode.removeChild(document.getElementById('cacheListFeed_divbut_return'));
    // Si l'utilisateur est connecté, on enregistre la position
	$.ajax({
				url: './action.php?action=cacheListFeed',
				type: 'post',
				data: 'cacher=1'
			});
  } else {
  	// cacher la menu bar
  	divmenubar.style.display = "none";
	Elem_article.style.width = "100%";
	returnButton = document.createElement('div');
	returnButton.setAttribute("class", "cacheListFeed_divbut");
	returnButton.setAttribute("id", "cacheListFeed_divbut_return");
	returnButton.setAttribute("title", "Afficher la liste des Feeds");
	returnButton.setAttribute("onclick", "cacheListFeed_toggle_div(this,'menuBar');");
	returnButton.innerHTML = "<";
	// Insère l'élément sans altérer les événements existants.
	Elem_article.insertBefore(returnButton, Elem_article.firstChild);
	// Si l'utilisateur est connecté, on enregistre la position
	$.ajax({
				url: './action.php?action=cacheListFeed',
				type: 'post',
				data: 'cacher=0'
			});
  }
}

function cacheListFeed_init(cache) {
  var divmenubar = document.getElementById('menuBar');
  var Elem_article = document.querySelector('#main article');
  
  if(cache) {
  	// affichage de la menu bar
    divmenubar.style.display = "block";
	if (Elem_article.style.position!="fixed") { Elem_article.style.width="67%"; }
    if (document.getElementById('cacheListFeed_divbut_return'))
      document.getElementById('cacheListFeed_divbut_return').parentNode.removeChild(document.getElementById('cacheListFeed_divbut_return'));
  } else {
  	// cacher la menu bar
  	divmenubar.style.display = "none";
	Elem_article.style.width = "100%";
	returnButton = document.createElement('div');
	returnButton.setAttribute("class", "cacheListFeed_divbut");
	returnButton.setAttribute("id", "cacheListFeed_divbut_return");
	returnButton.setAttribute("title", "Afficher la liste des Feeds");
	returnButton.setAttribute("onclick", "cacheListFeed_toggle_div(this,'menuBar');");
	returnButton.innerHTML = "<";
	// Insère l'élément sans altérer les événements existants.
	Elem_article.insertBefore(returnButton, Elem_article.firstChild);
  }
}

$.ajax({
	url: './action.php?action=getCacheListFeed',
	type: 'post',
	success : function(data) {
		if($('.index').length) { cacheListFeed_init(parseInt(data)); }
	}
});

