---
layout: psource-theme
title: "SEO Suite - Dashboard - PS Padma"
---

<div class="menu">
  <a href="/ps-padma/" style="color:#38c2bb;">🏠 Start</a>
  ·
  <a href="/ps-padma/dokumentation/" style="color:#38c2bb;">📖 Dokumentation</a>
  ·
  <a href="/ps-padma/dokumentation/dashboard/" style="color:#38c2bb;">📋 Dashboard</a>
</div>

# 🔍 SEO Suite

Die **SEO Suite** ist seit Version 1.0.7 ein eigenständiger Bereich in PS Padma. Hier konfigurierst du alle SEO-relevanten Einstellungen — ganz ohne externe Plugins.

## 📍 Wo finde ich diese Seite?

**Dashboard » PS Padma » SEO**

**💡 Neu in 1.0.7:** SEO-Einstellungen haben jetzt eine eigene Seite (vorher unter Optionen).

---

## 🎯 Was bietet die SEO Suite?

Die PS Padma SEO Suite ersetzt grundlegende SEO-Plugins und bietet:

- ✅ Meta-Tags & Beschreibungen pro Seite/Post
- ✅ XML Sitemaps (automatisch generiert)
- ✅ Schema.org Structured Data
- ✅ Open Graph (Facebook, LinkedIn, etc.)
- ✅ Twitter Cards
- ✅ Organization Schema für lokale Unternehmen

**Keine externen Plugins nötig!**

---

## 📋 SEO Suite Tabs

Die Seite ist in mehrere Bereiche unterteilt:

### 1. 🌐 Allgemein

**Theme-SEO aktivieren/deaktivieren**

- **Theme-SEO deaktivieren:** Falls du ein anderes SEO-Plugin verwendest (Yoast, RankMath, etc.)
- **Empfehlung:** Entweder PS Padma SEO **oder** externes Plugin — nicht beides gleichzeitig!

**Warum deaktivieren?**
- Verhindert Konflikte zwischen SEO-Plugins
- Duplicate Meta-Tags vermeiden

---

### 2. 🗺️ XML Sitemaps

**Was sind Sitemaps?**

XML Sitemaps helfen Suchmaschinen, alle Seiten deiner Website zu finden und zu indexieren.

**Aktivierung:**

- **Checkbox:** "XML Sitemaps aktivieren"
- Nach Aktivierung automatisch verfügbar unter:
  - `https://deine-domain.de/sitemap.xml` (Hauptsitemap)
  - `https://deine-domain.de/sitemap-posts-1.xml` (Posts)
  - `https://deine-domain.de/sitemap-pages-1.xml` (Seiten)
  - `https://deine-domain.de/sitemap-{post-type}-1.xml` (Custom Post Types)

**Was wird in die Sitemap aufgenommen?**

- ✅ Veröffentlichte Posts & Pages
- ✅ Custom Post Types
- ✅ Taxonomien (Kategorien, Tags)
- ❌ Entwürfe, private Seiten
- ❌ "noindex"-Seiten

**💡 Tipp:** Nach Aktivierung die Sitemap in der Google Search Console einreichen!

---

### 3. 📊 Schema.org

**Was ist Schema.org?**

Structured Data (strukturierte Daten), die Suchmaschinen helfen, deine Inhalte besser zu verstehen.

**Aktivierung:**

- **Checkbox:** "Schema.org Markup aktivieren"

**Was wird automatisch ausgegeben?**

- **Article Schema:** Für Blog-Posts (Autor, Datum, Bild)
- **WebSite Schema:** Für die Homepage
- **Organization Schema:** Dein Unternehmen/Organisation
- **BreadcrumbList:** Breadcrumb-Navigation

**Schema-Support deaktivieren:**

Falls du ein anderes Plugin für Schema.org nutzt, deaktiviere die PS Padma Schema-Ausgabe.

---

### 4. 🌐 Social Media

**Open Graph (Facebook, LinkedIn)**

- **Facebook App ID:** Optional, für Facebook Insights
- **Default Image:** Fallback-Bild, wenn kein Featured Image vorhanden

**Twitter Cards**

- **Twitter Username:** Dein @handle (z.B. `@deinbrand`)
- **Card Type:** 
  - `summary` (kleines Bild)
  - `summary_large_image` (großes Bild, Standard)

**Was macht das?**

Wenn jemand deine Seite auf Social Media teilt:
- Schöne Vorschau mit Bild, Titel, Beschreibung
- Professioneller Auftritt
- Höhere Click-Through-Rate

---

### 5. 🏢 Organization Schema

**Für lokale Unternehmen & Organisationen**

- **Kontakt-Telefon:** Für Local SEO wichtig
- **Kontakt-E-Mail:** Support-Kontakt

**Ausgabe im Schema:**

```json
{
  "@type": "Organization",
  "name": "Dein Firmenname",
  "telephone": "+49 123 456789",
  "email": "kontakt@deine-domain.de"
}
```

**💡 Wichtig für:**
- Local SEO (Google My Business)
- Knowledge Graph
- Rich Snippets in Suchergebnissen

---

## 🖊️ SEO pro Seite/Post

### Meta-Box in Posts & Pages

Beim Bearbeiten von Posts/Pages findest du die **PS Padma SEO Meta-Box**:

**Verfügbare Felder:**

- **SEO Title:** Titel für Suchmaschinen (max. 60 Zeichen)
- **Meta Description:** Beschreibung in Suchergebnissen (max. 160 Zeichen)
- **Focus Keyword:** Haupt-Keyword (optional)
- **noindex:** Seite von Indexierung ausschließen
- **nofollow:** Links auf der Seite nicht folgen

**💡 Best Practice:**

- **Title:** Keyword vorne, max. 60 Zeichen
- **Description:** Call-to-Action, 155-160 Zeichen
- **Keywords:** 1 Haupt-Keyword pro Seite

---

## 🚀 Quick Start Guide

### Basis-Setup (5 Minuten)

1. **Allgemein:**
   - Theme-SEO aktiviert lassen (Standard)

2. **XML Sitemaps:**
   - ✅ "XML Sitemaps aktivieren"
   - Sitemap in Google Search Console einreichen

3. **Schema.org:**
   - ✅ "Schema Markup aktivieren"

4. **Social Media:**
   - Twitter Username eintragen
   - Facebook App ID (optional)

5. **Organization:**
   - Telefon & E-Mail eintragen (für Local SEO)

6. **Speichern**

✅ Basis-SEO ist fertig!

---

## 💡 SEO Best Practices

### 1. Sitemaps & Search Console

**Nach Aktivierung:**

1. Gehe zu [Google Search Console](https://search.google.com/search-console)
2. Sitemap hinzufügen: `https://deine-domain.de/sitemap.xml`
3. Indexierung beobachten

**Kontrolle:** Nach 24-48h sollten erste Seiten indexiert sein.

---

### 2. Meta-Daten pflegen

**Für wichtige Seiten:**

- Individuellen SEO Title schreiben
- Überzeugende Meta Description (155-160 Zeichen)
- Klarer Call-to-Action

**Beispiel:**

- **Titel:** "PS Padma Theme Builder | Visueller Editor für WordPress"
- **Description:** "Erstelle individuelle Themes mit Drag-and-Drop. Keine Programmierkenntnisse nötig. Jetzt kostenlos testen!"

---

### 3. Images optimieren

**Für Social Media:**

- Featured Image setzen (min. 1200x630px)
- Aussagekräftiges Alt-Attribut
- Dateiname optimiert (keywords-im-namen.jpg)

---

### 4. Schema.org nutzen

**Automatisch aktiv für:**

- Blog-Posts → Article Schema
- Homepage → WebSite & Organization Schema
- Breadcrumbs → BreadcrumbList Schema

**Kontrolle:** [Google Rich Results Test](https://search.google.com/test/rich-results)

---

## 🔄 Migration von anderen SEO-Plugins

### Von Yoast SEO zu PS Padma SEO

**Schritte:**

1. **Daten sichern:** Export der Yoast-Einstellungen (optional)
2. **PS Padma SEO aktivieren:** Alle Settings konfigurieren
3. **Yoast deaktivieren:** Plugin deaktivieren (nicht löschen)
4. **Testen:** Prüfen, ob Meta-Tags korrekt ausgegeben werden
5. **Yoast löschen:** Nach erfolgreicher Prüfung

**⚠️ Wichtig:** Meta-Daten müssen ggf. manuell übertragen werden (kein Auto-Import).

---

### PS Padma SEO deaktivieren (anderes Plugin nutzen)

Wenn du ein externes SEO-Plugin bevorzugst:

1. **Dashboard » PS Padma » SEO**
2. Tab **"Allgemein"**
3. ✅ Checkbox: **"Theme-SEO deaktivieren"**
4. **Speichern**

Jetzt gibt PS Padma keine SEO-Meta-Tags mehr aus.

---

## 🆘 Probleme & Lösungen

### Sitemap nicht erreichbar

**Prüfen:**

1. Sind Sitemaps aktiviert? (PS Padma » SEO)
2. Permalinks erneuern: **Einstellungen » Permalinks » Speichern**
3. `.htaccess`-Konflikte prüfen

**Manuell testen:** `https://deine-domain.de/sitemap.xml` im Browser öffnen

---

### Schema-Fehler in Google Search Console

**Häufige Ursachen:**

1. **Doppelte Schemas:** PS Padma SEO **und** anderes Plugin aktiv
2. **Fehlende Pflichtfelder:** z.B. Organization ohne Name

**Lösung:**
- Nur ein Schema-Plugin aktiv lassen
- Pflichtfelder in Organization Schema ausfüllen

**Prüfen:** [Google Rich Results Test](https://search.google.com/test/rich-results)

---

### Meta Description wird nicht angezeigt

**Google überschreibt manchmal Descriptions:**

- Das ist normal und von Google so gewollt
- Google zeigt oft dynamisch generierte Snippets
- Deine Description ist trotzdem im Quellcode (Google sieht sie)

**Kontrolle:** Quellcode der Seite anschauen (`Strg+U`), nach `<meta name="description"` suchen

---

## 🔗 Siehe auch

- [📋 Dashboard-Übersicht](/ps-padma/dokumentation/dashboard/)
- [⚙️ Optionen](/ps-padma/dokumentation/dashboard/optionen)
- [🔧 Tools](/ps-padma/dokumentation/dashboard/tools)
- [📦 Templates](/ps-padma/dokumentation/dashboard/templates)

---

## 📚 Externe Ressourcen

- [Google Search Console](https://search.google.com/search-console)
- [Google Rich Results Test](https://search.google.com/test/rich-results)
- [Schema.org Dokumentation](https://schema.org/)
- [Open Graph Protocol](https://ogp.me/)
- [Twitter Cards Validator](https://cards-dev.twitter.com/validator)

---

**Navigation:**
- [← Templates](/ps-padma/dokumentation/dashboard/templates)
- [Tools →](/ps-padma/dokumentation/dashboard/tools)
