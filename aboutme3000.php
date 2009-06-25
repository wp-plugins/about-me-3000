<?php
/*
Plugin Name: About Me 3000
Plugin URI: http://www.webdev3000.com/
Description: Add an "About Me" widget to your sidebar.
Author: Csaba Kissi
Version: 1.1
Author URI: http://www.webdev3000..com/
*/

$arr_am_titles = Array("Facebook","Friendfeed","Feedburner","Delicious","Technorati","Twitter");
$arr_am_urls   = Array("http://www.facebook.com/profile.php?id=","http://friendfeed.com/","http://feeds2.feedburner.com/","http://delicious.com/","http://technorati.com/people/technorati/","http://twitter.com/");

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
       .aboutme * {border: 0px solid;}
    </style>
    <? 
    echo "<div class='aboutme'>";
    if(!empty($options['email'])) echo "<img style='float:left; width=90px; ".(($options["frame_on"]=='1')?'border:1px solid #999;':'')." margin:5px;' src='http://www.gravatar.com/avatar/".md5($options['email'])."?s=80'>";
    echo $options['text'];
    echo "<div style='clear:both'></div><div style='border-top: 1px solid #eee; padding-top:5px; position:relative; height:25px'><div style='left:0; position: absolute'>";
    for ($i=0;$i<count($arr_am_titles);$i++) {
        $tag_id = strtolower($arr_am_titles[$i]); 
        if($options[$tag_id.'_on']) echo "<a style='padding:2px' href='".$arr_am_urls[$i]."".$options[$tag_id]."'><img src='".get_bloginfo('url')."/wp-content/plugins/about-me-3000/".$tag_id.".png' border='0'></a>";
    }
    if(!empty($options['email'])) {
        $arr_email = explode('@',$options['email']);
        ?>
<script type="text/javascript">
<!--
    var string1 = "<?=$arr_email[0]?>";
    var string2 = "@";
    var string3 = "<?=$arr_email[1]?>";
    var string4 = string1 + string2 + string3;
    document.write("<a style='padding:2px' href=" + "mail" + "to:" + string1 + string2 + string3 + ">");
    document.write("<img src='<?=get_bloginfo('url')?>/wp-content/plugins/about-me-3000/email.png' border='0'>");
    document.write("</a>");
//-->
</script>
        <?
        //echo "<a style='padding:2px' href='mailto:".$options['email']."'><img src='".get_bloginfo('url')."/wp-content/plugins/about-me-3000/email.png' border='0'></a>";
    }    
    echo "</div>";
    if($options['feedburner_on']) echo "<div style='right:0; position: absolute;'><img src='http://feeds.feedburner.com/~fc/".$options['feedburner']."'></div>";
    echo "</div></div>";
    echo $after_widget;  
}
// Widget options
function control_aboutme() {
    global $arr_am_titles;
    $options = get_option("widget_aboutme");  
    if (!is_array( $options )) {
        $options = array(
        'title' => 'About Me'
        );
    }
    if ($_POST['aboutme-submit']) {
        $options['title'] = strip_tags(stripslashes($_POST['aboutme-title']));
        $options['text'] = strip_tags(stripslashes($_POST['aboutme-text']));
        $options['email'] = strip_tags(stripslashes($_POST['aboutme-email']));
        $options['frame_on'] = $_POST["aboutme-frame_on"];
        for ($i=0;$i<count($arr_am_titles);$i++) {            
            $tag_id = strtolower($arr_am_titles[$i]);
            $options[$tag_id] = $_POST["aboutme-".$tag_id];
            $options[$tag_id."_on"] = $_POST["aboutme-".$tag_id."_on"];
        }
        update_option("widget_aboutme", $options);
    }    
    ?>    
    <p>  
    <label for="aboutme-title">Title: </label><br />  
    <input class="widefat" type="text" id="aboutme-title" name="aboutme-title" value="<?php echo $options['title'];?>" />
    </p>
    <p>
    <label for="aboutme-email">Email: <small><em>(used for gravatar)</em></small></label><br />  
    <input class="widefat" type="text" id="aboutme-email" name="aboutme-email" value="<?php echo $options['email'];?>" /><br />
    <input class="checkbox" type="checkbox" id="aboutme-frame_on" name="aboutme-frame_on" value="1" <?php echo (($options["frame_on"]=='1')?' checked':''); ?>"/>
    <label for="aboutme-frame_on">Show frame for avatar</label>
    </p>
    <hr />
    <p>
    <label for="aboutme-text">About you: </label><br />
    <textarea class="widefat" id="aboutme-text" name="aboutme-text" rows="5"><?php echo $options['text'];?></textarea>
    </p>
    <hr />
    <?php 
        for ($i=0;$i<count($arr_am_titles);$i++) {
              $tag_id = strtolower($arr_am_titles[$i]);
              ?>
              <label for="aboutme-<?=$tag_id?>"><?=$arr_am_titles[$i]?> profile ID:</label><br />  
                <input class="widefat" type="text" id="aboutme-<?=$tag_id?>" name="aboutme-<?=$tag_id?>" value="<?php echo $options[$tag_id];?>" /><br />
                <input class="checkbox" type="checkbox" id="aboutme-<?=$tag_id?>_on" name="aboutme-<?=$tag_id?>_on" value="1" <?php echo (($options[$tag_id."_on"]=='1')?' checked':''); ?>"/>
                <label for="aboutme-<?=$tag_id?>_on">Show <?=$arr_am_titles[$i]?> icon</label>
                <hr />
              </p>
            <?
        }
    ?>
    <input type="hidden" id="aboutme-submit" name="aboutme-submit" value="1" />  
    <br /> 
    <?
}

function init_aboutme3000(){
    register_sidebar_widget('About Me 3000', 'widget_aboutme');
    register_widget_control('About Me 3000', 'control_aboutme');  
}
add_action('plugins_loaded', 'init_aboutme3000');
?>
