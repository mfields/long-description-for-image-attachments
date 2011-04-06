<?php
/*
Plugin Name:       Long Description
Description:       Automatically adds a londesc attribute to images when inserting into post content with the media uploader.
Version:           1.2
Author:            Michael Fields
Author URI:        http://wordpress.mfields.org/
License:           GPLv2

Copyright 2010-2011  Michael Fields  michael@mfields.org

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
define( 'LONGDESC_DIR', trailingslashit( dirname( __FILE__ ) ) );


/**
 * Load Template.
 *
 * The ID for an image attachment is expected to be
 * passed via $_GET['longdesc']. If this value exists
 * and a post is successfully queried, postdata will
 * be prepared and a template will be loaded to display
 * the post content.
 *
 * This template must be named "longdesc-template.php".
 *
 * First, this function will look in the child theme
 * then in the parent theme and if no template is found
 * in either theme, the default template will be loaded
 * from the plugin's folder.
 *
 * This function is hooked into the "template_redirect"
 * action and terminates script execution.
 *
 * @return    void
 *
 * @since     2010-09-26
 * @alter     2011-03-27
 */
function longdesc_template() {
	
	/* Return early if there is no reason to proceed. */
	if ( ! isset( $_GET['longdesc'] ) ) {
		return;
	}

	global $post;

	/* Get the image attachment's data. */
	$id = absint( $_GET['longdesc'] );
	$post = get_post( $id );
	if ( is_object( $post ) ) {
		setup_postdata( $post );
	}

	/* Attachment must be an image. */
	if ( false === strpos( get_post_mime_type(), 'image' ) ) {
		header( 'HTTP/1.0 404 Not Found' );
		exit;
	}

	/* The whole point here is to NOT show an image :) */
	remove_filter( 'the_content', 'prepend_attachment' );

	/* Check to see if there is a template in the theme. */
	$template = locate_template( array( 'longdesc-template.php' ) );
	if ( ! empty( $template ) ) {
		require_once( $template );
		exit;
	}
	/* Use plugin's template file. */
	else {
		require_once( LONGDESC_DIR . 'longdesc-template.php' );
		exit;
	}

	/* You've gone too far! */
	header( 'HTTP/1.0 404 Not Found' );
	exit;
}
add_action( 'template_redirect', 'longdesc_template' );


/**
 * Anchor.
 *
 * Create anchor id for linking from a Long Description to referring post.
 * Also creates an anchor to return from Long Description page.
 *
 * @param     int       ID of the post which contains an image with a longdesc attribute.
 * @return    string
 *
 * @since     2010-09-26
 */
function longdesc_return_anchor( $id ) {
	return 'longdesc-return-' . $id;
}


/**
 * Add Attribute.
 *
 * Add longdesc attribute when WordPress sends image to the editor.
 * Also creates an anchor to return from Long Description page.
 *
 * @return    string
 *
 * @since     2010-09-20
 * @alter     2011-04-06
 */
function longdesc_add_attr( $html, $id, $caption, $title, $align, $url, $size, $alt ) {

	/* Get data for the image attachment. */
	$image = get_post( $id );

	if ( isset( $image->ID ) && !empty( $image->ID ) ) {
		$args = array( 'longdesc' => $image->ID );
		/* The referrer is the post that the image is inserted into. */
		if ( isset( $_GET['post_id'] ) ) {
			$args['referrer'] = (int) $_GET['post_id'];
		}
		$search = 'title="' . $title . '"';
		$replace = $search . ' longdesc="' . esc_url( add_query_arg( $args, home_url() ) ) . '"';
		$html = str_replace( $search, $replace, $html );
		$html.= '<a id="' . esc_attr( longdesc_return_anchor( $image->ID ) ) . '"></a>';
	}

	return $html;
}
add_filter( 'image_send_to_editor', 'longdesc_add_attr', 10, 8 );


/* Backward compatibility with previous versions. */
add_action( 'wp_ajax_longdesc', 'longdesc' );
add_action( 'wp_ajax_nopriv_longdesc', 'longdesc' );