=== PS Padma ContentBuilder ===
Contributors: PSOURCE
Requires at least: 5.0
Tested up to: WordPress 8.6 
ClassicPress: 2.7.0
Version: 1.1.5
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
https://psource.eimen.net/wiki/ps-padma-contentbuilder-dokumentation/

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

= 1.1.6 =
* Visual Editor: Fontpicker-Tabs (Traditional Fonts / Google Web Fonts) im Design-Editor stabilisiert; unzuverlaessiges Umschalten und haengende Erstinitialisierung behoben
* Visual Editor: Google-Webfonts-Liste laedt beim Oeffnen jetzt zuverlaessig, auch ohne vorher manuell zwischen Sortieroptionen zu wechseln
* Visual Editor: Provider-Tab-Auswahl im Fontpicker korrigiert, damit beim erneuten Oeffnen wieder der passende Font-Provider aktiv ist
* Visual Editor: Auswahl aus Fontliste/Vorschau uebernimmt den gewaehlten Font jetzt konsistent in das Font-Family-Readout und markiert die Eigenschaft korrekt als angepasst
* Visual Editor: Live-Preview-Rendering von Google Fonts im VE-iframe repariert; ausgewaehlte Webfonts werden wieder sichtbar gerendert
* Visual Editor: Robustere Font-Metadaten-Verarbeitung (ID/Name/Family/Variants), damit Werte auch bei inkonsistenten Datenquellen sauber uebernommen werden

= 1.1.5 =
* Visual Editor: Colorpicker im Design-Editor und in Panel-Color-Inputs wieder stabil verfuegbar (`$(...).colorpicker is not a function` behoben)
* Visual Editor: RequireJS-Konfiguration um Shim fuer `deps/colorpicker` erweitert, damit das nicht-AMD-Plugin zuverlaessig mit jQuery geladen wird
* Visual Editor: Event-Handler-Regression behoben (`...handleObj.handler).apply is not a function`), asynchronen Colorpicker-Require-Fallback entfernt
* Architektur: Kein jQuery UI reaktiviert; bestehender jQuery-UI-freier Ansatz bleibt erhalten
* Visual Editor: JS-Fallback-Texte im Code selbst (nicht nur Laufzeit-Lokalisierung) breit auf Deutsch Informell umgestellt, inkl. Inspector-Kontextmenues, Tooltips, Confirm-Dialoge und Snapshot-UI
* Visual Editor: Weitere harte UI-Strings in Modulen fuer Layout/Content-Selector, Saving, Iframe-Hinweise, Wrapper/Grid-Management und Benachrichtigungen auf Deutsch umgestellt
* UX/Konsistenz: Terminologie im Editor vereinheitlicht (z. B. Instanzen, Zustaende, Block-/Wrapper-Optionen, Mehr laden)

= 1.1.4 =
* NEU: Nativer Hero-Shortcode plus passender Hero-Block fuer den Padma Builder hinzugefuegt
* Shortcode Builder: Markierte bestehende Shortcodes im Classic Editor werden jetzt erkannt und direkt zur passenden Builder-Maske geladen
* Shortcode Builder: Beim Bearbeiten bestehender Wrap-Shortcodes wird im Feld "Inhalt" nur noch der innere Inhalt geladen, nicht mehr der komplette Shortcode
* Shortcode Builder: Bearbeitete markierte Shortcodes werden im Editor jetzt direkt ersetzt statt zusaetzlich neu eingefuegt
* Shortcode Builder: Editor-/Selektions-Erkennung fuer TinyMCE und Textarea-Fallback robuster gemacht
* Shortcode Builder: Admin-Skripte fuer den Builder mit Cache-Busting versehen, damit JS-Aenderungen im Backend sofort greifen
* Visual Editor: Inspector-Initialisierung gehaertet, damit fehlende qTip-Tooltip-API keinen JS-Abbruch mehr ausloest (`Cannot read properties of undefined (reading 'show')`)
* Visual Editor: Element-Filter in der rechten Leiste beim Erstladen stabilisiert ("Zeige Elemente des aktuellen Layouts" wird nach Iframe-Ready zuverlaessig angewendet)
* Visual Editor: Weitere UI-Texte in der rechten Leiste auf Deutsch Informell umgestellt (u.a. Navigation, Stile, Instanzen, Gruppenbezeichnungen)

= 1.1.3 =
* Fix: Einen Bug der nur begrenzt Medien im Uploader angezeigt hat 
* Fix: Blockoptionen Tabs lassen sich nun wieder wechseln
* Weitere Textanpassungen

= 1.1.2 =
* NEU: Theme-nativer Shortcode Builder integriert (kein psource-shortcodes Plugin mehr erforderlich)
* Shortcode Builder: „Shortcode einfügen"-Button im TinyMCE-Editor (Pages, Posts, Custom Post Types)
* Shortcode Builder: Vollständiges Modal-Popup mit Shortcode-Auswahl, Gruppen-Filter und Suchfeld
* Shortcode Builder: Alle Shortcode-Felder (Text, Select, Bool, Farbe, Slider, Icon, Upload, Textarea) theme-intern gerendert
* Shortcode Builder: AJAX-Handler für Settings, Live-Vorschau, Icons, Terms und Taxonomien
* Shortcode Builder: Live-Vorschau-Funktion via `su_generator_preview` AJAX-Action implementiert
* Neue Klassen: `Padma_Shortcode_Generator`, `Padma_Generator_Data`, `Padma_Generator_Views`
* Assets: Magnific Popup, SimpleSlider, Farbtastic und generator.css werden nur auf Post-Edit-Seiten geladen

= 1.1.1 =
* BETA: Online Template Verwaltung und öffentlicher Template-Katalog
* Grid Editor: Horizontales Block-Resize gehaertet, damit fehlende Grid-Guide/CSS-Werte keinen TypeError mehr ausloesen (`Cannot read properties of undefined (reading 'replace')` in `grid.js`)
* Grid Editor: Guide-Alignment defensiv abgesichert, wenn Guides oder Margin-Werte temporär fehlen
* Wrapper Controls: Parsing von `minHeight` und Wrapper-Margins robust gemacht, um weitere `replace`-Fehler in Randfaellen zu vermeiden
* Visual Editor: Weitere CSS-Parsing-Stellen (Inspector-Nudging, WYSIWYG-Panel, Wrapper-Margin-Feedback, Font-Browser) mit defensiven Fallbacks gegen `undefined.replace` gehaertet
* Visual Editor: Strict-Mode-Fehler bereinigt, indem ungueltige `delete <bezeichner>`-Aufrufe durch sichere Resets (`= undefined`) ersetzt wurden
* Optionen bereinigt: "Padma Entwickler-Version verwenden" und "Debug-Modus" inklusive zugehoeriger Laufzeitlogik vollstaendig entfernt

= 1.1.0 =
* Grid Editor: Wrapper-Resize stabilisiert, TypeError bei der Hoehenaenderung behoben (`Cannot read properties of undefined (reading 'height')` in `wrappers.js`)
* Visual Editor Tour: First-Run-Flow gehaertet (spaeterer Start nach UI-Ready-Checks, Fallbacks fuer fehlende Targets/Next-Handler, keine blockierende Tour mehr)
* Empty Template UX: Neuer gestalteter Empty-State statt nackter Standardmeldung mit prominentem CTA-Button "Starte den PS Padma ContentBuilder"
* Empty Template Texte: Auf Deutsch Informell umgestellt (inkl. Login-CTA fuer Nutzer ohne Bearbeitungsrechte)
* PHP 8: Warning für fehlenden Meta-Box-Input-Schlüssel type im Seiteneditor behoben
* Visual Editor: qTip2/jQuery-Offset-Fehler behoben (`elem.getClientRects is not a function`)
* Designer: Erfassen und Hover/Tooltip-Verhalten für normale Seitenelemente stabilisiert
* qTip2 Viewport-Berechnung für `window`/`document` robust gemacht (kein fehlerhafter `offset()`-Aufruf mehr)

= 1.0.9 =
* jQuery Migrate Deprecation Warnings behoben
* jQuery.fn.resize() durch jQuery.on('resize') ersetzt (unminified Versionen)
* jQuery.type() durch native typeof-Checks ersetzt
* Non-passive Event Listener für Scroll/Resize Events hinzugefügt
* Event-Namespacing für bessere Event-Verwaltung implementiert
* Owl Carousel minified und unminified Versionen aktualisiert
* Sticky Kit Event Handler optimiert
* Colorpicker Event Handler für Passive Listener aktualisiert
* QTip2 Event Handler modernisiert (typeof statt deprecated jQuery.type)
* require-and-jquery.js: jQuery.isNumeric mit nativen Type-Checks aktualisiert
* Slider Block komplett von FlexSlider auf Swiper 11.0+ migriert (keine jQuery-Abhängigkeit mehr)
* Slider Block: Moderne IIFE-basierte Initialisierung mit Retry-Logik implementiert
* Slider Block: HTML-Struktur modernisiert (.swiper statt .flexslider)
* Slider Block: CSS-Selektoren für Padma Design-Editor aktualisiert
* Visual Editor: Kritischen Bug im "Switch Block Type" Feature behoben
* Visual Editor: Block-Switching behält nun korrekt die Datenbank-ID (desired-id Mechanismus)
* Visual Editor: Operation-Reihenfolge bei Block-Type-Wechsel korrigiert
* Pin Board Block: Visual Editor Asset-Loading und Initialisierung mit Retry-Bootstrap verbessert
* Pin Board Block: jQuery Deprecations behoben (isFunction, bind, delegate → on)
* Pin Board Block: Design-Editor Selektoren korrigiert (.custom-fields img, ID-Duplikate behoben)
* Pin Board Block: Vollständige Lokalisierung auf Deutsch Informell (Optionen, Pagination, Metadata)
* Visual Editor Robustness: 7 Advanced Blocks mit direkten Asset-Fallbacks für VE-Iframe-Kontext gehärtet
* Content-Slider (Owl+Swiper Varianten): VE-Fallback für externe Carousel-Assets hinzugefügt
* Portfolio Block: VE-Fallback für Isotope, Magnific Popup und FontAwesome Assets hinzugefügt
* Portfolio-Cards Block: VE-Fallback für Block-spezifische Scripts und Styles hinzugefügt
* Content-to-Cards Block: VE-Fallback für Interaktivitäts-Assets hinzugefügt
* Lottiefiles Block: VE-Skip-Logik entfernt, direkter Script-Fallback für Lottie-Player implementiert
* Excerpts-Plus Block: VE-Fallback für jQuery UI, Cycle, DotDotDot und Slide-Content Assets hinzugefügt
* Gallery Block: VE-Asset-Loading mit direkten Fallback-Injections verstärkt
* Navigation Block: VE-Fallback für Menu-Dependencies (Superfish, SlickNav, Pushy) hinzugefügt
* INFO: Minified Files können nicht direkt editiert werden - nutze SCRIPT_DEBUG=true für Development

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
* NEU: ExcerptsPlus Block mit modularer Architektur integriert
* ExcerptsPlus: Magazine Layouts, Slider, umfangreiche Filter und Custom Fields
* ExcerptsPlus: 4 Core-Module (Helpers, ImageProcessor, MetaHandler, QueryBuilder)
* ExcerptsPlus: Vollständige Rückwärtskompatibilität mit Legacy-Code
* ExcerptsPlus: Bild-Cache-System mit Focal-Point und WPML-Support
* 21+ Advanced Blocks von falscher Plugin-Abhängigkeit befreit
* Spoiler-Block: Options-Struktur korrigiert, Repeater jetzt funktionsfähig
* Tabs-Block: Options-Struktur korrigiert, Repeater jetzt funktionsfähig
* Alle Shortcodes direkt im Theme integriert (keine externen Plugins erforderlich)
* PHP 8.0+ Kompatibilität für Image-Resizer verbessert
* Block-Registrierung und Init-Prozess für alle Advanced Blocks optimiert
* NEU: Erweitertes Template-Management-System mit Metadaten-Bearbeitung
* Template-Export als ZIP-Datei mit vollständigen Metadaten und Manifest
* Template-Import aus ZIP mit automatischer Metadaten-Wiederherstellung
* Template-Metadaten erweitert: Beschreibung, Dokumentations-URL, Bild-URL
* Admin-UI: Separate Ansicht für "Meine Vorlagen" vs "Standard Vorlagen"
* WordPress Media Library Integration für Template-Bilder
* Native Modal-Lösung implementiert (jQuery UI komplett entfernt)
* Knockout.js ViewModel für Template-Arrays mit bidirektionaler Synchronisation
* Sicherheits-Checks für alle neuen AJAX-Handler implementiert
* Deutsche Lokalisierung für alle Template-Management-Features
* GMap-Block zu PS Maps Block umgewandelt mit vollständiger PS Maps Plugin Integration
* PS Maps Block: Dropdown-Auswahl für alle existierenden Maps aus dem Backend
* PS Maps Block: Optional Override-Parameter für Breite, Höhe, Zoom und Kartentyp
* PS Maps Block: Nutzt zentrale Google Maps API-Key Verwaltung aus PS Maps Plugin
* PS Maps Block: Zugriff auf alle PS Maps Features (Marker, Routen, KML, Places, etc.)

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
