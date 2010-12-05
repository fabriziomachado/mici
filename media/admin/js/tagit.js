var BACKSPACE = 8;
var ENTER = 13;
var COMMA = 44;

(function($)
{
	$.fn.tagit = function(options)
	{
		var name = (options.name) ? options.name:'tags';
		var limit = (options.limit) ? options.limit:null;
		var existingTags = (options.existingTags) ? options.existingTags:[];
		var tag_count = 0;
		var el = this;
		
		el.addClass('tagit');

		var html_input_field = '<li class="tagit-new"><input class="tagit-input" type="text" \/><\/li>';
		el.html(html_input_field);

		var tag_input = el.children('.tagit-new').children('input[name=""].tagit-input');
		
		for(var i in existingTags)
		{
			create_choice(existingTags[i]);
		}
		
		$('.closetag').click(function()
		{
			tag_count -= 1;
		});

		$(this).click(function(e)
		{
			if (e.target.tagName == 'A')
			{
				$(e.target).parent().remove();
			}
			else
			{
				tag_input.focus();
			}
		});

		tag_input.keypress(function(event)
		{
			if (event.which == BACKSPACE)
			{
				if (tag_input.val() == '')
				{	
					tag_count -= 1;
					$(el).children('.tagit-choice:last').remove();
				}
			}
			else if (event.which == COMMA || event.which == ENTER)
			{
				event.preventDefault();

				var typed = tag_input.val();
				typed = typed.replace(/,+$/, '');
				typed = typed.trim();

				if (typed != '')
				{
					if (is_new (typed))
					{
						create_choice(typed);
					}
					
					tag_input.val('');
				}
			}
		});

		tag_input.autocomplete({
			source: options.availableTags, 
			select: function(event,ui)
			{
				if (is_new (ui.item.value))
				{
					create_choice (ui.item.value);
				}

				tag_input.val('');

				return false;
			}
		});

		function is_new (value)
		{
			var is_new = true;
			var message = '';
			tag_input.parents('ul').children('.tagit-choice').each(
				function(i)
				{
					n = $(this).children('input').val();
					if (value.toLowerCase() == n.toLowerCase())
					{
						is_new = false;
						message = 'The tag "'+value+'" already exists.';
					}
				}
			);
			
			if( !is_new)
			{
				alert(message);
			}
			
			return is_new;
		}
		
		function create_choice(value)
		{
			tag_count += 1;
			
			if(limit && tag_count > limit)
			{
				tag_count -= 1;
				alert('You have exceded the maximum number of tags allowed ( Limit of '+limit+' tags ).');
				return false;
			}

			var el  = '<li class="tagit-choice">'+value+'<a class="closetag">&times;<\/a><input type="hidden" value="'+value+'" name="'+name+'[]" \/><\/li>';
			var li_search_tags = tag_input.parent();
			$(el).insertBefore(li_search_tags);
			tag_input.val('');			
		}
	};

	String.prototype.trim = function()
	{
		return this.replace(/^\s+|\s+$/g, '');
	};

})(jQuery);
