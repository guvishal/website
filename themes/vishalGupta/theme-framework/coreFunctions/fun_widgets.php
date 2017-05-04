<?php

/*
Add Widgets
================================================================================================*/

function prdxn_widget_setup() {
    register_sidebar(
        array(
            'name'  => 'sidebar',
            'id'    => 'sidebar-1',
            'class' => 'custom-sidebar',
            'description'   =>  'Stander Sidebar',
            'before_widget' =>  '<aside class="widget %2$s">',
            'after_widget'  =>  '</aside>',
            'before_title'  =>  '<h2>',
            'after_title'   =>  '</h2>',
        )
    );
}

add_action('widgets_init', 'prdxn_widget_setup');

?>