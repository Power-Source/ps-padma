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
	
	// Plugin-Aktivierung
	$(document).on('click', '.padma-activate-plugin', function(event) {
		event.preventDefault();
		
		var $button = $(this);
		var plugin = $button.data('plugin');
		var nonce = $button.data('nonce');
		
		$button.prop('disabled', true).text(padmaAdvancedBlocks.activating);
		
		$.ajax({
			url: padmaAdvancedBlocks.ajaxurl,
			type: 'POST',
			data: {
				action: 'padma_activate_plugin',
				plugin: plugin,
				nonce: nonce
			},
			success: function(response) {
				if (response.success) {
					$button.text(padmaAdvancedBlocks.activated);
					setTimeout(function() {
						location.reload();
					}, 500);
				} else {
					$button.prop('disabled', false).text(padmaAdvancedBlocks.error);
					if (response.data && response.data.message) {
						alert(response.data.message);
					}
				}
			},
			error: function() {
				$button.prop('disabled', false).text(padmaAdvancedBlocks.error);
			}
		});
	});
});
