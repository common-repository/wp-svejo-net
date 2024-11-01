<?php
/**
* "Butoni za Svejo.net" WordPress Plugin
*
* This is the wp-admin administration page for managing the settings for this plugin
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

/**
* @see wp_admin_helpers
*/
require_once(
	dirname(__FILE__) . '/wp-admin-helpers.php'
	);

/////////////////////////////////////////////////////////////////////////////

	// sanitize referrer
	//
	$_SERVER['HTTP_REFERER'] = preg_replace(
		'~&saved=.*$~Uis','', $_SERVER['HTTP_REFERER']
		);
	
	// information updated ?
	//
	if ($_POST['submit']) {
		
		// sanitize
		//
		$_POST['wp_svejo_net_settings'] = (array) $_POST['wp_svejo_net_settings'];
		array_walk($_POST['wp_svejo_net_settings'],
			create_function('$k, &$v',' $v = stripSlashes($v);')
			);

		// save
		//
		update_option(
			'wp_svejo_net_settings',
			$_POST['wp_svejo_net_settings']
			);

		die("<script>document.location.href = '{$_SERVER['HTTP_REFERER']}&saved=settings:" . time() . "';</script>");
		}

	// operation report detected
	//
	if (@$_GET['saved']) {
		
		list($saved, $ts) = explode(':', $_GET['saved']);
		if (time() - $ts < 10) {
			echo '<div class="updated"><p>';

			switch ($saved) {
				case 'settings' :
					echo '&#1053;&#1072;&#1089;&#1090;&#1088;&#1086;&#1081;&#1082;&#1080;&#1090;&#1077; &#1089;&#1072; &#1079;&#1072;&#1087;&#1072;&#1079;&#1077;&#1085;&#1080;.';
					break;
				}

			echo '</p></div>';
			}
		}

	// read the settings
	//
	$wp_svejo_net_settings = get_option('wp_svejo_net_settings');

?>
<form method="post">
<div class="wrap">

	<h2>Бутони за Svejo.net &rarr; Настройки</h2>

	<div id="poststuff">
		<div class="submitbox">

			<div id="previewview"></div>

			<div class="inside">

			<div class="wp-svejo-net-preview">
				Стандартен бутон<br />
<div id="bgcolor_standard" style="padding: 6px; background-color: #<?php echo $wp_svejo_net_settings['bgcolor']; ?>;">
<script type="text/javascript">
<!--// 
svejo_url = 'http://kaloyan.info/blog/wp-svejo-net-plugin/';
svejo_title = 'WordPress &#1087;&#1083;&#1098;&#1075;&#1080;&#1085; &#1079;&#1072; Svejo.net &#1073;&#1091;&#1090;&#1086;&#1085;&#1080;';
svejo_topic = '';
svejo_summary = '&#1057; &#1090;&#1086;&#1079;&#1080; WordPress &#1087;&#1083;&#1098;&#1075;&#1080;&#1085; &#1074;&#1080;&#1077; &#1084;&#1086;&#1078;&#1077;&#1090;&#1077; &#1083;&#1077;&#1089;&#1085;&#1086; &#1080; &#1073;&#1098;&#1088;&#1079;&#1086; &#1076;&#1072; &#1087;&#1086;&#1089;&#1090;&#1072;&#1074;&#1103;&#1090;&#1077; &#1079;&#1072; Svejo.net &#1073;&#1091;&#1090;&#1086;&#1085;&#1080;';
svejo_skin = 'standard';
svejo_theme = '<?php echo $wp_svejo_net_settings['theme']; ?>';
svejo_bgcolor = '<?php echo $wp_svejo_net_settings['bgcolor']; ?>';
//-->
</script>
<script src="http://svejo.net/javascripts/svejo_button.js" type="text/javascript"></script>
</div></div>

			<div class="wp-svejo-net-preview">
				Компактен бутон<br />
<div id="bgcolor_compact" style="padding: 6px; background-color: #<?php echo $wp_svejo_net_settings['bgcolor']; ?>;">
<script type="text/javascript">
<!--// 
svejo_url = 'http://kaloyan.info/blog/wp-svejo-net-plugin/';
svejo_title = 'WordPress &#1087;&#1083;&#1098;&#1075;&#1080;&#1085; &#1079;&#1072; Svejo.net &#1073;&#1091;&#1090;&#1086;&#1085;&#1080;';
svejo_topic = '';
svejo_summary = '&#1057; &#1090;&#1086;&#1079;&#1080; WordPress &#1087;&#1083;&#1098;&#1075;&#1080;&#1085; &#1074;&#1080;&#1077; &#1084;&#1086;&#1078;&#1077;&#1090;&#1077; &#1083;&#1077;&#1089;&#1085;&#1086; &#1080; &#1073;&#1098;&#1088;&#1079;&#1086; &#1076;&#1072; &#1087;&#1086;&#1089;&#1090;&#1072;&#1074;&#1103;&#1090;&#1077; &#1079;&#1072; Svejo.net &#1073;&#1091;&#1090;&#1086;&#1085;&#1080;';
svejo_skin = 'compact';
svejo_theme = '<?php echo $wp_svejo_net_settings['theme']; ?>';
svejo_bgcolor = '<?php echo $wp_svejo_net_settings['bgcolor']; ?>';
//-->
</script>
<script src="http://svejo.net/javascripts/svejo_button.js" type="text/javascript"></script>
</div></div>

				<p><strong>Облик</strong></p>

				<table class="wp-svejo-net-look">
					<tr>
						<th>
							<label for="wp_svejo_net_settings_skin">Форма</label>
						</th>
						<td>
							<select id="wp_svejo_net_settings_skin" name="wp_svejo_net_settings[skin]">
							<?php wp_admin_helpers::html_options(
								array(
									'standard'=>'стандартнa',
									'compact'=>'компактнa'
									),
								$wp_svejo_net_settings['skin']
								); ?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<label for="wp_svejo_net_settings_theme">&#1058;&#1077;&#1084;&#1072;:</label><br />
						</th>
						<td>
							<select id="wp_svejo_net_settings_theme" name="wp_svejo_net_settings[theme]"
								onChange="button_reload(this.form);">
							<?php wp_admin_helpers::html_options(
								array(
									'standard'=>'стандартна',
									'blue'=>'синя',
									'red'=>'червена',
									'green'=>'зелена',
									'black'=>'черна',
									),
								$wp_svejo_net_settings['theme']
								); ?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<label for="wp_svejo_net_settings_bgcolor">&#1060;&#1086;&#1085;:</label><br />
						</th>
						<td> #
						<input id="wp_svejo_net_settings_bgcolor" name="wp_svejo_net_settings[bgcolor]" onBlur="button_reload(this.form);"
							maxlength="6" value="<?php echo $wp_svejo_net_settings['bgcolor']; ?>" style="width:100px;"/>
			
						<script type="text/javascript">
						jQuery(document).ready(
							function() {
								new Control.ColorPicker("wp_svejo_net_settings_bgcolor", {IMAGE_BASE: "<?php
							echo './../wp-content/plugins/' . basename(dirname(__FILE__)) . '/colorpicker/img/'; ?>"});
								}
							);

						</script>
						</td>
					</tr>
				</table>

				<p><strong>Разположение</strong></p>
				<p>Изберете къде да се показва бутона в постовете на блога ви.</p>

				<table class="wp-svejo-net-look">
					<tr>
						<th>
							<label for="wp_svejo_net_settings_append_position_horizontal">
								Хоризонтално:
							</label>
						</th>
						<td>
						<select id="wp_svejo_net_settings_append_position_horizontal"
								name="wp_svejo_net_settings[append_position_horizontal]">
						<?php wp_admin_helpers::html_options(
							array('left'=>'вляво','right'=>'вдясно'),
							$wp_svejo_net_settings['append_position_horizontal']
							); ?>
						</select></td>
					</tr>
					<tr>
						<th>
							<label for="wp_svejo_net_settings_append_position_vertical">
								Вертикално:
							</label>
						</th>
						<td>
						<select id="wp_svejo_net_settings_append_position_vertical"
							name="wp_svejo_net_settings[append_position_vertical]">
						<?php wp_admin_helpers::html_options(
							array('top'=>'горе','bottom'=>'долу'),
							$wp_svejo_net_settings['append_position_vertical']
							); ?>
						</select></td>
					</tr>
				</table>

			</div>

			<p class="submit">
				<input type="submit" name="submit" id="save-post" value="Запази" class="button button-highlighted" />
				<br class="clear" />
			</p>

			<div class="side-info">
				<h5>Версия</h5>
				<p>Бутони за Svejo.net, версия <?php echo wp_svejo_net::version();?></p>

				<h5>Повече информация</h5>
				<ul>
				<li><a href="http://kaloyan.info/blog/wp-svejo-net-plugin/">Страницата на проекта</a>
				<li><a href="http://svejo.net/reception/button">Описание на бутоните</a>
				<li><a href="http://svejo.net/">Svejo.net</a>
				<li><a href="http://kaloyan.info">Kaloyan.info</a>
				</ul>

				<p style="text-align: center; margin: 20px 0;"><a
					href="http://svejo.net" title="Svejo.net"><img alt="Svejo.net"
					src="http://wp-svejo-net.googlecode.com/svn/trunk/svejo.png"/></a></p>
				
			</div>
		</div>

	<div id="post-body">

		<p>
			От тази страница може да настроите как да работе 
			бутоните на <em>Svejo.net</em> на вяшятя страница. За 
			повече информация посете тази <a 
			href="http://kaloyan.info/blog/wp-svejo-net-plugin/" 
			title="Бутони за Svejo.net" 
			target="_blank">страница</a>.
		</p>

		<h2>Автоматично добавяне</h2>
		<p class="note">
			Една от възможностите, които предлага този плъгин, е бутоните за 
			<em>Svejo.net</em> да се добавят автоматично към всички материали публикувани на 
			вашия блог. Ако искате да се възползвате от този вариант, използвайте 
			тази форма за да укажете къде точно искате да се покажат бутоните (ако 
			искате вие да контролирате къде се появяват бутоните прочетете секцията 
			<a href="#howto">"Бутони за Svejo.net: Начин на използване"</a>).
		</p>
	
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="wp_svejo_net_settings_append">
						Автоматично добавяне:
					</label><br />
				</th>
				<td>
				<select style="width:150px;" id="wp_svejo_net_settings_append" name="wp_svejo_net_settings[append]">
				<?php wp_admin_helpers::html_options(
					array(0=>'не',1=>'да'),
					$wp_svejo_net_settings['append']
					); ?>
				</select><br />
				Дали да се добавят автоматично бутоните или не. 
				</td>
			</tr>
	
		</table>



		<h2>Страници</h2>
		<p class="note">
			От тук може да контролирате на кои части от блога да се показват 
			бутоните и на кои не. 
		</p>
	
		<table class="form-table">
			<?php
			$_pages = array(
				'is_home' => array(
					'Първа страница:',
					'Това е първата страница на блога ви, където обикновенно се показват най-новите ви постове.'
					),
				'is_single' =>  array(
					'Блог постове:',
					'Това са страниците, на които се показват индивидуално всеки пост (и коментарите му).'
					),
				'is_page' =>  array(
					'Самостоятелни страници:',
					'Това са "статичните" страници, които например "About Me" и "За Мен" страниците.'
					),
				'is_category' =>  array(
					'Архиви по категории:',
					'Това са страниците, чрез които разглеждате постовете, подредени по категории.'
					),
				'is_tag' =>  array(
					'Архиви по етикети (тагове):',
					'Това са страниците, чрез които разглеждате постовете, подредени по етикети(тагове).'
					),
				'is_date' =>  array(
					'Архиви по дати:',
					'Това са страниците, чрез които разглеждате постовете, подредени по дата (по година, по месец, по ден)'
					),
				'is_search' =>  array(
					'Резултати от търсене:',
					'Това са страниците, на които се покзават резултатите от търсенето в постовете от вашия блог.'
					),
				);
			
			foreach ($_pages as $k=>$v) {
				?>
			<tr>
				<th scope="row">
					<label for="wp_svejo_net_settings_pages_<?php echo $k;?>"><?php echo $v[0];?></label>
				</th>
				<td>
				<select style="width:150px;" id="wp_svejo_net_settings_pages_<?php echo $k;?>"
					name="wp_svejo_net_settings[pages][<?php echo $k;?>]">
				<?php wp_admin_helpers::html_options(
					array(0=>'не',1=>'да'),
					$wp_svejo_net_settings['pages'][$k]
					); ?>
				</select><br />
				<?php echo $v[1];?>
				</td>
			</tr>
			<? } ?>
		</table>

		<h2>Оформление</h2>
		<p class="note">
			Ако искате да "облечете" бутоните в допълнителен HTML код,
			използвайте следващите две настройки: 
		</p>
	
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="wp_svejo_net_settings_append_pre">
						HTML код за показване преди бутона:
					</label><br />
				</th>
				<td>
				<textarea id="wp_svejo_net_settings_append_pre"
					name="wp_svejo_net_settings[append_pre]"
					><?php echo htmlSpecialChars($wp_svejo_net_settings['append_pre']); ?></textarea><br />
				HTML код, който ще бъде отпечатан преди бутоните. 
				</td>
			</tr>
			
			<tr>
				<th scope="row">
					<label for="wp_svejo_net_settings_append_post">
						HTML код за показване след бутона:
					</label><br />
				</th>
				<td>
				<textarea id="wp_svejo_net_settings_append_post"
					name="wp_svejo_net_settings[append_post]"
					><?php echo htmlSpecialChars($wp_svejo_net_settings['append_post']); ?></textarea><br />
				HTML код, който ще бъде отпечатан след бутоните.
				</td>
			</tr>
		</table>
	</div>

</div>

<br />
<br />
<br />
<br />

<a name="howto"></a>
<h2>Бутони за Svejo.net &rarr; Начин на използване</h2>

<div style="width: 76%;">
<p>
	Поставянето на бутоните за <em>Svejo.net</em> изобщо не е трудно, но 
	автоматизирането на тази дейност може да ви спести малко време, а и 
	малко неприятности (като например да поставите бутоните в темата която 
	използвате и да се налага да ги копирате отново ако смените темата).
</p>
	
<blockquote>
	<p>
	Начините (режимите) на използване са два: "ръчен" и "автоматичен".
	</p>
</blockquote>

<p>
	При <em>автоматичния режим</em>, бутоните се добавят автоматично към 
	всики материали от блога ви. Забележете, че те се добавят чак при 
	отпечатването, и по никакъв начин не променят съдържанието на 
	материалите, запаметено в базата данни. Недостатък на този режим, е че 
	бутоните, ще се появат на всички страници и не можете да укажете, ако 
	искате някои от страниците да нямат бутон.
</p>

<p>
	При <em>ръчния режим</em>, вие сами контролирате на кои страници да се 
	показват бутоните, като поставяте малки псевдо тагове, ето така:
</p>

<blockquote>
	<pre style="background:#BFDFFF; padding: 1em; "><?php echo WP_SVEJO_NET_TAG; ?></pre>
</blockquote>

<p>
	При този режим имате пълен контрол - сами може да определите дали да 
	сложите бутона или не, и ако решите да го сложите, имате възможност да 
	го "засадите" където пожелаете в страниците си. Ето един пример:
</p>
	
<blockquote>
	<pre style="background:#BFDFFF; padding: 1em; ">Здравейте,
това е кратък увод, след който ще дойде реда да поставя
бутона на който ще се радвам да гласувате.

<b><?php echo WP_SVEJO_NET_TAG; ?></b>

Сега, да продължим&hellip;</pre></blockquote>

<p>
	Ако използвате и двата режима (автоматичния режим е включен, и въпреки 
	това поставите псевдо-таг), "ръчния" режим ще има по-голям приоритет, и 
	бутонът ще се покаже там, където сте поставили <code><?php echo WP_SVEJO_NET_TAG; ?></code> таг-а, a 
	за сметка на това, автоматичното поставяне на бутона няма да се включи.
</p>

<p>
	За повече информация посете тази <a
	href="http://kaloyan.info/blog/wp-svejo-net-plugin/"
	title="Бутони за Svejo.net" target="_blank">страница</a>.
</p>
</div>

</div>
</form>