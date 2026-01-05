<?php defined( 'ABSPATH' ) or exit; ?>

<div class="notice notice-info padma-unlimited-notice-rate">

	<img alt="PS Padma" src="<?php echo get_template_directory_uri() . '/library/admin/images/padma-theme-logo-square-250.png'; ?>" class="avatar avatar-120 photo" height="120" width="120">

	<div class="padma-unlimited-notice-rate-content">

		<div class="padma-unlimited-notice-rate-content-text">
			<p><?php _e( 'Hallo bei PS Padma', 'padma' ); ?>,</p>
			<p><?php _e( 'Unser Team hat wirklich hart daran gearbeitet, Dir dieses leistungsstarke Tool zur Verfügung zu stellen. Wir hoffen, es gefällt Dir.', 'padma' ); ?></p>
			<p><?php _e( 'PS Padma ist der offizielle Nachfolger unseres UpFront-Frameworks. Beachte bitte, derzeit befindet sich dieser Theme-Builder in der Finalen Testphase.', 'padma' ); ?></p>
			<h4><?php _e( 'Möchtest Du mitarbeiten?', 'padma' ); ?></h4>
			<ul>				
				<li><?php _e( '- Fehler melden: https://github.com/Power-Source/ps-padma/issues', 'padma' ); ?></li>
				<li><?php _e( '- Gemeinsames Programmieren über GitHub', 'padma' ); ?></li>
				<li><?php _e( '- Schlage Funktionalitäten, Blöcke oder Plugins vor', 'padma' ); ?></li>
				<li><?php _e( '- Trete unseren Netzwerk bei', 'padma' ); ?></li>
				<li><?php _e( '- Teile PS Padma Builder mit Kollegen und Freunden', 'padma' ); ?></li>
				<li><?php _e( '- Sag es weiter!', 'padma' ); ?></li>
			</ul>			
			<p><?php _e( 'Lasst uns gemeinsam bauen!', 'padma' ); ?></p>
			<p><?php _e( '@PSOURCE', 'padma' ); ?></p>
		</div>

		<p class="padma-unlimited-notice-rate-actions">		
			<a href="https://github.com/Power-Source/ps-padma" class="padma-admin-social-icon" target="_blank"><img src="<?php echo get_template_directory_uri() . '/library/admin/images/github.png'; ?>"></a>			
			<a href="<?php echo self::get_dismiss_link(); ?>" class="padma-unlimited-notice-rate-dismiss"><?php _e( 'Hab es gelesen', 'padma' ); ?></a>
		</p>

	</div>

</div>

<style>
	.padma-unlimited-notice-rate {
		position: relative;
		padding: 15px 20px;
	}
	.padma-unlimited-notice-rate .avatar {
		position: absolute;
		left: 20px;
		top: 20px;
	}
	.padma-unlimited-notice-rate-content {
		margin-left: 140px;
	}
	.padma-unlimited-notice-rate-content-text p {
		font-size: 15px;
	}
	p.padma-unlimited-notice-rate-actions {
		margin-top: 15px;
	}
	p.padma-unlimited-notice-rate-actions a {
		vertical-align: middle !important;
	}
	p.padma-unlimited-notice-rate-actions a + a {
		margin-left: 20px;
	}
	.padma-unlimited-notice-rate-dismiss {
		position: absolute;
		top: 10px;
		right: 10px;
		padding: 10px 15px 10px 21px;
		font-size: 13px;
		line-height: 1.23076923;
		text-decoration: none;
	}
	.padma-unlimited-notice-rate-dismiss:before {
		position: absolute;
		top: 8px;
		left: 0;
		margin: 0;
		-webkit-transition: all .1s ease-in-out;
		transition: all .1s ease-in-out;
		background: 0 0;
		color: #b4b9be;
		content: "\f153";
		display: block;
		font: 400 16px / 20px dashicons;
		height: 20px;
		text-align: center;
		width: 20px;
	}
	.padma-unlimited-notice-rate-dismiss:hover:before {
		color: #c00;
	}
</style>
