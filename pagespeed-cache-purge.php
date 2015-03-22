<?php
/**
 * Plugin Name: Pagespeed Cache Purge
 * Plugin URI: https://github.com/lhansey/pagespeed-cache-purge
 * Description: Flushes the mod_pagespeed cache
 * Version: 1.0
 * Author: Luke Hansey
 * Author URI: http://lukehansey.com
 * License: GPL2
 */

add_action('admin_bar_menu', 'pagespeed_dumpnow_adminbar', 200);

if ($_GET['ps_flush_all']) {
        if (!current_user_can('activate_plugins')) {
                add_action( 'admin_notice' , 'pagespeed_cache_failed');
        } else {
                $resp = wp_remote_get(home_url() . "/pagespeed_admin/cache?purge=*");
                if ($resp['body'] == 'Purge successful' && $resp['headers']['x-cache'] == 'MISS') {
                        add_action( 'admin_notices' , 'pagespeed_cache_flushed');
                } else {
                        add_action( 'admin_notices' , 'pagespeed_cache_failed');
                }
        }
}

function pagespeed_dumpnow_adminbar($admin_bar){
        $admin_bar->add_menu( array(
                'id'    => 'purge-pagespeed-cache-all',
                'title' => 'Purge Pagespeed',
                                                        /* _wpnonce and vhp_flush_all removal avoids conflict with varnish http purge */
                'href'  => wp_nonce_url(add_query_arg( array('_wpnonce' => false, 'vhp_flush_all' => false, 'ps_flush_all' => false, 'ps_flush_all' => 1)  ), 'ps-cache-purge', '_psnonce'),
                'meta'  => array(
                        'title' => __('Purge Pagespeed','ps-cache-purge'),
                ),
        ));
}

function pagespeed_cache_flushed() {
        echo "<div id='message' class='updated fade'><p><strong>".__('Pagespeed cache purged!', 'ps-cache-purge')."</strong></p></div>";
}

function pagespeed_cache_failed() {
        echo "<div id='message' class='error'><p><strong>".__('Pagespeed cache failed to purge!', 'ps-cache-fail')."</strong></p></div>";
}
