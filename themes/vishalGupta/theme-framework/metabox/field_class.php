<?php
if (empty($cutom_post_types)) :

// Set which custom post types you want to include in your theme
    $cutom_post_types = array(
        'Home' => array(
            'menu_name' => 'Home',
        ),
        'Works' => array(
            'menu_name' => 'Works',
            'menu_position' => 58
        )
    );
endif;
/* -------------------------------------------------------------------------------- */
/*
 * Creates costom post as per assignment of $custom_post_types array
 *
 * @return void
 */

function create_custom_post() {
    global $cutom_post_types;
    foreach ($cutom_post_types as $key => $val) :

        $labels = array(
            'name' => _x($key, 'post type general name'),
            'singular_name' => _x($key, 'post type singular name'),
            'add_new' => _x('Add New', $key),
            'add_new_item' => __('Add New ' . $key),
            'edit_item' => __('Edit ' . $key),
            'new_item' => __('New ' . $key),
            'all_items' => __('All ' . $key),
            'view_item' => __('View ' . $key),
            'search_items' => __('Search ' . $key),
            'not_found' => __('No ' . $key . ' found'),
            'not_found_in_trash' => __('No ' . $key . ' found in the Trash'),
            'parent_item_colon' => '',
            'menu_name' => $key,
        );

        if (!empty($val)):
            foreach ($labels as $keys => $vals) :
                if (array_key_exists($keys, $val))
                    $labels[$keys] = $val[$keys];
            endforeach;
        endif;
        $args = array(
            'labels' => $labels,
            'description' => 'Holds our ' . $key . ' specific data',
            'public' => true,
            'menu_position' => $key,
            'query_var' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'taxonomies' => array('post_tag','category'),
            'supports' => array('title','editor', 'thumbnail', 'page-attributes'),
            'has_archive' => true,
        );
        if (!empty($val)):
            foreach ($args as $keys => $vals) :
                if (array_key_exists($keys, $val))
                    $args[$keys] = $val[$keys];
            endforeach;
        endif;
        register_post_type($key, $args);
    endforeach;
}

if (!empty($cutom_post_types)):
    add_action('init', 'create_custom_post', 10);
endif;

// Including class metabox to add fields
//require get_template_directory() . '/framework/admin/metabox/classes/class-metabox.php';

/*
 * Creates costom metabox as per assignment of $cutom_metabox array
 *
 *
 * @return void
 */
if (empty($cutom_metabox)) :

// Set which custom metabox you want to include in your theme
    $cutom_metabox = array(
        'page' => array(
            'SubTitle' => array(
                'field_name' => 'SubTitle',
                'field_type' => 'text',
                'field_description' => 'Sub Title',
                'description' => 'Sub Title',
                'class' => 'textfield',
                'metablock' => TRUE
            )
        ),
        'home' => array(
            'homechoice' => array(
            'field_name' => 'homechoice',
            'field_type' => 'radio',
            'field_description' => 'Please enter your choice',
            'val' => array('About','Technology','Post','Other'),
            'description' => 'Homeslider Radio',
            'class' => 'radio-type',
            'helper_text' => 'Select the post type to display on Home page.',
            'metablock' => TRUE,
            ),
            'aboutImage' => array(
            'field_name' => 'aboutImage',
            'field_type' => 'image',
            'field_description' => 'About Image',
            'description' => 'Homeslider Date',
            'class' => 'about-image',
            'helper_text' => 'Upload you image you want to show in about Section',
            'metablock' => TRUE,
            ),
            'aboutText' => array(
            'field_name' => 'aboutText',
            'field_type' => 'wysiwygEditor',
            'field_description' => 'About Detail',
            'description' => 'Homeslider Textarea',
            'class' => 'about-text',
            'helper_text' => 'Enter you description on below editor',
            'metablock' => TRUE,
            ),

            'aboutCTA' => array(
            'field_name' => 'aboutCTA',
            'field_type' => 'text',
            'field_description' => 'CTA Text',
            'description' => 'Enter you Load more text CTA',
            'helper_text' => 'Enter you description on below editor',
            'class' => 'about-cta',
            'metablock' => TRUE,
            ),
            'aboutCTAlink' => array(
            'field_name' => 'aboutCTAlink',
            'field_type' => 'page-drop',
            'field_description' => 'CTA Link',
            'description' => '',
            'helper_text' => 'Select Page link for you CTA',
            'class' => 'textfield',
            'metablock' => TRUE,
            ),
            'techc' => array(
            'field_name' => 'techc',
            'field_type' => 'image',
            'field_description' => 'Technology Image',
            'description' => 'Homeslider Date',
            'class' => 'tech-image',
            'helper_text' => 'Upload you image you want to show in Center Section',
            'metablock' => TRUE,
            ),
            'techctext' => array(
            'field_name' => 'techctext',
            'field_type' => 'text',
            'field_description' => 'Technology Title',
            'description' => 'Enter you Load more text CTA',
            'helper_text' => 'Enter you description on below editor',
            'class' => 'tech-title',
            'metablock' => TRUE,
            ),
            'tech1' => array(
            'field_name' => 'tech1',
            'field_type' => 'image',
            'field_description' => 'Technology Image 1',
            'description' => '',
            'class' => 'tech-image',
            'helper_text' => 'Upload you image you want to show in Technology 1 Section',
            'metablock' => TRUE,
            ),

            'techCTA' => array(
            'field_name' => 'techCTA',
            'field_type' => 'text',
            'field_description' => 'Technology CTA Text',
            'description' => 'Enter you Load more text CTA',
            'helper_text' => 'Enter you description on below editor',
            'class' => 'about-cta',
            'metablock' => TRUE,
            ),
            'techCTAlink' => array(
            'field_name' => 'techCTAlink',
            'field_type' => 'page-drop',
            'field_description' => 'Technology CTA Link',
            'description' => '',
            'helper_text' => 'Select Page link for you CTA',
            'class' => 'textfield',
            'metablock' => TRUE,
            ),
            'posttype' => array(
            'field_name' => 'posttype',
            'field_type' => 'post-drop',
            'field_description' => 'Post',
            'description' => '',
            'helper_text' => 'Select Post to display on home page',
            'class' => 'textfield',
            'metablock' => TRUE,
            ),
            'postCTA' => array(
            'field_name' => 'postCTA',
            'field_type' => 'text',
            'field_description' => 'Post CTA Text',
            'description' => 'Enter you Load more text CTA',
            'helper_text' => 'Enter you description on below editor',
            'class' => 'about-cta',
            'metablock' => TRUE,
            ),
            'postCTAlink' => array(
            'field_name' => 'postCTAlink',
            'field_type' => 'page-drop',
            'field_description' => 'Post CTA Link',
            'description' => 'Home Page POST Detail',
            'helper_text' => 'Select Page link for you CTA',
            'class' => 'textfield',
            'metablock' => TRUE,
            )

        ),
        'works' => array(
            'subTitle' => array(
            'field_name' => 'subTitle',
            'field_type' => 'text',
            'field_description' => 'Sub Title',
            'description' => 'Sub Title',
            'class' => 'textfield',
            'metablock' => TRUE
            ),
            'postCTA' => array(
            'field_name' => 'postCTA',
            'field_type' => 'text',
            'field_description' => 'Post CTA Text',
            'description' => 'Enter you Load more text CTA',
            'helper_text' => 'Enter you description on below editor',
            'class' => 'about-cta',
            'metablock' => TRUE,
            ),
            'postCTAlink' => array(
            'field_name' => 'postCTAlink',
            'field_type' => 'text',
            'field_description' => 'Post CTA Link',
            'description' => 'Home Page POST Detail',
            'helper_text' => 'Select Page link for you CTA',
            'class' => 'textfield',
            'metablock' => TRUE,
            )
        )
    );
    override_defaultmetabox();
endif;

/*
 * Overriding default costom metabox from $cutom_post_types array
 *
 *
 * @return void
 */

function override_defaultmetabox() {
    global $cutom_post_types, $cutom_metabox;

    foreach ($cutom_post_types as $keypost => $valpost) :
        if (!empty($valpost)):
            foreach ($valpost as $keyspost => $valspost) :
                if ($keyspost == 'metabox') :
                    foreach ($valspost as $keyspost1 => $valspost1) :
                        foreach ($cutom_metabox as $key => $val) :
                            if (!empty($val)):
                                if ($key == $keypost) :
                                    foreach ($val as $keys => $vals) :

                                        // Overriding metabox to default metabox
                                        if ($keys == $keyspost1) :
                                            if (!empty($valspost1)):
                                                $overmeta = $cutom_metabox[$keypost][$keyspost1];
                                                foreach ($valspost1 as $keymetapost => $valmetapost) :
                                                    foreach ($overmeta as $keymeta => $valmeta) :
                                                        if ($keymetapost == $keymeta) :
                                                            $cutom_metabox[$keypost][$keyspost1][$keymeta] = $valmetapost;
                                                        endif;
                                                    endforeach;
                                                endforeach;
                                            else :
                                                $cutom_metabox1 = $valspost1;
                                                $cutom_metabox[$keypost][$keyspost1] = $cutom_metabox1;
                                            endif;
                                        endif;
                                    endforeach;
                                endif;
                            endif;
                        endforeach;
                    endforeach;
                endif;

                // Pushing metabox to default metabox
                if ($keyspost == 'pushmetabox') :
                    append_metabox($valspost, $keypost);
                endif;
            endforeach;
        endif;
    endforeach;
}

/*
 * Function for pushing metabox value to default metabox
 *
 * @return void
 */

function append_metabox($element, $pushkey) {
    global $cutom_metabox, $cutom_post_types;
    foreach ($element as $key => $val) :
        if (!empty($val)):
            if (is_array($val)) :
                foreach ($val as $keys => $vals) :
                    array_push($cutom_metabox[$pushkey], $vals);
                endforeach;
            endif;
        endif;
    endforeach;
}

/* -------------------------------------------------------------------------------- */
/*
 * Creates costom metabox as per assignment of $cutom_metabox array
 *
 * @return void
 */

function call_metabox() {
    global $cutom_metabox;
    foreach ($cutom_metabox as $key => $val) :
        $field_data1 = array();
        if (!empty($val)):
            foreach ($val as $keys => $vals) :
                if (!empty($vals)):
                    $field_data = array(
                        array(
                            'field_name' => $vals['field_name'],
                            'field_type' => $vals['field_type'],
                            'field_description' => $vals['field_description'],
                            'class' => $vals['class'],
                            'val' => $vals['val'],
                            'helper_text' => $vals['helper_text']
                        )
                    );

                    if ($vals['metablock'] == 'One') :
                        $field_data2 = array_merge($field_data1, $field_data);
                        $field_data1 = $field_data2;
                        $metabox_data1 = array(
                            'name' => $key . "_" . $keys,
                            'description' => $vals['description'],
                            'meta_type' => array($key)
                        );
                    elseif ($vals['metablock'] == 'Two') :
                        $field_data3 = array_merge($field_data1, $field_data);
                        $field_data1 = $field_data3;
                        $metabox_data2 = array(
                            'name' => $key . "_" . $keys,
                            'description' => $vals['description'],
                            'meta_type' => array($key)
                        );
                    elseif ($vals['metablock'] === 'Three') :
                        $field_data4 = array_merge($field_data1, $field_data);
                        $field_data1 = $field_data4;
                        $metabox_data3 = array(
                            'name' => $key . "_" . $keys,
                            'description' => $vals['description'],
                            'meta_type' => array($key)
                        );
                    elseif ($vals['metablock'] === 'Four') :
                        $field_data5 = array_merge($field_data1, $field_data);
                        $field_data1 = $field_data5;
                        $metabox_data4 = array(
                            'name' => $key . "_" . $keys,
                            'description' => $vals['description'],
                            'meta_type' => array($key)
                        );
                    elseif ($vals['metablock'] === 'None'):
                        $metabox_data = array(
                            'name' => $key . "_" . $keys,
                            'description' => $vals['description'],
                            'meta_type' => array($key)
                        );
                        new metabox($metabox_data, $field_data);
                    endif;
                endif;
            endforeach;
            if (isset($metabox_data1)) :
                new metabox($metabox_data1, $field_data2);
            elseif (isset($metabox_data2)) :
                new metabox($metabox_data2, $field_data3);
            elseif (isset($metabox_data3)) :
                new metabox($metabox_data3, $field_data4);
            elseif (isset($metabox_data4)) :
                new metabox($metabox_data4, $field_data5);
            endif;
        endif;
    endforeach;
}

if (is_admin()) {
    add_action('load-post.php', 'call_metabox');
    add_action('load-post-new.php', 'call_metabox');
}
?>