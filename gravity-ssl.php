<?php
/*	
Plugin Name: Gravity SSL
Plugin URI: https://github.com/cftp/gravity-ssl
Description: Force all Gravity Forms to be shown over SSL
Version: 1.0
Author: Scott Evans (Code For The People)
Author URI: http://codeforthepeople.com
Network: True
Text Domain: gravity-ssl
Domain Path: /assets/languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Copyright © 2013 Code for the People ltd

                _____________
               /      ____   \
         _____/       \   \   \
        /\    \        \___\   \
       /  \    \                \
      /   /    /          _______\
     /   /    /          \       /
    /   /    /            \     /
    \   \    \ _____    ___\   /
     \   \    /\    \  /       \
      \   \  /  \____\/    _____\
       \   \/        /    /    / \
        \           /____/    /___\
         \                        /
          \______________________/


This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

class gravity_ssl {

	public function __construct() {
		
		if ( !is_admin() ) {

			add_filter('shortcode_atts_gravityforms', array( $this, "shortcode_atts" ) );
			add_filter("gform_shortcode_force_ssl", array( $this, "force_ssl" ), 10, 3);
		
		}
	}

	/**
	 * shortcode_atts
	 *
	 * If the current page is not SSL then add new form action
	 * 
	 * @author Scott Evans
	 * @param  array $atts 
	 * @return array
	 */
	function shortcode_atts($atts) {

		if ( ! is_ssl() )
			$atts['action'] = 'force_ssl';

		return $atts;
	}

	/**
	 * force_ssl 
	 *
	 * Throw error message
	 * 
	 * @param  string $string    
	 * @param  array $attributes
	 * @param  string $content   
	 * @return string            
	 */
	function force_ssl($string, $attributes, $content) {
		return '<div class="alert alert-danger">' . sprintf( __( 'This form must be served over an secure/SSL connection. %1$s Reload over secure connection %2$s.', 'gravity-ssl' ), '<a href="'. set_url_scheme( $this->current_url(), 'https' ) .'">', '</a>' ) . '</div>';
	}

	/**
	 * current_url
	 * 
	 * Determine the current url.
	 * 
	 * @author Scott Evans
	 * @param  boolean $parse
	 * @return string or array
	 */
	public function current_url($parse = false) {
		$s = empty($_SERVER['HTTPS']) ? '' : ($_SERVER['HTTPS'] == 'on') ? 's' : '';
		$protocol = substr(strtolower($_SERVER['SERVER_PROTOCOL']), 0, strpos(strtolower($_SERVER['SERVER_PROTOCOL']), '/')) . $s;
		$port = ($_SERVER['SERVER_PORT'] == '80') ? '' : (":".$_SERVER['SERVER_PORT']);
		if ($parse) {
			return parse_url($protocol . "://" . $_SERVER['HTTP_HOST'] . $port . $_SERVER['REQUEST_URI']);
		} else { 
			return $protocol . "://" . $_SERVER['HTTP_HOST'] . $port . $_SERVER['REQUEST_URI'];
		}
	}
}

global $gravity_ssl;
$gravity_ssl = new gravity_ssl;