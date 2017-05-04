<?php 
add_action('init', 'radio_category_module');
function radio_category_module() {
 add_action ( 'edit_category_form_fields', 'add_radio_cat');
 add_action ( 'edited_category', 'save_radio');
 }

 function add_radio_cat($tag){
 $category_radio = get_option( 'category_radio' );
 if ( is_array( $category_radio ) && array_key_exists( $tag->term_id, $category_radio ) ) {
 $category_radio = $category_radio[$tag->term_id] ;
 }


 ?>

 <tr>
 <th scope="row" valign="top"><label for="auteur_revue_image">Image</label></th>
 <td>
 <br/>
  <span>This field allows you to add a picture to illustrate the category. Upload the image from the media tab WordPress and paste its URL here.</span>

   <input type="radio" name="category_radio" value="dev" <?php if (isset($category_radio) && $category_radio=="dev") echo "checked";?>> Development<br>
  <input type="radio" name="category_radio" value="task"  <?php if (isset($category_radio) && $category_radio=="task") echo "checked";?>> Task Manager<br>
  <input type="radio" name="category_radio" value="svn"  <?php if (isset($category_radio) && $category_radio=="svn") echo "checked";?>> SVN<br>
  <input type="radio" name="category_radio" value="cms"  <?php if (isset($category_radio) && $category_radio=="cms") echo "checked";?>> CMS<br>
  <input type="radio" name="category_radio" value="db"  <?php if (isset($category_radio) && $category_radio=="db") echo "checked";?>> Database<br>
  <input type="radio" name="category_radio" value="ide"  <?php if (isset($category_radio) && $category_radio=="ide") echo "checked";?>> IDE and DevTools<br>
    <input type="radio" name="category_radio" value="other"  <?php if (isset($category_radio) && $category_radio=="other") echo "checked";?>> Other<br>

 </td>
 </tr>
 <?php
 }

 function save_radio($term_id){


 if ( isset( $_POST['category_radio'] ) ) {
 //load existing category featured option
 $category_radios = get_option( 'category_radio' );
 //set featured post ID to proper category ID in options array
 $category_radios[$term_id] =  $_POST['category_radio'];
 //save the option array
 update_option( 'category_radio', $category_radios );
 }
 }
