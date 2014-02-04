function openURL(url){
   window.open(url, '_blank');
   return false;
}

function social_toggle_div(main, id) {
  var div = document.getElementById(id);
  var maindiv = document.getElementById(main);
  
  if(div.style.display=="none") {
	div.style.left=maindiv.parentNode.offsetLeft+3+'px';
    div.style.display = "block";
    maindiv.innerHTML = _t('P_SOCIAL_SHARE_MOINS');
  } else {
    div.style.display = "none";
    maindiv.innerHTML = _t('P_SOCIAL_SHARE_PLUS');
  }
}

