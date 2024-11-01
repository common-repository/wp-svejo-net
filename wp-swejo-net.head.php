<?php
/**
* "Butoni za Svejo.net" WordPress Plugin
*
* This is the header for the wp-admin administration page, which adds the necessary assets.
*
* @author Kaloyan K. Tsvetkov <kaloyan@kaloyan.info>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

/////////////////////////////////////////////////////////////////////////////

/**
* @internal prevent from direct calls
*/
if (!defined('ABSPATH')) {
	return ;
	}

/////////////////////////////////////////////////////////////////////////////

	// show only for the wp-svejo-net settings page
	//
	if (strstr($_SERVER['REQUEST_URI'], 'options-general.php?')
			&& strstr($_SERVER['REQUEST_URI'], 'wp-swejo-net.php')
		) {
?>
<!-- [wp-svejo-net] -->
<?php $plugin = './../wp-content/plugins/' . basename(dirname(__FILE__)); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $plugin; ?>/colorpicker/colorPicker.css" />
<script src="<?php echo $plugin; ?>/colorpicker/lib/prototype.js" type="text/javascript"></script>
<script src="<?php echo $plugin; ?>/colorpicker/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script src="<?php echo $plugin; ?>/colorpicker/yahoo.color.js" type="text/javascript"></script>
<script src="<?php echo $plugin; ?>/colorpicker/colorPicker.js" type="text/javascript"></script>

<script type="text/javascript"><!--//
function button_reload(frm) {
	var ifr = document.getElementsByTagName('iframe');
	for (i=0;i<ifr.length;i++) {
		if (ifr[i].src.indexOf('http://svejo.net/tools/button?url=') > -1) {
		
			ifr[i].src = ifr[i].src.substring(
					0,
					ifr[i].src.indexOf('&theme=')
					)
				+ '&theme=' + frm.elements['wp_svejo_net_settings[theme]'].value
				+ ifr[i].src.substring(
					
					ifr[i].src.substring(
						0,
						ifr[i].src.indexOf('&theme=') + 7
						
					
					).indexOf('&')
					);
			ifr[i].src = ifr[i].src.substring(
					0,
					ifr[i].src.indexOf('&bgcolor=')
					)
				+ '&bgcolor=' + frm.elements['wp_svejo_net_settings[bgcolor]'].value
				+ ifr[i].src.substring(
					
					ifr[i].src.substring(
						0,
						ifr[i].src.indexOf('&bgcolor=') + 9
						
					
					).indexOf('&')
					);
			}
		}
	
	var color = frm.elements['wp_svejo_net_settings[bgcolor]'].value
		? ('#' + frm.elements['wp_svejo_net_settings[bgcolor]'].value)
		: '';
	document.getElementById('bgcolor_standard').style.backgroundColor = color;
	document.getElementById('bgcolor_compact').style.backgroundColor = color;
	}

//-->
</script>
<style type="text/css">
td.label-row label {
	display: block;
	}
td.label-row input.chckbx {
	position: relative;
	left: -2px;
	top: 3px;
	}
.wp-svejo-net-preview {
	text-align: center;
	line-height: 24px;
	padding: 0px 10px 10px;
	border: solid 1px silver;
	background: white;
	margin-top: 10px;
	}
table.wp-svejo-net-look {
	width: 100%;
	}
table.wp-svejo-net-look th {
	font-weight: normal;
	text-align: right;
	white-space: nowrap;
	}
table.wp-svejo-net-look td select,
table.wp-svejo-net-look td input {
	width: 97%;
	}
#wp_svejo_net_settings_append_pre,
#wp_svejo_net_settings_append_post {
	width: 97%
	}
</style>

<?php
			}
?>