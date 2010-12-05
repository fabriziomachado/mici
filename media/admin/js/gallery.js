jQuery.expr[':'].Contains = function(a,i,m){
    return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase())>=0;
};

function filter_gallery(filter, target, open)
{
    $(filter).keyup(function(){
        $(target+' a').hide();
        $(target).find('a:Contains("'+$(this).val()+'")').show();

        if(open)
        {
            $(target).show();
            $('#body-wrapper').click(function(){
                $(target).hide();
            });
        }
    });
}

function auto_gallery()
{
    $('.sku').bind('blur', function(){
	var name = $(this).attr('name');
	name = name.split('_', 2);
	var val = $(this).val();
	$('#colorway-gallery_'+name[1]+' option[text='+val+']').attr("selected", true);
    });
}

this.imagePreview = function(){
    xOffset = 225;
    yOffset = 5;

    $('.ddChild a').hoverIntent(function(e){
        var c = ($(this).children('span').text()) ? '<br/>' + $(this).children('span').text() : '';

        if($(this).children('img').attr('src') != undefined)
        {
            var url = $(this).children('img').attr('src').replace('img/height/25', 'img/prod/grid/s');
            $('body').append('<p id="preview"><img src="'+ url +'" alt="Image preview" />'+ c +'<\/p>');
            $('#preview').css('top',(e.pageY - xOffset) + 'px').css('left', ($(this).parent().width()+450)+yOffset).fadeIn('fast');
        }
    },
    function(){
        $('#preview').remove();
    });
};