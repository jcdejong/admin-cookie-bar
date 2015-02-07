<?php
/**
 * @package Admin Cookie Bar
 * @version 0.1
 */
/*
Plugin Name: Admin Cookie Bar
Description: Add ability to display the admin-bar based on a cookie, so you can enable the bar when full-page-cache is enabled on the frontend, without everybody seeing the admin-bar on top. (basicaly a quick'n dirty workaround)
Author: Jeroen de Jong
Version: 0.1
Author URI: http://www.allict.nl
*/

// disable the normal admin bar
add_filter('show_admin_bar', '__return_false');

// add the javascript to display the admin bar
function add_admin_cookie_bar_scripts() {
	wp_enqueue_script(
	    'admin-cookie-bar',
	    plugins_url('admincookiebar.js', __FILE__),
	    array('jquery'),
	    '0.1',
	    true
    );
}
add_action( 'wp_enqueue_scripts', 'add_admin_cookie_bar_scripts' );

// set a cookie that we can read later
function set_admin_cookie_bar_cookie($user_login, $user) {
    global $post;

    // set a cookie when a user is logged in
        setcookie("AdminCookieBar", $user->ID, time()+3600);  /* expire in 1 hour */
}
add_action('wp_login', 'set_admin_cookie_bar_cookie', 10, 2);

// remove cookie when user logs out
function del_admin_cookie_bar_cookie() {
    setcookie("AdminCookieBar", null, -1);
}
add_action('wp_logout', 'del_admin_cookie_bar_cookie');

// execute the javascript call on pageload with the current pageid
function add_admin_cookie_bar_meta_tag() {
    global $wp_query;

    $admin_url = base64_encode(esc_url(home_url('/')) . 'wp-admin/post.php?post=' . $wp_query->post->ID . '&amp;action=edit');

    echo '<script type="text/javascript">jQuery( document ).ready(function() { adminCookieBar("' . $admin_url . '"); });</script>' . PHP_EOL;
}
add_action('wp_footer', 'add_admin_cookie_bar_meta_tag', 1000);