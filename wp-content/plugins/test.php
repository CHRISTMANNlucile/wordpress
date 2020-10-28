<?php
/*
Plugin Name:    Mfields Github Shortcode
Plugin URI:     null
Description:    Display recent commits from a Github repository.
Version:        0.1
Author:         Michael Fields
Author URI:     http://wordpress.mfields.org/
License:        GPLv2 or later

Copyright 2011 Michael Fields
*/
class github extends WP_Widget 
{
  
  public function __construct()
  {
      parent::__construct('test', 'github', array( 'description' => 'Affiche les 10 derniers commit'));
     
  }


function github_commits_shortcode( $atts ) {
	$capability = 'read';

	$atts = shortcode_atts( array(
		'branch' => null,
	), $atts );

	if ( null === $atts['branch'] ) {
		if ( current_user_can( $capability ) ) {
			return '<p>Github Shortcode: No branch given.</p>';
		}
		return;
	}

	$transient_key = 'gh_commit' . md5( $atts['branch'] );

	$cached = get_transient( $transient_key );

	if ( false !== $cached ) {
		return $cached .= "\n" . '<!--Returned from transient cache.-->';
	}

	$remote = wp_remote_get( esc_url( 'http://github.com/api/v2/json/commits/list/' . $atts['branch'] ) );

	if ( is_wp_error( $remote ) ) {
		if ( current_user_can( $capability ) ) {
			return '<p>Github Shortcode: Github is unavailable.</p>';
		}
		return;
	}

	if ( '200' != $remote['response']['code'] ) {
		if ( current_user_can( $capability ) ) {
			return '<p>Github Shortcode: Github responded with an HTTP status code of ' . esc_html( $remote['response']['code'] ) . '.</p>';
		}
		return;
	}

	$data = json_decode( $remote['body'] );	

	if ( ! isset( $data->commits ) ) {
		if ( current_user_can( $capability ) ) {
			return '<p>No commits could be found.</p>';
			return '<p>Github Shortcode: No commits could be found for this branch.</p>';
		}
		return;
	}

	$output = '';
	$output .= "\n" . '<table class="github-api github-commits">';
	$output .= "\n" . '<thead>';
	$output .= "\n\t" . '<tr><th>Time</th><th>Commit Message</th></tr>';
	$output .= "\n" . '</thead>';
	$output .= "\n" . '<tbody>';

	foreach( $data->commits as $i => $commit ) {
		if ( $i > 4 ) {
			continue;
		}
		$url = 'https://github.com' . $commit->url;
		$message = $commit->message;
		$time = get_date_from_gmt( date( 'Y-m-d H:i:s', strtotime( $commit->committed_date ) ), 'U' );

		$time_human = 'About ' . human_time_diff( $time, get_date_from_gmt( date( 'Y-m-d H:i:s' ), 'U' ) ) . ' ago';
		$time_machine = date( 'Y-m-d\TH:i:s\Z', $time );
		$time_title_attr = date( get_option( 'date_format' ) . ' at ' . get_option( 'time_format' ), $time );

		$output .= "\n\t" . '<tr>';
		$output .= '<td><time title="' . esc_attr( $time_title_attr ) . '" datetime="' . esc_attr( $time_machine ) . '">' . esc_html( $time_human ) . '</time></td>';
		$output .= '<td><a href="' . esc_url( $url ) . '">' . esc_html( $message ) . '</td>';
		$output .= '</tr>';
	}

	$output .= "\n" . '</tbody>';
	$output .= "\n" . '</table>';

	set_transient( $transient_key, $output, 600 );

	return $output;
}
}
add_shortcode( 'github-commits', 'github_commits_shortcode' );