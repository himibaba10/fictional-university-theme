<?php

function university_files()
{
    wp_enqueue_style("google_fonts", "//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
    wp_enqueue_style("fontawesome_icons", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style("university_main_style", get_theme_file_uri("/build/style-index.css"));
    wp_enqueue_style("university_additional_style", get_theme_file_uri("/build/index.css"));

    wp_enqueue_script("university_main_script", get_theme_file_uri("/build/index.js"), array("jquery"), "1.0", true);
}

//wp_enqueue_scripts is for adding css and js files
add_action("wp_enqueue_scripts", "university_files");

function university_features()
{
    // To add title on the browser tab
    add_theme_support("title-tag");

    // To add navigation menu
    register_nav_menu("mainMenuLocation", "Main Menu Location");
}

add_action("after_setup_theme", "university_features");

function university_adjust_queries($query)
{
    $today = date('Ymd');
    if (!is_admin() and is_post_type_archive("event") and $query->is_main_query()) {

        $query->set("posts_per_page", -1);
        $query->set("meta_key", "event_date");
        $query->set("orderby", "meta_value_num");
        $query->set("order", "ASC");
        $query->set("meta_query", array(
            array(
                "key" => "event_date",
                "compare" => ">=",
                "value" => $today,
                "type" => "numeric"
            )
        ));
    }
}

add_action("pre_get_posts", "university_adjust_queries");