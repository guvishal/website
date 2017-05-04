<?php 
add_action('init', 'my_category_module');
function my_category_module() {
 add_action ( 'edit_category_form_fields', 'add_image_cat');
 add_action ( 'edited_category', 'save_image');
 }

 function add_image_cat($tag){
 $category_images = get_option( 'category_images' );
 $category_image = '';
 if ( is_array( $category_images ) && array_key_exists( $tag->term_id, $category_images ) ) {
 $category_image = $category_images[$tag->term_id] ;
 }
 ?>
   <script>

    ( function( $ ) {

      $(document).ready(

        function()
        {
          $('#upload').click(
            function()
            {
              tb_show('', 'media-upload.php?type=image&TB_iframe=true');
              window.send_to_editor = function (html) {
                imgurl = html.match(/src="(.+?)"/);
                $('#category_image').val(imgurl[1]);
                $('#cat_image').attr('src',imgurl[1]);
                tb_remove();
              }
              return false;
            }
            );
        }
        );

    } ) ( jQuery );
  </script>
 <tr>
 <th scope="row" valign="top"><label for="auteur_revue_image">Image</label></th>
 <td>
 <?php
 if ($category_image !=""){
 ?>
 <img src="<?php echo $category_image;?>" alt="" title="" id="cat_image"/>
 <?php
 }
 ?>
 <br/>
  <span>This field allows you to add a picture to illustrate the category. Upload the image from the media tab WordPress and paste its URL here.</span>
 <input type="text" name="category_image" id="category_image" value="<?php echo $category_image; ?>"/><br />
 <input id="upload" type="button" value=" Upload image">

 </td>
 </tr>
 <?php
 }

 function save_image($term_id){
 if ( isset( $_POST['category_image'] ) ) {
 //load existing category featured option
 $category_images = get_option( 'category_images' );
 //set featured post ID to proper category ID in options array
 $category_images[$term_id] =  $_POST['category_image'];
 //save the option array
 update_option( 'category_images', $category_images );
 }
 }
  function admin_scripts()
  {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
  }

  function admin_styles()
  {
    wp_enqueue_style('thickbox');
  }

  add_action('admin_print_scripts', 'admin_scripts');
  add_action('admin_print_styles', 'admin_styles');