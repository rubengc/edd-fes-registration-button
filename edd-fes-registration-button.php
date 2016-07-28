<?php
/**
 * Plugin Name:     EDD - FES Registration Button
 * Plugin URI:      https://wordpress.org/plugins/edd-fes-registration-button/
 * Description:     Adds [fes_registration_button] shortcode to allow already logged users apply to become a vendor with a simple click
 * Version:         1.0.0
 * Author:          rubengc
 * Author URI:      http://rubengc.com
 * Text Domain:     fes-registration-button
 *
 * @package         EDD\FESRegistrationButton
 * @author          rubengc
 * @copyright       Copyright (c) rubengc
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'EDD_FES_Registration_Button' ) ) {

    /**
     * Main EDD_FES_Registration_Button class
     *
     * @since       1.0.0
     */
    class EDD_FES_Registration_Button {

        /**
         * @var         EDD_FES_Registration_Button $instance The one true EDD_FES_Registration_Button
         * @since       1.0.0
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      object self::$instance The one true EDD_FES_Registration_Button
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new EDD_FES_Registration_Button();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'EDD_FES_REGISTRATION_BUTTON_VER', '1.0.0' );

            // Plugin path
            define( 'EDD_FES_REGISTRATION_BUTTON_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'EDD_FES_REGISTRATION_BUTTON_URL', plugin_dir_url( __FILE__ ) );
        }

        public function locate_template( $template_name, $load = false, $require_once = true ) {
            // No file found yet
            $located = false;

            // Continue if template is empty
            if ( ! empty( $template_name ) ) {
                $template_name .= '.php';

                // Trim off any slashes from the template name
                $template_name = ltrim( $template_name, '/' );
                // try locating this template file by looping through the template paths
                foreach( self::get_template_paths() as $template_path ) {
                    if ( file_exists( $template_path . $template_name ) ) {
                        $located = $template_path . $template_name;
                        break;
                    }
                }
            }
        
            if ( ( true == $load ) && ! empty( $located ) ){
                load_template( $located, $require_once );
            }
            return $located;
        }

        private function get_template_paths() {
            $fes_template_dir = EDD_FES()->templates->fes_get_theme_template_dir_name();
            $edd_template_dir = edd_get_theme_template_dir_name();
            $file_paths = array(
                1 => trailingslashit( get_stylesheet_directory() )  . $fes_template_dir,
                10 => trailingslashit( get_stylesheet_directory() ) . $edd_template_dir,
                100 => trailingslashit( get_template_directory() )  . $fes_template_dir,
                1000 => trailingslashit( get_template_directory() ) . $edd_template_dir,
                10000 => EDD_FES_REGISTRATION_BUTTON_DIR . 'templates'
            );

            // sort the file paths based on priority
            ksort( $file_paths, SORT_NUMERIC );
            return array_map( 'trailingslashit', $file_paths );
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            // Include scripts
            require_once EDD_FES_REGISTRATION_BUTTON_DIR . 'includes/scripts.php';
            require_once EDD_FES_REGISTRATION_BUTTON_DIR . 'includes/shortcodes.php';
            require_once EDD_FES_REGISTRATION_BUTTON_DIR . 'includes/ajax.php';
        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = EDD_FES_REGISTRATION_BUTTON_DIR . '/languages/';
            $lang_dir = apply_filters( 'edd_fes_registration_button_languages_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), 'edd-fes-registration-button' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'edd-fes-registration-button', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/edd-fes-registration-button/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/edd-fes-registration-button/ folder
                load_textdomain( 'edd-fes-registration-button', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/edd-fes-registration-button/languages/ folder
                load_textdomain( 'edd-fes-registration-button', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'edd-fes-registration-button', false, $lang_dir );
            }
        }
    }
} // End if class_exists check


/**
 * The main function responsible for returning the one true EDD_FES_Registration_Button
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      \EDD_FES_Registration_Button The one true EDD_FES_Registration_Button
 *
 * @todo        Inclusion of the activation code below isn't mandatory, but
 *              can prevent any number of errors, including fatal errors, in
 *              situations where your extension is activated but EDD is not
 *              present.
 */
function edd_fes_registration_button() {
    return EDD_FES_Registration_Button::instance();
}
add_action( 'plugins_loaded', 'edd_fes_registration_button' );


/**
 * The activation hook is called outside of the singleton because WordPress doesn't
 * register the call from within the class, since we are preferring the plugins_loaded
 * hook for compatibility, we also can't reference a function inside the plugin class
 * for the activation function. If you need an activation function, put it here.
 *
 * @since       1.0.0
 * @return      void
 */
function edd_fes_registration_button_activation() {
    /* Activation functions here */
}
register_activation_hook( __FILE__, 'edd_fes_registration_button_activation' );
