<?php
/*
Plugin Name: Easy Maintenance Mode
Plugin URI: http://www.wpkoder.com
Description: Let's people know that your site is temporarily under maintenance and will back shortly.
Version: 1.0
Author: wpkoder Team
Author URI: http://www.wpkoder.com
Text Domain: easy-maintenance-mode
*/

?>
<?php

  	// Exit if accessed directly.
	if ( !defined( 'ABSPATH' ) ) { exit; }

	/* Define constant */
	defined( 'Wpkoder_Dir_Root' ) or define( 'Wpkoder_Dir_Root', plugin_dir_path( __FILE__ ) );


	if( !class_exists( 'Wpkoder_Easy_Maintenance_Mode' ) ) {
		class Wpkoder_Easy_Maintenance_Mode {
	    	
	    	// Construct
	    	public function __construct() {
	    		add_action( 'plugins_loaded', array( $this, 'wpkoder_load_plugin_textdomain' ) );
				add_action( 'admin_init', array( $this, 'wpkoder_init' ) );

				/* For Customizer */
				add_action( 'customize_register', array( $this, 'wpkoder_add_customizer_sections' ) );

		      	require_once( Wpkoder_Dir_Root.'/extend-options/extend-options.php' );

			}

			/* Load plugin textdomain. */
		    public function wpkoder_load_plugin_textdomain() {
		      load_plugin_textdomain( 'easy-maintenance-mode', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' ); 
		    }

			public function wpkoder_init() {

				/* Check current user has capability or role. */
	    		if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
					return;
				}
			}

		    public function wpkoder_add_customizer_sections( $wp_customize ) {

				/* Add General layout Section */

			    $wp_customize->add_section( 'add_under_maintenance_section', array(
					'title' 	 => esc_attr__( 'Under Maintenance Setting', 'easy-maintenance-mode' ),
					'capability' => 'manage_options',
					'priority'	 => 200
				) );

			    require_once( Wpkoder_Dir_Root.'/customizer/under-maintenance-settings.php' );
		    }
		} // end of class

		$Wpkoder_Easy_Maintenance_Mode = new Wpkoder_Easy_Maintenance_Mode();	

	} // end of class_exists