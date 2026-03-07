# ExcerptsPlus Block - Modulare Struktur

## 📁 Verzeichnisstruktur

```
excerpts-plus/
├── excerpts-plus-block.php              # Hauptblock-Klasse (Wrapper)
├── excerpts-plus-options.php            # Block-Optionen
├── excerpts-plus-display-legacy.php     # Legacy Display-Logik (temp)
├── excerpts-plus-functions-legacy.php   # Legacy Funktionen (temp)
├── README.md                           # Diese Datei
│
├── classes/                            # Moderne modulare Klassen
│   ├── Helpers.php                     # Helper-Funktionen
│   ├── ImageProcessor.php              # Bild-Verarbeitung & Cache
│   ├── MetaHandler.php                 # Meta & Custom Fields
│   └── QueryBuilder.php                # WP_Query Building
│
├── assets/                             # Frontend-Assets
│   ├── css/                           # Stylesheets
│   ├── js/                            # JavaScript
│   └── media/                         # Bilder
│
└── includes/                           # Externe Libraries
    └── jo-resizer/                     # Image Resizer

```

## 🎯 Module-Übersicht

### 1. **Helpers.php** (Basis-Funktionen)
- Kategorien/Tags/Taxonomies holen
- HTML-Bereinigung
- Debugging
- Farbkonvertierung

### 2. **ImageProcessor.php** (Bildverarbeitung)
- Bild-Resize & Crop- Cache-Verwaltung
- Focal-Point Support
- WPML-Unterstützung

### 3. **MetaHandler.php** (Meta-Informationen)
- Post-Meta parsen (%date%, %author%, etc.)
- Custom Fields verarbeiten
- Kommentar-Formatierung

### 4. **QueryBuilder.php** (Query-Building)
- WP_Query zusammenbauen
- Kategorien/Tags/Taxonomies
- Custom Fields Filter
- Pagination

## 🔄 Migration-Status

**Aktuell:** Hybrid-Ansatz
- ✅ Neue Module erstellt und funktionsfähig
- ⚠️ Legacy-Code wird noch verwendet (sicher & stabil)
- 🎯 Schrittweise Migration möglich

**Vorteile:**
- Sofort bessere Struktur
- Keine Funktionalitätsverluste
- Wartbarer Code
- Schrittweise Verbesserungen möglich

## 🚀 Verwendung

### Block verwenden
```php
// Im Layout
PadmaBlocks::display_block($block);
```

### Shortcode verwenden
```
[excerptsplus id=123]
[excerptsplus id=123 conditions='{"field":"category","value":"news"}']
```

### Module direkt nutzen
```php
use Padma_Advanced\ExcerptsPlus\Helpers;
use Padma_Advanced\ExcerptsPlus\ImageProcessor;

// Helper
$categories = Helpers::get_category_list();

// Bild verarbeiten
$image_html = ImageProcessor::process_image($block_id, $post_id, $url, $path, $settings, $dimensions);
```

## 🎨 Styling-Elemente

Der Block registriert folgende CSS-Selektoren:
- `.excerpts-plus-excerpt` - Cell Wrapper
- `.ep-cell` - einzelne Zelle
- `.excerpt-title` - Titel
- `.excerpt-content` - Content
- `.excerpt-image` - Bilder
- `.ep-meta` - Meta-Informationen
- uvm.

## ⚙️ Optionen

Über 100+ Optionen in Tabs organisiert:
- **General:** Basis-Einstellungen
- **Filters:** Post Type, Kategorien, Tags
- **Layout:** Anordnung, Spalten
- **Images:** Bild-Einstellungen
- **Content:** Excerpt/Content, Länge
- **Meta:** Meta-Informationen
- **Titles:** Titel-Optionen
- **Sliders:** Slider-Funktionalität
- **Advanced:** Erweiterte Einstellungen

## 🔧 Weiterentwicklung

### Nächste Schritte für vollständige Modernisierung:
1. ✅ Module erstellt
2. ⏳ Renderer-Modul (HTML-Ausgabe trennen)
3. ⏳ Cache-System modernisieren
4. ⏳ Legacy-Code schrittweise ersetzen
5. ⏳ Unit Tests hinzufügen

