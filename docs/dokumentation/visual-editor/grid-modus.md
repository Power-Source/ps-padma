---
layout: psource-theme
title: "Grid-Modus - Visual Editor - PS Padma"
---

<div class="menu">
  <a href="/ps-padma/" style="color:#38c2bb;">🏠 Start</a>
  ·
  <a href="/ps-padma/dokumentation/" style="color:#38c2bb;">📖 Dokumentation</a>
  ·
  <a href="/ps-padma/dokumentation/visual-editor/" style="color:#38c2bb;">🎨 Visual Editor</a>
</div>

# 🧱 Grid-Modus

Der **Grid-Modus** ist der Struktur-Editor in PS Padma. Hier baust du das Layout-Gerüst deiner Website mit Wrappers, Blöcken und einem flexiblen Spaltensystem.

## 📍 Wie aktiviere ich den Grid-Modus?

**Visual Editor » Grid-Tab (oben links)**

Im Grid-Modus siehst du:
- Sichtbare Grid-Linien (graue Spalten)
- Wrapper-Bereiche (horizontale Container)
- Blöcke mit Platzhalter-Content
- Drag & Drop-Handles

---

## 🎯 Was macht man im Grid-Modus?

### Hauptaufgaben

1. **Wrappers hinzufügen** — Horizontale Container-Zeilen
2. **Blöcke hinzufügen** — Content-Elemente in Wrappers
3. **Layout strukturieren** — Drag & Drop zum Anordnen
4. **Grid konfigurieren** — Spalten, Breiten, Responsive
5. **Block-Einstellungen** — Content-Quellen, Optionen

---

## 🏗️ Die Grid-Struktur

### Hierarchie

```
Layout (z.B. "Homepage")
  └─ Wrapper 1 (z.B. Header)
      ├─ Block: Logo
      └─ Block: Navigation
  └─ Wrapper 2 (z.B. Content)
      ├─ Block: Content (8 Spalten)
      └─ Block: Sidebar (4 Spalten)
  └─ Wrapper 3 (z.B. Footer)
      └─ Block: Footer Text
```

**Verständnis:**
- **5Layout** = Seite/Seitentyp (z.B. Homepage, Blog, Kontakt)
- **Wrapper** = Horizontale Zeile/Container
- **Block** = Content-Element innerhalb eines Wrappers

---

## ➕ Wrapper hinzufügen

### Neuen Wrapper erstellen

**Methode 1: Button**
1. **"+ Wrapper"** Button klicken (unten im Viewport)
2. Wrapper wird am Ende hinzugefügt

**Methode 2: Zwischenfügen**
1. Über bestehendem Wrapper hovern
2. **"+ Wrapper hier einfügen"** (erscheint oben am Wrapper)
3. Neuer Wrapper wird darüber eingefügt

### Wrapper-Einstellungen

Klicke auf einen Wrapper, um Einstellungen zu öffnen:

**General-Tab:**
- **Alias:** Beschreibender Name (z.B. "Header", "Hero Section")
- **CSS-Klasse:** Eigene CSS-Klasse hinzufügen
- **Wrapper-ID:** HTML-ID für Ankerlinks

**Grid-Tab:**
- **Spaltenanzahl:** 6-24 Spalten (Default: 12)
- **Spaltenbreite:** Breite jeder Spalte in px
- **Gutter-Breite:** Abstand zwischen Spalten
- **Fixed vs. Fluid:** Feste Breite oder 100% Breite

**Mirroring-Tab:**
- **Mirror:** Wrapper von anderem Layout übernehmen
- **Nutzen:** Spart Zeit bei wiederkehrenden Elementen (z.B. Header)

→ [Mehr zu Wrappers](/ps-padma/dokumentation/visual-editor/wrappers)

---

## 🧩 Blöcke hinzufügen

### Neuen Block hinzufügen

1. **"+ Block"** Button im Wrapper klicken
2. **Block-Typ wählen** aus der Liste:
   - **Content:** Post/Page-Inhalt
   - **Text:** Freier Text/HTML
   - **Image:** Einzelnes Bild
   - **Navigation:** Menü
   - **Widget Area:** Sidebar-Widgets
   - **Und viele mehr...**
3. Block wird hinzugefügt

→ [Alle Block-Typen](/ps-padma/dokumentation/bloecke/)

### Block konfigurieren

**Nach dem Hinzufügen:**
1. Auf Block klicken
2. **Options Panel (rechts)** öffnet sich
3. **Setup-Tab:** Block-spezifische Einstellungen
4. **Options-Tab:** Weitere Optionen

**Beispiel: Content-Block**
- **Content Display:** Full Content, Excerpt, None
- **Show Title:** Titel anzeigen ja/nein
- **Show Meta:** Autor, Datum, Kategorien

→ [Block-Optionen Details](/ps-padma/dokumentation/bloecke/optionen)

---

## 📏 Grid-System verstehen

### Was ist das Grid?

PS Padma nutzt ein **Spalten-basiertes Grid-System**:

- Wrapper geteilt in X Spalten (Standard: 12)
- Blöcke belegen Y Spalten
- Summe aller Blöcke = Wrapper-Breite

**Beispiel:**
```
Wrapper (12 Spalten)
  ├─ Block 1: 8 Spalten (66.6%)
  └─ Block 2: 4 Spalten (33.3%)
```

### Spalten-Berechnung

**Formel:**
```
Block-Breite = (Anzahl Spalten × Spaltenbreite) + ((Anzahl Spalten - 1) × Gutter-Breite)
```

**Beispiel mit Standard-Werten:**
- Spaltenbreite: 26px
- Gutter-Breite: 22px
- Block mit 4 Spalten: (4 × 26px) + (3 × 22px) = 170px

**💡 Tipp:** PS Padma berechnet das automatisch — du musst nicht rechnen!

---

## 🎛️ Grid-Einstellungen

### Globale Grid-Einstellungen

**Wo:** Visual Editor » Grid-Modus » Rechts: "Setup"-Panel

**Verfügbare Einstellungen:**

#### Grid-Tab

**Spaltenanzahl (Columns)**
- **Range:** 6-24
- **Default:** 24
- **Anwendung:** Neue Wrappers
- **Wichtig:** Wirkt NICHT auf bestehende Wrappers!

**Spaltenbreite (Column Width)**
- **Range:** 10-120px
- **Default:** 26px
- **Anwendung:** Alle Wrappers (global)

**Gutter-Breite (Gutter Width)**
- **Range:** 0-60px
- **Default:** 22px
- **Anwendung:** Abstand zwischen Spalten

**Grid-Breite (Grid Width)**
- **Berechnet:** Automatisch
- **Formel:** (Spalten × Column Width) + ((Spalten - 1) × Gutter Width)
- **Beispiel:** (12 × 26px) + (11 × 22px) = 554px

#### Responsive Grid-Tab

**Responsive Grid aktivieren**
- ✅ Grid passt sich an Gerätegröße an
- Tablet & Mobile: Angepasste Spalten-Layouts
- Nutzer kann optional zurück zu Desktop-Ansicht

**Responsive Video Resizing**
- ✅ Videos (YouTube, Vimeo) skalieren mit
- Verhindert Overflow auf kleinen Bildschirmen

→ [Responsive Design Details](/ps-padma/dokumentation/visual-editor/responsive-design)

---

### Wrapper-spezifische Grid-Einstellungen

**Pro Wrapper individuell:**

Jeder Wrapper kann eigene Grid-Settings haben:

1. Wrapper anklicken
2. **Grid-Tab** im Options Panel
3. **Independent Grid:** Checkbox aktivieren
4. Eigene Werte setzen:
   - Spaltenanzahl
   - Spaltenbreite
   - Gutter-Breite

**Wann nutzen?**
- ✅ Besondere Content-Bereiche (z.B. wide Hero Section)
- ✅ Fine-tuning für spezifische Layouts
- ❌ Nicht für jeden Wrapper (Konsistenz behalten!)

---

## 🎨 Block-Dimensionen

### Block-Breite ändern

**Grafischer Modus (Drag):**
1. Block anklicken
2. **Rechter Rand:** Resize-Handle erscheint
3. Ziehen nach links/rechts
4. Spalten ändern sich automatisch

**Präziser Modus (Input):**
1. Block anklicken
2. **Dimensions-Tab** (rechts)
3. **Columns:** Zahl eingeben (z.B. "6")
4. Enter drücken

### Block-Position ändern

**Horizontal verschieben:**
1. Block anklicken und halten
2. Nach links/rechts ziehen
3. Grid-Snapping: Automatisch auf Spalten

**Vertikal verschieben:**
1. Block anklicken und halten
2. Nach oben/unten ziehen
3. Zwischen oder in andere Wrappers

**Zwischen Wrappers:**
1. Block greifen
2. Über anderen Wrapper hovern
3. "Drop hier"-Indikator erscheint
4. Loslassen

---

## 🔄 Wrapper & Block Management

### Wrapper-Aktionen

**Rechtsklick auf Wrapper:**
- **Clone:** Wrapper duplizieren
- **Delete:** Wrapper (mit allen Blöcken) löschen
- **Mirror:** Wrapper von anderem Layout spiegeln

**Wrapper verschieben:**
- Drag & Drop-Handle (links am Wrapper)
- Reihenfolge ändern

### Block-Aktionen

**Rechtsklick auf Block:**
- **Copy:** Block kopieren
- **Clone:** Block duplizieren (im selben Wrapper)
- **Delete:** Block entfernen

**Block zwischen Layouts kopieren:**
1. Block kopieren (Rechtsklick » Copy)
2. Anderes Layout wählen
3. In Wrapper: Rechtsklick » Paste

---

## 💡 Best Practices

### Grid-Struktur planen

**✅ Gut:**
```
Header-Wrapper (12 Spalten)
  └─ Logo (3 Spalten) + Navigation (9 Spalten)

Content-Wrapper (12 Spalten)
  └─ Content (8 Spalten) + Sidebar (4 Spalten)

Footer-Wrapper (12 Spalten)
  └─ Footer Text (12 Spalten)
```

**❌ Schlecht:**
- Zu viele Wrapper (Performance)
- Inkonsistente Spaltenanzahl
- Keine logische Gruppierung

---

### Wrapper sinnvoll benennen

**✅ Aussagekräftig:**
- "Header"
- "Hero Section"
- "Main Content Area"
- "Footer CTA"

**❌ Generisch:**
- "Wrapper 1"
- "Test"
- "div"

**Wie:** Wrapper anklicken » General-Tab » Alias

---

### Spalten-Layouts für Content

**Typische Layouts:**

**Full Width (1 Spalte):**
```
Content: 12 Spalten
```

**2/3 + 1/3 (Sidebar rechts):**
```
Content: 8 Spalten
Sidebar: 4 Spalten
```

**1/2 + 1/2 (Gleichmäßig):**
```
Block 1: 6 Spalten
Block 2: 6 Spalten
```

**1/3 + 1/3 + 1/3 (Drei Spalten):**
```
Block 1: 4 Spalten
Block 2: 4 Spalten
Block 3: 4 Spalten
```

---

### Independent Grid sparsam nutzen

**Nur verwenden für:**
- ✅ Hero Sections (extra breit)
- ✅ Gallerien (mehr Spalten für Thumbnails)
- ✅ Spezielle Layout-Abschnitte

**Vermeiden:**
- ❌ Standard Content-Bereiche
- ❌ Einfach "weil es geht"

**Warum?** Konsistenz = besseres UX & einfacheres Maintenance.

---

## 🆘 Häufige Probleme

### Block passt nicht in Zeile

**Symptom:** Block bricht in neue Zeile um.

**Ursache:** Zu viele Spalten belegt.

**Lösung:**
1. Spalten aller Blöcke zusammenzählen
2. Dürfen max. 12 sein (oder Wrapper-Spaltenanzahl)
3. Block-Breiten anpassen

**Beispiel-Problem:**
```
Block 1: 8 Spalten
Block 2: 6 Spalten
= 14 Spalten (zu viel!)
```

**Fix:**
```
Block 1: 8 Spalten
Block 2: 4 Spalten
= 12 Spalten ✅
```

---

### Wrapper lässt sich nicht löschen

**Symptom:** Delete-Button funktioniert nicht.

**Mögliche Ursachen:**
1. **Letzter Wrapper:** Layouts brauchen mind. 1 Wrapper
2. **Mirror-Quelle:** Andere Layouts spiegeln diesen Wrapper

**Lösung:**
1. **Neuen Wrapper hinzufügen** (dann löschen)
2. **Mirror-Verbindungen prüfen:** Andere Layouts checken

---

### Grid wird nicht angezeigt

**Symptom:** Keine sichtbaren Spalten-Linien.

**Ursachen:**
1. **Design-Modus aktiv:** Nur im Grid-Modus sichtbar
2. **Grid-Option deaktiviert:** In Visual Editor Settings

**Lösung:**
1. **Grid-Modus aktivieren** (Tab oben)
2. **Visual Editor » Setup:** "Show Grid" aktivieren

---

### Block springt beim Verschieben

**Symptom:** Block "snapped" nicht sauber auf Grid.

**Ursache:** Grid-Spalten-Breite zu klein oder zu groß.

**Lösung:**
1. Grid-Setup öffnen
2. Spaltenbreite anpassen (Standard: 26px)
3. Gutter-Breite anpassen (Standard: 22px)

---

## 🔗 Siehe auch

- [🎨 Visual Editor Übersicht](/ps-padma/dokumentation/visual-editor/)
- [🎨 Design-Modus](/ps-padma/dokumentation/visual-editor/design-modus)
- [📦 Wrappers im Detail](/ps-padma/dokumentation/visual-editor/wrappers)
- [🧩 Blöcke](/ps-padma/dokumentation/bloecke/)
- [📱 Responsive Design](/ps-padma/dokumentation/visual-editor/responsive-design)

---

## 📚 Nächste Schritte

1. ✅ **Grid-Struktur aufbauen** (diese Seite)
2. → **[Design-Modus nutzen](/ps-padma/dokumentation/visual-editor/design-modus)** (Styling)
3. → **[Layouts verstehen](/ps-padma/dokumentation/visual-editor/layouts)** (Hierarchie)
4. → **[Responsive optimieren](/ps-padma/dokumentation/visual-editor/responsive-design)** (Mobile)

---

**Navigation:**
- [← Visual Editor](/ps-padma/dokumentation/visual-editor/)
- [Design-Modus →](/ps-padma/dokumentation/visual-editor/design-modus)
