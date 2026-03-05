<?php

defined('ABSPATH') or die("No script kiddies please!");

?>
<div class="padma-admin-container">

	<img class="padma-logo" src="<?php echo padma_url() . '/library/admin/images/padma-theme-logo-square-250.png'; ?>">

	<div class="padma-admin-row menu">
		<a href="javascript:void(0)" onclick="openTabAdmin(event, 'welcome');">
			<div class="padma-admin-title tablink padma-admin-border-red"><?php _e('Willkommen','padma'); ?></div>
		</a><a href="javascript:void(0)" onclick="openTabAdmin(event, 'options');">
			<div class="padma-admin-title tablink"><?php _e('Optionen','padma'); ?></div>
		</a><a href="javascript:void(0)" onclick="openTabAdmin(event, 'need-help');">
			<div class="padma-admin-title tablink"><?php _e('Brauchst Du Hilfe?','padma'); ?></div>
		</a><a href="javascript:void(0)" onclick="openTabAdmin(event, 'unlimited-growth');">
			<div class="padma-admin-title tablink"><?php _e('Unbegrenztes Wachstum','padma'); ?></div>
		</a>
	</div>

	<div id="welcome" class="padma-admin-tab" style="">	
		<div class="content">

			<h1><?php _e('Willkommen!','padma'); ?></h1>

			<p><?php _e('Deine <strong>Padma Theme Builder</strong> Installation ist bereit.','padma'); ?></p>
			
			<p><strong><?php _e('Jetzt loslegen!','padma'); ?></strong></p>

			<br>

			<p><?php _e('Um diese Seite auszublenden, ändere einfach die Standard-Admin-Seite in <a href="?page=padma-options">Padma » Optionen</a>.','padma'); ?></p>

			<div class="separator"></div>

			<h2><?php _e('Padma | Unlimited - Kernfunktionen im ClassicPress-Dashboard / Admin-Menü.','padma'); ?></h2>
			<div class="box">
				<h3><?php _e('Padma | Unlimited - Willkommen!','padma'); ?></h3>
				<p><?php _e('(Diese Seite)','padma'); ?></p>
				<p><?php _e('Zugang zu ►','padma'); ?></p>
				<p><?php _e('Allgemeine Informationen, Dokumentation und Support für <b>Padma</b> | Unlimited Theme Builder.','padma'); ?></p>
				<p><?php _e('Blöcke und Vorlagen zur Erweiterung der Möglichkeiten des <b>Padma</b> | Unlimited Theme Builder.','padma'); ?></p>
			</div>
			

			<h2><?php _e('PS Padma starter users','padma'); ?></h2>
			<div class="box">
				<p><?php _e('Bitte lies die PS Padma | Unlimited Theme Builder <a href="https://cp-psource.github.io/ps-padma/">Dokumentation</a>.','padma'); ?></p>
			</div>


			<div class="box">
				<h3><?php _e('PS Padma | Unlimited Visual Editor','padma'); ?></h3>
				
				<p><?php _e('PS Padma | Unlimited Visual Editor ist ein leistungsstarkes Werkzeug zur Gestaltung von WordPress-Website-Layouts und -Vorlagen. Passen Sie fast jedes visuelle Element Ihrer Websites über eine grafische Benutzeroberfläche an (Code kann bei Bedarf einfach über den integrierten Code-Editor hinzugefügt werden).','padma'); ?></p>

				<p><?php _e('Erfahre mehr über die Plattform in der Dokumentation <a rel="noopener" href="https://power-source.github.io/ps-padma/blog/basics/before-using-the-visual-editor/">"Einführung in den PS Padma | Unlimited Visual Editor". </a>','padma'); ?></p>

				<a href="<?php echo home_url() . '/?visual-editor=true'; ?>" class="access-to-unlimited-editor"><span class="text"><?php _e('Starte <b>PS Padma</b> | Unlimitierten Editor','padma'); ?></span><span class="line -right"></span><span class="line -top"></span><span class="line -left"></span><span class="line -bottom"></span></a>
			</div>

		</div>
	</div>

	<div id="options" class="padma-admin-tab" style="display:none">
		<div class="content">
			<div class="box">
				<h3><?php _e('PS Padma | Optionen','padma'); ?></h3>
				<p><?php _e('Richte deine Google Analytics, SEO, Favicons und andere erweiterte Einstellungen ein.','padma'); ?></p>
			</div>
			<h2 class="center"><?php _e('PS Padma | Werkzeuge','padma'); ?></h2>
			<div class="box">
				<h3><?php _e('Systeminformationen','padma'); ?></h3>
				<p><?php _e('Um ein Ticket zu öffnen oder eine Hilfefrage im Forum zu stellen, gib bitte diese Systeminformationen an.','padma'); ?></p>
			</div>
			<div class="box">
				<h3><?php _e('Snapshots','padma'); ?></h3>
				<p><?php _e('Um Speicherplatz freizugeben, lösche bitte PS Padma | Theme Builder-Snapshots.','padma'); ?></p>
			</div>
			<div class="box">
				<h3><?php _e('Zurücksetzen','padma'); ?></h3>
				<p><?php _e('Anweisungen zum Zurücksetzen deiner PS Padma | Unlimited WordPress Theme Builder-Installation.','padma'); ?></p>
			</div>
		</div>
	</div>

	<div id="need-help" class="padma-admin-tab" style="display:none">
		<div class="content">
			<h2 class="center"><?php _e('Hilfe','padma'); ?></h2>
			<p><?php _e('PS Padma | Unlimited Theme Builder bietet professionellen Support und umfassende Dokumentation, um Ihnen zu helfen, Ihre Projekte zum Leben zu erwecken.','padma'); ?></p>			
			<div class="separator"></div>
			<div class="box">
				<h3><?php _e('PS Padma | Dokumentation','padma'); ?></h3>
			<p><?php _e('Registriere dich bei uns und erhalte kostenlosen Zugang zu unserer umfassenden Dokumentation. <a target="_blank" href="https://power-source.github.io/ps-padma/" rel="noopener">https://power-source.github.io/ps-padma/</a>','padma'); ?></p>
			</div>
		</div>
	</div>

	<div id="unlimited-growth" class="padma-admin-tab" style="display:none">
		<div class="content">
			<h2 class="center"><?php _e('PS Padma | Unlimitiertes Wachstum','padma'); ?></h2>			
			<p><?php _e('Entwickle, teile und bringe Templates und benutzerdefinierte Blöcke auf den Markt.','padma'); ?></p>
			<p><?php _e('Gemeinsames Arbeiten wird das Wachstum der Community fördern, also mach mit!','padma'); ?></p>
			<p><?php _e('Gestalte schneller und passe deinen Workflow mit nützlichen Tools an.','padma'); ?></p>
			<div class="separator"></div>
			<div class="box">
				<h3><?php _e('PS Padma | Unlimited Templates','padma'); ?></h3>
				<p><?php _e('<strong>PS Padma | Unlimited Templates</strong> minimiert deinen Projektentwicklungsprozess, optimiert die Designphase und führt zu einer schnelleren Inhaltserstellung und -bereitstellung.','padma'); ?></p>
			</div>
			<div class="box">
				<h3><?php _e('PS Padma | Unlimited Blocks','padma'); ?></h3>
				<p><?php _e('Erweitere die Funktionalität deiner PS Padma | Unlimited WordPress Theme Builder-Installation, indem du nützliche benutzerdefinierte Blöcke zu deinen Projekten hinzufügst.','padma'); ?></p>
				<p><?php _e('Dienste: Dokumentation, Lifesaver (Migration von HTW/Blox), Child Theme, Templates in der Cloud, Site-Monitor.','padma'); ?></p>								

				<p><?php _e('Nutze PSOURCE-Dienste, um das Potenzial von PS Padma freizuschalten.','padma'); ?></p>
				<p><?php _e('Installiere den <a href="https://github.com/Power-Source/ps-update-manager/releases" target="_blank" rel="noopener">PSOURCE MANAGER</a>, um Dein Projekt so richtig awesome zu machen.','padma'); ?></p>
			</div>
		</div>
	</div>

</div>