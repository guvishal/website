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
            . '/framework/admin/metabox/assests/js/script.js');
    wp_enqueue_style('style-metabox', get_template_directory_uri()
            . '/framework/admin/metabox/assests/css/style.css');

    // Limit meta box to certain post types
    $post_types = $this->metabox['meta_type'];
    if (is_array($post_types) && in_array($post_type, $post_types)) {
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
    if (!isset($_POST[$this->metabox['name'] . '_custom_box_nonce'])) {
      return $post_id;
    }

    $nonce = $_POST[$this->metabox['name'] . '_custom_box_nonce'];

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($nonce, $this->metabox['name'] . '_custom_box')) {
      return $post_id;
    }

    /* If this is an autosave, our form has not been submitted,
      so we don't want to do anything. */
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $post_id;
    }

    // Check the user's permissions.
    if ('page' == $_POST['post_type']) {

      if (!current_user_can('edit_page', $post_id)) {
        return $post_id;
      }
    } else {

      if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
      }
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
        case 'instruction':
          $this->instruction_structure($post, $values);
          break;
        case 'checkboxes':
          $this->featured_posts_check($post, $values);
          break;
        case 'mediatypecheckboxes':
          $this->featured_posts_mediatypecheckboxes($post, $values);
          break;
        // case 'checkbox':
        // $this->checkbox_structure($post, $values);
        // break;
      }
    }
  }
  
  private function featured_posts_mediatypecheckboxes($post, $value) {

    // To set a default value for radio button
    $profile_value = get_post_meta($post->ID);

    if (isset($profile_value['_' . $value['field_name'] . '_key'])) :

      // Use get_post_meta to retrieve an existing value from the database.
      $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
        '_key', true);

      $profile_value = explode(',', $profile_value);

    else :
      $profile_value = '';
    endif;

    if (isset($value['custom_post'])) {
      $custom_post_name = $value['custom_post'];
    }

    $args = array('post_type' => $custom_post_name, 'posts_per_page' => -1);
    $postslist = get_posts($args);
    echo '<div><div>' . _e($value['field_description'], 'rgen') . '</div>';

    foreach ($postslist as $post) {
      $checked = '';
      setup_postdata($post);
      if ($profile_value != ''):
        foreach ($profile_value as $val) {
          if ($val == $post->ID) {
            $checked = 'checked';
          }
        }
      endif;
      echo '<div class="checkbox-container"><label class="' . $value['class'] . '">'
           . '<input class="messageCheckbox" type="checkbox" name="' . $value['field_name'] . '_field" value="' . $post->ID . '" ' . $checked . '>'
      . $post->post_title . '</label></div>';
    }

    echo '<input type="hidden" value="" name="' . $value['field_name'] . '_field" class="' . $value['field_name'] . 'hidden">';
    wp_reset_postdata();
    ?>
    <script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/assets/vendor/jquery.1.11.3.min.js'></script>
    <script>

      jQuery(document).ready(function() {
        
        $val1 = '';
        jQuery('.messageCheckbox').each(function () {
            if (jQuery(this).is(':checked')) { 
            $val1 += jQuery(this).val() + ',';
          }
          jQuery(this).parent().parent().siblings(".<?php echo $value['field_name'] . 'hidden' ?>").val($val1);
        }); 

        jQuery('.messageCheckbox').click(function() {
          $val = '';
          jQuery(this).parent().parent().parent().find('.messageCheckbox').each(function() {
            if (jQuery(this).is(':checked')) {

              $val += jQuery(this).val() + ',';
              
            }
          });
          jQuery(this).parent().parent().siblings(".<?php echo $value['field_name'] . 'hidden' ?>").val($val);
        });
        
        jQuery('.messageCheckbox').each(function() {
          $val = '';
          jQuery(this).parent().parent().children().children('.messageCheckbox').each(function() {
            if (jQuery(this).is(':checked')) {

              $val += jQuery(this).val() + ',';
              
            }
          });
          jQuery(this).parent().parent().siblings(".<?php echo $value['field_name'] . 'hidden' ?>").val($val);
        });
      });

    </script>
    <?php
    echo '</div>';
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

    // To set checked attribute to the radio button
    if ($profile_value == 'No') :
      $checkingNo = 'checked';
      $checkingYes = '';
    else :
      $checkingYes = 'checked';
      $checkingNo = '';
    endif;

    // Display the form, using the current value.
    echo '<p><label for="' . $value['field_name'] . '_field">'
    . _e($value['field_description'], 'rgen')
    . '</label> '
    . '<input type="radio" '
    . 'name="' . $value['field_name'] . '_field"'
    . ' value="No" '
    . 'class ="' . $value['class'] . '"'
    . $checkingNo . ' id="No"/><label for="No" class="radiolabel"> No</label>'
    . '<input type="radio" '
    . 'name="' . $value['field_name'] . '_field"'
    . ' value="Yes" '
    . 'class ="' . $value['class'] . '"'
    . $checkingYes
    . ' id="Yes"/> <label for="Yes" class="radiolabel">Yes</label>'
    . '</p>';
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
    echo '</label> ';
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
    echo '</label> ';
    echo '<input type="text" '
    . 'id="' . $value['field_name'] . '_field" '
    . 'name="' . $value['field_name'] . '_field"'
    . ' value="' . esc_attr($profile_value) . '" '
    . 'class ="' . $value['class'] . '"'
    . 'placeholder = "Use Hex Color values"'
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
    echo '</label>
  <textarea id="' . $value['field_name'] . '_field" '
    . 'name="' . $value['field_name'] . '_field"'
    . ' value="' . esc_attr($profile_value) . '" '
    . 'class ="' . $value['class'] . '">' . esc_attr($profile_value)
    . '
</textarea></p>';
  }

  private function date_structure($post, $value) {

    // Use get_post_meta to retrieve an existing value from the database.
    $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
            '_key', true);

    wp_enqueue_style('jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', true);
    wp_enqueue_script('jquery-ui-datepicker');

    // Display the form, using the current value.
    echo '<p><label for="' . $value['field_name'] . '_field">';
    _e($value['field_description'], 'rgen');
    echo '</label> ';
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

  private function instruction_structure($post, $value) {

    // Use get_post_meta to retrieve an existing value from the database.
    $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
            '_key', true);
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style('wp-color-picker');

    // Display the form, using the current value.
    echo '<p>Fill either PDF download block or Link block or both, below to show this post on the site</p>';
  }

// private function checkbox_structure($post, $value) {
//   $profile_value = get_post_meta($post->ID);
//   if (isset($profile_value['_' . $value['field_name'] . '_key'])) :
//       // Use get_post_meta to retrieve an existing value from the database.
//     $profile_value = get_post_meta($post->ID, '_' . $value['field_name'] .
//       '_key', true);
//   else :
//     $profile_value = 'No';
//   endif;
//   echo '<p><label for="' . $value['field_name'] . '_field">';
//   _e($value['field_description'], 'rgen');
//   echo '</label>
//   <div class="prfx-row-content checkboxs-homepage">
//     <label style="width: 25%;">
//       <input type="checkbox" name="'. $value['field_name'] .'" id="meta-checkbox" value="yes" style = "display: inline-block; width: 4%;" ';
//       if ( isset ( $prfx_stored_meta['meta-checkbox'] ) ) checked( $prfx_stored_meta['meta-checkbox'][0], 'yes' );
//       echo '/>';
//       _e($value['field_name'], 'prfx-textdomain' );
//       echo '</label>';
//       $checkbox = get_post_meta($post->ID, '_' . $value['field_name'] .
//         '_key', true);
//       var_dump($checkbox);
//       if(!empty($checkbox)) {
//         echo "hiii";
//         update_post_meta( $post_id, $value['field_name'], 'yes' );
//       } else {
//         add_post_meta( $post_id, $value['field_name'], 'yes' );
//       }
//       echo $value['field_name'];
//       echo $profile_value;
//       echo '</div>
//     </p>';
//   }
}
