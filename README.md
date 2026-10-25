# PS Padma ContentBuilder

**Deutsch** | [English](README.en.md)

[![Version](https://img.shields.io/badge/Version-1.2.2-2271b1?style=flat-square)](readme.txt)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777bb4?style=flat-square&logo=php&logoColor=white)
![WordPress](https://img.shields.io/badge/WordPress-bis%207.1.0-21759b?style=flat-square&logo=wordpress&logoColor=white)
![ClassicPress](https://img.shields.io/badge/ClassicPress-2.7.4-03768e?style=flat-square)
[![Lizenz](https://img.shields.io/badge/Lizenz-GPL--2.0--or--later-2ea44f?style=flat-square)](https://www.gnu.org/licenses/gpl-2.0.html)

![PS Padma ContentBuilder](screenshot.png)

**PS Padma** ist ein visueller Theme- und Template-Builder fuer WordPress und
ClassicPress. Er verbindet ein flexibles Drag-and-Drop-Grid mit einer
umfangreichen Blockbibliothek, damit individuelle Websites ohne verpflichtende
externe Page-Builder-Plugins entstehen.

## Highlights

### Visueller Editor

- Layouts direkt im Browser mit einem **Drag-and-Drop-Grid** erstellen
- Abstaende, Farben, Typografie und weitere Gestaltungseigenschaften per
  **Point-and-Click** anpassen
- Inhalte, Vorlagen und Layout-Varianten im Editor verwalten
- Aenderungen im Kontext der eigenen Website sehen, statt Einstellungen blind
  zu konfigurieren

### Integrierte Bausteine

PS Padma bringt Bausteine fuer typische Website-Bereiche direkt mit, darunter:

- Header, Footer, Navigation, Suche und Site-Logo
- Text, Bilder, Video, Audio, Galerien und Social Media
- Inhalte, Listings, Breadcrumbs, Widget-Bereiche und Formulare
- Accordion, Tabs, Buttons, Spalten, Slider, Portfolios und Hero-Bereiche
- Karten, Kartenraster, Zitate, Spoiler und viele weitere Erweiterungen

### Shortcode-Workflow

- Shortcodes im Builder zusammenstellen und konfigurieren
- Wiederverwendbare Presets fuer gleichbleibende Komponenten speichern
- Eigene Farbpaletten und Icon-Auswahl im Generator nutzen
- Verschachtelte Inhalte und Shortcodes auch im Visual Editor bearbeiten

### Auf Projekte ausgelegt

- Funktioniert mit **WordPress** und **ClassicPress**
- Keine Pflicht zu einem zusaetzlichen Builder-Plugin
- Lokale, wartbare Theme-Struktur mit PHP-, CSS- und JavaScript-Assets
- Laufende Sicherheits- und Qualitaetsverbesserungen
- SEO laesst sich mit **PS SEO** ergaenzen, das Metadaten, strukturierte Daten
  und Sitemap-Filter bereitstellt

## Voraussetzungen

| Komponente | Voraussetzung |
| --- | --- |
| WordPress | 5.0 oder neuer |
| ClassicPress | 2.7.0 oder neuer |
| PHP | 8.0 oder neuer |
| Empfohlenes `memory_limit` | 256M oder hoeher |
| Empfohlenes `post_max_size` | 32M oder hoeher |

## Schnellstart

1. Dieses Repository herunterladen oder das Theme als ZIP bereitstellen.
2. Den Ordner nach `wp-content/themes/` kopieren oder die ZIP-Datei unter
   **Design > Themes > Theme hinzufuegen** hochladen.
3. **PS Padma ContentBuilder** aktivieren.
4. Die PS-Padma-Seiten im Administrationsbereich oeffnen.
5. Den Visual Editor starten, ein Layout anlegen und Bausteine platzieren.

## Sicherheit

PS Padma wird fortlaufend gepflegt und haertet dynamische Inhalte, URLs und
Legacy-Abhaengigkeiten. Falls die PHP-Verarbeitung in entsprechenden Bloecken
deaktiviert werden soll, kann dies in der `wp-config.php` gesteuert werden:

```php
define('PADMA_DISABLE_PHP_PARSING', false);
```

## Dokumentation und Hilfe

- [Dokumentation](https://power-source.github.io/ps-padma/)
- [Issues und Feedback](https://github.com/Power-Source/ps-padma/issues)
- [Releases](https://github.com/Power-Source/ps-padma/releases)
- [PSOURCE Manager](https://github.com/Power-Source/ps-update-manager/releases)

## Lizenz

PS Padma steht unter der [GNU General Public License v2.0 oder neuer](LICENSE).
