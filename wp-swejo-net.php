<?php
/*
Plugin Name: &#1041;&#1091;&#1090;&#1086;&#1085;&#1080; &#1079;&#1072; Svejo.net
Plugin URI: http://kaloyan.info/blog/wp-svejo-net-plugin/
Description: &#1058;&#1086;&#1079;&#1080; &#1087;&#1083;&#1098;&#1075;&#1080;&#1085; &#1097;&#1077; &#1074;&#1080; &#1087;&#1086;&#1084;&#1086;&#1075;&#1085;&#1077; &#1083;&#1077;&#1089;&#1085;&#1086; &#1080; &#1073;&#1098;&#1088;&#1079;&#1086; &#1076;&#1072; &#1087;&#1086;&#1089;&#1090;&#1072;&#1074;&#1077;&#1090;&#1077; &#1073;&#1091;&#1090;&#1086;&#1085;&#1080;&#1090;&#1077; &#1079;&#1072; &#1075;&#1083;&#1072;&#1089;&#1091;&#1074;&#1072;&#1085;&#1077; &#1085;&#1072; <a target="_blank" href="http://svejo.net">Svejo.net</a> (&#1087;&#1086;&#1074;&#1077;&#1095;&#1077; &#1079;&#1072; &#1073;&#1091;&#1090;&#1086;&#1085;&#1080;&#1090;&#1077; &#1084;&#1086;&#1078;&#1077; &#1076;&#1072; &#1087;&#1088;&#1086;&#1095;&#1077;&#1090;&#1077;&#1090;&#1077; &#1085;&#1072; &#1090;&#1086;&#1079;&#1080; &#1072;&#1076;&#1088;&#1077;&#1089;: <a target="_blank" href="http://svejo.net/reception/button">http://svejo.net/reception/button</a>)
Author: &#1050;&#1072;&#1083;&#1086;&#1103;&#1085; &#1050;. &#1062;&#1074;&#1077;&#1090;&#1082;&#1086;&#1074;
Version: 0.3.4 
Author URI: http://kaloyan.info/
*/

/////////////////////////////////////////////////////////////////////////////

/**
* @internal prevent from direct calls
*/
if (!defined('ABSPATH')) {
	return ;
	}

/**
* @internal prevent from second inclusion
*/
if (!isset($wp_svejo_net)) {

	/**
	* Initiating the plugin...
	* @see wp_svejo_net
	*/
	$wp_svejo_net = new wp_svejo_net;
	}

/////////////////////////////////////////////////////////////////////////////

/**
* The tag for the manual mode
*/
define('WP_SVEJO_NET_TAG', '[wp:svejo-net]');

/**
* "Butoni za Svejo.net" WordPress Plugin
*
* @author Kaloyan K. Tsvetkov <kaloyan@kaloyan.info>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/
Class wp_svejo_net {

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

	/**
	* Constructor
	*
	* This constructor attaches the needer plugin hook callbacks
	*/
	function wp_svejo_net() {

		// attach the handler
		//
		if (!strstr($_SERVER['PHP_SELF'], 'wp-admin')) {
			add_action('the_content',
				array(&$this, '_content'),
				12
				);
			}

		// attach to admin menu
		//
		if (is_admin()) {
			add_action('admin_menu',
				array(&$this, '_menu')
				);
				
			add_action('admin_head',
				array(&$this, '_head')
				);
			}
		
		// attach to plugin installation
		//
		add_action(
			'activate_' . str_replace(
				DIRECTORY_SEPARATOR, '/',
				str_replace(
					realpath(ABSPATH . PLUGINDIR) . DIRECTORY_SEPARATOR,
						'', __FILE__
					)
				),
			array(&$this, 'install')
			);
		}
	
	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
	
	/**
	* UTF8-safe substr()
	* @param string $string
	* @param integer $from
	* @param integer $length
	* @return string
	*/
	Function utf8_substr($string, $from, $length){

		$chunk = 35;
		if ($length > $chunk) {
			$result = '';
			$c = ceil($length/$chunk);
			for ($i = 0; $i < $c; $i++) {
				$result .= $this->utf8_substr(
					$string,
					$from + $i * $chunk,
					($i+1 == $c ) ? $length % $chunk : $chunk
					);
				}
			return $result;
			}		
		
		return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$length.'}).*#s',
                       '$1',$string);
		}

	/**
	* Adds the `onError` handlers to the images
	*
	* @param string $content
	* @return string
	*/
	function _content($content) {

		// feed detected - give up ...
		//
		if (is_feed()) {
			return str_replace(WP_SVEJO_NET_TAG, '', $content);
			}

		// read the settings only once
		//
		static $_;
		if (!isset($_)) {
			$_ = (array) get_option('wp_svejo_net_settings');
			}

		// is this the correct URL ?
		//
		if (!((is_home() && $_['pages']['is_home'])
				|| (is_single() && $_['pages']['is_single'])
				|| (is_page() && $_['pages']['is_page'])
				|| (is_category() && $_['pages']['is_category'])
				|| (is_tag() && $_['pages']['is_tag'])
				|| (is_date() && $_['pages']['is_date'])
				|| (is_search() && $_['pages']['is_search'])
			)){
		     	return str_replace(WP_SVEJO_NET_TAG, '', $content);
		     	}
		
		// nothing found ?
		//
		$manual = strstr($content, WP_SVEJO_NET_TAG) !== false;
		if (!$_['append'] && !$manual) {
			return $content;
			}

		// the css classes ?
		//
		$css = 'svejo ';
		if (is_home()) {
			$css .= ' svejo_home ';
			}
		if (is_single()) {
			$css .= ' svejo_single ';
			}
		if (is_category()) {
			$css .= ' svejo_category ';
			}
		if (is_tag()) {
			$css .= ' svejo_tag ';
			}
		if (is_date()) {
			$css .= ' svejo_date ';
			}
		if (is_search()) {
			$css .= ' svejo_search ';
			}		

		global $post;

		// the button
		//
		$button = $_['append_pre'] . '<span class="' . $css . '"><script type="text/javascript">
<!--// [wp-svejo-net, v' . wp_svejo_net::version() . ']
var svejo_url = \'' . get_permalink($post->ID) . '\';
var svejo_title = \'' . addCSlashes($post->post_title, "'\"\r\t\n\\") . '\';
var svejo_topic = \'\';
var svejo_summary = \'' . addCSlashes(
	$this->utf8_substr(
		preg_replace(
			'~\s+~',
			' ',
			strip_tags(
				$post->post_excerpt
					? $post->post_excerpt
					: $post->post_content
				)
			),
		0, 450),
		"'\"\r\t\n\\") . '\';
var svejo_skin = \'' . (
	in_array($_['skin'], array('standard', 'compact'))
		? $_['skin']
		: 'standard') . '\';
var svejo_theme = \'' . (
	in_array($_['theme'], array('standard', 'blue', 'red', 'green', 'black'))
		? $_['theme']
		: 'standard') . '\';
var site_charset = \'' . addSlashes(get_option('blog_charset')) .'\';
var svejo_bgcolor = \'' . $_['bgcolor'] . '\';

if (navigator.userAgent.indexOf(\'MSIE\') > -1) {
	svejo_title = svejo_summary = \'\';
	}

//-->
</script><script src="http://svejo.net/javascripts/svejo_button.js" type="text/javascript"></script></span>' . $_['append_post'];


		// manual ?
		//
		if ($manual) {
			return str_replace(
				WP_SVEJO_NET_TAG,
				$button,
				$content
				);
			}

		// auto-append
		//
		$horizontal = in_array(
				$_['append_position_horizontal'], array('left', 'right')
				)
			? $_['append_position_horizontal']
			: 'left';
			
		switch($_['append_position_vertical']) {
			case 'top' :
				$button = '<span style="float:' . $horizontal . ';'
					. ($horizontal === 'left'
						? 'padding-right: 1em;'
						: 'padding-left: 1em;'
						)
					. '">' . $button . '</span>';
				break;
			default :
				$button = '<div style="text-align:' . $horizontal . ';'
					. '">' . $button . '</div>';
				break;
			}

		return ($_['append_position_vertical'] == 'top')
			? ($button . $content)
			: ($content . $button);
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

	/**
	* Performs the routines required at plugin installation: 
	* in general introducing the settings array
	*/	
	function install() {
		add_option(
			'wp_svejo_net_settings',
				array(
					'skin' => 'standard',
					'theme' => 'standard',
					'bgcolor' => 'FFFFFF',
					
					'append' => false,
					'append_position_horizontal' => 'left',
					'append_position_vertical' => 'bottom',
					'append_pre' => '',
					'append_post' => '',
					
					'pages' => array(
						'is_home' => 1,
						'is_single' => 1,
						'is_page' => 0,
						'is_category' => 0,
						'is_tag' => 0,
						'is_date' => 0,
						'is_search' => 0,
						),
					
					'external_js' => 0,
				)
			);
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	
	/**
	* Attach the menu page to the `Options` tab
	*/
	function _menu() {
		add_submenu_page('options-general.php',
			 '&#1041;&#1091;&#1090;&#1086;&#1085;&#1080; &#1079;&#1072; Svejo.net',
			 '&#1041;&#1091;&#1090;&#1086;&#1085;&#1080; &#1079;&#1072; Svejo.net', 8,
			 __FILE__,
			 array($this, 'menu')
			);
		}

	/**
	* Attach the admin header
	*/
	function _head() {
		require_once(
			dirname(__FILE__)
				. '/wp-swejo-net.head.php'
			);		
		}

	/**
	* Handles and renders the menu page
	*/
	function menu() {
		require_once(
			dirname(__FILE__)
				. '/wp-swejo-net.menu.php'
			);
		}
	
	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

	/**
	* Get the version of the plugin
	* @access public
	*/
	Function version() {
		if (preg_match("~Version\:\s*(.*)\s*~i", file_get_contents(__FILE__), $R)) {
			return trim($R[1]);
			}
		return '$Rev: 66798 $';
		}

	//--end-of-class
	}



/////////////////////////////////////////////////////////////////////////////

?>