<?php

/**
* The metabox Class.
*
* Metabox class supports themes additional fiels
*
* @since rgen 1.0
*/
class metabox {

  private $metabox_field, $metabox;

/**
* Hook into the appropriate actions when the class is constructed.
*
* @param string $metabox_data
* @param string $field_data
* @return void
*/
public function __construct($metabox_data, $field_data) {
  add_action('add_meta_boxes', array($this, 'add_meta_box'));
  add_action('save_post', array($this, 'save'));
  $this->metabox_field = $field_data;
  $this->metabox = $metabox_data;
}

/**
* Adds the meta box container.
*
* @param string $post_type
* @return void
*/
public function add_meta_box($post_type) {
  wp_enqueue_script('script-metabox', get_template_directory_uri()
    . '/theme-framework/metabox/assests/js/script.js');
  wp_enqueue_style('style-metabox', get_template_directory_uri()
    . '/theme-framework/metabox/assests/css/style.css');

// Limit meta box to certain post types
  $post_types = $this->metabox['meta_type'];
  if (in_array($post_type, $post_types)) {
    add_meta_box(
      $this->metabox['name'], __($this->metabox['description'] . ':', 'rgen'), array($this, 'render_meta_box_content'), $post_type, 'normal', 'high'
      );
  }
}

/**
* Save the meta when the post is saved.
*
* @param int $post_id The ID of the post being saved.
* @return int $post_id
*/
public function save($post_id) {
// Allowable tags for Editor
  global $allowedposttags;
/*
* We need to verify this came from the our screen and with proper
* authorization,
* because save_post can be triggered at other times.
*/

// Check if our nonce is set.
if (!isset($_POST[$this->metabox['name'] . '_custom_box_nonce']))
  return $post_id;

$nonce = $_POST[$this->metabox['name'] . '_custom_box_nonce'];

// Verify that the nonce is valid.
if (!wp_verify_nonce($nonce, $this->metabox['name'] . '_custom_box'))
  return $post_id;

/* If this is an autosave, our form has not been submitted,
so we don't want to do anything. */
if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
  return $post_id;

// Check the user's permissions.
if ('page' == $_POST['post_type']) {

  if (!current_user_can('edit_page', $post_id))
    return $post_id;
} else {

  if (!current_user_can('edit_post', $post_id))
    return $post_id;
}

// Its safe for us to save the data now.
foreach ($this->metabox_field as $key => $value) {

// Sanitize the user input.
  $mydata = sanitize_text_field($_POST[$value['field_name'] . '_field']);

  if ($value['field_type'] == 'wysiwygEditor') :

// Sanitize the user input.
    $mydata = wp_kses($_POST[$value['field_name'] . '_field'], $allowedposttags);

// Update the wysiwyg Editor field.
  update_post_meta($post_id, '_' . $value['field_name'] . '_key', $mydata);
  else :
// Sanitize the user input.
    $mydata = sanitize_text_field($_POST[$value['field_name'] . '_field']);

// Update the meta field.
  update_post_meta($post_id, '_' . $value['field_name'] . '_key', sanitize_text_field($mydata));
  endif;
}
}

/**
* Render Meta Box content.
*
* @param WP_Post $post The post object.
* @return void
*/
public function render_meta_box_content($post) {

// Add an nonce field so we can check for it later.
  wp_nonce_field($this->metabox['name'] . '_custom_box', $this->metabox['name'] . '_custom_box_nonce');
  foreach ($this->metabox_field as $key => $values) {
    switch ($values['field_type']) {
      case 'text':
      $this->text_structure($post, $values);
      break;
      case 'font':
      $this->font_structure($post, $values);
      break;
      case 'textarea':
      $this->text_area_structure($post, $values);
      break;
      case 'radio':
      $this->featured_event_fields($post, $values);
      break;
      case 'wysiwygEditor':
      $this->wysiwyg_render_editor($post, $values);
      break;
      case 'date':
      $this->date_structure($post, $values);
      break;
      case 'page-drop':
      $this->page_select_structure($post, $values);
      break;
      case 'post-drop':
      $this->post_select_structure($post, $values);
      break;
      case 'image':
      $this->image_structure($post, $values);
      break;
      case 'checkbox':
      $this->checkbox_structure($post, $values);
      break;
    }
  }
}

private function featured_event_fields($post, $value) {
// To set a default value for radio button
  $profile_value = get_post_meta($post->ID);
  if (isset($profile_value['_' . $value['field_name'] . '_key'])) :

// Use get_post_meta to retrieve an existing value from the database.
    $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
      '_key', true);
  else :
    $profile_value = 'No';
  endif;
  echo '<label>'.$value['field_description'].':</label>';
  echo '<span>'. $value['helper_text']. '</span>';
  foreach ($value['val'] as  $radVal) {
    if($profile_value == $radVal)
      $checkingNo = 'checked';
    else
      $checkingNo = '';

    echo '<p class= "radio_button"><label for="'.$radVal.'_field" style="vertical-align:top">'
    . $radVal.'</label> '
    . '<input type="radio" id="'.$radVal.'_field"'
    . 'name="' . $value['field_name'] . '_field"'
    . ' value="'.$radVal.'" '
    . 'class ="' . $value['class'] . '"'
    . 'style="float:left"'
    . $checkingNo . '/> '
    . '</p>';
# code...
  }

}

private function upload_file_structure($post, $value){
  wp_nonce_field(plugin_basename(__FILE__), 'wp_custom_attachment_nonce');

  $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
    '_key', true);

  $html = '<p class="description">';
  $html .= 'Upload your PDF here.';
  $html .= '</p>';
  $html .= '<input type="file" id="wp_custom_attachment" name="wp_custom_attachment" value="'.esc_attr($profile_value).'" size="25" />';

  echo $html;
}


private function image_structure($post, $value){


  ?>
  <script>

    ( function( $ ) {

      $(document).ready(

        function()
        {
          $('#<?php echo 'image'.$value['field_name'] ; ?>').click(
            function()
            {
              tb_show('', 'media-upload.php?type=image&TB_iframe=true');
              window.send_to_editor = function (html) {
                imgurl = html.match(/src="(.+?)"/);
                $('#<?php echo $value['field_name'] . '_field'; ?>').val(imgurl[1]);
                $('#<?php echo $value['field_name'] .'_label'; ?>').text(imgurl[1]);
                $('#<?php echo $value['field_name'] .'_img'; ?>').attr('src',imgurl[1]);
                tb_remove();
              }
              return false;
            }
            );
        }
        );

    } ) ( jQuery );
  </script>


  <?php 
  $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
    '_key', true);
  wp_enqueue_script('wp-color-picker');
  wp_enqueue_style('wp-color-picker');

// Display the form, using the current value.
  echo '<p class="upload_image"><label for="' . $value['field_name'] . '_field">';
  _e($value['field_description'], 'rgen');
  echo ':</label> ';
    echo '<span>'. $value['helper_text']. '</span>';
  echo '<input id="image'.$value['field_name'] .'" type="button" value=" Upload image"><input type="hidden" '
  . 'id="' . $value['field_name'] . '_field" '
  . 'name="' . $value['field_name'] . '_field"'
  . ' value="' . esc_attr($profile_value) . '" '
  . 'class ="' . $value['class'] . '"'
  . ' readonly/> <label class="up_msg" id="'.$value['field_name'] .'_label">'.esc_attr($profile_value).'</label>'
  . '<img src="'.esc_attr($profile_value).'" alt="image" class="up_msg" id="'.$value['field_name'] .'_img" >'
  . '</p>';

  // function admin_scripts()
  // {
  //   wp_enqueue_script('media-upload');
  //   wp_enqueue_script('thickbox');
  // }

  // function admin_styles()
  // {
  //   wp_enqueue_style('thickbox');
  // }

  // add_action('admin_print_scripts', 'admin_scripts');
  // add_action('admin_print_styles', 'admin_styles');

}

private function wysiwyg_render_editor($post, $value) {
  $editor_id = $value['field_name'];
  $container_editor_id = $value['field_name'] . "_field";

//Add CSS & jQuery goodness to make this work like the original WYSIWYG
  echo "<style type='text/css'>
#$editor_id #edButtonHTML, #$editor_id #edButtonPreview {background-color: #F1F1F1; border-color: #DFDFDF #DFDFDF #CCC; color: #999;}
#$container_editor_id{width:100%;}
#$editor_id #editorcontainer{background:#fff !important;}
</style>

<script type='text/javascript'>
  jQuery(function($){
    $('#$editor_id #editor-toolbar > a').click(function(){
      $('#$editor_id #editor-toolbar > a').removeClass('active');
      $(this).addClass('active');
    });

    if($('#$editor_id #edButtonPreview').hasClass('active')){
      $('#$editor_id #ed_toolbar').hide();
    }

    $('#$editor_id #edButtonPreview').click(function(){
      $('#$editor_id #ed_toolbar').hide();
    });

    $('#$editor_id #edButtonHTML').click(function(){
      $('#$editor_id #ed_toolbar').show();
    });

//Tell the uploader to insert content into the correct WYSIWYG editor
    $('#media-buttons a').bind('click', function(){
      var customEditor = $(this).parents('#$editor_id');
      if(customEditor.length > 0){
        edCanvas = document.getElementById('$container_editor_id');
      }
      else{
        edCanvas = document.getElementById('content');
      }
    });
  });
</script>
";

//Create The Editor
echo '<label>'.$value['field_description'].':</label>';
  echo '<span>'. $value['helper_text']. '</span>';
$profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
  '_key', true);
wp_editor($profile_value, $container_editor_id);

//Clear The Room!
echo "<div style='clear:both; display:block;'></div>";
}

private function text_structure($post, $value) {

// Use get_post_meta to retrieve an existing value from the database.
  $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
    '_key', true);
  wp_enqueue_script('wp-color-picker');
  wp_enqueue_style('wp-color-picker');

// Display the form, using the current value.
  echo '<p><label for="' . $value['field_name'] . '_field">';
  _e($value['field_description'], 'rgen');
  echo ':</label> ';
    echo '<span>'. $value['helper_text']. '</span>';
  echo '<input type="text" '
  . 'id="' . $value['field_name'] . '_field" '
  . 'name="' . $value['field_name'] . '_field"'
  . ' value="' . esc_attr($profile_value) . '" '
  . 'class ="' . $value['class'] . '"'
  . '/>'
  . '</p>';
}

private function font_structure($post, $value) {

// Use get_post_meta to retrieve an existing value from the database.
  $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
    '_key', true);
  wp_enqueue_script('wp-color-picker');
  wp_enqueue_style('wp-color-picker');

// Display the form, using the current value.
  echo '<p><label for="' . $value['field_name'] . '_field">';
  _e($value['field_description'], 'rgen');
  echo ':</label> ';
    echo '<span>'. $value['helper_text']. '</span>';
  echo '<input type="text" '
  . 'id="' . $value['field_name'] . '_field" '
  . 'name="' . $value['field_name'] . '_field"'
  . ' value="' . esc_attr($profile_value) . '" '
  . 'class ="' . $value['class'] . '"'
  . 'placeholder = "22"'
  . '/>'
  . '</p>';
}

private function text_area_structure($post, $value) {

// Use get_post_meta to retrieve an existing value from the database.
  $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
    '_key', true);

// Display the form, using the current value.
  echo '<p><label for="' . $value['field_name'] . '_field">';
  _e($value['field_description'], 'rgen');
  echo ':</label>';
    echo '<span>'. $value['helper_text']. '</span>
  <textarea id="' . $value['field_name'] . '_field" '
  . 'name="' . $value['field_name'] . '_field"'
  . ' value="' . esc_attr($profile_value) . '" '
  . 'class ="' . $value['class'] . '">' . esc_attr($profile_value)
  . '
</textarea></p>';
}


private function page_select_structure($post, $value) {

// Use get_post_meta to retrieve an existing value from the database.
  $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
    '_key', true);

 echo '<p><label for="' . $value['field_name'] . '_field">';
  _e($value['field_description'], 'rgen');
  echo ':</label>';

  echo '<span>'. $value['helper_text']. '</span>';

  ?>

  <select class="<?php echo $value['class']; ?>" name="<?php echo $value['field_name'] . '_field'; ?>">

      <?php

 $args = array(
  'sort_order' => 'asc',
  'sort_column' => 'post_title',
  'hierarchical' => 1,
  'exclude' => '',
  'include' => '',
  'meta_key' => '',
  'meta_value' => '',
  'authors' => '',
  'child_of' => 0,
  'parent' => -1,
  'exclude_tree' => '',
  'number' => '',
  'offset' => 0,
  'post_type' => 'page',
  'post_status' => 'publish'
); 
$pages = get_pages($args); 
      //$posts = get_posts($args);
      foreach( $pages as $page ) : setup_postdata($page); 
      if($profile_value==get_page_link( $page->ID )){ ?>
        <option value="<?php echo get_page_link( $page->ID ); ?>" selected><?php echo $page->post_title; ?></option>

      <?php }else{
        ?>
         <option value="<?php echo get_page_link( $page->ID ); ?>"><?php echo $page->post_title; ?></option>

        <?php
        } endforeach; ?>
      </select></p>

  <?php

}


private function post_select_structure($post, $value) {

// Use get_post_meta to retrieve an existing value from the database.
  $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
    '_key', true);

 echo '<p><label for="' . $value['field_name'] . '_field">';
  _e($value['field_description'], 'rgen');
  echo ':</label>';

  echo '<span>'. $value['helper_text']. '</span>';

  ?>

  <select class="<?php echo $value['class']; ?>" name="<?php echo $value['field_name'] . '_field'; ?>">

      <?php


$args = array(
   'public'   => true,
   '_builtin' => false
);

$output = 'objects'; // names or objects

$posts = get_post_types( $args, $output );

      //$posts = get_posts($args);
      foreach( $posts as $post ) : setup_postdata($post); 
      if($profile_value==$post->name){ ?>
        <option value="<?php echo $post->name ; ?>" selected><?php echo $post->name ; ?></option>

      <?php }else{
        ?>
         <option value="<?php echo $post->name ; ?>"><?php echo $post->name  ; ?></option>

        <?php
        } endforeach; ?>
      </select></p>

  <?php

}


private function checkbox_structure($post,$value){
//var_dump($value);
  $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
    '_key', true);
  $profile_value_arr = explode( ',', $profile_value );
  ?>
  <script>

    ( function( $ ) {

      $(document).ready(

        function()
        {
          $( "select.<?php echo $value['class']; ?>" )
          .change(function () {
            var str = "";
            $( "select.<?php echo $value['class']; ?> option:selected" ).each(function() {
              str += $( this ).text() + ",";
            });
            str = str.substring(0, str.length - 1);
            $( "#<?php echo $value['field_name'] . '_field'; ?>" ).val( str );
          })
          .change();
             var strl = "<?php echo $profile_value; ?>";
           var array = strl.split(',');
            console.log(array);

        }
        );

    } ) ( jQuery );
  </script>
  <?php
    echo '<p><label>';
  _e($value['field_description'], 'rgen');
  echo ':</label>';
  echo '<span>'. $value['helper_text']. '</span>';
  ?>
  <select multiple class="<?php echo $value['class']; ?>">
  <?php

    foreach ($value['val'] as $valu) {
      if(in_array($valu, $profile_value_arr)){
        ?>
          <option value="<?php echo $valu; ?>" selected><?php echo $valu; ?></option>
        <?php
      }else{
        ?>
          <option value="<?php echo $valu; ?>"><?php echo $valu; ?></option>
        <?php
      }
    }
  ?>
  </select> 

<input type="hidden" name="<?php echo $value['field_name'] . '_field'; ?>" value="" id="<?php echo $value['field_name'] . '_field'; ?>"/>

  <?php
}

private function single_checkbox_structure($post,$value){
  $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
    '_key', true);

  echo '<p><label for="' . $value['field_name'] . '_field">';
  _e($value['field_description'], 'rgen');
  echo ':</label>';
    echo '<span>'. $value['helper_text']. '</span>';
    ?>

    <input type="checkbox" name="<?php echo $value['field_name'] . '_field'; ?>" <?php if( esc_attr($profile_value) == true ) { ?>checked="checked"<?php } ?> />  Check the Box.


    <?php
  }

  private function date_structure($post, $value) {

// Use get_post_meta to retrieve an existing value from the database.
    $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
      '_key', true);

    wp_enqueue_style('jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', true);
    wp_enqueue_script('jquery-ui-datepicker');

    ?>
    <script>
      jQuery(document).ready(function(){
        jQuery('#<?php echo $value['field_name'] . '_field'; ?>').datepicker({
          dateFormat : 'dd/mm/yy'
        });
      });
    </script>
    <?php

// Display the form, using the current value.
    echo '<p><label for="' . $value['field_name'] . '_field">';
    _e($value['field_description'], 'rgen');
    echo ':</label> ';
        echo '<span>'. $value['helper_text']. '</span>';
    echo '<input type="text" '
    . 'id="' . $value['field_name'] . '_field" '
    . 'name="' . $value['field_name'] . '_field"'
    . ' value="' . esc_attr($profile_value) . '" '
    . 'class ="' . $value['class'] . ' date-picker"'
    . 'id ="date"'
    . '/>'
    . '</p>';
    ?>
    <?php

  }

}
