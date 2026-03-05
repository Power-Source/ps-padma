---
layout: psource-theme
title: "Tools - Dashboard - PS Padma"
---

<div class="menu">
  <a href="/ps-padma/" style="color:#38c2bb;">🏠 Start</a>
  ·
  <a href="/ps-padma/dokumentation/" style="color:#38c2bb;">📖 Dokumentation</a>
  ·
  <a href="/ps-padma/dokumentation/dashboard/" style="color:#38c2bb;">📋 Dashboard</a>
</div>

# 🔧 Tools

Die **Tools**-Seite ist deine Wartungs- und Diagnose-Zentrale für PS Padma. Hier findest du System-Informationen, Cache-Management, Snapshots und hilfreiche Werkzeuge.

## 📍 Wo finde ich diese Seite?

**Dashboard » PS Padma » Tools**

---

## 🛠️ Verfügbare Tools

### 1. 📊 System-Informationen

**Wozu?**

- Technische Diagnose bei Problemen
- Prüfung der Server-Konfiguration
- Info für Support-Anfragen

**Was wird angezeigt?**

**WordPress/ClassicPress:**
- WordPress/ClassicPress Version
- Aktives Theme
- Installierte Plugins

**Server:**
- PHP Version
- Web Server (Apache, Nginx, etc.)
- MySQL Version

**PHP-Limits:**
- `memory_limit` — empfohlen: 256M+
- `post_max_size` — empfohlen: 32M+
- `upload_max_filesize` — empfohlen: 32M+
- `max_execution_time`

**PS Padma:**
- Theme-Version
- Aktives Template
- Anzahl Layouts

**💡 Tipp:** Bei Problemen den kompletten Text kopieren und an den Support schicken!

---

### 2. 🗑️ Cache leeren

**Wozu?**

Löscht alle zwischengespeicherten Daten:
- Compiler-Cache (CSS/JS)
- Layout-Cache
- Transients

**Wann nutzen?**

- ✅ Änderungen werden nicht angezeigt
- ✅ Nach Plugin-Updates
- ✅ Bei CSS-Problemen
- ✅ Nach Theme-Update

**So geht's:**

1. Button **"Cache leeren"** klicken
2. ✅ Bestätigung erscheint
3. Hard-Refresh im Browser (Strg+F5)

**⚠️ Hinweis:** Seite lädt beim ersten Aufruf danach etwas langsamer (Cache wird neu aufgebaut).

---

### 3. 📸 Snapshots verwalten {#snapshots}

**Was sind Snapshots?**

Sicherungspunkte deines aktiven Templates — schneller als volle Template-Exports.

**Unterschied zu Templates:**

| Feature | **Snapshot** | **Template** |
|---------|-------------|-------------|
| Speicherort | Datenbank | `.zip`-Datei |
| Geschwindigkeit | Sehr schnell | Etwas langsamer |
| Wiederherstellung | Instant Rollback | Re-Import nötig |
| Für was? | Arbeits-Backups | Finale Backups, Transfer |

#### Snapshot erstellen

**Manuell:**
1. Button **"Snapshot erstellen"**
2. Optional: Kommentar hinzufügen (z.B. "Vor Header-Redesign")
3. ✅ Snapshot wird gespeichert

**Automatisch:**
- PS Padma erstellt automatisch Snapshots bei wichtigen Änderungen
- Maximal alle 15 Minuten (Throttling, um DB nicht zu überladen)

#### Snapshot wiederherstellen (Rollback)

**So geht's:**

1. Finde den gewünschten Snapshot in der Liste
2. Klicke auf **"Rollback"**
3. ⚠️ **Wichtig:** Aktuelle Änderungen gehen verloren!
4. Bestätigen
5. ✅ Design wird auf Snapshot-Stand zurückgesetzt

**💡 Best Practice:** Vor Rollback einen aktuellen Snapshot erstellen!

#### Snapshots löschen

**Alte Snapshots aufräumen:**

1. Checkbox bei unerwünschten Snapshots
2. Button **"Ausgewählte löschen"**
3. Bestätigen

**⚠️ Achtung:** Gelöschte Snapshots können nicht wiederhergestellt werden!

---

### 4. 🔄 URL-Replacement {#url-replacement}

**Wozu?**

Nach Website-Umzug alle URLs im Design anpassen.

**Typische Szenarien:**

- Migration von Entwicklungs- zu Produktiv-Server
- Domain-Wechsel
- HTTP zu HTTPS-Umstellung
- Subdomain-Änderung

**So geht's:**

1. **Alte URL eingeben:**
   - z.B. `http://localhost/meine-site`
   - oder `http://alt-domain.de`

2. **Neue URL eingeben:**
   - z.B. `https://neue-domain.de`

3. **Preview:** Zeigt, wie viele URLs betroffen sind

4. **"URLs ersetzen" klicken**

5. ✅ Alle URLs im Template werden aktualisiert

**Was wird ersetzt?**

- ✅ Bild-URLs in Blöcken
- ✅ Link-URLs in Navigation
- ✅ Custom CSS mit URLs
- ✅ Eingebettete Medien

**Was wird NICHT ersetzt?**

- ❌ WordPress-Inhalte (Posts, Pages)
- ❌ Datenbank-URLs außerhalb PS Padma

**💡 Tipp:** Für WordPress-Inhalte nutze ein Plugin wie "Better Search Replace".

---

### 5. 🔄 Theme zurücksetzen

**⚠️ VORSICHT: Gefährlich!**

Setzt **alle PS Padma Daten** auf Werkseinstellungen zurück:

- ❌ Alle Layouts gelöscht
- ❌ Alle Templates gelöscht
- ❌ Alle Design-Einstellungen weg
- ❌ Alle Snapshots gelöscht
- ❌ Alle Blöcke & Wrappers entfernt

**Nur nutzen wenn:**
- Kompletter Neuanfang gewünscht
- Schwere Probleme, die anders nicht lösbar sind

**Vor dem Reset:**
1. ✅ Template exportieren! ([Templates-Seite](/ps-padma/dokumentation/dashboard/templates))
2. ✅ Snapshot erstellen
3. ✅ Backup der Datenbank

**So geht's:**

1. Button **"Theme zurücksetzen"**
2. ⚠️ **Sicherheitsabfrage:** Bestätigen erforderlich
3. Text eingeben: "RESET" (Groß- und Kleinschreibung beachten!)
4. Bestätigen

**Danach:**
- PS Padma ist im Auslieferungszustand
- Base Template ist aktiv
- Du kannst von vorne beginnen oder ein Backup importieren

---

## 💡 Tipps & Best Practices

### Regelmäßige Wartung

**Wöchentlich:**
- System-Infos checken (PHP Memory OK?)

**Monatlich:**
- Alte Snapshots aufräumen (behalte max. 10)
- Cache leeren (Fresh Start)

**Bei großen Änderungen:**
- Vor Beginn: Snapshot erstellen
- Nach Abschluss: Template exportieren

---

### Performance-Checks

**Ist dein Server optimal konfiguriert?**

Prüfe in den **System-Informationen**:

| Einstellung | Minimum | Empfohlen | Ideal |
|-------------|---------|-----------|-------|
| PHP Memory Limit | 128M | 256M | 512M |
| Post Max Size | 16M | 32M | 64M |
| Upload Max Filesize | 16M | 32M | 64M |
| Max Execution Time | 30s | 60s | 120s |

**Zu niedrig?** 
→ Kontakt zu deinem Hoster aufnehmen oder in `php.ini` / `.htaccess` erhöhen

---

### Snapshot-Strategie

**Gute Snapshot-Gewohnheiten:**

✅ **Vor jeder größeren Änderung:** "Vor Header-Redesign"  
✅ **Nach Meilensteinen:** "Homepage fertig"  
✅ **Vor Updates:** "Vor PS Padma Update auf 1.0.8"  
❌ **Nicht:** Zu viele Snapshots (überladen die DB)

**Aufräumen:**
- Behalte: Wichtige Meilensteine
- Lösche: Experimental-Snapshots, alte Test-Versionen

---

### URL-Replacement richtig nutzen

**Checkliste vor Domain-Umzug:**

1. ✅ Template der alten Site exportieren
2. ✅ Auf neuer Site importieren & aktivieren
3. ✅ **Tools » URL-Replacement:**
   - Alt: `https://alt-domain.de`
   - Neu: `https://neue-domain.de`
4. ✅ WordPress-URLs mit Plugin ersetzen (Better Search Replace)
5. ✅ Cache leeren
6. ✅ Permalinks erneuern (Einstellungen » Permalinks » Speichern)
7. ✅ Alle Seiten testen

---

## 🆘 Häufige Probleme

### "Änderungen werden nicht angezeigt"

**Lösung:**
1. **Tools » Cache leeren**
2. Hard-Refresh im Browser (`Strg + F5`)
3. Falls Caching-Plugin aktiv: Auch dort Cache leeren
4. Falls CDN aktiv: CDN-Cache purgen

---

### "Memory Limit erschöpft"

**Symptome:**
- White Screen
- Fehlermeldung: "Allowed memory size exhausted"
- Visual Editor lädt nicht

**Lösung:**
1. **System-Info prüfen:** Aktuelles Memory Limit?
2. In `wp-config.php` hinzufügen:
   ```php
   define('WP_MEMORY_LIMIT', '256M');
   ```
3. Falls das nicht hilft: Hoster kontaktieren

---

### "Snapshot-Rollback schlägt fehl"

**Mögliche Ursachen:**
- Datenbank-Problem
- Snapshot korrupt
- Berechtigungsproblem

**Lösung:**
1. Anderen Snapshot versuchen
2. Falls alle fehl schlagen: Template re-importieren
3. Letzter Ausweg: [Theme zurücksetzen](#5-theme-zurücksetzen) & Backup importieren

---

### "URL-Replacement findet nichts"

**Prüfen:**
- Ist die alte URL exakt richtig geschrieben?
- Mit/ohne `www.`?
- Mit/ohne abschließenden Slash `/`?
- HTTP vs. HTTPS?

**Tipp:** Genau die URL verwenden, wie sie im Quellcode der alten Site steht.

---

## 🔗 Siehe auch

- [📋 Dashboard-Übersicht](/ps-padma/dokumentation/dashboard/)
- [📦 Templates](/ps-padma/dokumentation/dashboard/templates) — Template Import & Export
- [⚙️ Optionen](/ps-padma/dokumentation/dashboard/optionen) — Performance-Einstellungen
- [🔍 SEO](/ps-padma/dokumentation/dashboard/seo)

---

## 📚 Externe Ressourcen

- [Better Search Replace Plugin](https://wordpress.org/plugins/better-search-replace/) — Für WordPress-Content URLs
- [PHP Memory Limit erhöhen](https://wordpress.org/support/article/editing-wp-config-php/#increasing-memory-allocated-to-php) — WordPress Codex
- [.htaccess Generator](https://www.htaccessredirect.net/) — PHP-Limits anpassen

---

**Navigation:**
- [← SEO Suite](/ps-padma/dokumentation/dashboard/seo)
- [Dashboard-Übersicht](/ps-padma/dokumentation/dashboard/)
