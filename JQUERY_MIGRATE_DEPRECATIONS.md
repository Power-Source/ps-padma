# jQuery Migrate Deprecation Warnings

## Problem

Your browser console shows jQuery Migrate deprecation warnings like:
- `jQuery.fn.resize() event shorthand is deprecated`
- `jQuery.type is deprecated`

These warnings come from the jQuery Migrate plugin which WordPress loads for backwards compatibility.

## Solutions

### Solution 1: Disable jQuery Migrate in Production (Recommended)

Add to your `wp-config.php`:

```php
// Disable jQuery Migrate in production (keep it for dev)
if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
    add_action( 'wp_default_scripts', function( $scripts ) {
        if ( ! empty( $scripts->registered['jquery'] ) ) {
            $scripts->registered['jquery']->deps = array_diff( 
                $scripts->registered['jquery']->deps, 
                array( 'jquery-migrate' ) 
            );
        }
    });
}
```

Or simply:

```php
define( 'SCRIPT_DEBUG', false );
```

### Solution 2: Enable SCRIPT_DEBUG for Development

Add to your `wp-config.php` for **local development only**:

```php
define( 'SCRIPT_DEBUG', true );
```

This loads unminified versions of scripts where we've already fixed the deprecated `.resize()` calls.

### Solution 3: Suppress Warnings in JavaScript

Add to your theme's footer area or custom JavaScript file:

```javascript
// Suppress jQuery Migrate warnings (temporary fix)
if ( window.jQuery ) {
    window.jQuery.migrateWarnings = [];
    window.jQuery.migrateReset = function() {};
}
```

## Which Files Were Fixed

The following files have been updated to remove jQuery deprecations:

- ✅ `/library/blocks-advanced/post-slider/js/owl.carousel.js` (unminified)
- ✅ `/library/blocks/gallery/admin/js/wp-admin.js`
- ✅ `/assets/js/psource-shortcodes/other-shortcodes.js`
- ✅ `/library/media/js/sticky.js`
- ✅ `/library/visual-editor/scripts-src/deps/colorpicker.js`

**Note:** Minified versions cannot be safely edited as they are auto-generated. In production (`SCRIPT_DEBUG=false`), use Solution 1 or 2 to disable jQuery Migrate entirely.

## Best Practice

For **production sites**: Disable jQuery Migrate (Solution 1)  
For **development sites**: Enable SCRIPT_DEBUG (Solution 2)  
For **temporary fixes**: Suppress warnings (Solution 3)

## References

- jQuery Migrate Plugin: https://github.com/jquery/jquery-migrate
- WordPress wp-config Documentation: https://developer.wordpress.org/plugins/plugin-basics/
