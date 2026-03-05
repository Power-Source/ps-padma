---
layout: psource-theme
title: "Design-Modus - Visual Editor - PS Padma"
---

<div class="menu">
  <a href="/ps-padma/" style="color:#38c2bb;">🏠 Start</a>
  ·
  <a href="/ps-padma/dokumentation/" style="color:#38c2bb;">📖 Dokumentation</a>
  ·
  <a href="/ps-padma/dokumentation/visual-editor/" style="color:#38c2bb;">🎨 Visual Editor</a>
</div>

# 🎨 Design-Modus

Der **Design-Modus** ist der Styling-Editor in PS Padma. Hier gibst du deinem Layout den visuellen Feinschliff — Farben, Fonts, Abstände, Effekte und vieles mehr.

## 📍 Wie aktiviere ich den Design-Modus?

**Visual Editor » Design-Tab (oben links)**

Im Design-Modus siehst du:
- ✅ Normale Website-Ansicht (keine Grid-Linien)
- ✅ Klickbare Elemente mit Hover-Effekt
- ✅ Property Inspector (rechts) für Styling
- ✅ Instant Live-Preview aller Änderungen

---

## 🎯 Was macht man im Design-Modus?

### Hauptaufgaben

1. **Elemente auswählen** — Klick auf beliebiges Element im iFrame
2. **Properties anpassen** — Farben, Fonts, Abstände, etc.
3. **CSS schreiben** — Für fortgeschrittene Anpassungen
4. **States stylen** — Hover, Active, Focus
5. **Responsive optimieren** — Mobile, Tablet, Desktop

---

## 🖱️ Element-Selektor

### Element auswählen

**Klick-Modus (Standard):**
1. Element im iFrame anklicken
2. **Property Inspector (rechts)** öffnet sich
3. Zeigt alle verfügbaren Properties für dieses Element

**Was ist auswählbar?**
- 🏷️ **Default-Elemente:** Body, Headings (H1-H6), Links, Paragraphs
- 🧩 **Block-Elemente:** Content-Blöcke, Navigation, Widgets
- 📦 **Wrapper-Elemente:** Container, Backgrounds
- 🔧 **Spezifische Elemente:** Post Title, Meta Info, etc.

### Element-Hierarchie

**Breadcrumbs (oben rechts im Inspector):**

Zeigt die hierarchische Struktur des gewählten Elements:

```
Body » Wrapper » Block: Content » Entry Title
```

**Nutzen:**
- Schnell zu Parent-Element wechseln
- Verständnis der Verschachtelung
- Vererbung nachvollziehen

### Inspector-Modi

**Element Tab:**
- Styling des aktuell gewählten Elements
- Properties nach Gruppen sortiert
- Live-Preview bei jeder Änderung

**States Tab:**
- **:hover** — Element beim Überfahren mit Maus
- **:active** — Element beim Klick
- **:focus** — Element bei Fokus (z.B. Formular-Inputs)

---

## 🎨 Property-Gruppen

Der Property Inspector organisiert CSS-Properties in thematischen Gruppen:

### 1. 🔤 Fonts

**Schriftarten & Textstyling**

| Property | Beschreibung | Werte |
|----------|--------------|-------|
| **Font Family** | Schriftart | System Fonts, Google Fonts, Custom |
| **Font Size** | Schriftgröße | px, em, rem, % |
| **Font Color** | Textfarbe | Farbwähler |
| **Line Height** | Zeilenhöhe | px, %, unitless |
| **Font Styling** | Bold, Italic | Normal, Light, Bold, Italic, Bold Italic |
| **Text Alignment** | Ausrichtung | Left, Center, Right, Justify |
| **Capitalization** | Groß-/Kleinschreibung | Normal, Uppercase, Lowercase, Small Caps |
| **Letter Spacing** | Buchstabenabstand | -3px bis 3px |
| **Text Decoration** | Unterstreichung | None, Underline, Overline, Line-through |
| **Word Spacing** | Wortabstand | px-Werte |
| **Text Direction** | Textrichtung | LTR (links-rechts), RTL (rechts-links) |

**Text Shadow:**
- Horizontal Offset
- Vertical Offset
- Blur
- Shadow Color

**💡 Tipp:** Für Site-Wide Font-Änderungen: "Default" oder "Body" Element stylen!

---

### 2. 🎨 Background

**Hintergründe & Bilder**

| Property | Beschreibung | Optionen |
|----------|--------------|----------|
| **Background Color** | Hintergrundfarbe | Farbwähler mit Opacity |
| **Background Image** | Hintergrundbild | Upload oder URL |
| **- Repeat** | Wiederholung | Tile, No Tiling, Horizontal, Vertical |
| **- Position** | Position | 9 Positions (Top Left, Center, etc.) |
| **- Behavior** | Scroll-Verhalten | Scroll, Fixed (Parallax) |
| **- Size** | Skalierung | Auto, Cover, Contain |
| **- Parallax** | Parallax-Effekt | Enable/Disable |
| **- Parallax Ratio** | Parallax-Geschwindigkeit | 0-1 (Standard: 0.5) |

**Beispiel: Full-Width Hero mit Parallax**
```
Background Image: hero.jpg
Background Size: Cover
Background Position: Center Center
Background Behavior: Fixed
Parallax: Enable
Parallax Ratio: 0.5
```

---

### 3. 🖼️ Borders

**Rahmen & Linien**

| Property | Beschreibung | Werte |
|----------|--------------|-------|
| **Border Color** | Rahmenfarbe | Farbwähler |
| **Border Style** | Linienstil | Solid, Dashed, Dotted, Double, etc. |
| **Border Width** | Rahmenbreite | Top, Right, Bottom, Left (einzeln) |

**Box Model Control:**
- 🔒 **Lock:** Alle Seiten gleich
- 🔓 **Unlock:** Jede Seite individuell

**Beispiel: Feine obere Linie**
```
Border Style: Solid
Border Color: #cccccc
Border Width: Top = 1px, Rest = 0px
```

---

### 4. 🔘 Outlines

**Außenlinien (außerhalb des Border)**

Ähnlich wie Borders, aber:
- Nehmen keinen Platz ein
- Gut für Fokus-Styles (Accessibilität)
- Können nicht pro Seite gesetzt werden

| Property | Werte |
|----------|-------|
| **Outline Color** | Farbwähler |
| **Outline Style** | Solid, Dashed, Dotted, etc. |
| **Outline Width** | px-Wert |

---

### 5. 📏 Padding

**Innenabstand (zwischen Content und Border)**

| Seite | CSS-Property |
|-------|--------------|
| Top | padding-top |
| Right | padding-right |
| Bottom | padding-bottom |
| Left | padding-left |

**Box Model UI:**
```
┌─────────────────┐
│   Padding Top   │
│ P │ Content │ P │  ← P = Padding Left/Right
│   Padding Bot   │
└─────────────────┘
```

**💡 Tipp:** Lock-Icon nutzen für gleichmäßiges Padding!

---

### 6. 📐 Margins

**Außenabstand (außerhalb des Border)**

Funktioniert wie Padding, nur außen:

| Seite | CSS-Property |
|-------|--------------|
| Top | margin-top |
| Right | margin-right |
| Bottom | margin-bottom |
| Left | margin-left |

**Negative Margins:**
- ✅ Erlaubt (z.B. `-20px` für Overlays)
- Nützlich für spezielle Layouts

---

### 7. 🔲 Corners (Rounded Corners)

**Abgerundete Ecken**

| Property | Beschreibung |
|----------|--------------|
| **Border Radius** | Rundung aller Ecken |
| **Top Left** | Obere linke Ecke |
| **Top Right** | Obere rechte Ecke |
| **Bottom Right** | Untere rechte Ecke |
| **Bottom Left** | Untere linke Ecke |

**Beispiele:**
- **Leicht abgerundet:** 5px
- **Button:** 20px
- **Kreis:** 50% (bei quadratischen Elementen)
- **Pill-Shape:** 999px

---

### 8. 🔲 Box Shadow

**Schatten um Elemente**

| Property | Beschreibung | Wert |
|----------|--------------|------|
| **Horizontal Offset** | Links/Rechts | px (negativ = links) |
| **Vertical Offset** | Oben/Unten | px (negativ = oben) |
| **Blur** | Weichzeichnung | px (größer = weicher) |
| **Spread** | Ausdehnung | px |
| **Shadow Color** | Schattenfarbe | Mit Opacity |
| **Inset** | Innen-Schatten | Checkbox |

**Beispiel: Subtiler Card-Shadow**
```
Horizontal: 0px
Vertical: 2px
Blur: 8px
Spread: 0px
Color: #000000 (Opacity: 10%)
```

---

### 9. ↔️ Nudging

**Micro-Positioning**

Feinjustierung der Position ohne Layout zu brechen:

| Property | CSS-Equivalent |
|----------|----------------|
| **Nudge Top** | position: relative; top: Xpx |
| **Nudge Right** | position: relative; right: Xpx |
| **Nudge Bottom** | position: relative; bottom: Xpx |
| **Nudge Left** | position: relative; left: Xpx |

**Wann nutzen?**
- ✅ Fein-Tuning von Icons/Texten
- ✅ Kleine Positionsanpassungen
- ❌ **Nicht** für große Layout-Änderungen (nutze Grid-Modus!)

---

### 10. 📱 Responsive

**Geräte-spezifische Anpassungen**

Nicht eine Property-Gruppe, sondern ein **Kontext-Switch:**

**Device-Buttons (oben im Editor):**
- 🖥️ **Desktop:** > 1024px
- 📱 **Tablet:** 768px - 1024px
- 📱 **Mobile:** < 768px

**Workflow:**
1. Device-Button wählen
2. Element stylen
3. Änderungen gelten nur für gewähltes Gerät

→ [Mehr zu Responsive Design](/ps-padma/dokumentation/visual-editor/responsive-design)

---

### 11. ⚡ Effects

**CSS-Transformationen & Effekte**

| Property | Beschreibung | Werte |
|----------|--------------|-------|
| **Opacity** | Transparenz | 0-1 (0 = unsichtbar) |
| **Transform** | 2D/3D Transformationen | Rotate, Scale, Translate, Skew |
| **Transition** | Animationen | Duration, Timing Function |
| **Filter** | Visuelle Filter | Blur, Brightness, Contrast, etc. |

**Beispiel: Hover-Animation**
```
Element:
  Transition: all 0.3s ease

Element:hover:
  Transform: scale(1.05)
  Box Shadow: Erhöht
```

---

### 12. 🎬 Animations

**CSS-Keyframe-Animationen**

Für fortgeschrittene Animationen:
- Fade In/Out
- Slide In
- Bounce
- Custom Keyframes

→ Wird über Custom CSS konfiguriert

---

## 🎯 Element-Typen

### Default-Elemente

**Globale Styling-Basis**

| Element | Beschreibung | Wirkt auf |
|---------|--------------|-----------|
| **Body** | Basis-Element | Ganze Website |
| **Headings** | Überschriften | H1, H2, H3, H4, H5, H6 |
| **Paragraph** | Fließtext | `<p>`-Tags |
| **Link** | Hyperlinks | `<a>`-Tags (verschiedene States!) |
| **Lists** | Listen | `<ul>`, `<ol>`, `<li>` |

**💡 Wichtig:** Default-Elemente werden von allen Seiten geerbt!

**Best Practice:**
1. **Body stylen:** Basis-Font, Farbe, Hintergrund
2. **Headings stylen:** Hierarchie aufbauen (H1 > H2 > H3)
3. **Links stylen:** Normal, Hover, Active states

---

### Block-spezifische Elemente

**Pro Block-Typ verfügbar**

Beispiel: **Content-Block**
- Entry Title
- Entry Meta
- Entry Content
- Read More Link

Beispiel: **Navigation-Block**
- Menu Item
- Sub Menu Item
- Active Menu Item

→ [Alle Block-Elemente](/ps-padma/dokumentation/bloecke/)

---

### Wrapper-Elemente

**Container-Styling**

| Element | Anwendung |
|---------|-----------|
| **Wrapper** | Der Container selbst |
| **Wrapper Background** | Hintergrund-Layer |
| **Wrapper Inner** | Innerer Content-Bereich |

**Wozu?**
- Full-Width Backgrounds
- Innere Breiten-Begrenzungen
- Multi-Layer-Effekte

---

## 💻 Custom CSS

### CSS-Editor

**Für fortgeschrittene Anpassungen:**

**Wo:** Design-Modus » Element auswählen » "CSS"-Tab

**Features:**
- Syntax-Highlighting
- Auto-Completion
- Live-Preview
- Pro Element oder Global

**Beispiel: Gradients**
```css
background: linear-gradient(
  135deg, 
  #667eea 0%, 
  #764ba2 100%
);
```

**Beispiel: Custom Hover**
```css
.my-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.2);
  transition: all 0.3s ease;
}
```

---

### Live CSS

**Site-Wide Custom CSS**

**Wo:** Visual Editor » Options Panel » "Live CSS"-Tab

**Nutzen:**
- Eigene CSS-Klassen
- Vendor-spezifische Properties
- Custom Animations
- Media Queries

**Beispiel: Custom Animation**
```css
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in-element {
  animation: fadeInUp 0.6s ease-out;
}
```

---

## 🎨 Color System

### Farbwähler

**Features:**
- **Hex:** #FF5733
- **RGB:** rgb(255, 87, 51)
- **RGBA:** rgba(255, 87, 51, 0.8) — mit Opacity!
- **HSL:** hsl(360, 100%, 50%)

**Eyedropper:**
- Farbe vom Bildschirm aufnehmen
- Aus bestehendem Design kopieren

### Color Palette

**Häufig verwendete Farben speichern:**

1. Farbe wählen
2. "Save to Palette" klicken
3. Wiederverwendbar in allen Elementen

**💡 Tipp:** Markenfarben in Palette speichern für Konsistenz!

---

## 🔄 States stylen

### Verfügbare States

**:hover** — Maus über Element
- Links
- Buttons
- Cards
- Bilder

**:active** — Klick-Moment
- Buttons
- Links

**:focus** — Fokussiert (Keyboard/Tab)
- Formular-Inputs
- Buttons
- Links

**:visited** — Besuchte Links
- Nur für `<a>`-Tags

### State-Workflow

1. **Element wählen** (z.B. Link)
2. **Normal State stylen** (Base-Styling)
3. **:hover State wählen** (oben im Inspector)
4. **Hover-spezifische Properties** setzen
5. Repeat für andere States

**Beispiel: Button-Hover**
```
Normal:
  Background: #3490dc
  Color: #ffffff
  Padding: 12px 24px

:hover:
  Background: #2779bd (dunkler)
  Transform: translateY(-2px)
  Box Shadow: 0 4px 6px rgba(0,0,0,0.2)
```

---

## 💡 Best Practices

### 1. Von Allgemein zu Spezifisch

**✅ Richtige Reihenfolge:**
1. **Body** — Basis-Font, Farbe
2. **Default-Elemente** — Headings, Paragraphs, Links
3. **Wrapper** — Container-Styles
4. **Blöcke** — Block-spezifische Anpassungen
5. **Spezifische Elemente** — Feintuning

**❌ Falsch:**
- Einzelne Elemente stylen, ohne Basis zu setzen
- Überall individuelle Styles, keine Konsistenz

---

### 2. Konsistentes Spacing

**Spacing-Scale nutzen:**
```
4px  — Minimal (Icon-Padding)
8px  — Small (Between Elements)
16px — Medium (Paddings)
24px — Large (Section Spacing)
32px — XL (Wrapper Padding)
48px — XXL (Section Margins)
```

**Vorteil:** Harmonisches, professionelles Design

---

### 3. Typografie-Hierarchie

**Heading-Größen progressiv:**
```
H1: 36px — Hauptüberschrift
H2: 28px — Sektionsüberschrift
H3: 22px — Untertitel
H4: 18px — Kleine Überschrift
H5: 16px — Label
H6: 14px — Caption
Body: 16px — Fließtext (Basis)
```

**Line Heights:**
- Headings: 1.2-1.3
- Body Text: 1.6-1.8

---

### 4. Farb-Konsistenz

**Brand Color Palette:**
- **Primary:** Haupt-Call-to-Action
- **Secondary:** Akzente
- **Neutral:** Grautöne (Text, Borders)
- **Success:** Grün (Bestätigungen)
- **Warning:** Gelb/Orange (Warnungen)
- **Error:** Rot (Fehler)

**60-30-10 Regel:**
- 60% Neutral/Background
- 30% Primary
- 10% Accent/Secondary

---

### 5. States nicht vergessen

**Checklist für alle interaktiven Elemente:**
- ✅ Normal State
- ✅ Hover State (visuelles Feedback!)
- ✅ Active State
- ✅ Focus State (Accessibilität!)

---

## 🆘 Häufige Probleme

### Änderungen werden nicht sichtbar

**Mögliche Ursachen:**

1. **Höhere Spezifität:** Anderes CSS überschreibt dein Styling
   - **Lösung:** Custom CSS mit `!important` oder spezifischere Selektoren

2. **Cache:** Browser zeigt alte Version
   - **Lösung:** [Cache leeren](/ps-padma/dokumentation/dashboard/tools), Strg+F5

3. **Falsches Element gewählt:** Du stylst ein Parent statt Child
   - **Lösung:** Element-Hierarchie (Breadcrumbs) prüfen

---

### Farben sehen anders aus

**Ursachen:**
1. **Opacity:** Transparenz macht Farben heller/dunkler
2. **Parent-Background:** Durchscheinend durch transparente Elemente
3. **Monitor-Kalibrierung:** Unterschiedliche Geräte

**Lösung:**
- Farbwerte mit **100% Opacity** testen
- Auf mehreren Geräten prüfen
- Hex-Werte dokumentieren

---

### Hover funktioniert nicht auf Mobile

**Grund:** Touch-Geräte haben kein "Hover"-Konzept

**Lösungen:**
- Focus-State statt Hover auf Mobile
- Touch-Event-spezifisches Styling
- Active-State für Touch nutzen

**CSS-Trick:**
```css
@media (hover: hover) {
  /* Hover nur auf Geräten mit Maus */
  .element:hover {
    background: blue;
  }
}
```

---

### Fonts laden nicht

**Ursachen:**
1. **Google Fonts:** DSGVO-Einstellungen
2. **Custom Fonts:** Falsche Dateipfade
3. **Font-Family-Name:** Falsch geschrieben

**Lösung:**
1. **Dashboard » Optionen » Fonts:** Google Fonts aktiviert?
2. **Custom Fonts:** In `/wp-content/themes/ps-padma/assets/fonts/` hochladen
3. **Font-Name:** Exakt wie in Font-Datei

---

## 🔗 Siehe auch

- [🎨 Visual Editor Übersicht](/ps-padma/dokumentation/visual-editor/)
- [🧱 Grid-Modus](/ps-padma/dokumentation/visual-editor/grid-modus)
- [📱 Responsive Design](/ps-padma/dokumentation/visual-editor/responsive-design)
- [🧩 Blöcke](/ps-padma/dokumentation/bloecke/)
- [⚙️ Optionen](/ps-padma/dokumentation/dashboard/optionen)

---

## 📚 Weiterführende Themen

- [CSS Customization](/ps-padma/dokumentation/visual-editor/css-customization) _(folgt bald)_
- [Advanced Animations](/ps-padma/dokumentation/visual-editor/animations) _(folgt bald)_
- [Typography Best Practices](/ps-padma/dokumentation/visual-editor/typography) _(folgt bald)_
- [Color Theory](/ps-padma/dokumentation/visual-editor/color-theory) _(folgt bald)_

---

**Navigation:**
- [← Grid-Modus](/ps-padma/dokumentation/visual-editor/grid-modus)
- [Responsive Design →](/ps-padma/dokumentation/visual-editor/responsive-design)
