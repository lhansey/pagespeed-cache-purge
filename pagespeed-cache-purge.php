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

define('pagespeed_purge_url', home_url() . '/pagespeed_admin/cache?purge=*');
add_action('init', 'pagespeed_init');

function pagespeed_init() {
        if (current_user_can('activate_plugins')) {
                add_action('admin_bar_menu', 'pagespeed_dumpnow_adminbar', 200);
        }
        if (isset($_GET['ps_flush_all']) && check_admin_referer('ps-cache-purge','_psnonce')) {
                if (current_user_can('activate_plugins')) {
                        $resp = wp_remote_get(pagespeed_purge_url);
                        if ($resp['body'] == 'Purge successful' && $resp['headers']['x-cache'] == "MISS") {
                                add_action( 'admin_notices' , 'pagespeed_cache_flushed');
                        } else {
                                add_action( 'admin_notices' , 'pagespeed_cache_failed');
                        }
                }
        }

}

function pagespeed_dumpnow_adminbar($admin_bar){
        $admin_bar->add_menu( array(
                'id'    => 'purge-pagespeed-cache-all',
                'title' => 'Purge Pagespeed',
                'href'  => wp_nonce_url(add_query_arg( array('ps_flush_all' => 1)  ), 'ps-cache-purge', '_psnonce'),
                'meta'  => array(
                        'title' => __('Purge Pagespeed','ps-cache-purge'),
                ),
        ));
}

function pagespeed_cache_flushed() {
        echo "<div id='message' class='updated fade'><p><strong>".__('Pagespeed cache purged!', 'ps-cache-purge')."</strong></p></div>";
}

function pagespeed_cache_failed() {
        echo "<div id='message' class='error'><p><strong>".__('Pagespeed cache failed to purge!', 'ps-cache-purge')."</strong></p></div>";
}

