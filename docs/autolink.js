(function($){

	$(init);

	function init(){
		$('h2[id], h3[id], h4[id]').each(function(){
			var h = $(this);
			var id = h.attr('id');
			var link = $('<a></a>');

			link
				.attr('href', '#' + id)
				.addClass('link')
				.html('¶')
			;
			h.prepend(link);
		});
	}

})(jQuery);
