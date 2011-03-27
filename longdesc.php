<?php
/*
Plugin Name: Long Description
Version: 1.1
Author: Michael Fields
Author URI: http://wordpress.mfields.org/
License: GPLv2

Copyright 2010  Michael Fields  michael@mfields.org

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

load_plugin_textdomain( 'longdesc', false, dirname( plugin_basename( __FILE__ ) ) );

/**
 * The directory where the longdesc plug-in is installed.
 * @since 2010-09-26
 */
define( 'LONGDESC_DIR', dirname( __FILE__ ) . '/' );


/**
 * Generate a html document that displays only post_content.
 * @uses $_GET['id'] int post ID.
 * @return void.
 * @since 2010-09-26
 */
function longdesc_template() {
	if( isset( $_GET['longdesc'] ) ) {
		global $post;
		$content = '';
		$id = (int) abs( $_GET['longdesc'] );
		$post = get_post( $id );
		if( isset( $post->post_content ) ) {
			remove_filter( 'the_content', 'prepend_attachment' );
			setup_postdata( $post );
			$template = locate_template( array( 'longdesc-template.php' ) );
			if( !empty( $template ) ) {
				require_once( $template );
			}
			else {
				require_once( LONGDESC_DIR . 'longdesc-template.php' );
			}
		}
		else {
			header( 'HTTP/1.0 404 Not Found' );
		}
		exit;
	}
}
add_action( 'template_redirect', 'longdesc_template' );


/**
 * Create anchor id for linking from a Long Description to referring post.
 * Also creates an anchor to return from Long Description page.
 * @param int ID of the post which contains an image with a longdesc attribute.
 * @return string
 * @since 2010-09-26
 */
function longdesc_return_anchor( $id ) {
	return 'longdesc-return-' . $id;
}


/**
 * Add longdesc attribute when WordPress sends image to the editor.
 * Also creates an anchor to return from Long Description page.
 * @uses longdesc_return_anchor()
 * @return string
 * @since 2010-09-20
 * @alter 2010-09-26
 */
function longdesc_add_attr( $html, $id, $caption, $title, $align, $url, $size, $alt ) {
	$id = (int) $id;
	$args = array( 'longdesc' => $id );
	if( isset( $_GET['post_id'] ) ) {
		$args['referrer'] = (int) $_GET['post_id'];
	}
	$url = add_query_arg( $args, home_url() );
	$post = get_post( $id );
	if( isset( $post->post_content ) && !empty( $post->post_content ) ) {
		$search = 'title="' . $title . '"';
		$replace = $search . ' longdesc="' . $url . '"';
		return str_replace( $search, $replace, $html ) . '<a id="' . longdesc_return_anchor( $id ) . '"></a>';
	}
	return $html;
}
add_filter( 'image_send_to_editor', 'longdesc_add_attr', 10, 8 );


/* Backward compatibility with previous versions. */
add_action( 'wp_ajax_longdesc', 'longdesc' );
add_action( 'wp_ajax_nopriv_longdesc', 'longdesc' );
?>