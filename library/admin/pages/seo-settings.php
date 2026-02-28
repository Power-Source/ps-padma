<?php
/**
 * Padma SEO Settings Admin Page
 * 
 * Hauptdatei für SEO Einstellungen
 * Die einzelnen Module werden aus dem /seo Verzeichnis eingebunden
 */

?>

<div class="wrap padma-admin-page" id="padma-seo-page">

	<div class="icon-padma icon-32"></div>

	<h1><?php _e('Padma SEO Einstellungen', 'padma'); ?></h1>
	
	<p class="description"><?php _e('Optimiere deine Website für Suchmaschinen mit diesen umfassenden SEO-Einstellungen.', 'padma'); ?></p>

	<?php
	// Prüfe auf SEO Plugin Konflikte
	if (PadmaSEO::plugin_active()) {
		switch (PadmaSEO::plugin_active()) {
			case 'aioseop':
				echo '<div class="notice notice-warning"><p>' . __('Padma hat erkannt, dass du das All In One SEO Pack Plugin verwendest. Um Konflikte zu vermeiden, kann einige Padma SEO Funktionalität deaktiviert sein.', 'padma') . '</p></div>';
				break;
			case 'wpseo':
				echo '<div class="notice notice-warning"><p>' . __('Padma hat erkannt, dass du das Yoast\'s WordPress SEO Plugin verwendest. Um Konflikte zu vermeiden, kann einige Padma SEO Funktionalität deaktiviert sein.', 'padma') . '</p></div>';
				break;
		}
	}
	?>

	<h2 class="nav-tab-wrapper">
		<a class="nav-tab nav-tab-active" href="#tab-templates"><?php _e('SEO Templates', 'padma'); ?></a>
		<a class="nav-tab" href="#tab-sitemaps"><?php _e('Sitemaps', 'padma'); ?></a>
		<a class="nav-tab" href="#tab-schema"><?php _e('Schema.org', 'padma'); ?></a>
		<a class="nav-tab" href="#tab-social"><?php _e('Social Media', 'padma'); ?></a>
		<a class="nav-tab" href="#tab-advanced"><?php _e('Erweitert', 'padma'); ?></a>
	</h2>

	<form method="post" action="options.php" enctype="multipart/form-data" id="padma-admin-form">
		<?php settings_fields('padma-general'); ?>

		<div class="tab-content">

			<?php
			// Lade alle SEO Module aus dem /seo Verzeichnis
			$seo_modules_dir = PADMA_LIBRARY_DIR . '/admin/pages/seo';
			
			// SEO Templates
			if (file_exists($seo_modules_dir . '/templates.php')) {
				require_once $seo_modules_dir . '/templates.php';
			}
			
			// Sitemaps
			if (file_exists($seo_modules_dir . '/sitemaps.php')) {
				require_once $seo_modules_dir . '/sitemaps.php';
			}
			
			// Schema.org
			if (file_exists($seo_modules_dir . '/schema.php')) {
				require_once $seo_modules_dir . '/schema.php';
			}
			
			// Social Media
			if (file_exists($seo_modules_dir . '/social.php')) {
				require_once $seo_modules_dir . '/social.php';
			}
			
			// Advanced
			if (file_exists($seo_modules_dir . '/advanced.php')) {
				require_once $seo_modules_dir . '/advanced.php';
			}
			?>

		</div><!-- .tab-content -->

		<p class="submit" style="margin-top: 30px;">
			<input type="submit" name="submit" id="submit" class="button button-primary button-hero" value="<?php _e('Änderungen speichern', 'padma'); ?>" />
		</p>

	</form>



</div><!-- #padma-seo-page -->

<script type="text/javascript">
jQuery(document).ready(function($) {
	
	// Initialize - show first tab
	$('.big-tab').removeClass('active').hide();
	$('#tab-templates-content').addClass('active').show();
	
	// Tab Navigation
	$(document).on('click', '.nav-tab', function(e) {
		e.preventDefault();
		
		var target = $(this).attr('href');
		
		// Update nav tabs
		$('.nav-tab').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		
		// Show/hide content - hide all and show only the active one
		$('.big-tab').removeClass('active').hide();
		$(target + '-content').addClass('active').show();
	});

	// SEO Templates Toggle Advanced Options
	$(document).on('click', '#seo-templates-advanced-options-title', function() {
		$('#seo-templates-advanced-options').slideToggle();
		$(this).find('span').text(function() {
			return $('#seo-templates-advanced-options').is(':visible') ? 
				'<?php _e('verbergen &uarr;', 'padma'); ?>' : 
				'<?php _e('anzeigen &darr;', 'padma'); ?>';
		});
	});

	// SEO Template Selector (from old options.php JS)
	if ($('#seo-template-select').length) {
		$(document).on('change', '#seo-template-select', function() {
			var selected = $(this).val();
			
			// Update form inputs with saved template values
			$('input[name="title"]').val($('#seo-' + selected + '-title').val());
			$('textarea[name="description"]').val($('#seo-' + selected + '-description').val());
			
			// Update checkboxes
			$('input[name="noindex"]').prop('checked', $('#seo-' + selected + '-noindex').val() == '1');
			$('input[name="nofollow"]').prop('checked', $('#seo-' + selected + '-nofollow').val() == '1');
			$('input[name="noarchive"]').prop('checked', $('#seo-' + selected + '-noarchive').val() == '1');
			$('input[name="nosnippet"]').prop('checked', $('#seo-' + selected + '-nosnippet').val() == '1');
			$('input[name="noodp"]').prop('checked', $('#seo-' + selected + '-noodp').val() == '1');
			$('input[name="noydir"]').prop('checked', $('#seo-' + selected + '-noydir').val() == '1');
		});

		// Save template changes back to hidden inputs
		$(document).on('change keyup', '#seo-templates-inputs input, #seo-templates-inputs textarea', function() {
			var selected = $('#seo-template-select').val();
			var field = $(this).attr('name') || $(this).attr('id');
			
			if ($(this).is(':checkbox')) {
				$('#seo-' + selected + '-' + field).val($(this).is(':checked') ? '1' : '0');
			} else {
				$('#seo-' + selected + '-' + field).val($(this).val());
			}
		});

		// Trigger initial load
		$('#seo-template-select').trigger('change');
	}
});
</script>

<style>
#padma-seo-page {
	background: #fff;
	padding: 20px 30px;
	margin: 20px 20px 20px 0;
	box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

#padma-seo-page h1 {
	font-size: 28px;
	margin-bottom: 10px;
}

#padma-seo-page .description {
	font-size: 14px;
	color: #666;
	margin-bottom: 25px;
}

/* Tab Content Styling */
.tab-content {
	position: relative;
}

.big-tab {
	display: none;
}

.big-tab.active {
	display: block;
}

#seo-templates-header {
	background: #f7f7f7;
	padding: 15px 20px;
	border-left: 4px solid #2271b1;
	margin: 20px 0;
}

#seo-template-select {
	padding: 8px 12px;
	font-size: 14px;
	min-width: 250px;
}

#seo-templates-inputs {
	background: #fafafa;
	padding: 25px;
	border: 1px solid #ddd;
}

#seo-templates-advanced-options-title {
	background: #f0f0f0;
	padding: 12px 20px;
	margin: 30px 0 0 0;
	border-left: 4px solid #999;
}

#seo-templates-advanced-options-title span {
	float: right;
	color: #2271b1;
	font-weight: normal;
}

#seo-templates-advanced-options {
	background: #fafafa;
	padding: 20px;
	border: 1px solid #ddd;
	border-top: none;
}

.alert {
	padding: 15px 20px;
	border-left: 4px solid;
	margin: 15px 0;
}

.alert-blue {
	background: #e7f3ff;
	border-color: #2271b1;
}

.alert-yellow {
	background: #fff8e5;
	border-color: #dba617;
}

.alert-green {
	background: #ecf7ed;
	border-color: #46b450;
}

.alert h3 {
	margin: 0 0 10px 0;
	font-size: 16px;
}

.alert p:last-child {
	margin-bottom: 0;
}

.hr {
	border-top: 1px solid #ddd;
	margin: 25px 0;
}

/* Padma Admin Inputs Overrides */
.padma-admin-options-group .inside {
	padding: 20px;
}

.padma-admin-options-group h2.hndle {
	font-size: 16px;
	padding: 15px 20px;
}
</style>
