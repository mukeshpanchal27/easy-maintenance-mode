<?php
/**
 * Extend Theme Option
 */

 /* Force All Page To Under Construction If "Under Maintenance" is enable */

if ( ! function_exists( 'wpkoder_get_address' ) ) {
    function wpkoder_get_address() {
        // return the full address
        return wpkoder_get_protocol().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    } // end function wpkoder_get_address
}

if ( ! function_exists( 'wpkoder_get_protocol' ) ) {
    function wpkoder_get_protocol() {
        // Set the base protocol to http
        $wpkoder_protocol = 'http';
        // check for https
        if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {
            $wpkoder_protocol .= "s";
        }
        
        return $wpkoder_protocol;
    } // end function wpkoder_get_protocol
}

add_action( 'template_redirect', 'wpkoder_force_under_construction' );
if ( ! function_exists( 'wpkoder_force_under_construction' ) ) {
    function wpkoder_force_under_construction() {

        $wpkoder_userrequest = str_ireplace( home_url('/'), '', wpkoder_get_address() );
        $wpkoder_userrequest = rtrim( $wpkoder_userrequest, '' );
        $wpkoder_enable_under_maintenance = get_theme_mod( 'enable_under_maintenance', 0 );
        if ( $wpkoder_enable_under_maintenance == 1 && !current_user_can( 'level_10' ) && get_theme_mod( 'enable_under_maintenance_pages' ) != '0' ) { 
            $wpkoder_do_redirect = '';
            if( get_option( 'permalink_structure' ) ){
                $get_page = get_page_uri( get_theme_mod( 'enable_under_maintenance_pages' ) );
                $wpkoder_do_redirect = '/'.$get_page;

            } else {
                //echo get_theme_mod('enable_under_maintenance_pages');die;
                $wpkoder_getpost = get_page_uri( get_theme_mod( 'enable_under_maintenance_pages' ) );
                if ($wpkoder_getpost) {
                    $wpkoder_do_redirect = '/?page_id='.get_theme_mod( 'enable_under_maintenance_pages' );
                }
            }
            
            /* Added Contact Form 7 Compatibility */
            if( strpos( $wpkoder_userrequest, '/contact-form-7/v1' ) !== false ) {
                return;
            }
            if( !preg_match( "/login|admin|dashboard|account/i", $wpkoder_userrequest ) > 0 ) {
                
                // Make sure it gets all the proper decoding and rtrim action
                $wpkoder_userrequest = str_replace( '*', '(.\*)', $wpkoder_userrequest );
                $wpkoder_pattern = '/^' . str_replace( '/', '\/', rtrim( $wpkoder_userrequest, '/' ) ) . '/';
                $wpkoder_do_redirect = str_replace( '*', '$1', $wpkoder_do_redirect );
                $output = preg_replace( $wpkoder_pattern, $wpkoder_do_redirect, $wpkoder_userrequest );
                if ( $output !== $wpkoder_userrequest ) {
                    // pattern matched, perform redirect
                    $wpkoder_do_redirect = $output;
                }

            } else {
                // simple comparison redirect
                $do_redirect = $wpkoder_userrequest;
            }
            
            if( $wpkoder_do_redirect !== '' && trim( $wpkoder_do_redirect, '/' ) !== trim( $wpkoder_userrequest, '/' ) ) {
                // check if destination needs the domain prepended

                if( strpos( $wpkoder_do_redirect, '/' ) === 0 ) {
                    $wpkoder_do_redirect = home_url('/').$wpkoder_do_redirect;
                }

                header ( 'Location: ' . $wpkoder_do_redirect );
                exit();
                
            }
        }
    }
}


 /* To Add Under Construction Notice To Admin bar For All Logged User */
if ( ! function_exists( 'wpkoder_admin_bar_under_construction_notice' ) ) {
    function wpkoder_admin_bar_under_construction_notice() {
        global $wp_admin_bar;
        $wpkoder_enable_under_maintenance = get_theme_mod('enable_under_maintenance', 0 );
        if ( $wpkoder_enable_under_maintenance == 1 ) {
            $wp_admin_bar->add_menu( array(
                'id'     => 'admin-bar-under-construction-notice',
                'parent' => 'top-secondary',
                'href'   => esc_url( home_url( '/' ) ).'wp-admin/customize.php?autofocus%5Bsection%5D=add_under_maintenance_section',
                'title'  => '<span style="color: #FF0000;">'.esc_html__( 'Under Construction', 'easy-maintenance-mode' ).'</span>'
            ) );
        }
    }
}
add_action( 'admin_bar_menu', 'wpkoder_admin_bar_under_construction_notice' );