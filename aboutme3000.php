<?php
/*
Plugin Name: About Me 3000
Plugin URI: http://www.webdev3000.com/
Description: Add an "About Me" widget to your sidebar.
Author: Csaba Kissi
Version: 1.5
Author URI: http://www.webdev3000..com/
*/

$arr_am_titles = Array("Facebook","Friendfeed","Feedburner","Delicious","MySpace","LinkedIn","StumbleUpon","Technorati","Twitter","YouTube");
$arr_am_urls   = Array("http://www.facebook.com/profile.php?id=","http://friendfeed.com/","http://feeds2.feedburner.com/","http://delicious.com/","http://www.myspace.com/","http://www.linkedin.com/in/","http://YourID.stumbleupon.com","http://technorati.com/people/technorati/","http://twitter.com/","http://www.youtube.com/user/");

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
    if(!empty($options['email']) && $options['grav_on']) echo "<img width=\"".$x."\" height=\"".$y."\" style='float:left;".(($options["frame_on"]=='1')?'border:1px solid #999;':'')." margin:5px;' src='http://www.gravatar.com/avatar/".md5($options['email'])."?s=80'>";
    echo $options['text'];
    echo "<div style='clear:both'></div><div style='border-top: 1px solid #eee; padding-top:5px; position:relative; height:25px'><div style='left:0; position: absolute'>";
    for ($i=0;$i<count($arr_am_titles);$i++) {
        $tag_id = strtolower($arr_am_titles[$i]);
        if($i == 0 && $options['vanityurl_on']) echo "<a style='padding:2px' href='http://www.facebook.com/".$options[$tag_id]."'><img src='".get_bloginfo('wpurl')."/wp-content/plugins/about-me-3000/".$tag_id.".png' border='0'></a>";
        else
        if($i == 6 && $options[$tag_id.'_on'])  echo "<a style='padding:2px' href='http://".$options[$tag_id].".stumbleupon.com/'><img src='".get_bloginfo('wpurl')."/wp-content/plugins/about-me-3000/".$tag_id.".png' border='0'></a>";
        else   
        if($options[$tag_id.'_on']) echo "<a style='padding:2px' href='".$arr_am_urls[$i]."".$options[$tag_id]."'><img src='".get_bloginfo('wpurl')."/wp-content/plugins/about-me-3000/".$tag_id.".png' border='0'></a>";
    }
    if(!empty($options['email']) && $options['email_on']) {
        $arr_email = explode('@',$options['email']);
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
        $options['text'] = strip_tags(stripslashes($_POST['aboutme-text']));
        $options['email'] = strip_tags(stripslashes($_POST['aboutme-email']));
        $options['frame_on'] = $_POST["aboutme-frame_on"];
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
        update_option("widget_aboutme", $options);
    }    
    ?>
    <div class="wrap">
    <?php    echo "<h2>" . __( 'About Me 3000', '' ) . "</h2>"; ?>
    <?php    echo "<h4>" . __( 'Settings', 'settings_h4' ) . "</h4>"; ?>    
    <form name="aboutme3000_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="sent" value="Y">
    <table class="form-table">
    <tr> 
        <th><label for="aboutme-title">Title: </label></th>  
        <td><input type="text" id="aboutme-title" name="aboutme-title" value="<?php echo $options['title'];?>" /></td>
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
            <input class="checkbox" type="checkbox" id="aboutme-grav_on" name="aboutme-grav_on" value="1" <?php echo (($options["grav_on"]=='1')?' checked=1':''); ?> />
            <label for="aboutme-grav_on">Show gravatar</label><br />
            <input class="checkbox" type="checkbox" id="aboutme-frame_on" name="aboutme-frame_on" value="1" <?php echo (($options["frame_on"]=='1')?' checked=1':''); ?> />
            <label for="aboutme-frame_on">Show frame for gravatar</label><br />
        </td>    
    </tr>
    <tr>
        <th><label for="aboutme-text">About you: </label></th>
        <td><textarea class="widefat" id="aboutme-text" name="aboutme-text" rows="5"><?php echo $options['text'];?></textarea></td>
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
                  if($i == 6) {?>
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
     To configure this widget go to (Settings/About Me 3000) or click <a href='<?php echo get_bloginfo('wpurl')?>/wp-admin/options-general.php?page=About%20Me'>here</a>
   </p>
   <?php  
}
function init_aboutme3000(){
    register_sidebar_widget('About Me 3000', 'widget_aboutme');
    register_widget_control('About Me 3000', 'control_aboutme_');  
}
function aboutme3000_admin_actions() {
    add_options_page("About Me 3000", "About Me", 1, "About Me", "control_aboutme");
}

add_action('plugins_loaded', 'init_aboutme3000');
add_action('admin_menu', 'aboutme3000_admin_actions');  
?>
