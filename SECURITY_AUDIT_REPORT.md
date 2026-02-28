# PHP 8+ Kompatibilitäts- & Security-Audit Report
## WordPress Theme: ps-padma

**Audit-Datum:** 28. Februar 2026  
**Basis-Pfad:** `/home/dern3rd/Local Sites/ps-dev/app/public/wp-content/themes/ps-padma/`

---

## KRITISCHE PROBLEME (MÜSSEN SOFORT BEHOBEN WERDEN)

### 1. **Remote Code Execution (RCE) via eval()**
**Kategorie:** PHP 8+ Compatibility & Security - CRITICAL  
**Datei:** [library/common/parse-php.php](library/common/parse-php.php#L26)  
**Zeilennummer:** 26  
**Severity:** 🔴 CRITICAL

```php
$eval = eval("?>$content<?php ;");
```

**Problem:**
- `eval()` ist eine der gefährlichsten PHP Funktionen
- Direktes Ausführen von benutzerdefinierten oder datenbankgesteuerten PHP-Code
- Ermöglicht Remote Code Execution (RCE) bei fehlender Input-Validierung
- Veraltet und unsicher in PHP 8+

**Empfohlene Lösung:**
```php
// Statt eval() sollte ein Template-Engine wie Twig verwendet werden
// Oder sicherer: preg_match_all() zum Parsen von Template-Variablen
// Keine dynamische Code-Ausführung verwenden
$parsed = preg_replace_callback('/\{\{(\w+)\}\}/', function($matches) use ($data) {
    return isset($data[$matches[1]]) ? htmlspecialchars($data[$matches[1]]) : '';
}, $content);
```

**Impact:** Extrem kritisch - ermöglicht vollständigen Serverzugriff

---

### 2. **SQL Injection in data-blocks.php**
**Kategorie:** Security - CRITICAL  
**Datei:** [library/data/data-blocks.php](library/data/data-blocks.php#L334-L336)  
**Zeilennummern:** 334-336  
**Severity:** 🔴 CRITICAL

```php
$query_string = $wpdb->prepare("SELECT * FROM $wpdb->pu_blocks WHERE layout = '%s' AND template = '%s'", $layout_id, PadmaOption::$current_skin);

if ( $wrapper_id )
    $query_string .= " AND wrapper_id = '$wrapper_id'";  // ❌ UNSICHER!
```

**Problem:**
- String-Konkatenation NACH `wpdb->prepare()` negiert den Schutz
- `$wrapper_id` wird direkt in SQL eingebunden ohne Escaping
- Ermöglicht SQL-Injection Attacks

**Empfohlene Lösung:**
```php
// FALSCH:
$query_string .= " AND wrapper_id = '$wrapper_id'";

// RICHTIG:
$query_string = $wpdb->prepare(
    "SELECT * FROM $wpdb->pu_blocks 
     WHERE layout = '%s' AND template = '%s'" . 
    ($wrapper_id ? " AND wrapper_id = '%s'" : ""),
    $wrapper_id ? [$layout_id, PadmaOption::$current_skin, $wrapper_id] : [$layout_id, PadmaOption::$current_skin]
);
```

**oder noch besser:**
```php
$where = [
    'layout' => $layout_id,
    'template' => PadmaOption::$current_skin
];
if ($wrapper_id) {
    $where['wrapper_id'] = $wrapper_id;
}
$layout_blocks_query = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM $wpdb->pu_blocks WHERE " . 
        implode(' AND ', array_keys(array_fill_keys(array_keys($where), '%s'))),
        array_values($where)
    ),
    ARRAY_A
);
```

---

### 3. **SQL Injection in layout-selector.php**
**Kategorie:** Security - CRITICAL  
**Datei:** [library/visual-editor/layout-selector.php](library/visual-editor/layout-selector.php#L343)  
**Zeilennummer:** 343  
**Severity:** 🔴 CRITICAL

```php
$posts_query = $wpdb->prepare(
    "SELECT ID, post_title, post_status, post_type FROM $wpdb->posts 
     WHERE $wpdb->posts.post_title LIKE '%s' AND ...",
    '%' . $query . '%'
);
```

**Problem:**
- `$query` wird aus `padma_get('query', ...)`bezogen
- Der Wert wird mit Wildcards verkettet, aber KEINE Escaping für SQL LIKE-Wildcards
- Ein Benutzer kann `%` oder `_` einfügen zur Wildcard-Injection oder String-Manipulation

**Empfohlene Lösung:**
```php
$safe_query = '%' . $wpdb->esc_like($query) . '%';
$posts_query = $wpdb->prepare(
    "SELECT ID, post_title, post_status, post_type FROM $wpdb->posts 
     WHERE $wpdb->posts.post_title LIKE %s AND ...",
    $safe_query
);
```

---

### 4. **Unsafe parse_str() mit User Input**
**Kategorie:** Security - CRITICAL  
**Datei:** [library/visual-editor/visual-editor-ajax.php](library/visual-editor/visual-editor-ajax.php#L833)  
**Zeilennummern:** 833, 846  
**Severity:** 🔴 CRITICAL

```php
parse_str(padma_get('skin-info'), $skin_info);              // Zeile 833
parse_str(padma_post('skin-info'), $skin_info);             // Zeile 846
```

**Problem:**
- `parse_str()` mit dem zweiten Parameter ist seit PHP 7.2 deprecated und gefährlich
- Ohne korrekten Input-Handling können Variablen überschrieben werden
- Ermöglicht Variable Injection / Export Register Globals Style Attack

**Empfohlene Lösung:**
```php
// FALSCH (deprecated):
parse_str(padma_get('skin-info'), $skin_info);

// RICHTIG:
$skin_info = [];
$raw_data = padma_get('skin-info', '');
if (!empty($raw_data)) {
    // Validieren Sie den Input als Query String und parse manuell:
    $parsed = wp_parse_args($raw_data);
    // Oder direkt JSON verwenden:
    $skin_info = json_decode(wp_unslash($raw_data), true) ?? [];
    
    // Validieren Sie Struktur:
    if (is_array($skin_info)) {
        // Nur erlaubte Keys whitelisten
        $skin_info = array_intersect_key($skin_info, array_flip(['key1', 'key2', 'key3']));
    }
}
```

---

## HOHE SICHERHEITSPROBLEME (HIGH)

### 5. **Reflected XSS in visual-editor-ajax.php Callback-Handling**
**Kategorie:** Security (XSS) - HIGH  
**Datei:** [library/visual-editor/visual-editor-ajax.php](library/visual-editor/visual-editor-ajax.php#L10)  
**Zeilennummer:** 10  
**Severity:** 🟠 HIGH

```php
if ( padma_get('callback') )
    echo padma_get('callback') . '(';
```

**Problem:**
- Gibt Benutzer-Input direkt in JavaScript aus
- Ermöglicht JSONP Callback-Injection / XSS Attacks
- Kein Escaping oder Validierung des Callback-Namens

**Empfohlene Lösung:**
```php
// Validierung der Callback-Funktion
$callback = padma_get('callback');
if ($callback && preg_match('/^[a-zA-Z_$][\w$]*$/', $callback)) {
    // Nur valid JS-Funktionsnamen erlauben
    echo esc_js($callback) . '(';
} else {
    // Fehlerbehandlung
    wp_die('Invalid callback', 400);
}
```

---

### 6. **Multiple XSS Vulnerabilities in api-admin-inputs.php**
**Kategorie:** Security (XSS) - HIGH  
**Datei:** [library/admin/api-admin-inputs.php](library/admin/api-admin-inputs.php#L101)  
**Zeilennummern:** 101, 162  
**Severity:** 🟠 HIGH

```php
// Zeile 101:
echo '<input type="' . $type_attr . '" class="' . $input['size'] . '-text" value="' . $input['value'] . '" ...

// Zeile 162:
echo '<textarea ... >' . $input['value'] . '</textarea>';
```

**Problem:**
- `$input['value']` wird direkt ausgegeben ohne Escaping
- Ermöglicht HTML/JavaScript Injection
- Besonders kritisch in Form-Eingaben

**Empfohlene Lösung:**
```php
// Zeile 101:
echo '<input type="' . esc_attr($type_attr) . '" class="' . esc_attr($input['size']) . '-text" value="' . esc_attr($input['value']) . '" ...

// Zeile 162:
echo '<textarea ...>' . esc_textarea($input['value']) . '</textarea>';
```

---

### 7. **XSS in display.php (Filter Categories)**
**Kategorie:** Security (XSS) - HIGH  
**Datei:** [library/visual-editor/display.php](library/visual-editor/display.php#L428)  
**Zeilennummer:** 428  
**Severity:** 🟠 HIGH

```php
echo '<li><a class="" data-filter="'.$categorie.'">' . ucfirst(str_replace('-', ' ', $categorie)) . '</a></li>';
```

**Problem:**
- `$categorie` wird direkt in HTML-Attribute eingefügt
- Keine Verwendung von `esc_attr()`

**Empfohlene Lösung:**
```php
echo '<li><a class="" data-filter="' . esc_attr($categorie) . '">' . esc_html(ucfirst(str_replace('-', ' ', $categorie))) . '</a></li>';
```

---

### 8. **Dangerous use of extract() Function**
**Kategorie:** Security - HIGH  
**Dateien:**
- [library/data/data-elements.php](library/data/data-elements.php#L83)  
- [library/fonts/traditional-fonts.php](library/fonts/traditional-fonts.php#L123)  
- [library/common/compiler.php](library/common/compiler.php#L285)  
- [library/elements/properties.php](library/elements/properties.php#L1773)  
- [library/blocks/content/content-display.php](library/blocks/content/content-display.php#L125)  
- [library/blocks/gallery/gallery-display.php](library/blocks/gallery/gallery-display.php#L207)  

**Zeilennummern:** Wie oben aufgelistet  
**Severity:** 🟠 HIGH

**Beispiel Problem:**
```php
extract(array_merge($defaults, $args));  // ❌ Gefährlich!
```

**Problem:**
- `extract()` erstellt Variablen aus Array-Keys
- Mit `EXTR_OVERWRITE` (Standard) können bestehende Variablen überschrieben werden
- Führt zu Variable Poaching und Buffer Overflows
- Unvorhersehbare Behavior und Security Holes

**Empfohlene Lösung:**
```php
// Statt:
extract($args);

// Verwenden Sie:
$args = array_merge($defaults, $args);
$value = $args['key'] ?? $default;

// Oder mit Typisierung:
foreach ($defaults as $key => $default_value) {
    ${$key} = $args[$key] ?? $default_value;
}
```

---

### 9. **Missing Nonce Verification in visual-editor-ajax.php**
**Kategorie:** Security (CSRF) - HIGH  
**Datei:** [library/visual-editor/visual-editor-ajax.php](library/visual-editor/visual-editor-ajax.php#L49)  
**Zeilennummer:** 49, und andere Methoden  
**Severity:** 🟠 HIGH

**Problem:**
- Viele AJAX Methoden fehlt die Nonce-Verifikation
- CSRF Attacken sind möglich
- Benutzer können von böswilligen Seiten zu Aktionen verleitet werden

**Beispiel (secure_method_delete_skin):**
```php
public static function secure_method_delete_skin() {
    // ❌ Kein wp_verify_nonce() !
    $skin_to_delete = padma_post('skin');
    ...
}
```

**Empfohlene Lösung:**
```php
public static function secure_method_delete_skin() {
    // ✅ Nonce Verifikation hinzufügen
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'padma_delete_skin_action')) {
        wp_die('Security check failed', 403);
    }
    
    $skin_to_delete = padma_post('skin');
    ...
}
```

---

### 10. **Dangerous SQL Queries without wpdb->prepare()**
**Kategorie:** Security (SQL Injection) - HIGH  
**Dateien:**
- [library/admin/admin.php](library/admin/admin.php#L173)  
- [library/common/application.php](library/common/application.php#L414)  

**Zeilennummern:** 107, 173-179, 414-417  
**Severity:** 🟠 HIGH

**Beispiele:**
```php
// ❌ UNSICHER:
$wpdb->query( "TRUNCATE TABLE $wpdb->pu_snapshots" );
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name = 'padma'" );
$wpdb->query( "DROP TABLE IF EXISTS $wpdb->pu_blocks" );
```

**Problem:**
- Keine Verwendung von `wpdb->prepare()`
- Table-Namen sind hartkodiert, aber Code ist anfällig für zukünftige Probleme

**Empfohlene Lösung:**
```php
// ✅ SICHER (Table-Namen sind automatisch escaped):
$wpdb->query("TRUNCATE TABLE {$wpdb->pu_snapshots}");
$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name = %s", 'padma'));
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->pu_blocks}");

// Oder mit direktem Zugriff:
$wpdb->query("TRUNCATE TABLE " . $wpdb->pu_snapshots);
```

---

## MITTLERE PROBLEME (MEDIUM)

### 11. **Unvalidated User Input in padma_get() / padma_post()**
**Kategorie:** Security & Code Quality - MEDIUM  
**Datei:** [library/common/functions.php](library/common/functions.php#L103)  
**Zeilennummern:** 103-180  
**Severity:** 🟡 MEDIUM

```php
function padma_post($name, $default = null) {
    return padma_get($name, $_POST, $default);
}

// Kein Escaping oder Sanitization!
```

**Problem:**
- Diese Funktionen geben Raw User Input zurück
- Keine Sanitization / Escaping
- Wird direkt in sensiblen Kontexten verwendet

**Empfohlene Lösung:**
```php
function padma_get_raw($name, $array = false, $default = null) {
    // Alte Funktion umbenennen, um Raw-Daten zu signalisieren
    if ( $array === false ) $array = $_GET;
    // ... existing code
}

function padma_post_sanitized($name, $default = null, $callback = 'sanitize_text_field') {
    $value = padma_get_raw($name, $_POST, $default);
    
    if ($value === $default) return $default;
    
    // Anwende Sanitization basierend auf Kontext
    if (is_callable($callback)) {
        return call_user_func($callback, $value);
    }
    return $value;
}

// Beispiel Verwendung:
$safe_value = padma_post_sanitized('my_field', '', 'sanitize_text_field');
```

---

### 12. **Missing Type Declarations (PHP 8+ Incompatibility)**
**Kategorie:** PHP 8+ Compatibility - MEDIUM  
**Dateien:** Alle Klassen und Funktionen in der Library  
**Severity:** 🟡 MEDIUM

**Beispiele von fehlenden Type Declarations:**
- [library/abstract/api-admin-meta-box.php](library/abstract/api-admin-meta-box.php#L197) - `public function save( $post_ID )`
- [library/abstract/api-panel.php](library/abstract/api-panel.php#L80) - `public function modify_arguments($args)`
- [library/data/data-elements.php](library/data/data-elements.php#L16) - `public static function get_raw_data($defaults = array())`

**Problem:**
- Keine Type Hints für Parameter und Return Types
- PHP 8 erfordert präzisere Typing
- Erschwert Code-Verständnis und führt zu Runtime-Fehlern
- IDE-Unterstützung ist limitiert

**Empfohlene Lösung:**
```php
// VORHER:
public function save( $post_ID ) {
    // ...
}

// NACHHER:
public function save( int $post_ID ): bool {
    // ...
}

// Mit Union Types (PHP 8+):
public function process_data( string|int|array $data ): ?array {
    // ...
}

// Mit Nullsafe Operator (PHP 8+):
public function get_title( ?array $item ): string {
    return $item?->['title'] ?? 'Untitled';
}
```

---

### 13. **Unserialize() Potential (Security Risk)**
**Kategorie:** Security - MEDIUM  
**Datei:** [library/common/functions.php](library/common/functions.php#L11)  
**Zeilennummern:** 11-40  
**Severity:** 🟡 MEDIUM

```php
function padma_maybe_unserialize($string) {
    if ( is_serialized( $string ) ) {
        $data = maybe_unserialize( $string );  // WordPress Function - aber trotzdem risky
        ...
    }
}
```

**Problem:**
- `maybe_unserialize()` nutzt `unserialize()` intern
- Kann zu Object Injection / RCE führen bei bösartigen serialisierten Daten
- `__wakeup()` und `__destruct()` Methoden können ausgenutzt werden

**Empfohlene Lösung:**
```php
function padma_maybe_unserialize($string) {
    if (is_serialized($string)) {
        // Nur mit explicititem Objekt-Filtering verwenden:
        $data = unserialize($string, ['allowed_classes' => false]);
        
        // ODER besser: JSON statt Serialisierung verwenden
        // if (is_json($string)) {
        //     $data = json_decode($string, true);
        // }
    }
    return $string;
}
```

---

### 14. **Missing Authorization Checks in Admin Functions**
**Kategorie:** Security & WordPress Standards - MEDIUM  
**Dateien:**
- [library/admin/admin.php](library/admin/admin.php#L90)  
- [library/admin/admin.php](library/admin/admin.php#L152)  

**Zeilennummern:** 90, 152, etc.  
**Severity:** 🟡 MEDIUM

**Beispiel:**
```php
public static function form_action_delete_snapshots() {
    global $wpdb;
    
    if ( ! padma_post( 'padma-delete', false ) ) {
        return false;
    }
    
    // ❌ Kein current_user_can() Check!
    if ( ! wp_verify_nonce( padma_post( 'padma-delete-snapshots-nonce', false ), 'padma-delete-snapshots-nonce' ) ) {
        // ...
    }
    // ...
}
```

**Problem:**
- Fehlt die `current_user_can()` Überprüfung
- Nur Nonce wird geprüft, nicht die Berechtigungen
- Ein authentifizierter, aber nicht-autorisierter Benutzer könnte Aktionen ausführen

**Empfohlene Lösung:**
```php
public static function form_action_delete_snapshots() {
    global $wpdb;
    
    // ✅ Zuerst Nonce prüfen
    if ( ! wp_verify_nonce( padma_post( 'padma-delete-snapshots-nonce', false ), 'padma-delete-snapshots-nonce' ) ) {
        $GLOBALS['padma_admin_save_message'] = 'Security nonce did not match.';
        return false;
    }
    
    // ✅ Dann Capabilities prüfen
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'You do not have permission to perform this action.', 403 );
    }
    
    if ( ! padma_post( 'padma-delete', false ) ) {
        return false;
    }
    
    // ... proceed with action
}
```

---

## NIEDRIGE/INFORMATIVE PROBLEME (LOW)

### 15. **Unused Variable Variables Pattern**
**Kategorie:** Code Quality & PHP 8+ - LOW  
**Datei:** [library/visual-editor/display.php](library/visual-editor/display.php#L463)  
**Zeilennummern:** Multiple occurrences  
**Severity:** 🔵 LOW

```php
echo '<div id="block-type-' . $block_type_id . '"...';
```

**Problem:**
- Mehrfache String-Konkatenation
- Weniger lesbar als sprintf() oder Heredoc
- Minor performance impact

**Empfohlene Lösung:**
```php
echo sprintf(
    '<div id="block-type-%s" class="block-type %s"...>',
    esc_attr($block_type_id),
    esc_attr($filter_categories)
);

// Oder mit Heredoc:
echo <<<HTML
<div id="block-type-{$block_type_id}" class="block-type {$filter_categories}">
HTML;
```

---

### 16. **Potential $_SERVER Superglobal Usage**
**Kategorie:** Security - LOW  
**Datei:** [library/admin/pages/tools.php](library/admin/pages/tools.php#L62)  
**Zeilennummer:** 62  
**Severity:** 🔵 LOW

```php
echo isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] . "\n" : "Unknown\n";
```

**Problem:**
- `$_SERVER['SERVER_SOFTWARE']` wird direkt ausgegeben
- Könnte in seltenen Fällen manipulierbar sein (abhängig vom Server)
- Minor XSS Risk

**Empfohlene Lösung:**
```php
echo isset($_SERVER['SERVER_SOFTWARE']) ? esc_html($_SERVER['SERVER_SOFTWARE']) . "\n" : "Unknown\n";
```

---

## ZUSAMMENFASSUNG & PRIORITÄTEN

| Severity | Anzahl | Kritischste Issues |
|----------|--------|-------------------|
| 🔴 CRITICAL | 4 | eval(), SQL Injection (2x), parse_str() |
| 🟠 HIGH | 6 | XSS (3x), extract(), CSRF, Old SQL |
| 🟡 MEDIUM | 3 | Input Validation, Type Declarations, Unserialize |
| 🔵 LOW | 3 | Code Quality, $_SERVER Usage |

---

## EMPFOHLENE AKTIONSPROTOKOLLE

### Phase 1 - SOFORT (Woche 1)
1. **eval() entfernen** - Parse-PHP.php umschreiben
2. **SQL Injections beheben** - data-blocks.php, layout-selector.php
3. **parse_str() ersetzen** - visual-editor-ajax.php

### Phase 2 - WICHTIG (Woche 2-3)
4. **XSS-Schwachstellen fixen** - Alle echo-Statements überprüfen
5. **extract() entfernen** - Alle Vorkommen refaktorieren
6. **CSRF-Schutz verbessern** - Nonce-Verifikation hinzufügen

### Phase 3 - EMPFOHLEN (Monat 1-2)
7. **Type Declarations hinzufügen** - PHP 8 Kompatibilität
8. **Authorization Checks** - current_user_can() überall einfügen
9. **Input Validation** - Sanitization für alle User Inputs

### Phase 4 - LANGFRISTIG (Laufend)
10. **Code Review Process** - Vor jedem Commit
11. **Sicherheits-Unit-Tests** - Für kritische Funktionen
12. **WordPress PHPCS Standards** - Automatisierte Checks

---

## TESTING-EMPFEHLUNGEN

```bash
# PHPCS - WordPress Standards
phpcs --standard=WordPress library/

# Psalm - Type Checking
psalm library/

# PHP Security Check
php-security-check-tool scan library/
```

---

## DEPENDENCIES PRÜFEN

Stelle sicher, dass `composer.json` aktuell ist:
```bash
composer audit          # Sicherheits-Vulnerabilities in Dependencies
composer update         # Alle Dependencies aktualisieren (für PHP 8+ Compatibility)
```

---

**Bericht erstellt durch:** Automated Security Audit System  
**Nächste Review:** Nach 30 Tagen (nach Behebung der kritischen Issues)
