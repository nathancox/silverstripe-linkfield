(function($) {
	$('.LinkField').livequery(function() {
		var $selectField = $(this);
		var name = $selectField.attr('name');

		$selectField.change(function() {
			var value = $('.selector:checked', $selectField).val();
			$('li', $selectField).removeClass('selected');
			$(':radio[value='+value+']', $selectField).parent().addClass('selected');
		});
		
	//	$selectField.trigger('change');
		
	});
	
})(jQuery);