<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Plugin Name: PE Theme Elements Info - get info about sidebars and widgets types
 * Plugin URI: http://pixelemu.com
 * Description: Get info about visible sidebars and widgets types and easily find them in the dashboard in <a href="widgets.php">Appearance -> Widgets</a>
 * Version: 1.10
 * Author: pixelemu.com
 * Author URI: http://www.pixelemu.com
 * Text Domain: pe-check
 * License: http://www.pixelemu.com/license.html PixelEmu Proprietary Use License
 */

if ( !class_exists ( 'PEthemeElementsInfo' ) ) {
	class PEthemeElementsInfo {

		function __construct() {
			if( ! is_admin() ) {
				add_filter( 'dynamic_sidebar_params', array($this, 'filter_sidebar'), 10, 1 );
				add_filter( 'wp_nav_menu', array($this, 'filter_menu'), 10, 2 );
				add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
			}
		}

		/**
		 * Add styles
		 */
		public function styles() {
			wp_enqueue_style( 'font-awesome-free', '//use.fontawesome.com/releases/v5.15.1/css/all.css' );
			wp_enqueue_style( 'pe-theme-elements-info', plugin_dir_url( __FILE__ ) . 'css/pe-theme-elements-info.css', array(), '1.10' );
		}

		function check_user_admin() {
			if( is_user_logged_in() ) {
				$user = wp_get_current_user();
				$roles = (array) $user->roles;
				if( in_array( 'administrator', $roles, true ) ) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		/**
		 * Update widget markup
		 */
		function filter_sidebar( $params ) {

			if( $this->check_user_admin() ) {
				$sidebar = $params[0]['name'];
				$type    = $params[0]['widget_name'];

				$badge = '<div class="pe-theme-elements-info-code">
							<span class="fa fa-info-circle" aria-hidden="true"></span>
							<div class="pe-theme-elements-info-content">
								<div class="pe-theme-elements-info-label"><label>Sidebar:</label> ' . $sidebar . '</div>
								<div class="pe-theme-elements-info-type"><label>Type:</label> ' . $type . '</div>
							</div>
						</div>';
				
				$params[0]['before_widget'] = $params[0]['before_widget'] . $badge;
			}
			return $params;
		}

		/**
		 * Update menu markup
		 */
		function filter_menu( $nav_menu, $args ) {

			if( $this->check_user_admin() ) {
				$menu = $args->menu->name;

				$badge = '<div class="pe-theme-elements-info-code">
							<span class="fa fa-info-circle" aria-hidden="true"></span>
							<div class="pe-theme-elements-info-content">
								<div class="pe-theme-elements-info-label"><label>Menu:</label> ' . $menu . '</div>
							</div>
						</div>';

				$nav_menu = $badge . $nav_menu;
			}

			return $nav_menu;
		}

	}
}
new PEthemeElementsInfo(); ?>