<?php
/*
 * Plugin Name: Sproose Remote Vote Button
 * Plugin URI:
 * Description: Add a remote vote button to the bottom of each post.
 * Version: 1.0
 * Author: Sproose Inc.
 * Author URI:
 * 
 * Copyright 2007  Sproose Inc.  (email : feedback@sproose.com)
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


function sproose_remote_vote_button_add_pages() {
    add_options_page('sproose_romte_vote_button', 'Sproose Remote Vote Button', 'manage_options', _FILE_, 'print_sproose_romte_vote_button_options_form');
}

function print_sproose_romte_vote_button_options_form() {
	$sproose_buttons = array();
	$sproose_buttons['srvb1'] = 'Sproose It Up! (big, javascript, iframe)';
	$sproose_buttons['srvb2'] = 'Sproose It Up! (middle, javascript)';
	$sproose_buttons['srvb3'] = 'Sproose It Up! (small, javascript)';
	$sproose_buttons['srvb4'] = 'Sproose It Up! (small, without javascript)';

	$ok = false;	

	if ($_REQUEST['submit']){
		foreach ($sproose_buttons as $key => $data) {
			if ($_REQUEST[$key]=="1"){
				update_option($key,"1");
				$ok = true;
			}else{
				update_option($key,"0");
				$ok = true;
			}
		}

		if ($ok){
			?><div id="message" class="updated fade">
				<p>Options Saved</p>
				</div> <?php


		}else{
			?><div id="message" class="error fade">
				<p>Faied to Save Options</p>
				</div> <?php
		}
	}
	
	?>
	<div class="wrap">
	<h2><?php _e('Sproose It Up Options') ?></h2>
	<form method="post">
	<p class="submit"><input type="submit" name="submit" value="Submit" /></p>
	<ul> <?php 
	if 	(!empty($sproose_buttons)){
	foreach ($sproose_buttons as $key => $data) {
		?>	
			<li> 
				<label for="<?php echo $key ?>"> 
				<input name="<?php echo $key ?>" type="checkbox" id="<?php echo $key ?>" value="1" <?php checked('1', get_option($key)); ?>/> 
				<?php echo $data ?>
				</label> 
			</li>

			<?php
		}
	}
	?>
	</ul> 
	<p class="submit"><input type="submit" name="submit" value="Submit" /></p>
	</form>
	</div>
	<?
	
}



function sproose_remote_vote_button_link()
{
	$link = urlencode(get_permalink());
	$title = urlencode(the_title('', '', false));

	$sproose_buttons_content = array(
		'srvb1' => array(
			'content' => "<script language=\"javascript\">\n".
			"sprooseItUp_buttonType = '1';\n".
			"sprooseItUp_url = '".$link."';\n".
			"sprooseItUp_query = '".$title."';\n".
			"</script>".
			"<script type=\"text/javascript\" src=\"http://www.sproose.com/js/sprooseItUp.js\">".
			"</script>"
			, 'visible' => get_option('srvb1')

		)
		,'srvb2' => array(
			'content' => "<script language=\"javascript\">\n".
			"sprooseItUp_buttonType = '2';\n".
			"sprooseItUp_url = '".$link."';\n".
			"sprooseItUp_query = '".$title."';\n".
			"</script>".
			"<script type=\"text/javascript\" src=\"http://www.sproose.com/js/sprooseItUp.js\">".
			"</script>"
			, 'visible' => get_option('srvb2')
		)
		,'srvb3' => array(
			'content' => "<script language=\"javascript\">\n".
			"sprooseItUp_buttonType = '3';\n".
			"sprooseItUp_url = '".$link."';\n".
			"sprooseItUp_query = '".$title."';\n".
			"</script>".
			"<script type=\"text/javascript\" src=\"http://www.sproose.com/js/sprooseItUp.js\">".
			"</script>",
			'visible' => get_option('srvb3')
		)
		,'srvb4' => array(
			'content' => '<a href="http://www.sproose.com/remoteVote?url='.$link.'&query='.$title.'" title="Sproose it up!" target="_blank"><img src="http://www.sproose.com/favicon.ico" style="float:none"/></a>'
			, 'visible' => get_option('srvb4')
		)
	);
	$links = array();
	unset($links);
	foreach ($sproose_buttons_content as $key => $data) {
		if ($data['visible'] == '1'){
			$links[$key] = $data['content'];
		}
	}

	return '<p><span>'
		. implode("\n", $links)
		. "</span></p>";
}

function set_sproose_remote_vote_button_options(){
	$sproose_buttons = array();
	unset($sproose_buttons);
	$sproose_buttons['srvb1'] = 'Sproose It Up! (big, javascript)';
	$sproose_buttons['srvb2'] = 'Sproose It Up! (middle, javascript)';
	$sproose_buttons['srvb3'] = 'Sproose It Up! (small, javascript)';
	$sproose_buttons['srvb4'] = 'Sproose It Up! (small, without javascript)';


	foreach ($sproose_buttons as $key => $data) {
		add_option($key,'0',$key);
	}
}

function unset_sproose_remote_vote_button_options(){
	$sproose_buttons = array();
	unset($sproose_buttons);
	$sproose_buttons['srvb1'] = 'Sproose It Up! (big, javascript)';
	$sproose_buttons['srvb2'] = 'Sproose It Up! (middle, javascript)';
	$sproose_buttons['srvb3'] = 'Sproose It Up! (small, javascript)';
	$sproose_buttons['srvb4'] = 'Sproose It Up! (small, without javascript)';
	
	foreach ($sproose_buttons as $key => $data) {
		delete_option($key);
	}
}


function sproose_remote_vote_button($content)
{

	return "$content\n".sproose_remote_vote_button_link();
}


if (function_exists('add_action')) {
	// Hook for adding admin menus
	add_action('admin_menu', 'sproose_remote_vote_button_add_pages');
	add_action('the_content', 'sproose_remote_vote_button');
}


register_activation_hook(_FILE_,'set_sproose_remote_vote_button_options');
register_deactivation_hook(_FILE_, 'unset_sproose_remote_vote_button_options')

?>
