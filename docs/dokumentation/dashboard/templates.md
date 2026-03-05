---
layout: psource-theme
title: "Templates - Dashboard - PS Padma"
---

<div class="menu">
  <a href="/ps-padma/" style="color:#38c2bb;">🏠 Start</a>
  ·
  <a href="/ps-padma/dokumentation/" style="color:#38c2bb;">📖 Dokumentation</a>
  ·
  <a href="/ps-padma/dokumentation/dashboard/" style="color:#38c2bb;">📋 Dashboard</a>
</div>

# 📦 Templates

Die **Templates**-Seite ist deine Zentrale für Import, Export und Verwaltung von PS Padma Templates. Hier sicherst du deine Designs und installierst fertige Vorlagen.

## 📍 Wo finde ich diese Seite?

**Dashboard » PS Padma » Templates**

---

## 🎯 Was sind Templates?

Ein **Template** in PS Padma beinhaltet:

- ✅ **Alle Layouts** (Seiten, Posts, Archive, etc.)
- ✅ **Design-Einstellungen** (Farben, Fonts, Styles)
- ✅ **Blöcke & Wrappers** (komplette Grid-Struktur)
- ✅ **Layout-spezifische Optionen**
- ✅ **SEO-Einstellungen** pro Layout

**Nicht enthalten:**
- ❌ WordPress-Inhalte (Posts, Pages, Medien)
- ❌ Plugin-Einstellungen (außer PS Padma)
- ❌ Theme-Optionen-Einstellungen

---

## 📋 Template-Funktionen

### 1. 📤 Export Current Template

**Wozu?**
- Komplettes Design sichern
- Design auf andere Website übertragen
- Backup vor großen Änderungen

**So geht's:**

1. Klicke auf **"Export Current Template"**
2. Fülle das Formular aus:
   - **Template Name:** Beschreibender Name (z.B. "Mein Blog Design 2026")
   - **Template Author:** Dein Name (wird automatisch vorgeschlagen)
   - **Template Version:** z.B. "1.0" oder "2.1"
   - **Template Image:** Screenshot deines Designs (optional, aber empfohlen)
3. Klicke auf **"Export Template"**
4. `.zip`-Datei wird heruntergeladen

**💡 Tipp:** Erstelle regelmäßig Exports als Backup vor größeren Design-Änderungen!

---

### 2. 📥 Install Template

**Wozu?**
- Fertige Design-Vorlagen installieren
- Backup wiederherstellen
- Design von anderer Website importieren

**So geht's:**

1. Klicke auf **"Install Template"**
2. Wähle `.zip`-Datei von deinem Computer
3. Upload startet automatisch
4. Nach erfolgreicher Installation erscheint das Template in der Liste
5. Klicke auf **"Activate"**, um es zu nutzen

**⚠️ Wichtig:** 
- Der Import **überschreibt keine bestehenden Templates**
- Er erstellt ein **neues Template**
- Dein aktives Template bleibt unverändert, bis du das neue aktivierst

---

### 3. 🗂️ Template-Verwaltung

**Template-Liste**

Alle installierten Templates werden als Kacheln angezeigt:

- **Screenshot:** Vorschaubild (falls vorhanden)
- **Template-Name & Version**
- **Author:** Ersteller des Templates
- **"Active":** Markiert das aktuell aktive Template

**Verfügbare Aktionen:**

- **Activate:** Template aktivieren (wird sofort live)
- **Delete:** Template löschen (nur bei inaktiven Templates)

**Base Template:** Das Standard-Template kann nicht gelöscht werden.

---

## 🔄 Template wechseln

### Aktives Template ändern

1. Finde das gewünschte Template in der Liste
2. Klicke auf **"Activate"**
3. ✅ Das Template ist sofort aktiv
4. Deine Website zeigt jetzt das neue Design

**⚠️ Achtung:** Der Wechsel erfolgt **sofort** — teste vorher auf einer Staging-Umgebung!

---

## 💾 Snapshots vs. Templates

| Feature | **Template** | **Snapshot** |
|---------|-------------|-------------|
| Was ist es? | Komplette Design-Vorlage | Sicherungspunkt eines Templates |
| Wann nutzen? | Design übertragen, neue Vorlagen | Backup vor Änderungen |
| Wiederherstellung | Als neues Template importieren | Rollback zum Snapshot |
| Speicherort | `.zip`-Datei (Download) | In der Datenbank |
| Verwaltung | Hier auf der Templates-Seite | [Tools-Seite](/ps-padma/dokumentation/dashboard/tools) |

**💡 Quick-Tipp:**
- **Snapshots** für schnelle Rückgängig-Punkte während der Arbeit
- **Templates** für finale Backups und Design-Transfer

→ [Mehr zu Snapshots: Tools-Dokumentation](/ps-padma/dokumentation/dashboard/tools#snapshots)

---

## 🛠️ Erweiterte Funktionen

### Template-Bild ändern

Nach dem Import kannst du nachträglich kein Bild ändern. Lösung:

1. Template exportieren
2. Beim Export neues Bild auswählen
3. Altes Template löschen
4. Neu exportiertes Template importieren

### Template zwischen Websites übertragen

**Von Website A zu Website B:**

1. **Auf Website A:**
   - PS Padma » Templates » Export Current Template
   - `.zip`-Datei speichern

2. **Auf Website B:**
   - PS Padma » Templates » Install Template
   - `.zip`-Datei hochladen
   - Template aktivieren

**⚠️ URLs anpassen:**
Falls die Websites unterschiedliche Domains haben:

→ Nutze das [URL-Replacement-Tool](/ps-padma/dokumentation/dashboard/tools#url-replacement) in den Tools

---

## 💡 Best Practices

### 1. Regelmäßige Backups

- Export vor jeder größeren Design-Änderung
- Monatliche Backups des aktuellen Stands
- Versionsnummern konsequent vergeben (1.0, 1.1, 2.0, ...)

### 2. Aussagekräftige Namen

**❌ Schlecht:**
- "Template 1"
- "Neu"
- "test"

**✅ Gut:**
- "Corporate Blog v2.3 - März 2026"
- "Weihnachts-Design 2025"
- "Homepage Redesign Final"

### 3. Screenshots nutzen

- Machen Templates sofort erkennbar
- Helfen beim Vergleichen von Design-Versionen
- Professioneller Eindruck bei Team-Arbeit

**Ideal:** 1200x900px Screenshot der Startseite

---

## 🆘 Probleme & Lösungen

### Template-Upload schlägt fehl

**Mögliche Ursachen:**

1. **Datei zu groß:**
   - Prüfe PHP-Limits: [Tools » System-Info](/ps-padma/dokumentation/dashboard/tools)
   - `upload_max_filesize` sollte mind. 32M sein
   - `post_max_size` sollte mind. 32M sein

2. **Keine gültige Template-Datei:**
   - Nur `.zip`-Dateien, die von PS Padma exportiert wurden
   - Keine manuell erstellten ZIP-Archive

3. **Berechtigungsproblem:**
   - Server-Schreibrechte für `/wp-content/uploads/` prüfen

→ [System-Check durchführen](/ps-padma/dokumentation/dashboard/tools)

---

### Template aktiviert, aber Design ändert sich nicht

**Lösungen:**

1. **Cache leeren:**
   - [PS Padma » Tools » Cache leeren](/ps-padma/dokumentation/dashboard/tools)
   - Browser-Cache leeren (Strg+F5)
   - Falls vorhanden: CDN/Caching-Plugin-Cache leeren

2. **Hard-Refresh im Browser:**
   - Windows: `Strg + F5`
   - Mac: `Cmd + Shift + R`

---

### Template löschen nicht möglich

**Grund:** Man kann nur **inaktive** Templates löschen.

**Lösung:**
1. Anderes Template aktivieren
2. Dann das gewünschte Template löschen

**Base Template:** Kann grundsätzlich nicht gelöscht werden (ist das Fallback-Template).

---

## 🔗 Siehe auch

- [📋 Dashboard-Übersicht](/ps-padma/dokumentation/dashboard/)
- [🔧 Tools](/ps-padma/dokumentation/dashboard/tools) — Snapshots & URL-Replacement
- [⚙️ Optionen](/ps-padma/dokumentation/dashboard/optionen)
- [🎨 Visual Editor](/ps-padma/dokumentation/visual-editor/) _(folgt bald)_

---

## 📚 Verwandte Themen

- **Snapshots:** Schnelle Sicherungspunkte → [Tools-Doku](/ps-padma/dokumentation/dashboard/tools)
- **URL-Replacement:** Domains nach Umzug ändern → [Tools-Doku](/ps-padma/dokumentation/dashboard/tools)
- **Layouts:** Einzelne Seiten-Layouts bearbeiten → Visual Editor _(folgt bald)_

---

**Navigation:**
- [← Optionen](/ps-padma/dokumentation/dashboard/optionen)
- [SEO Suite →](/ps-padma/dokumentation/dashboard/seo)
