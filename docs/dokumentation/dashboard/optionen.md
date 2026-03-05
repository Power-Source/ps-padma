---
layout: psource-theme
title: "Optionen - Dashboard - PS Padma"
---

<div class="menu">
  <a href="/ps-padma/" style="color:#38c2bb;">🏠 Start</a>
  ·
  <a href="/ps-padma/dokumentation/" style="color:#38c2bb;">📖 Dokumentation</a>
  ·
  <a href="/ps-padma/dokumentation/dashboard/" style="color:#38c2bb;">📋 Dashboard</a>
</div>

# ⚙️ Optionen

Die **Optionen**-Seite ist deine Zentrale für alle globalen PS Padma Einstellungen. Hier konfigurierst du das Verhalten des Themes, des Visual Editors und viele weitere Features.

## 📍 Wo finde ich diese Seite?

**Dashboard » PS Padma » Optionen**

---

## 📋 Optionen-Tabs

Die Optionen-Seite ist in mehrere Tabs unterteilt:

### 1. 🌐 General (Allgemein)

**Favicon**
- URL zu deinem Favicon-Bild
- Das kleine Icon, das im Browser-Tab und in den Lesezeichen erscheint
- Falls du kein Favicon hast: [favicon.cc](http://www.favicon.cc/) zum Erstellen nutzen

**Feed URL**
- Für Services wie FeedBurner
- Leitet RSS-Feed-Anfragen an deine externe Feed-URL weiter

**Admin Preferences (Admin-Einstellungen)**

- **Standard Admin-Seite:** Welche Seite beim Klick auf "PS Padma" im Menü öffnen soll
  - Erste Schritte (Willkommensseite)
  - Visual Editor (direkt zum Layout-Builder)
  - Optionen (diese Seite)
  
- **Versionsnummer im Menü verbergen:** Theme-Version aus dem Admin-Menü ausblenden

**Grid-Einstellungen**

- **Spaltenbreite:** Standardbreite für Grid-Spalten (in Pixeln)
- **Gutter-Breite:** Abstand zwischen Spalten (in Pixeln)
- Diese Einstellungen gelten global, können aber pro Layout überschrieben werden

---

### 2. 📜 Scripts/Analytics

**Tracking-Codes & Custom Scripts**

- **Google Analytics:** Tracking-ID eintragen (z.B. `UA-XXXXXXXX-X` oder `G-XXXXXXXXXX`)
- **Custom Header Scripts:** Code, der im `<head>` geladen wird (z.B. Webfonts, Tracking)
- **Custom Footer Scripts:** Code, der vor `</body>` geladen wird (z.B. Chat-Widgets)

**Unterstützte Formate:**
- JavaScript (`<script>...</script>`)
- CSS (`<style>...</style>`)
- Meta-Tags

**💡 Tipp:** Für Performance solltest du Scripts bevorzugt im Footer laden.

---

### 3. 🎨 Visual Editor

**Editor-Verhalten**

- **Live-Vorschau aktivieren:** Änderungen sofort im Editor sichtbar machen
- **Design-Editor-Optionen:**
  - Welche CSS-Properties verfügbar sein sollen
  - Vereinfachte vs. detaillierte Ansicht
- **Grid-Overlay-Farbe:** Farbe der Grid-Hilfslinien im Editor

**Performance-Einstellungen**

- **Editor-Caching:** Zwischenspeicherung von Editor-Daten
- **Auto-Save-Intervall:** Wie oft automatisch gespeichert wird (in Sekunden)

---

### 4. 🔧 Advanced (Erweitert)

**Developer-Optionen**

- **Debug-Modus:** Zeigt zusätzliche Debug-Informationen im Editor
- **Disable Responsive Grid:** Deaktiviert responsive Anpassungen (nur für spezielle Fälle)
- **Custom CSS laden:** Eigene CSS-Dateien einbinden

**Capabilities (Berechtigungen)**

- Welche Benutzerrollen Zugriff auf welche Bereiche haben
- Standard: Nur Administratoren haben vollen Zugriff

**PHP Parsing**

- Standardmäßig aktiviert (für fortgeschrittene Nutzer)
- Kann mit `define('PADMA_DISABLE_PHP_PARSING', true)` in der `wp-config.php` deaktiviert werden

---

### 5. 🔄 Compatibility (Kompatibilität)

**Plugin-Kompatibilität**

- **Headway-Theme-Kompatibilität:** Für Migration von Headway zu PS Padma
- **Legacy-Support:** Unterstützung für ältere PS Padma-Versionen

**Konflikt-Behebung**

- Deaktivierung von störenden Plugins/Features im Visual Editor
- Script-Konflikt-Management

---

### 6. 📱 Mobile

**Mobile Ansicht**

- **Mobile Breakpoints:** Ab welcher Bildschirmbreite gilt welches Layout
  - Desktop: > 1024px
  - Tablet: 768px - 1024px
  - Mobile: < 768px

**Mobile-spezifische Einstellungen**

- **Touch-optimierte Navigation:** Größere Touch-Bereiche
- **Mobile-Menü-Verhalten:** Slide-in, Dropdown, etc.

---

### 7. 🔤 Fonts

**Web Fonts Verwaltung**

- **Google Fonts (lokal):** DSGVO-konform via lokale Einbindung
- **System Fonts:** Standard-Schriften des Betriebssystems
- **Custom Fonts:** Eigene Schriftarten hochladen

**Font-Loading-Strategie**

- **Preload:** Kritische Fonts sofort laden
- **Async:** Fonts asynchron nachladen (bessere Performance)

**💡 Sicherheitshinweis:** PS Padma bindet Google Fonts standardmäßig lokal ein (DSGVO-konform).

---

## 💾 Speichern nicht vergessen!

Alle Änderungen werden erst nach Klick auf **"Änderungen speichern"** am Ende der Seite wirksam.

---

## 💡 Tipps & Best Practices

### Performance-Optimierung

1. **Grid-Werte moderat halten:** Zu große Grids können langsam werden
2. **Scripts im Footer laden:** Bessere Page-Speed-Werte
3. **Editor-Caching aktiviert lassen:** Schnellere Editor-Ladezeiten

### SEO-Einstellungen

**Hinweis:** SEO-Optionen haben seit Version 1.0.7 eine eigene Seite!

→ [SEO Suite Dokumentation](/ps-padma/dokumentation/dashboard/seo)

### Standard Admin-Seite wählen

- **Für Anfänger:** "Erste Schritte" (Übersicht)
- **Für erfahrene Nutzer:** "Visual Editor" (direkter Zugang)
- **Für Wartung:** "Tools" (schneller System-Check)

---

## 🔗 Siehe auch

- [📋 Dashboard-Übersicht](/ps-padma/dokumentation/dashboard/)
- [🚀 Erste Schritte](/ps-padma/dokumentation/dashboard/erste-schritte)
- [🔍 SEO Suite](/ps-padma/dokumentation/dashboard/seo)
- [🔧 Tools](/ps-padma/dokumentation/dashboard/tools)

---

## 🆘 Probleme?

**Änderungen werden nicht übernommen?**
- Cache leeren: [PS Padma » Tools » Cache leeren](/ps-padma/dokumentation/dashboard/tools)
- Browser-Cache leeren (Strg+F5)

**Editor lädt nicht?**
- PHP Memory Limit prüfen (min. 256M)
- JavaScript-Fehler in der Browser-Konsole checken

→ [Tools & System-Infos](/ps-padma/dokumentation/dashboard/tools)

---

**Navigation:**
- [← Erste Schritte](/ps-padma/dokumentation/dashboard/erste-schritte)
- [Templates →](/ps-padma/dokumentation/dashboard/templates)
