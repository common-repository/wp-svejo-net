<?php
/**
* This is a collection of helpers to build easily the admin panels in WordPress
*
* @version SVN: $Id: wp-admin-helpers.php 56116 2008-07-24 08:17:33Z Mrasnika $
* @author Kaloyan K. Tsvetkov <kaloyan@kaloyan.info>
* @link http://kaloyan.info/blog/wp-admin-helpers/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

/////////////////////////////////////////////////////////////////////////////

/**
* @internal check if some other plugin has already imported this class
*/
if (class_exists('wp_admin_helpers')) {
	return ;
	}

/////////////////////////////////////////////////////////////////////////////

/**
* @internal initiate the session, if not already started
*/
if (!session_id()) {
	session_start();
	}

/////////////////////////////////////////////////////////////////////////////

/**
* Collection of WordPress admin panel helpers
*/
Class wp_admin_helpers {

	/**
	* Render a list of options
	*
	* @param array $options key/value pairs for the options
	* @param mixed $selected key(s) of the selected options
	* @static
	*/
	Function html_options($options, $selected) {
		
		// convert to array in order to
		// allow multiple selected options
		//
		if (is_scalar($selected)) {
			$selected = array($selected);
			} else {
			$selected = (array) $selected;
			}

		// render the options
		//
		foreach ($options as $k=>$v) {
			echo '<option value="', $k , (
				in_array($k, $selected)
					? '" selected="selected"> &rarr; '
					: '">'
				), $v, '&nbsp; </option>';
			}
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	
	/**
	* Render a list of radio buttons
	*
	* @param string $name name for the radio button inputs
	* @param array $options key/value pairs for the options
	* @param mixed $selected key of the selected options; if the selected
	*	value is not found in the keys of the $options argument, then
	*	the first key will be automatically selected
	* @static
	*/
	Function html_radios($name, $options, $selected) {
		
		// check if selection is valid
		//
		if (!in_array($selected, $k = array_keys($options))) {
			$selected = $k[0];
			}
		
		// render the radio buttons
		//
		foreach ($options as $k=>$v) {
			$id = 'hr_' . md5($k . $v);
			echo '<label for="', $id, '">',
				'<input class="chckbx" type="radio" name="' , $name, '" ', (
					$selected == $k
						? ' checked="checked" '
						: '' ),'
				value="', $k , '" id="', $id, '"/>', (
					$selected == $k
						? "<em>{$v}</em>"
						: $v
					), ' </label>';
			}
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Error handling
	*
	* This method serves dual purpose: when an argument is provided, it is 
	* stored to the collection of errors; when there is no argument, the 
	* collection of errors (if any) is rendered
	*
	* @param string $error
	* @param boolean $render whether to render the errors
	* @static
	*/
	Function error($error = null, $render = 0) {
		
		// error container
		//
		static $_errors;
		
		// store new error ?
		//
		if (isset($error)) {
			$_errors[] = $error;
			return true;
			}
		
		// no errors to render ? 
		//
		if (!$_errors) {
			return 0;
			}
		
		// render the errors
		//
		if (!!$render) : ?>
<div class="error">
	<ul>
		<?php foreach ($_errors as $e) {echo '<li>' , $e, '</li>';} ?>
	</ul>
</div>
<?php		endif;

		return 1;
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Halts the execution of current script, and reloads the page to the
	* provided command. The status messages passed as the $code argument,
	* and saved in the session will be shown on the reloaded page if you 
	* invoke it with the {@link wp_admin_helpers::ok()} method
	*
	* @param string $class a name to group the messages under; usually
	*	this is the name of a class
	* @param string $code
	* @param string $command this string is appended to the URL of
	*	the page that will be (re)loaded.
	* @static
	* @see wp_admin_helpers::ok()
	*/
	Function halt($class, $code, $command = '') {

		// no session ?
		//
		if (!session_id()) {
			die('In order to use ' . __CLASS__ . '::'
				. __FUNCTION__
				. '() you got to have the session initiated.');
			}

		$_SESSION[$class][md5($code)] = $code;
		session_write_close();

		// kick the bucket
		//
		wp_admin_helpers::referer_redirect(
			'&saved=' . md5($code). ':' . time()
				. ($command ? "&{$command}" : '')
			,1);
		}

	/**
	* Shows the stored status messages by the {@link wp_admin_helpers::halt()} method.
	*
	* @param string $class a name to group the messages under; usually
	*	this is the name of a class
	* @param integer $ttl time-to-live of the status messages; if omitted,
	*	then default value of 30 seconds is used.
	* @static
	* @see wp_admin_helpers::halt()
	*/	
	Function ok($class, $ttl = 30) {
		
		// no status marker
		//
		if (!$_ = $_REQUEST['saved']) {
			return ;
			}

		list($code, $t) = explode(':', $_);

		// nothing found in session
		//
		if (!$_SESSION[$class][$code]) {
			return;
			}
			
		// message expired ?
		//
		if (time() - $t > $ttl) {
			unset($_SESSION[$class][$code]);
			return;
			}
		
		// render message
		//
		echo '<div class="updated"><p>',
			$_SESSION[$class][$code],
			'</p></div>';
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --

	/**
	* Performs a redirect
	*
	* @param string $url
	* @param boolean $force whether to attempt javasctipt/meta-tag redirection
	* @access public
	* @static
	*/
	Function redirect($url, $force = 1) {
		
		if (!$force && !headers_sent()) {
			header('Location: ' . $url);
			exit;
			}
		
		echo '<html>
<head><meta http-equiv="refresh" content="5;URL=' , $url, '" /></head>
<body><script type="text/javascript">document.location.href = \'', $url, '\';</script></body>
</html>';
		exit;
		}

	/**
	* Performs a redirect using a sanitized http referrer and
	* with some parameters attached to it (if provided)
	*
	* @param string $params extra params
	* @param boolean $force whether to attempt javasctipt/meta-tag redirection
	* @access public
	* @static
	*/
	Function referer_redirect($params = '', $force = 0) {
		wp_admin_helpers::redirect(
			preg_replace('~&saved=.*$~Uis','',
				$_SERVER['HTTP_REFERER']
				) . '&saved=0&' . $params
			,$force);
		}

	//--end-of-class--
	}

/////////////////////////////////////////////////////////////////////////////

?>