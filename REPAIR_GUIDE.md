# Reparatur-Guide für kritische Sicherheitsprobleme
## ps-padma Theme - PHP 8+ Kompatibilität & Security Fixes

---

## PROBLEM 1: eval() RCE - SOFORT REPARIEREN!

### Datei: `library/common/parse-php.php` (Zeile 20-30)

**AKTUELL (UNSICHER):**
```php
if ( empty( $content ))
    return $content;

ob_start();

$eval = eval("?>$content<?php ;");

if ( $eval === null ) {
    $parsed = ob_get_contents();
```

**EMPFOHLENE LÖSUNG 1 - Template mit Regex (Einfach):**
```php
/**
 * Parse PHP-ähnliche Variablen in Templates (SICHER - ohne eval())
 * @param string $content Template-Inhalt
 * @return string Geparster Inhalt
 */
function padma_parse_php_template($content = '') {
    
    if ( empty( $content ) )
        return $content;
    
    // Parse nur sichere Template-Variablen {{variable}}
    $parsed = preg_replace_callback(
        '/\{\{([a-zA-Z_][a-zA-Z0-9_]*)\}\}/',
        function($matches) {
            $var = $matches[1];
            // Nur whitelisted Variablen erlauben
            $whitelist = ['site_url', 'home_url', 'admin_email', 'blogname'];
            
            if (in_array($var, $whitelist, true)) {
                return htmlspecialchars(constant(strtoupper($var)) ?? '', ENT_QUOTES, 'UTF-8');
            }
            return $matches[0]; // Nicht ersetzen wenn nicht whitelisted
        },
        $content
    );
    
    return $parsed;
}
```

**EMPFOHLENE LÖSUNG 2 - Twig Template Engine (Robust):**
```php
// composer.json hinzufügen:
{
    "require": {
        "twig/twig": "^3.0"
    }
}

// Dann in parse-php.php:
use Twig\Environment;
use Twig\Loader\ArrayLoader;

function padma_parse_php_template($content = '') {
    
    if ( empty( $content ) )
        return $content;
    
    try {
        $loader = new ArrayLoader(['template' => $content]);
        $twig = new Environment($loader, ['autoescape' => true]);
        $template = $twig->loadTemplate('template');
        
        // Safe context - nur whitelisted Variablen
        $context = [
            'site_url' => home_url(),
            'blogname' => get_bloginfo('name'),
        ];
        
        return $template->render($context);
    } catch (Exception $e) {
        error_log('Padma Template Error: ' . $e->getMessage());
        return $content; // Falls Error - Original zurückgeben
    }
}
```

---

## PROBLEM 2: SQL Injection in data-blocks.php

### Datei: `library/data/data-blocks.php` (Zeile 330-340)

**AKTUELL (UNSICHER):**
```php
public static function get_blocks_by_layout($layout_id, $wrapper_id = false, ...) {
    global $wpdb;
    
    $query_string = $wpdb->prepare(
        "SELECT * FROM $wpdb->pu_blocks WHERE layout = '%s' AND template = '%s'", 
        $layout_id, 
        PadmaOption::$current_skin
    );

    if ( $wrapper_id )
        $query_string .= " AND wrapper_id = '$wrapper_id'";  // ❌ SQL INJECTION!
    
    $layout_blocks_query = $wpdb->get_results($query_string, ARRAY_A);
    return $layout_blocks_query;
}
```

**RICHTIG NEUGESCHRIEBEN:**
```php
/**
 * Get blocks by layout with proper SQL escaping
 * @param string $layout_id Layout ID
 * @param string|bool $wrapper_id Optional wrapper ID
 * @return array Array of blocks
 */
public static function get_blocks_by_layout($layout_id, $wrapper_id = false) {
    global $wpdb;
    
    // Build der WHERE-Bedingungen
    $where_clauses = [
        $wpdb->prepare("layout = %s", $layout_id),
        $wpdb->prepare("template = %s", PadmaOption::$current_skin)
    ];
    
    // Optional wrapper_id hinzufügen (mit Escaping)
    if ( $wrapper_id ) {
        $where_clauses[] = $wpdb->prepare("wrapper_id = %s", $wrapper_id);
    }
    
    // Alle WHERE-Bedingungen kombinieren
    $query = "SELECT * FROM $wpdb->pu_blocks WHERE " . implode(" AND ", $where_clauses);
    
    // Caching bleibt
    $cache_key = 'pu_blocks_by_layout_' . $layout_id;
    if ( $wrapper_id )
        $cache_key .= '_wrapper_' . md5($wrapper_id); // Sicherer Cache-Key
    
    $layout_blocks = wp_cache_get($cache_key);
    
    if ( $layout_blocks === false ) {
        $layout_blocks = $wpdb->get_results($query, ARRAY_A);
        wp_cache_set($cache_key, $layout_blocks);
    }
    
    return $layout_blocks;
}
```

---

## PROBLEM 3: SQL Injection in layout-selector.php

### Datei: `library/visual-editor/layout-selector.php` (Zeile 343)

**AKTUELL (UNSICHER):**
```php
if ( empty($query) || strlen($query) <= 2 ) {
    return false;
}

$posts_query = $wpdb->prepare(
    "SELECT ID, post_title, post_status, post_type FROM $wpdb->posts 
     WHERE $wpdb->posts.post_title LIKE '%s' AND $wpdb->posts.post_type != 'revision' 
     ORDER BY $wpdb->posts.post_title",
    '%' . $query . '%'  // ❌ Wildcard-Injection möglich
);
```

**RICHTIG NEUGESCHRIEBEN:**
```php
public static function search_posts($query = '') {
    global $wpdb;
    
    // Input-Validierung
    $query = sanitize_text_field($query);
    
    if ( empty($query) || strlen($query) <= 2 ) {
        return false;
    }
    
    // WICHTIG: $wpdb->esc_like() für sichere LIKE-Queries verwenden
    $safe_query = '%' . $wpdb->esc_like($query) . '%';
    
    // Jetzt sql Injection-sicher:
    $posts_query = $wpdb->prepare(
        "SELECT ID, post_title, post_status, post_type FROM $wpdb->posts 
         WHERE $wpdb->posts.post_title LIKE %s 
         AND $wpdb->posts.post_type != %s
         ORDER BY $wpdb->posts.post_title",
        $safe_query,
        'revision'  // Auch hardcoded strings escapen
    );
    
    $results = [];
    foreach ( $wpdb->get_results($posts_query) as $post ) {
        $results[] = [
            'ID' => (int)$post->ID,
            'title' => esc_html($post->post_title),
            'type' => esc_attr($post->post_type)
        ];
    }
    
    return $results;
}
```

---

## PROBLEM 4: parse_str() Vulnerability

### Datei: `library/visual-editor/visual-editor-ajax.php` (Zeile 833, 846)

**AKTUELL (UNSICHER):**
```php
public static function method_export_skin() {
    Padma::load('data/data-portability');
    
    parse_str(padma_get('skin-info'), $skin_info);  // ❌ Variable Injection!
    
    return PadmaDataPortability::export_skin($skin_info['skin-export-info']);
}
```

**RICHTIG NEUGESCHRIEBEN:**
```php
public static function method_export_skin() {
    Padma::load('data/data-portability');
    
    // ✅ SICHER: Manuelles Parsing mit Validierung
    $raw_data = padma_get('skin-info', '');
    
    // Whitelist der erlaubten Parameter
    $allowed_keys = ['skin-export-info', 'skin-save-on-cloud-info'];
    
    // Sicheres Parsing
    $skin_info = [];
    if (!empty($raw_data)) {
        // Versuche als JSON (bevorzugt)
        $decoded = json_decode(wp_unslash($raw_data), true);
        
        if (is_array($decoded)) {
            // Nur whitelisted Keys zulassen
            $skin_info = array_intersect_key($decoded, array_flip($allowed_keys));
        } else {
            // Fallback: als Query String parsen (sicherer mit Validation)
            parse_str(wp_unslash($raw_data), $parsed);
            $skin_info = array_intersect_key($parsed, array_flip($allowed_keys));
        }
    }
    
    // Validiere dass die erforderliche Info vorhanden ist
    if (!isset($skin_info['skin-export-info'])) {
        wp_die('Invalid skin export data', 400);
    }
    
    return PadmaDataPortability::export_skin($skin_info['skin-export-info']);
}

public static function method_save_skin_on_cloud() {
    if (!class_exists('padmaServices')) {
        return;
    }
    
    Padma::load('data/data-portability');
    
    // ✅ SICHER Version:
    $raw_data = padma_post('skin-info', '');
    $skin_info = [];
    
    if (!empty($raw_data)) {
        $decoded = json_decode(wp_unslash($raw_data), true);
        if (is_array($decoded)) {
            $skin_info = $decoded; // JSON ist sicherer als Query String
        }
    }
    
    if (empty($skin_info['skin-save-on-cloud-info'])) {
        wp_send_json_error('Invalid data');
        return;
    }
    
    $skin = PadmaDataPortability::export_skin(
        $skin_info['skin-save-on-cloud-info'],
        true
    );
    
    // ... rest of method
}
```

---

## PROBLEM 5: XSS in Callback-Handling

### Datei: `library/visual-editor/visual-editor-ajax.php` (Zeile 1-15)

**AKTUELL (UNSICHER):**
```php
private static function json_encode($data) {
    header('content-type:application/json');

    if ( padma_get('callback') )
        echo padma_get('callback') . '(';  // ❌ XSS!

    echo json_encode($data);

    if ( padma_get('callback') )
        echo ')';
}
```

**RICHTIG NEUGESCHRIEBEN:**
```php
/**
 * Safe JSON encoding with optional JSONP callback
 * @param array $data Data to encode
 * @return void
 */
private static function json_encode($data) {
    header('Content-Type: application/json; charset=utf-8');
    
    // Hole und validiere Callback-Namen
    $callback = padma_get('callback', '');
    
    // Validierung: Nur gültige JavaScript Funktionsnamen erlauben
    // Pattern: [a-zA-Z_$][\w$]*(\.[a-zA-Z_$][\w$]*)*
    if (!empty($callback) && preg_match('/^[a-zA-Z_$][\w$]*(\.[a-zA-Z_$][\w$]*)*$/', $callback)) {
        // JSONP: Aber NICHT die Daten ausgegeben bevor wir sicher sind!
        echo esc_js($callback) . '(';
    }
    
    // JSON mit proper escaping
    $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    if (JSON_ERROR_NONE !== json_last_error()) {
        http_response_code(500);
        $json = json_encode(['error' => 'JSON encoding failed']);
    }
    
    echo $json;
    
    if (!empty($callback) && preg_match('/^[a-zA-Z_$][\w$]*$/', $callback)) {
        echo ');';
    }
}
```

---

## PROBLEM 6: extract() entfernen (Sicherheit)

### Beispiel: `library/data/data-elements.php` (Zeile 83)

**AKTUELL (UNSICHER):**
```php
public static function get_raw_data($defaults = array()) {
    $args = do_action('padma_elements_data_before_defaults', $defaults);
    
    extract(array_merge($defaults, $args));  // ❌ Gefährlich!
    
    // $value wird hier verwendet?
}
```

**RICHTIG NEUGESCHRIEBEN:**
```php
public static function get_raw_data($defaults = array()) {
    $args = do_action('padma_elements_data_before_defaults', $defaults);
    
    // ✅ Sichere Alternative zu extract():
    $merged_data = array_merge($defaults, $args);
    
    // Explizit die benötigten Variablen setzen mit Typ-Überprüfung:
    foreach ($merged_data as $key => $value) {
        // Nur whitelisted Keys akzeptieren
        if (in_array($key, ['id', 'name', 'type', 'value'], true)) {
            // Keine variable Variablen - explizit:
            $$key = $value;
        }
    }
    
    // ODER noch besser - kein extract() nutzen:
    return array_intersect_key(
        $merged_data,
        array_flip(['id', 'name', 'type', 'value'])
    );
}
```

---

## PROBLEM 7: Add Type Declarations (PHP 8+)

### Alle Funktionen sollten Type Hints haben

**BEISPIELE:**

```php
// VORHER (keine Type Hints):
public function save( $post_ID ) {
    global $wpdb;
    
    foreach ( $this->inputs as $input ) {
        $name = padma_post( $input['id'], '' );
        // ...
    }
}

// NACHHER (PHP 8 strict types):
<?php declare(strict_types=1);

namespace Padma;

class AdminMetaBox {
    public function save(int $post_ID): bool {
        global $wpdb;
        
        if (!is_array($this->inputs)) {
            return false;
        }
        
        foreach ($this->inputs as $input) {
            if (!isset($input['id'])) {
                continue;
            }
            
            $name = padma_post((string)$input['id'], '');
            // Type ist jetzt garantiert string
        }
        
        return true;
    }
}
```

---

## PROBLEM 8: Add CSRF + Authorization Checks

### Datei: `library/visual-editor/visual-editor-ajax.php` (Alle secure_method_*() Methoden)

**BEISPIEL FIX:**
```php
// VORHER (keine Sicherheit):
public static function secure_method_delete_skin() {
    global $wpdb;
    
    $skin_to_delete = padma_post('skin');
    if ( $skin_to_delete == PadmaOption::get('current-skin') || $skin_to_delete == 'base' ) {
        echo 'error: cannot delete current template';
        return;
    }
    // ... löscht Skin
}

// NACHHER (mit Sicherheit):
public static function secure_method_delete_skin(): void {
    // 1. Check: Ist der User angemeldet?
    if (!is_user_logged_in()) {
        wp_send_json_error('Not authenticated', 401);
    }
    
    // 2. Check: Hat der User die Berechtigung?
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Not authorized', 403);
    }
    
    // 3. Check: Valid NONCE?
    $nonce = padma_post('nonce', '');
    if (!$nonce || !wp_verify_nonce($nonce, 'padma_delete_skin')) {
        wp_send_json_error('Invalid nonce', 403);
    }
    
    // 4. Validate Input
    $skin_to_delete = sanitize_text_field(padma_post('skin', ''));
    if (empty($skin_to_delete)) {
        wp_send_json_error('No skin specified');
    }
    
    // 5. Check: Darf dieser Skin gelöscht werden?
    if ($skin_to_delete === PadmaOption::get('current-skin') || $skin_to_delete === 'base') {
        wp_send_json_error('Cannot delete current or base template');
    }
    
    // 6. Action ausführen
    global $wpdb;
    $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->options WHERE option_name LIKE %s", 
        'pu_|template=' . $skin_to_delete . '|%'));
    
    wp_send_json_success('Skin deleted');
}
```

---

## TESTING NACH FIXES

```bash
# 1. Überprüfe PHP 8 Kompatibilität
php -l library/common/parse-php.php
php -l library/data/data-blocks.php

# 2. Generischer WordPress Security Scan
phpcs --standard=WordPress library/

# 3. Simpler SQL-Injection Test
grep -r "\"SELECT.*\$" library/ --color=always

# 4. XSS Audit
grep -r "echo.*\$_[GP]" library/ --color=always

# 5. eval() Check
grep -r "eval(" library/ --color=always
```

---

## DEBUGGING HILFEN

```php
// Logs Datenbank-Queries während Entwicklung
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

// In wp-config.php
defined('SAVEQUERIES') || define('SAVEQUERIES', true);

// Queries in Objekten debuggen:
var_dump($wpdb->queries);  // Alle Queries ausgeben
```
