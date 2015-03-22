# pagespeed-cache-purge
A WordPress Plugin to Flush the mod_pagespeed Cache

Initial idea of a WordPress plug-in to purge the mod_pagespeed cache.  
Have not seen one out there and have run into issues with pagespeed heavily caching.

For this to work you'll need a handler set-up for pagespeed_admin otherwise you'll get 403 errors.

<Location /pagespeed_admin/>
RewriteEngine Off
Order allow,deny
Allow from localhost
Allow from 127.0.0.1
SetHandler pagespeed_admin
</Location>

If you have front-end caching set-up then you'll need to configure accordingly.  For example, Varnish:

In your vcl_recv sub-routine:

  # PASS THROUGH VARNISH IF PURGING MOD_PAGESPEED CACHE
  if (req.url ~ "^/pagespeed_admin/") {
    return (pass);
