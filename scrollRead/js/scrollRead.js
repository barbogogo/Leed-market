/*
 @nom: scrollRead
 @auteur: Maël ILLOUZ (mael.illouz@cobestran.com)
 @description: Js de gestion du scroll pour la lecture automatique des articles.
 */

$(window).data('plugin_scroll_read', true);

// Remplacement de la fonction existante de Leed.
// A faire évoluer en fonction des évolutions et corrections de la fonction dans Leed
var oldReadThis = readThis;
readThis = function(element,id,from,callback) {
    var activeScreen = $('#pageTop').html();
    var parent = $(element).parent().parent();
    var nextEvent = $('#'+id).next();
    //sur les éléments non lus
    if(!parent.hasClass('eventRead')){
        $.ajax({
            url: "./action.php?action=readContent",
            data:{id:id},
            success:function(msg){
                if(msg.status == 'noconnect') {
                    alert(msg.texte)
                } else {
                    if( console && console.log && msg!="" ) console.log(msg);
                    switch (activeScreen){
                        case '':
                            // cas de la page d'accueil
                            parent.addClass('eventRead');
                            if(callback){ callback(); }
                            // on simule un scroll si tous les events sont cachés
                            /*if($('article section:last').attr('style')=='display: none;') {
                                $(window).scrollTop($(document).height());
                            }*/
                            // on compte combien d'article ont été lus afin de les soustraires de la requête pour le scroll infini
                            $(window).data('nblus', $(window).data('nblus')+1);
                            // on diminue le nombre d'article en haut de page
                            $('#nbarticle').html(parseInt($('#nbarticle').html()) - 1)
                            break;
                        case 'selectedFolder':
                            parent.addClass('eventRead');
                            if(callback){ callback(); }
                            // on compte combien d'article ont été lus afin de les soustraires de la requête pour le scroll infini
                            $(window).data('nblus', $(window).data('nblus')+1);
                            break;
                        default:
                            // autres cas : favoris, selectedFeed ...
                            parent.addClass('eventRead');
                            if(callback){ callback(); }
                            break;
                    }
                }
            }
        });
    }else{  // sur les éléments lus
        // si ce n'est pas un clic sur le titre de l'event
        if(from!='title'){
            $.ajax({
                url: "./action.php?action=unreadContent",
                data:{id:id},
                success:function(msg){
                    if(msg.status == 'noconnect') {
                        alert(msg.texte)
                    } else {
                        if( console && console.log && msg!="" ) console.log(msg);
                        parent.removeClass('eventRead');
                        // on compte combien d'article ont été remis à non lus
                        if ( (activeScreen=='') || (activeScreen=='selectedFolder') ) $(window).data('nblus', $(window).data('nblus')-1);
                        if(callback){ callback(); }
                    }
                }
            });
        }
    }
}

$(window).scroll(function(){
    if(!$('.settings').length){
        scrollRead();
    }
});

// récupérer la position d'un DOM
jQuery.fn.extend({
    findPos : function() {
        obj = jQuery(this).get(0);
        var curleft = obj.offsetLeft || 0;
        var curtop = obj.offsetTop || 0;
        while (obj = obj.offsetParent) {
            curleft += obj.offsetLeft
            curtop += obj.offsetTop
        }
        return {x:curleft,y:curtop};
    }
});

// fonction principal de gestion du scroll
function scrollRead(){

    // on arrête la fonction lorsqu'on fait le changement d'article
    if ($(window).data('plugin_scroll_read') == false) return;

    var posEventSelected = $('.eventSelected').findPos();

    /*
    console.info('posEventSelected.y : ' + posEventSelected.y);
    console.info('windows ScrollTop : '+ $(window).scrollTop());
    console.info('height:'+$(document).height());
    */

    if ($('article #loader').hasClass('finScroll'))
    {
        $('article section').last().height ($(window).height());
    }

    // je déclanche uniquement sur la ligne virtuelle située en dessous du titre d'un article.
    if (posEventSelected.y + 20 < $(window).scrollTop())
    {
        $(window).data('plugin_scroll_read', false);

        /*
        console.info('posEventSelected.y : ' + posEventSelected.y);
        console.info('windows ScrollTop : '+ $(window).scrollTop());
        console.info('height:'+$(document).height());
        */

        // mettre à lu l'article et passer au suivant uniquement si il n'est pas déjà lu.
        var buttonElement = $('.eventSelected .readUnreadButton');
        var parent = $(buttonElement).parent().parent();
        var tempo = true;
        //console.info(parent);
        // élément lu?
        if (parent.hasClass('eventRead'))
        {
            //passage à l'élément suivant
            //targetNextEvent();
            targetThisEvent($('.eventSelected').next(':visible'),false);
            $(window).data('plugin_scroll_read', true);
        }
        else
        {
            // l'élément n'est pas encore lu. on le passe à lu
            var id = $(target).attr('id');
            tempo = false;
            readThis(buttonElement,id,null,function(){
                // on fait un focus sur l'Event suivant
                targetThisEvent($('.eventSelected').next(),false);
                $(window).data('plugin_scroll_read', true);
            });
        }
    }
}