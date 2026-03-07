=== PS Padma ===
Contributors: PSOURCE
Requires at least: 5.0
Tested up to: WordPress 8.6, ClassicPress 2.6.0
Version: 1.0.8
Requires PHP: 8.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: theme, template, template builder, builder, theme builder, padma, visual editor, drag-and-drop

== Description ==

PS Padma ist ein moderner Theme- und Template-Builder für WordPress und ClassicPress.

Was euch erwartet:

* Visueller Editor mit Drag-and-Drop-Grid
* Design-Anpassungen per Point-and-Click
* Viele integrierte Blöcke direkt im Builder
* Keine Abhängigkeit von externen Builder-Plugins
* Solide Basis für schnelle, flexible und wartbare Projekte

Kurz gesagt: weniger Frickelei, mehr bauen.

Dokumentation:
https://power-source.github.io/ps-padma/

== Installation ==

1. Theme-ZIP hochladen (oder via FTP nach wp-content/themes/ kopieren).
2. Im Backend zu Design > Themes gehen.
3. PS Padma aktivieren.
4. Optional: In den PS Padma Optionen die Startseite/Standard-Adminseite festlegen.

== Empfohlene Serverwerte ==

PS Padma läuft auch mit kleineren Limits, stabiler wird es mit:

* PHP 8.0+
* memory_limit: 256M oder höher
* post_max_size: 32M oder höher

== Sicherheit ==

Wenn ihr die Ausführung von PHP in entsprechenden Blöcken deaktivieren wollt, ergänzt in eurer wp-config.php:

define('PADMA_DISABLE_PHP_PARSING', false);

== FAQ ==

= Brauche ich zusätzliche Builder-Plugins? =
Nein. PS Padma bringt den Visual Editor und die Kernfunktionen selbst mit.

= Wo finde ich Hilfe und Doku? =
Hier entlang: https://power-source.github.io/ps-padma/

= Läuft PS Padma auch mit WordPress? =
Ja, das Theme ist für WordPress und ClassicPress ausgelegt.

== Changelog ==

= 1.0.8 =
* Positionsmarker der Slider im Visual Editor wieder sichtbar gemacht
* Slider-Darstellung in Normal- und Night-Mode vereinheitlicht
* Veraltete jQuery-UI-Slider-Altlasten im Visual-Editor-Code bereinigt
* Neuer PowerForm-Block für ClassicPress-Kompatibilität hinzugefügt
* PowerForm-Block: Styling-Elemente für alle Formular-Komponenten im Design-Editor
* Contact-Form-7-Block entfernt (funktioniert nicht mit ClassicPress)
* Gravity-Forms-Block entfernt (nicht mehr benötigt)
* Shortcode-Block: E-Newsletter Integration mit Formular-Designer und Styling-Optionen
* PortfolioCards-Block vollständig auf Deutsch Informell lokalisiert
* PortfolioCards-Block: Standard Post-Type festgelegt und Kategorie-Ladung verbessert
* Alle Advanced Blocks (30+ Blöcke) auf Deutsch Informell lokalisiert

= 1.0.7 =
* Branding vereinheitlicht auf "PS Padma"
* Cloud-Save-Feature entfernt (nicht mehr benötigt)
* Get-More-Blocks-Werbung entfernt
* Admin-UI aufgeräumt und verbessert
* CSS-Konflikte im Admin-Bereich behoben
* Dokumentation modernisiert
* Sicherheits-Best-Practices für externe Links ergänzt

= 1.0.6 =
* Mehr Blöcke integriert
* Keine externen Plugins mehr erforderlich
* Verbesserungen für SEO-Workflows
* Weitere Stabilitäts- und UX-Optimierungen

= 1.0.5 =
* Kritischen Headway-Support-Fehler behoben

= 1.0.4 =
* Uploader überarbeitet

= 1.0.3 =
* Verbesserungen im UI des Visual Editors
* Fatal Error beim Anlegen einer Startseite behoben

= 1.0.2 =
* Google Fonts datenschutzsicher eingebunden
* Deprecated-Aufruf `isFunction` bereinigt

= 1.0.1 =
* PHP-8-Fixes
* Neue Dokumentation
* Neuer Updater

= 1.0.0 =
* Initial Release

== Copyright ==

Copyright 2014-2026 PSOURCE

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
