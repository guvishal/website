<?php

/*************************************************************

Add stagind and dev url in WP header menu.

**************************************************************/

add_action( 'admin_bar_menu', 'development_links', 900 );
function development_links($wp_admin_bar)
{

    $args = array(
        'id'     => 'environment',
        'title' =>  'environment',
        'meta'   => array( 'class' => 'first-toolbar-group' ),
    );
    $wp_admin_bar->add_node( $args );   
    $args = array(); 

    array_push($args,array(
        'id'        =>  'staging',
        'title'     =>  'staging',
        'href'      =>  'http://prdxn.com/staging',
        'parent'    =>  'environment',
    ));  
    array_push($args,array(
        'id'        => 'dev',
        'title'     =>  'Development',
        'href'      =>  'http://prdxn.com/dev',
        'parent'    => 'environment',
    ));
 
    sort($args);
    for($a=0;$a<sizeOf($args);$a++)
    {
        $wp_admin_bar->add_node($args[$a]);
    }          
    
} 

?>