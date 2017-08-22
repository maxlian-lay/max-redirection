<?php
	/*
	*	Plugin Name: Max Redirector
	*	Description: Simple Wordpress plugin for redirecting users based on their IP
	*	Version: 1.0.0
	*	Author: Maxlian Lay
	*	Author URI: Contact at maxlian.lay@gmail.com
	*	Tested up to: 4.8.1
	*/

	require dirname(__DIR__).'/max-redirector/vendor/autoload.php';
	use GeoIp2\Database\Reader;
	require_once __DIR__ . '/includes/class-mr-loads.php';
	require_once __DIR__ . '/includes/class-mr-metabox.php';
	$reader = new Reader(dirname(__DIR__).'/max-redirector/geolite-country.mmdb');
	$client_ip = $_SERVER['REMOTE_ADDR'];
	$current_path = $_SERVER["PHP_SELF"];
	$current_path_explode = explode("/", $current_path);
	$no_redir = false;
	$arr = array("wp-login.php","wp-admin");
	// if (in_array("wp-login.php", $current_path_explode)) {
	if ( count(array_intersect($current_path_explode, $arr)) ){
		$no_redir = true;
	}

	if ($client_ip != '::1' && $no_redir == false) {

		// echo 'HOLA';
		$record = $reader->country($client_ip);

		$client_country = $record->country->isoCode;

		// Set the parameter to get the posts from custom post type
		$args = array(
		  'post_type'   => 'mredir',
		  'post_status'    => 'publish'
		);
		$redirection_lists = get_posts( $args );
		// Loop each posts
		foreach ($redirection_lists as $key) {
			// Get the post meta to get each ID from the posts
			$post_meta = get_post_meta($key->ID);
			$mredir_country = $post_meta['country_id'];
			$mredir_url = $post_meta['target_url'];
			// Check if there is a saved country id that match the client country ID
			if( $client_country == $mredir_country[0] ){
				header("Location:".$mredir_url[0]);
				die();
				exit;
			}
		}
	}elseif($client_ip == '::1'){
		echo "You are in localhost";
	}

	function _sprintf($array){
		echo sprintf('<pre>%s</pre>', print_r($array, true));
	}
	
	// print($record->country->isoCode . "\n"); // 'US'