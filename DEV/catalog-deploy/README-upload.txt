Upload this structure to your server:

1) Copy `catalog-deploy/public_html/padma-catalog/*` to:
   Home/web/eimen.net/public_html/padma-catalog/

2) Copy `catalog-deploy/padma-catalog-storage/*` to:
   Home/web/eimen.net/padma-catalog-storage/

3) Set API key in:
   public_html/padma-catalog/api/_config.php
   - PADMA_CATALOG_API_KEY = 'your-long-random-key'

4) IMPORTANT: check storage path in _config.php:
   const PADMA_CATALOG_STORAGE = __DIR__ . '/../../../../padma-catalog-storage';
   Adjust only if your hosting path differs.

Quick tests:
- GET https://eimen.net/padma-catalog/api/templates.php
- GET https://eimen.net/padma-catalog/api/updates.php

Upload flow:
- POST https://eimen.net/padma-catalog/api/upload.php
  Header: X-PADMA-API-KEY: <key>
  multipart/form-data field: template_zip=<file.zip>

- POST https://eimen.net/padma-catalog/api/publish.php
  Header: X-PADMA-API-KEY: <key>
  form field: upload_id=<value from upload response>

Template ZIP must contain at least:
- manifest.json

Minimal manifest.json example:
{
  "slug": "my-template",
  "name": "My Template",
  "version": "1.0.0",
  "channel": "stable"
}
