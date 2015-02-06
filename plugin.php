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

// re-enable the adin-bar styles
function add_admin_cookie_bar_css() {
    echo '<style type="text/css" media="print">#wpadminbar { display:none; }</style>';
    echo '<style type="text/css" media="screen">'
    	.'html { margin-top: 32px !important; }'
        .'* html body { margin-top: 32px !important; }'
        .'@media screen and ( max-width: 782px ) {'
    	.'    html { margin-top: 46px !important; }'
        .'    * html body { margin-top: 46px !important; }'
        .'}'
        .'</style>';
}
add_action('wp_head', 'add_admin_cookie_bar_css', 1000);

// re-enable the admin-bar css and javascript and add our own to it as well
function add_admin_cookie_bar_scripts() {
	wp_enqueue_script(
	    'admin-cookie-bar',
	    plugins_url('admincookiebar.js', __FILE__),
	    array('jquery'),
	    '0.1',
	    true
    );

	wp_enqueue_script( 'admin-bar' );
	wp_enqueue_style( 'admin-bar' );
}
add_action( 'wp_enqueue_scripts', 'add_admin_cookie_bar_scripts' );

// re-enable the admin-bar classes
function add_admin_cookie_bar_body_classes( $classes ) {
	$classes[] = 'admin-bar';
	$classes[] = 'no-customize-support';
	return $classes;
}
add_filter( 'body_class', 'add_admin_cookie_bar_body_classes' );

// set a cookie that we can read later
function set_admin_cookie_bar_cookie() {
    global $post;

    // set a cookie when a user is logged in
    // @todo, do we really want to check if a user can edit here? mayb always set the cookie when the user logs in ;)
    if ( current_user_can( get_post_type_object($post->post_type)->cap->edit_post, $post->ID ) ) {
        setcookie("AdminCookieBar", get_current_user_id(), time()+3600);  /* expire in 1 hour */
    }
}
add_action('wp', 'set_admin_cookie_bar_cookie');

// remove cookie when user logs out
function del_admin_cookie_bar_cookie() {
    setcookie("AdminCookieBar", null, -1);
}
add_action('wp_logout', 'del_admin_cookie_bar_cookie');

// execute the javascript call on pageload with the current pageid
function add_admin_cookie_bar_meta_tag() {
    global $wp_query;

    $admin_url = 'http://wordpress.project.allict.nl/wp-admin/post.php?post=' . $wp_query->post->ID . '&amp;action=edit';

    echo '<script type="text/javascript">jQuery( document ).ready(function() { adminCookieBar("' . $admin_url . '"); });</script>' . PHP_EOL;
}
add_action('wp_footer', 'add_admin_cookie_bar_meta_tag', 1000);