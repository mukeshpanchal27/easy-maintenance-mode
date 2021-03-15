<?php
/*
Plugin Name: Easy Maintenance Mode
Plugin URI: https://mukeshpanchal27.com/
Description: Let's people know that your site is temporarily under maintenance and will back shortly.
Version: 1.3
Author: Mukesh Panchal
Author URI: https://mukeshpanchal27.com/
Text Domain: easy-maintenance-mode
*/

?>
<?php

  	// Exit if accessed directly.
	if ( !defined( 'ABSPATH' ) ) { exit; }

	/* Define constant */
	defined( 'Wp_Easy_Maintenance_Mode_Dir_Root' ) or define( 'Wp_Easy_Maintenance_Mode_Dir_Root', plugin_dir_path( __FILE__ ) );


	if( !class_exists( 'Wp_Easy_Maintenance_Mode_Easy_Maintenance_Mode' ) ) {
		class Wp_Easy_Maintenance_Mode_Easy_Maintenance_Mode {
	    	
	    	// Construct
	    	public function __construct() {
	    		add_action( 'plugins_loaded', array( $this, 'wpkoder_load_plugin_textdomain' ) );
				add_action( 'admin_init', array( $this, 'wpkoder_init' ) );

				/* For Admin Page*/
				add_action( 'admin_menu', array( $this, 'wpkoder_register_admin_menu_page' ) );

				/* For Customizer */
				add_action( 'customize_register', array( $this, 'wpkoder_add_customizer_sections' ) );

		      	require_once( Wp_Easy_Maintenance_Mode_Dir_Root.'/extend-options/extend-options.php' );

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

			/* Register a Easy Maintenance Mode menu page. */
			function wpkoder_register_admin_menu_page() {
				add_menu_page( 
					esc_html__( 'Easy Maintenance Mode', 'easy-maintenance-mode' ),
					esc_html__( 'Easy Maintenance Mode', 'easy-maintenance-mode' ),
					'manage_options',
					admin_url( '/customize.php?autofocus[section]=add_under_maintenance_section' ),
					'',
					'dashicons-admin-tools',
					80
				);
			}

		    public function wpkoder_add_customizer_sections( $wp_customize ) {

				/* Add General layout Section */

			    $wp_customize->add_section( 'add_under_maintenance_section', array(
					'title' 	 => esc_html__( 'Under Maintenance Setting', 'easy-maintenance-mode' ),
					'capability' => 'manage_options',
					'priority'	 => 200
				) );

			    require_once( Wp_Easy_Maintenance_Mode_Dir_Root.'/customizer/under-maintenance-settings.php' );
		    }
		} // end of class

		$Wp_Easy_Maintenance_Mode_Easy_Maintenance_Mode = new Wp_Easy_Maintenance_Mode_Easy_Maintenance_Mode();	

	} // end of class_exists