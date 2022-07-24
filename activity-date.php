<?php
/**
 * Plugin Name:       Activity Date
 * Plugin URI:        https://github.com/wdsides/activity-date
 * Description:       Displays the date(s) of a post activity from the &#39;activity-date&#39; and &#39;activity-end-date&#39; post meta fields
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            Will Sides
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       activity-date
 *
 * @package           willsides
 */

function render_block_willsides_activity_date( $attributes, $content, $block ) {
	if ( ! isset( $block->context['postId'] ) ) {
		return '';
	}

	$activity_date = get_post_meta($block->context['postId'], 'activity_date', true);
	$formatted_activity_date = mysql2date( 'F j, Y', $activity_date);
	if ( ! $formatted_activity_date ) {
		return '';
	}
	
	$activity_end_date = get_post_meta($block->context['postId'], 'activity_end_date', true);
	$formatted_end_date = mysql2date( 'F j, Y', $activity_end_date);

	$align_class_name = empty( $attributes['textAlign'] ) ? '' : "has-text-align-{$attributes['textAlign']}";

	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $align_class_name ) );

	$opening_tag = sprintf( 
		'<div %1$s><time datetime="%2$s">%3$s</time>', 
		$wrapper_attributes, 
		$activity_date, 
		$formatted_activity_date );
	
	$closing_tag = ( ! $formatted_end_date ) ? '</div>' : sprintf(
		' - <time datetime="%1$s">%2$s</time></div>',
		$activity_end_date, 
		$formatted_end_date);

	return $opening_tag . $closing_tag;

}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function willsides_activity_date_block_init() {
	register_block_type(
		__DIR__ . '/build' ,
		array(
			'render_callback' => 'render_block_willsides_activity_date',
		)
	);	

	register_post_meta( 'post', 'activity_date', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'default' => 'YYYY-MM-DD'
	) );
	register_post_meta( 'post', 'activity_end_date', array(
		'show_in_rest' => true,
		'single' => true,
		'type' => 'string',
		'default' => 'YYYY-MM-DD'
	) );
}
add_action( 'init', 'willsides_activity_date_block_init' );
