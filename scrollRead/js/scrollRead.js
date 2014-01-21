$(window).data('plugin_scroll_read', true);
$(window).scroll(function(){
    scrollRead();
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

function scrollRead(){

    // on arrête la fonction lorsqu'on fait le changement d'article
    if ($(window).data('plugin_scroll_read') == false) return;

    posEventSelected = $('.eventSelected').findPos();
    heightEventSelected = $('.eventSelected').height();
    pos_next_article = heightEventSelected + posEventSelected.y - 10;

    /*
    console.info('pos_next_article : '+ pos_next_article);
    console.info('windows ScrollTop : '+$(window).scrollTop());
    console.info('height:'+$(document).height());
    */

    if (pos_next_article < $(window).scrollTop())
    {
        $(window).data('plugin_scroll_read', false);

        /*
        console.info('pos_next_article : '+ pos_next_article);
        console.info('windows ScrollTop : '+$(window).scrollTop());
        console.info('height:'+$(document).height());
         */

        // mettre à lu l'article et passer au suivant uniquement si il n'est pas déjà lu.
        var buttonElement = $('.eventSelected .readUnreadButton');
        var parent = $(buttonElement).parent().parent();
        var tempo = true;
        //console.info(parent);
        if (parent.hasClass('eventRead'))
        {
            //passage à l'élément suivant
            targetNextEvent();
            $(window).data('plugin_scroll_read', true);
        }
        else
        {
            // l'élément n'est pas encore lu. on le passe à lu
            var id = $(target).attr('id');
            tempo = false;
            readThis(buttonElement,id,null,function(){
                // on fait un focus sur l'Event suivant
                targetThisEvent($('.eventSelected').next(),true);
                $(window).data('plugin_scroll_read', true);
            });
        }
    }
}