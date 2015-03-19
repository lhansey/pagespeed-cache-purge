<?php
/**
 * Plugin Name: Pagespeed Cache Purge
 * Plugin URI: http://lukehansey.com/pagespeed-cache-purge
 * Description: Flushes the mod_pagespeed cache
 * Version: 1.0
 * Author: Luke Hansey
 * Author URI: http://lukehansey.com
 * License: GPL2
 */

if ($_GET['ps_flush_all']) {
        $resp = wp_remote_get(home_url() . "/pagespeed_admin/cache?purge=*");
        if ($resp['body'] == 'Purge successful') {
                add_action( 'admin_notices' , 'ps_cache_flushed');
        } else {
                add_action( 'admin_notices' , 'ps_cache_failed');
        }
        print_r($resp);
}

add_action('admin_bar_menu', 'pagespeed_dumpnow_adminbar', 101);

function pagespeed_dumpnow_adminbar($admin_bar){
        $admin_bar->add_menu( array(
                'id'    => 'purge-pagespeed-cache-all',
                'title' => 'Purge Pagespeed',
                'href'  => wp_nonce_url(add_query_arg('ps_flush_all', 1), 'ps-cache-purge'),
                'meta'  => array(
                        'title' => __('Purge Pagespeed','ps-cache-purge'),
                ),
        ));
}

function ps_cache_flushed() {
        echo "<div id='message' class='updated fade'><p><strong>".__('Pagespeed cache purged!', 'ps-cache-purge')."</strong></p></div>";
}

function ps_cache_failed() {
        echo "<div id='message' class='updated fade'><p><strong>".__('Pagespeed cache failed to purge!', 'ps-cache-fail')."</strong></p></div>";
}
