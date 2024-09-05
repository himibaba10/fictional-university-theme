<?php

function university_files()
{
    // CSS files:
    wp_enqueue_style("google_font", "//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
    wp_enqueue_style("fontawesome_icons", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");

    wp_enqueue_style("university_main_styles", get_theme_file_uri("/build/style-index.css"));
    wp_enqueue_style("university_extra_styles", get_theme_file_uri("/build/index.css"));

    // JavaScript files:

    // Structure: wp_enqueue_script("nickname", "filepath", "dependency array", "version", "boolean whether the script will run before closing body tag") 
    wp_enqueue_script("university_main_js", get_theme_file_uri("/build/index.js"), array("jquery"), "1.0", true);
}

//wp_enqueue_scripts is for adding files like css and js files
add_action("wp_enqueue_scripts", "university_files");

function university_features()
{
    add_theme_support("title-tag");

    //For registering the navmenu to control from wp dashboard
    register_nav_menu("headerNavMenu", "Header Nav Menu");
}

// after_setup_theme is for adding extra features like adding tab title
add_action("after_setup_theme", "university_features");