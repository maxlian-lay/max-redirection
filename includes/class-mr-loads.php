<?php
	/*
		 ____________________
		|
		| --- This class is used to load necessary data. (Eg: Categories, Sub-categories, etc..)
		|____________________
  */
	class Mredir_Loads{

	  function __construct(){	
	  	add_action('init', array($this, 'mredir_register_custom_post_type'));
	  }

	  public function mredir_register_custom_post_type(){
	  	$labels = array(
			"name" => "Max Redirection",
			"singular_name" => "Max Redirection",
			);

			$args = array(
				"labels" => $labels,
				"description" => "",
				"public" => true,
				"show_ui" => true,
				"has_archive" => false,
				"show_in_menu" => true,
				"exclude_from_search" => false,
				"capability_type" => "post",
				'supports' => array('title'),
				"map_meta_cap" => true,
				"hierarchical" => false,
				"rewrite" => array( "slug" => "mredir", "with_front" => true ),
				"query_var" => true,
			);
			register_post_type( "mredir", $args );
	  }
	}
	new Mredir_Loads;
