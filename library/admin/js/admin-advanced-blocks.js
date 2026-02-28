jQuery(document).ready(function($) {
	// Kategorie-Filter
	$(document).on('click', '.padma-advanced-filter-categories a', function(event) {
		event.preventDefault();

		var filter = $(this).data('filter');
		$('.padma-advanced-filter-categories a').removeClass('active');
		$(this).addClass('active');

		if (filter === 'all') {
			$('.padma-advanced-list-item').show();
			return;
		}

		$('.padma-advanced-list-item').hide();
		$('.padma-advanced-list-item.' + filter).show();
	});
});
