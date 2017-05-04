<?php

/*
Add automic include functionalities
================================================================================================*/

$dirs = array_filter(glob(get_template_directory() . '/theme-framework/*'), 'is_dir');

foreach ($dirs as $dir):
    foreach(glob($dir . '/*.php') as $file) :
        include_once $file;
    endforeach;
endforeach;

