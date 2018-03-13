<?php
require_once dirname (__FILE__) . '/inc/generate.php'; 
function nb_sub_entries() {
	if (!nb_check_access())  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}?>
		<style>
		.plugin_rebranding{
            height: 300px;
            width:700px; 
			background: url(<?php echo plugins_url('/image/infobar.png', __FILE__);   ?>) no-repeat;
border: 2px solid #ffece0;
		}
		.ink_learn_infobar{
          bottom: 0px;	
          margin-top: 240px;
          float: right;
          cursor: pointer;

		}
		</style>
		<div class="plugin_rebranding" > <a target="_blank" href="http://www.inkthemes.com/plugin/wordpress-notification-plugin/" > <img class="ink_learn_infobar" src=" <?php echo plugins_url('/image/infobar-button.png', __FILE__);   ?> "> </a> </div>
		<?php
 
        echo '<div class="wrap"><div id="icon-edit-comments" class="icon32"></div><h2>' . __('Notifications entries','info-bar');		
	if (isset($_REQUEST['edit'])) {
	echo ' <a class="button-secondary" href="admin.php?page=pd_nb_entries" title="' . __('New entry','info-bar') . '">' . __('New entry','info-bar') . '</a></h2>';
	} else {
		echo '</h2>';
	}
	echo '<b><p class="notify"><a href="http://www.inkthemes.com/wp-themes/infoway-wordpress-theme-with-built-in-lead-capture/" target="_blank">InfoWay WordPress Theme</a> that is already have integrated with Notification bar system. So if you are looking for some very unique way of promoting your website InfoWay is the great option.</p></b>';
	echo '<h4>Useful Plugin that help you to improve your website</h4>
	<ul>	
	<li><a href="http://wordpress.org/plugins/formget-contact-form/" target="_blank">FormGet Contact Form</a></li>
	<li><a href="http://wordpress.org/plugins/sliding-contact-form-by-formget/" target="_blank">Sliding Contact Form By FormGet</a></li>
	<li><a href="http://wordpress.org/plugins/contact-form-integrated-with-google-maps/" target="_blank">Contact Form Integrated With Google Maps</a></li>
	</ul>';
	$msg = '';
	if ( !empty($_POST) && check_admin_referer('nb_submit','nb_nonce') ){
		if($_POST['notbar_hidden_post'] == 'Y') {
		$Path = $_SERVER['REQUEST_URI'];
			if($_POST['notbar_hidden_edit'] == 'Y') {
				// entry has been edited, only update the existing entry
				if ( function_exists('w3tc_pgcache_flush') ) {
				w3tc_pgcache_flush();
				$msg .= __(' &amp; W3 Total Cache Page Cache flushed');
				}
				nb_edit_entry($_POST['nb_id']);
				echo '<div id="message" class="updated"><p>' . __('Updated:','info-bar') . ' ' . $_POST['nb_name'] . $msg . '</p></div>';
			} else {
				nb_insert_entry();
				if ( function_exists('w3tc_pgcache_flush') ) {
	                        	w3tc_pgcache_flush();
                        		$msg .= __(' &amp; W3 Total Cache Page Cache flushed');
								
				}
        			echo '<div id="message" class="updated"><p>' . __('Saved:','info-bar') . ' ' . $_POST['nb_name'] . $msg . '</p></div>';
					header('Location:' . $Path);
					exist();
			}
        	}
	}
	if (isset($_REQUEST['delete'])) {
		nb_delete_entry($_REQUEST['id']);
                if ( function_exists('w3tc_pgcache_flush') ) {
                        w3tc_pgcache_flush();
                        $msg .= __(' &amp; W3 Total Cache Page Cache flushed');
                }
		echo '<div id="message" class="updated"><p>' . __('Deleted:','info-bar') .' ' . $_REQUEST['id'] . $msg . '</p></div>';
	}
	if (isset($_REQUEST['changestatus'])) {
                nb_change_status($_REQUEST['id'],$_REQUEST['status']);
                if ( function_exists('w3tc_pgcache_flush') ) {
                        w3tc_pgcache_flush();
                        $msg .= __(' &amp; W3 Total Cache Page Cache flushed');
                }
                echo '<div id="message" class="updated"><p>' . __('Status changed:','info-bar') . ' ' . $_REQUEST['id'] . $msg . '</p></div>';
        }

	global $wpdb;
	$table_name = $wpdb->prefix . "nb_data";
	$table_notifications = $wpdb->prefix . "nb_nottypes";
	$myrows = $wpdb->get_results( "SELECT id,timecreated,status,name,epochtimebegin,epochtimeend,content,nottype,fontcolor,bgcolor FROM " . $table_name );
	echo '
	<div class="postbox-container" style="width:70%;">
		<div class="metabox-holder">	
			<div class="meta-box-sortables ui-sortable">
				<div id="entries" class="postbox">
					<div class="handlediv" title="Click to toggle">
						<br>
					</div>
					<h3 class="hndle">
						<span>' . __('Entries','info-bar') . '</span>
					</h3>
					<div class="inside">
						<table id="pd-nb-table" class="widefat" cellspacing="0">';
							echo '
							<thead>
								<tr class="thead">
									<th style="width: 20px;">ID</th>
									<th style="width: 90px;">' . __('Time to display','info-bar') . '</th>
									<th style="width: 90px;text-align: center;">' . __('Notification type','info-bar') . '</th>
									<th style="width: 100px;">' . __('Name','info-bar') . '</th>
									<!--<th style="width: 250px;">' . __('Content','info-bar') . '</th>-->
							    	<th style="width: 100px;">' . __('Time created','info-bar') . '</th>
								    <th style="width: 100px;">' . __('Options','info-bar') . '</th>
									<th style="width: 70px;">' . __('Status','info-bar') . '</th>
									<th style="width: 30px;">' . __('Edit','info-bar') . '</th>
					            </tr>
					        </thead>
							<tfoot>
									<tr class="tfoot">
									<th style="width: 20px;">ID</th>
									<th style="width: 100px;">' . __('Time to display','info-bar') . '</th>
									<th style="width: 100px;text-align: center;">' . __('Notification type','info-bar') . '</th>
									<th style="width: 100px;">' . __('Name','info-bar') . '</th>
									<!--<th style="width: 250px;">' . __('Content','info-bar') . '</th>-->
									<th style="width: 100px;">' . __('Time created','info-bar') . '</th>
									<th style="width: 100px;">' . __('Options','info-bar') . '</th>
									<th style="width: 70px;">' . __('Status','info-bar') . '</th>
									<th style="width: 30px;">' . __('Edit','info-bar') . '</th>
								</tr>
							</tfoot>';

							foreach ($myrows as $myrows) {
								if (empty($myrows->epochtimebegin)) {
									$timebegin = __('Not set','info-bar');
								} else {
									$timebegin = nb_get_time($myrows->epochtimebegin);
								}
								if (empty($myrows->timeend)) {
									$timeend = __('Not set','info-bar');
		                        } else {
    			                    $timeend = nb_get_time($myrows->epochtimeend);
        	    			    }
								if (empty($myrows->epochtimebegin) && empty($myrows->epochtimeend)) {
									$timedisplay = '<b>' . __('Always visible','info-bar') . '</b>';
								} else {
									$timedisplay = '<b>Start:</b><br />' . nb_get_time($myrows->epochtimebegin) . '<br /><b>End:</b><br />' . nb_get_time($myrows->epochtimeend);
								}
								if ($myrows->status == 1) {
									if(empty($myrows->epochtimebegin) || ($myrows->epochtimebegin < nb_get_tzepoch_time(time())) && ($myrows->epochtimeend > nb_get_tzepoch_time(time()))) {
										$status = __('Enabled','info-bar');
										$rowcolor = "lightgreen";
									} else {
										$status = __('Enabled - (Disabled by schedule)','info-bar');
										$rowcolor = "#ffebe8";
									}
									$changestatus = 0;
	            	            } else {
									$changestatus = 1;
									$status = __('Disabled','info-bar');
									$rowcolor = "#ffebe8";
			              		}
								if (empty($myrows->bgcolor)) {
									$bgcolor = __('Not overruled','info-bar');
								} else {
									$bgcolor = $myrows->bgcolor;
								}
								if (empty($myrows->fontcolor)) {
									$fontcolor = __('Not overruled','info-bar');
								} else {
									$fontcolor = $myrows->fontcolor;
								}
								if (empty($myrows->nottype)) {
									$nottypename = __('Not set','info-bar');
									$nottypeimage = '';
								} else {
									$nottype = $myrows->nottype;
									$nottypename = $wpdb->get_var( "SELECT name FROM " . $table_notifications . " WHERE id = " . $nottype );
									$nottypeimage = '<img src="' . $wpdb->get_var( "SELECT icon FROM " . $table_notifications . " WHERE id = " . $nottype ) . '">';
								}
								$timecreated = nb_get_time(strtotime($myrows->timecreated));
								$deletelink = 'admin.php?page=pd_nb_entries&amp;delete&amp;id=' . $myrows->id;
								$changestatuslink = 'admin.php?page=pd_nb_entries&amp;changestatus&amp;id=' . $myrows->id . '&amp;status=' . $changestatus;
								$editlink = 'admin.php?page=pd_nb_entries&amp;edit&amp;id=' . $myrows->id;
								echo '<tr style="background: ' . $rowcolor . '";"><td><a href="' . $deletelink . '"><img src="' . NB_URL . '/css/images/admin/delete.png"></a>' . $myrows->id . '</td><td>' . $timedisplay . '</td><td style="text-align: center;font-size: 10px;">' . $nottypeimage . '<br />' . $nottypename . '</td><td><a href="' . $editlink . '" title="' . esc_html(stripslashes($myrows->content)) . '">' . $myrows->name . '</a></td><!--<td>' . esc_html(stripslashes($myrows->content)) . '</td>--><td>' . $timecreated . '</td><td><b>BC:</b><br />' . $bgcolor . '<br /><b>FC:</b><br />' . $fontcolor . '<td><a href="' . $changestatuslink . '">' . $status . '</a></td><td><a href="' . $editlink . '"><img src="' . NB_URL . '/css/images/admin/edit.png"> </a></td></tr>';
							}
							echo '
						</table>
					</div>
				</div>';
	

				// reset values to default
				$beginvalue = '';
				$endvalue = '';
				$namevalue = '';
				$contentvalue = '';
				$edithidden = 'N';
				$notstatusvalue = '1';
				$idhidden = '';
				$nottypevalue = '';
				// check if we are in edit mode
				if (isset($_REQUEST['edit'])) {
					$table_name = $wpdb->prefix . "nb_data";
					$editrow = $wpdb->get_results( "SELECT * FROM " . $table_name . ' WHERE id = ' . $_REQUEST['id'] );
					foreach ($editrow as $editrow) {
						$beginvalue = nb_get_edit_time($editrow->epochtimebegin);
						$endvalue = nb_get_edit_time($editrow->epochtimeend);
						$namevalue = $editrow->name;
						$contentvalue = stripslashes($editrow->content);
						$edithidden = "Y";
						$idhidden = $editrow->id;
						$bgcolorvalue = $editrow->bgcolor;
						$fontcolorvalue = $editrow->fontcolor;
						$nottypevalue = $editrow->nottype;
						$notstatusvalue = $editrow->status;
					}
				}
	echo '<b><p class="notify"><a href="http://www.inkthemes.com/wp-themes/infoway-wordpress-theme-with-built-in-lead-capture/" target="_blank">InfoWay WordPress Theme</a> that is already have integrated with Notification bar system. So if you are looking for some very unique way of promoting your website. InfoWay is the great option.</p></b>';
				echo '
				<div id="entry" class="postbox">
					<div class="handlediv" title="Click to toggle">
						<br>
					</div>
					<h3 class="hndle">
						<span>' . __('New/Edit Top Notification InfoBar','info-bar') . '</span>
					</h3>
					<div class="inside">
							<form method="POST" action="admin.php?page=pd_nb_entries">
							<input type="hidden" name="notbar_hidden_post" value="Y">
							<input type="hidden" name="notbar_hidden_edit" value="' . $edithidden . '">
							<input type="hidden" name="nb_id" value="' . $idhidden . '">';
							wp_nonce_field('nb_submit','nb_nonce');
				    	   
							echo '	
							<h4>Notification InfoBar Setting</h4>';
							nb_createinput('text',array(
								'id' => 'nb_name',
								'name' => 'nb_name',
								'label' => __('InfoBar Name','info-bar'),
								'desc' => __('Set unique name for the Notification InfoBar','info-bar'),
								'sel' => $namevalue,
								'size' => 30,
								'mand' => true
							));
							echo '<br class="clear">';				
							if (get_option('pd_nb_gui_mce','false') == 'true') {
								echo '<label class="textinput" for="nb_content">' . __('Content','info-bar') . '<span> *</span>: </label>
								<div id="poststuff">'; ?>
								<?php the_editor($contentvalue,$id="nb_content", $media_buttons = false, false); ?>
								<?php echo '</div>';
							} else {
								nb_createinput('multitext',array(
								'id' => 'nb_content',
								'name' => 'nb_content',
								'label' => __('Top InfoBar Content','info-bar'),
								'desc' => __('Text to be displayed on the top panel of InfoBar, Support HTML','info-bar'),
								'sel' => $contentvalue,
								'rows' => 3,
								'cols' => 75,
								'mand' => true
								));
							}
							echo '<br class="clear">
							<br/>';
							// Get all notification types
							$table_notifications = $wpdb->prefix . "nb_nottypes";
							$nottypes = $wpdb->get_results( "SELECT id,name,bgcolor,fontcolor FROM " . $table_notifications . " ORDER BY name");
							echo '
							<h4>Type Of InfoBar settings</h4>';
							nb_createinput('select',array(
								'id' => 'nb_nottype',
								'name' => 'nb_nottype',
								'label' => __('Notification type','info-bar'),
								'desc' => __('If no notification type is set, the default settings will be used as set in the settings panel','info-bar'),
								'sel' => $nottypevalue,
								'values' => $nottypes
							));
							echo '<br class="clear">';
							nb_createinput('color',array(
								'id' => 'nb_bgcolor',
								'class' => 'color',
								'name' => 'nb_bgcolor',
								'label' => __('Bar color','info-bar'),
								'desc' => __('Background color of the Info bar on a per message base. This overrules the color from the chosen notification type','info-bar'),
								'sel' => $bgcolorvalue,
								'size' => 8
							));
							echo '<br class="clear">';
							nb_createinput('color',array(
								'id' => 'nb_fontcolor',
								'name' => 'nb_fontcolor',
								'label' => __('Font color','info-bar'),
								'desc' => __('Font color of the info bar on a pre message base. This overrules the color from the chosen notification type','info-bar'),
								'sel' => $fontcolorvalue,
								'size' => 8
							));
							echo '<br class="clear">';
							nb_createinput('radio',array(
								'id' => 'nb_status',
								'name' => 'nb_status',
								'label' => __('Status','info-bar'),
								'values' => array('1','0'),
								'labels' => array( __('Enabled','info-bar'),__('Disabled','info-bar')),
								'sel' => $notstatusvalue,
								'after' => '<br />'
							));
							echo '<br class="clear"><br />
							<p class="submit">
								<input class="infobar_entries" id="infobar_entries" type="submit" name="Submit" value="' . __('Save','info-bar') . '" /> 
							</p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>';
	echo nb_get_info_box();
	echo '</div>';
}
?>