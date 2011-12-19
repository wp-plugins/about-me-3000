<?php
/*
Plugin Name: About Me 3000
Plugin URI: http://www.webdev3000.com/
Description: Add an "About Me" widget to your sidebar.
Author: Csaba Kissi
Version: 2.1.1
Author URI: http://www.webdev3000.com/
*/


require_once(ABSPATH . '/wp-admin/includes/image.php');

$arr_am_titles = Array("Facebook","Friendfeed","Feedburner","Flickr","Delicious","MySpace","LinkedIn","Posterous","StumbleUpon","Technorati","Twitter","YouTube","Tumblr","Xing","Pinterest");
$arr_am_urls   = Array("http://www.facebook.com/profile.php?id=","http://friendfeed.com/","http://feeds2.feedburner.com/","http://www.flickr.com/photos/","http://delicious.com/","http://www.myspace.com/","http://www.linkedin.com/in/","http://posterous.com/people/","http://YourID.stumbleupon.com","http://technorati.com/people/technorati/","http://twitter.com/","http://www.youtube.com/user/","http://YourID.tumblr.com","http://www.xing.com/profile/",'http://www.pinterest.com/');

// Shows widget
function widget_aboutme($args) {
    global $arr_am_titles, $arr_am_urls;
    extract($args); 
    $options = get_option("widget_aboutme");
    if(empty($options['title'])) $options['title'] = 'About Me';
    echo $before_widget;  
    echo $before_title;  
    echo $options['title'];
    echo $after_title;
    ?>
    <style>
       .aboutme {clear:both}
       .aboutme * {border: 0px solid;}
       .aboutme img {padding:0px;}
    </style>
    <?php
    echo "<div class='aboutme'>";
    if(!empty($options['grav_x']))   $x = $options['grav_x'];
                                else $x = 80;
    if(!empty($options['grav_y']))   $y = $options['grav_y'];
                                else $y = 80;                           
    if(!empty($options['email']) && $options['grav_on'] == 1) echo "<img width=\"".$x."\" height=\"".$y."\" style='float:".(($options["alignright_on"]=='1')?'right':'left').";".(($options["frame_on"]=='1')?'border:1px solid #999;':'')." margin:5px;' src='http://www.gravatar.com/avatar/".md5($options['email'])."?s=80'>";
    if($options['grav_on'] == 2) echo "<img width=\"80\" height=\"80\" style='float:".(($options["alignright_on"]=='1')?'right':'left').";".(($options["frame_on"]=='1')?'border:1px solid #999;':'')." margin:5px;' src='".$options['image_url']."'>";
    echo $options['text'];
    echo "<div style='clear:both'></div>";
    echo "<div style='border-top: 1px solid #eee; padding-top:5px; position:relative; height:25px'>";
    echo "<div style='left:0; position: absolute'>";
    for ($i=0;$i<count($arr_am_titles);$i++) {
        $tag_id = strtolower($arr_am_titles[$i]);
        if($i == 0 && $options['vanityurl_on']) echo "<a style='padding:2px' target='_blank' href='http://www.facebook.com/".$options[$tag_id]."'><img src='".get_bloginfo('wpurl')."/wp-content/plugins/about-me-3000/".$tag_id.".png' border='0'></a>";
        else
        if($i == 8 && $options[$tag_id.'_on'])  echo "<a style='padding:2px' target='_blank' href='http://".$options[$tag_id].".stumbleupon.com/'><img src='".get_bloginfo('wpurl')."/wp-content/plugins/about-me-3000/".$tag_id.".png' border='0'></a>";
        else
        if($i == 12 && $options[$tag_id.'_on'])  echo "<a style='padding:2px' target='_blank' href='http://".$options[$tag_id].".tumblr.com/'><img src='".get_bloginfo('wpurl')."/wp-content/plugins/about-me-3000/".$tag_id.".png' border='0'></a>";
        else   
        if($options[$tag_id.'_on']) echo "<a style='padding:2px' target='_blank' href='".$arr_am_urls[$i]."".$options[$tag_id]."'><img src='".get_bloginfo('wpurl')."/wp-content/plugins/about-me-3000/".$tag_id.".png' border='0'></a>";
    }
    if(!empty($options['email']) && $options['email_on']) {
        $arr_email = explode('@',strtolower($options['email']));
        ?>
<script type="text/javascript">
<!--
    var string1 = "<?php echo $arr_email[0]?>";
    var string2 = "@";
    var string3 = "<?php echo $arr_email[1]?>";
    var string4 = string1 + string2 + string3;
    document.write("<a style='padding:2px' href=" + "mail" + "to:" + string1 + string2 + string3 + ">");
    document.write("<img src='<?php echo get_bloginfo('url')?>/wp-content/plugins/about-me-3000/email.png' border='0'>");
    document.write("</a>");
//-->
</script>
        <?php
        //echo "<a style='padding:2px' href='mailto:".$options['email']."'><img src='".get_bloginfo('url')."/wp-content/plugins/about-me-3000/email.png' border='0'></a>";
    }
    echo "</div>";
    if($options['counter_on']) echo "<div style='right:0; position: absolute;'><img src='http://feeds.feedburner.com/~fc/".$options['feedburner']."'></div>";
    if($options['promote_on']) echo "<div style='left:0; position: absolute; margin-top:18px; padding-left:10px;'><small><a href=\"http://www.webdev3000.com\">Wordpress plugins</a></small></div>";
    echo "</div></div>";    
    echo $after_widget;  
}
// Widget options
function control_aboutme() {
    global $arr_am_titles, $arr_am_urls;
    $options = get_option("widget_aboutme");  
    if (!is_array( $options )) {
        $options = array(
        'title' => 'About Me'
        );
    }
    if($_POST['sent'] == 'Y') {    
        $options['title'] = strip_tags(stripslashes($_POST['aboutme-title']));
        //$options['text'] = strip_tags(stripslashes($_POST['aboutme-text']),'<p><a><b><strong><i><u><br>');
        $options['text'] = stripslashes($_POST['aboutme-text']);
        $options['email'] = strip_tags(stripslashes(strtolower($_POST['aboutme-email'])));
        $options['promote_on'] = $_POST["aboutme-promote_on"];
        $options['frame_on'] = $_POST["aboutme-frame_on"];
        $options['alignright_on'] = $_POST["aboutme-alignright_on"];
        $options['email_on'] = $_POST["aboutme-email_on"];
        $options['grav_on'] = $_POST["aboutme-grav_on"];
        $options['grav_x'] = $_POST["aboutme-grav_x"];
        $options['grav_y'] = $_POST["aboutme-grav_y"];
        $options['counter_on'] = $_POST["aboutme-counter_on"];
        $options['vanityurl_on'] = $_POST["aboutme-vanityurl_on"];                
        for ($i=0;$i<count($arr_am_titles);$i++) {            
            $tag_id = strtolower($arr_am_titles[$i]);
            $options[$tag_id] = $_POST["aboutme-".$tag_id];
            $options[$tag_id."_on"] = $_POST["aboutme-".$tag_id."_on"];
        }
        if(!empty($_FILES['wp_custom_attachment']['name'])) {
            // Setup the array of supported file types. In this case, it's just PDF.
            $supported_types = array('image/jpeg','image/png','image/gif');

            // Get the file type of the upload
            $arr_file_type = wp_check_filetype(basename($_FILES['wp_custom_attachment']['name']));
            $uploaded_type = $arr_file_type['type'];

            // Check if the type is supported. If not, throw an error.
            if(in_array($uploaded_type, $supported_types)) {

                // Use the WordPress API to upload the file
                $upload = wp_upload_bits($_FILES['wp_custom_attachment']['name'], null, file_get_contents($_FILES['wp_custom_attachment']['tmp_name']));

                if(isset($upload['error']) && $upload['error'] != 0) {
                    echo "<div id=\"err_message\" class=\"error\"><p>There was an error uploading your file. The error is: " . $upload['error']."</p></div>";
                } else {
                   //echo "<pre>"; print_r($upload); echo "</pre>";
                   if(file_exists($options['image'])) @unlink($options['image']);
                   if(file_exists(str_replace('-avat','',$options['image']))) @unlink(str_replace('-avat','',$options['image']));
                   $thumb = @image_resize($upload['file'],80,80,true,'avat');
                   if(is_object($thumb))  {
                       echo "<div id=\"err_message\" class=\"error\"><p>There was an error resize the image</p></div>";
                       $options['image'] = $upload['file'];
                       $options['image_url'] = content_url().str_replace(WP_CONTENT_DIR,'',$upload['file']);
                   }
                   else  
                   if(!file_exists($thumb)) echo "<div id=\"err_message\" class=\"error\"><p>There was an error resize the image</p></div>";
                                       else {
                                           $options['image'] = $thumb;
                                           $options['image_url'] = content_url().str_replace(WP_CONTENT_DIR,'',$options['image']);
                                       }
                   //echo "<pre>"; print_r($thumb); echo "</pre>";

                } // end if/else

            } else {
                echo "<div id=\"err_message\" class=\"error\"><p>The file type that you've uploaded is not an image</p></div>";
            }
        }
        update_option("widget_aboutme", $options);

    }    
    ?>
    <div class="wrap">
    <?php /*<div id="message" class="updated"><p>Help us to improve our plugin. Your feedback will be appreciated. Feel free to post your <a href="http://www.webdev3000.com/about-me-3000-ver-1-6-released/#comment" target="_blank">comment</a></p></div>*/ ?>
    <?php    echo "<h2>" . __( 'About Me 3000', '' ) . "</h2>"; ?>
    <div style="float:left;background-color:white;padding: 10px 10px 10px 10px;margin-right:15px;border: 1px solid #ddd;height:200px;">
		<div style="width:450px;height:130px;">
			<h3>Donate please...</h3>
			<em>If you like this plugin and find it useful, help keep this plugin free and actively developed by clicking the <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=A3M9YMDAMMH6U" target="_blank"><strong>donate</strong></a> button.
                Help us to improve our plugin. Your feedback will be appreciated. Feel free to post your <a href="http://www.webdev3000.com/about-me-3000-ver-1-6-released/#comment" target="_blank"><strong>comment</strong></a></em>
		</div>
		<a target="_blank" title="Donate" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FBM9MC66FQYC2">
		<img src="<?php echo plugins_url( 'donate.jpg', __FILE__ ); ?>" alt="Donate with Paypal">	</a>
	</div>
    <div style="clear:both;"></div>
    <?php    echo "<h4>" . __( 'Settings', 'settings_h4' ) . "</h4>"; ?>    
    <form name="aboutme3000_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
    <input type="hidden" name="sent" value="Y">
    <table class="form-table">
    <tr> 
        <th><label for="aboutme-title">Title: </label></th>  
        <td>
            <input type="text" id="aboutme-title" name="aboutme-title" value="<?php echo $options['title'];?>" /><br />
            <input class="checkbox" type="checkbox" id="aboutme-promote_on" name="aboutme-promote_on" value="1" <?php echo (($options["promote_on"]=='1')?' checked=1':''); ?> />
            <label for="aboutme-promote_on">Help us to promote this widget</label><br />
        </td>
    </tr>
    <tr>
        <th><label for="aboutme-email">Email: <small><em>(used for gravatar)</em></small></label></th>  
        <td><input type="text" id="aboutme-email" name="aboutme-email" value="<?php echo $options['email'];?>" /><br />
            <input class="checkbox" type="checkbox" id="aboutme-email_on" name="aboutme-email_on" value="1" <?php echo (($options["email_on"]=='1')?' checked=1':''); ?> />
            <label for="aboutme-email_on">Show email contact</label>
        </td>    
    </tr>
    <tr>
        <th><label for="aboutme-email">Gravatar:</label></th>  
        <td><input type="text" id="aboutme-gvaratar_x" name="aboutme-grav_x" value="<?php echo $options['grav_x'];?>" size="2" maxlength="2" /> x 
            <input type="text" id="aboutme-gvaratar_y" name="aboutme-grav_y" value="<?php echo $options['grav_y'];?>" size="2" maxlength="2" />
            <br />
            <?php /*<input class="checkbox" type="checkbox" id="aboutme-grav_on" name="aboutme-grav_on" value="1" <?php echo (($options["grav_on"]=='1')?' checked=1':''); ?> />
            <label for="aboutme-grav_on">Show gravatar</label><br />*/ ?>
            <input class="checkbox" type="checkbox" id="aboutme-frame_on" name="aboutme-frame_on" value="1" <?php echo (($options["frame_on"]=='1')?' checked=1':''); ?> />
            <label for="aboutme-frame_on">Show frame for gravatar</label><br />
            <input class="checkbox" type="checkbox" id="aboutme-alignright_on" name="aboutme-alignright_on" value="1" <?php echo (($options["alignright_on"]=='1')?' checked=1':''); ?> />
            <label for="aboutme-alignright_on">Align gravatar to right</label><br />
        </td>    
    </tr>
    <tr valign="top">
        <th scope="row">Upload Image<br/><small><em>(will be resized to 80x80px)</em></small></th>
        <td>
            <input type="file" id="wp_custom_attachment" name="wp_custom_attachment" value="" size="25"><br/>
            <?php
                //echo $options['image_url']."<br/>";
                echo '<img src="'.$options['image_url'].'" alt="image" />';
            ?>
        </td>
    </tr>
    <tr>
        <th scope="row">Image/Gravatar:</th>
        <td>
            <input type="radio" name="aboutme-grav_on" value="0" <?php echo (($options["grav_on"]=='0' || $options["grav_on"]=='' )?' checked=true':''); ?>> Show nothing<br />
            <input type="radio" name="aboutme-grav_on" value="1" <?php echo (($options["grav_on"]=='1')?' checked=true':''); ?>> Show Gravatar<br />
            <input type="radio" name="aboutme-grav_on" value="2" <?php echo (($options["grav_on"]=='2')?' checked=true':''); ?>> Show uploaded image
        </td>
    </tr>
    <tr>
        <th><label for="aboutme-text">About you: </label></th>
        <?php
	        if ( rich_edit_exists() && user_can_richedit() ) {
                wp_tiny_mce(false, array(
                    "editor_selector" => "aboutme-text", 
                    "height" => 150,
                )); }
			?>

			<script type="text/javascript" charset="utf-8">

				function toggleEditor(id) {
					if (!tinyMCE.get(id))
						tinyMCE.execCommand('mceAddControl', false, id);
					else
						tinyMCE.execCommand('mceRemoveControl', false, id);
				}

				jQuery(document).ready(function($) {
					var id = 'aboutme-text';
					$('a.toggleVisual').click(
						function() {
							tinyMCE.execCommand('mceAddControl', false, id);
						}
					);
					$('a.toggleHTML').click(
						function() {
							tinyMCE.execCommand('mceRemoveControl', false, id);
						}
					);
				});
			</script>
        <td>
            <p align="right">
	            <a class="button toggleVisual"><?php _e('Visual', 'tinymce_signature'); ?></a>
	            <a class="button toggleHTML"><?php _e('HTML', 'tinymce_signature'); ?></a>
            </p>
            <?php /*<textarea class="widefat" id="aboutme-text" name="aboutme-text" rows="5"><?php echo $options['text'];?></textarea>*/ ?>
            <div class="postbox">
            <textarea name="aboutme-text" class="aboutme-text" id="aboutme-text" style="width:inherit; height:150px;"><?php echo trim($options['text']); ?></textarea>
            </divt>
        </td>
    </tr>
    <?php 
        for ($i=0;$i<count($arr_am_titles);$i++) {
              $tag_id = strtolower($arr_am_titles[$i]);
              ?>
              <tr>
              <th><label for="aboutme-<?php echo $tag_id?>"><?php echo $arr_am_titles[$i]?> profile ID:</label></th>  
              <td><input type="text" id="aboutme-<?php echo $tag_id?>" name="aboutme-<?php echo $tag_id?>" value="<?php echo $options[$tag_id];?>" /> <img src="<?php echo get_bloginfo('wpurl')?>/wp-content/plugins/about-me-3000/<?php echo $tag_id?>.png" border="0"> 
                  <?php
                  if($i == 0) {?>
                      <?php if($options['vanityurl_on'])   $url = "http://www.facebook.com/"; 
                                                   else $url = $arr_am_urls[$i];                        
                      ?>
                      <small>(<?php echo $url?>YourID)</small><br />
                      <input class="checkbox" type="checkbox" id="aboutme-vanityurl_on" name="aboutme-vanityurl_on" value="1" <?php echo (($options["vanityurl_on"]=='1')?' checked=1':''); ?>/>
                      <label for="aboutme-vanityurl_on" title="http://www.facebook.com/YourID">Use Vanity URL</label><br />
                  <?php } else
                  if($i == 2) {?>
                      <small>(<?php echo $arr_am_urls[$i]?>YourID)</small><br />
                      <input class="checkbox" type="checkbox" id="aboutme-counter_on" name="aboutme-counter_on" value="1" <?php echo (($options["counter_on"]=='1')?' checked=1':''); ?>/>
                      <label for="aboutme-counter_on">Show subscribers</label><br />
                  <? }
                  else 
                  if($i == 8 || $i == 12)   {?>
                      <small>(<?php echo $arr_am_urls[$i]?>)</small><br />
                  <?}
                  else { ?>
                       <small>(<?php echo $arr_am_urls[$i]?>YourID)</small><br />
                  <?php } ?>
                  <input class="checkbox" type="checkbox" id="aboutme-<?php echo $tag_id?>_on" name="aboutme-<?php echo $tag_id?>_on" value="1" <?php echo (($options[$tag_id."_on"]=='1')?' checked=1':''); ?>/>
                  <label for="aboutme-<?php echo $tag_id?>_on">Show <?php echo $arr_am_titles[$i]?> icon</label>
              </td>
              </tr>
            <?php
        }
    ?>
    </table>
    <p class="submit">
      <input class="button-primary" type="submit" name="Submit" value="<?php _e('Update Options', 'oscimp_trdom' ) ?>" />
    </p>
    </form>
    </div>
    <?php
}
function control_aboutme_() {
   ?>
   <p>  
     To configure this widget go to (Settings/About Me 3000) or click <a href='<?php echo get_bloginfo('wpurl')?>/wp-admin/options-general.php?page=About-Me'>here</a>
   </p>
   <?php  
}
function init_aboutme3000(){
    register_sidebar_widget('About Me 3000', 'widget_aboutme');
    register_widget_control('About Me 3000', 'control_aboutme_');  
}
function aboutme3000_admin_actions() {
    add_options_page("About Me 3000", "About Me", 10, "About-Me", "control_aboutme");
}

add_action('plugins_loaded', 'init_aboutme3000');
add_action('admin_menu', 'aboutme3000_admin_actions');

?>
