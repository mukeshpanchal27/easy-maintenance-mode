<?php

	/* Exit if accessed directly. */
	if ( !defined( 'ABSPATH' ) ) { exit; }
	
	/* Set Under Construction page */

	$wp_customize->add_setting( 'enable_under_maintenance', array(
		'default' 			=> '0',
		'sanitize_callback' => 'esc_attr'
	) );

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'enable_under_maintenance', array(
		'label'       		=> esc_attr__( 'Under Maintenance', 'easy-maintenance-mode' ),
		'section'     		=> 'add_under_maintenance_section',
		'settings'			=> 'enable_under_maintenance',
		'type'              => 'radio',
		'choices'           => array(
										'1' => esc_html__( 'Yes', 'easy-maintenance-mode' ),
									  	'0' => esc_html__( 'No', 'easy-maintenance-mode' ),
								   	),	
	) ) );

	$wp_customize->add_setting( 'enable_under_maintenance_pages', array(
		'default' 			=> '',
		'sanitize_callback' => 'esc_attr'
	) );


	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'enable_under_maintenance_pages', array(
		'label'       		=> esc_attr__( 'Select Page', 'easy-maintenance-mode' ),
		'section'     		=> 'add_under_maintenance_section',
		'settings'			=> 'enable_under_maintenance_pages',
		'type'             	=> 'dropdown-pages',
		'active_callback'   => 'under_construction_page_callback',
	) ));

	/* Custom Callback Functions */
	if ( ! function_exists( 'under_construction_page_callback' ) ) :
		function under_construction_page_callback( $control ) {
			if ( $control->manager->get_setting( 'enable_under_maintenance' )->value() == '1' ) {
		        return true;
		    } else {
		    	return false;
		    }
		}
	endif;