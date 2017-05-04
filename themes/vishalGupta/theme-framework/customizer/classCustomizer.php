<?php

/*******************************************************************
* This function added the setting option in cutomizer. 
*
* @author Vishal Gupta
*
*
**********************************************************************/



/*
*
* Header Section
*
************************************************************************/
function add_customizer_setting($wp_customize){

$wp_customize -> add_section('header_section',array(
		'title' => 'Header Section',
		));

	 $wp_customize -> add_setting('header_logo');

	 $wp_customize->add_control(
    new WP_Customize_Image_Control(
        $wp_customize,
        'header_logo',
        array(
            'label'    => 'Site Logo',
            'settings' => 'header_logo',
            'section'  => 'header_section'
        )
    )
);

	 	 $wp_customize -> add_setting('header_video_banner');

	 $wp_customize->add_control(
    new WP_Customize_Image_Control(
        $wp_customize,
        'header_video_banner',
        array(
            'label'    => 'Header Video Banner',
            'settings' => 'header_video_banner',
            'section'  => 'header_section'
        )
    )
);


	 $wp_customize -> add_setting('header_title');

	$wp_customize -> add_control(new wp_Customize_Control($wp_customize, 
		'header_section_title',array(
			'label' => 'Header Banner Title',
			'section' => 'header_section',
			'settings' => 'header_title',
		)));


	 $wp_customize -> add_setting('header_subtitle');

	$wp_customize -> add_control(new wp_Customize_Control($wp_customize, 
		'header_section_subtitle',array(
			'label' => 'Header Banner Subtitle',
			'section' => 'header_section',
			'settings' => 'header_subtitle',
		)));



	 $wp_customize -> add_setting('header_cta1');

	$wp_customize -> add_control(new wp_Customize_Control($wp_customize, 
		'header_section_cta1',array(
			'label' => 'Header Banner CTA1',
			'section' => 'header_section',
			'settings' => 'header_cta1',
		)));


	 $wp_customize -> add_setting('header_cta1_dropdown');

	$wp_customize -> add_control(new wp_Customize_Control($wp_customize, 
		'cta1_section_link_control',array(
			'label' => 'CTA 1 link',
			'section' => 'header_section',
			'settings' => 'header_cta1_dropdown',
			'type' => 'dropdown-pages',
		)));


	 $wp_customize -> add_setting('header_cta2');

	$wp_customize -> add_control(new wp_Customize_Control($wp_customize, 
		'header_section_cta2',array(
			'label' => 'Header Banner CTA2',
			'section' => 'header_section',
			'settings' => 'header_cta2',
		)));


	 $wp_customize -> add_setting('header_cta2_dropdown');

	$wp_customize -> add_control(new wp_Customize_Control($wp_customize, 
		'cta2_section_link_control',array(
			'label' => 'CTA 2 link',
			'section' => 'header_section',
			'settings' => 'header_cta2_dropdown',
		)));


/*
*
* Footer Section
*
************************************************************************/

	$wp_customize -> add_section('footer_section',array(
		'title' => 'Footer Section',
		));

	 $wp_customize -> add_setting('footer_email_id');

	$wp_customize -> add_control(new wp_Customize_Control($wp_customize, 
		'footer_section_email_id',array(
			'label' => 'Enter email ID',
			'section' => 'footer_section',
			'settings' => 'footer_email_id',


		)));

		$wp_customize -> add_setting('footer_contact_no', array(
		'deafult' => 'Mobile Number',
		));

	$wp_customize -> add_control(new wp_Customize_Control($wp_customize, 
		'footer_section_mobile_no',array(
			'label' => 'Enter Mobile no',
			'section' => 'footer_section',
			'settings' => 'footer_contact_no',
		)));
}

add_action('customize_register', 'add_customizer_setting' );




?>
