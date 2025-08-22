<?php

require get_theme_file_path("/inc/search-route.php");
require get_theme_file_path("/inc/banner.php");

function university_custom_rest()
{
    register_rest_field("post", "authorName", array(
        "get_callback" => function () {
            return get_the_author();
        }
    ));
}

add_action("rest_api_init", "university_custom_rest");

function university_files()
{
    wp_enqueue_style("google_fonts", "//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
    wp_enqueue_style("fontawesome_icons", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style("university_main_style", get_theme_file_uri("/build/style-index.css"));
    wp_enqueue_style("university_additional_style", get_theme_file_uri("/build/index.css"));

    wp_enqueue_script("university_main_script", get_theme_file_uri("/build/index.js"), array("jquery"), "1.0", true);

    // To provide necessary variables to the javascript file
    wp_localize_script("university_main_script", "universityData", array(
        "rootUrl" => get_site_url(),
        "nonce" => wp_create_nonce("wp_rest") //to let WP know who is using the REST API methods
    ));
}

//wp_enqueue_scripts is for adding css and js files
add_action("wp_enqueue_scripts", "university_files");

function university_features()
{
    // To add title on the browser tab
    add_theme_support("title-tag");

    // To featured image
    add_theme_support("post-thumbnails");

    // Add image size
    add_image_size("professorLandscape", 400, 260, true);
    add_image_size("professorPortrait", 480, 650, true);
    add_image_size("pageBanner", 1500, 350, crop: true);

    // To add navigation menu
    register_nav_menu("mainMenuLocation", "Main Menu Location");
}

add_action("after_setup_theme", "university_features");

function university_adjust_queries($query)
{
    $today = date('Ymd');
    if (!is_admin() and is_post_type_archive("event") and $query->is_main_query()) {
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

    if (!is_admin() and is_post_type_archive("program") and $query->is_main_query()) {
        $query->set("posts_per_page", -1);
        $query->set("orderby", "title");
        $query->set("order", "ASC");
    }
}

add_action("pre_get_posts", "university_adjust_queries");

// Redirects subscribers to frontend
function redirectSubsToFrontend()
{
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) == 1 and $currentUser->roles[0] == "subscriber") {
        wp_redirect(site_url("/"));
        exit;
    }
}

add_action("admin_init", "redirectSubsToFrontend");

function noSubsAdminBar()
{
    $currentUser = wp_get_current_user();
    if (count($currentUser->roles) == 1 and $currentUser->roles[0] == "subscriber") {
        show_admin_bar(false);
    }
}

add_action("wp_loaded", "noSubsAdminBar");

// Customize WP login page 
function universityHeaderUrl()
{
    return esc_url(site_url("/"));
}

add_filter("login_headerurl", "universityHeaderUrl");

// Inserting css files in WP login page (by default, WP doesn't load it)
function universityLoginCSS()
{
    wp_enqueue_style("google_fonts", "//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
    wp_enqueue_style("fontawesome_icons", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style("university_main_style", get_theme_file_uri("/build/style-index.css"));
    wp_enqueue_style("university_additional_style", get_theme_file_uri("/build/index.css"));
}

add_action("login_enqueue_scripts", "universityLoginCSS");

// Changing title of WP Login page
function universityLoginTitle()
{
    return get_bloginfo("name");
}

add_filter("login_headertitle", "universityLoginTitle");

// Force Notes to be private
function customizeNotes($data, $postArr)
{
    if ($data["post_type"] == "note") {
        if (count_user_posts(get_current_user_id(), "note") > 4 and !$postArr["ID"]) {
            die("You have reached your note limit.");
        }

        $data["post_title"] = sanitize_text_field($data["post_title"]);
        $data["post_content"] = sanitize_textarea_field($data["post_content"]);
    }

    if ($data["post_type"] == "note" and $data["post_status"] != "trash") {
        $data["post_status"] = "private";
    }
    return $data;
}

add_filter("wp_insert_post_data", "customizeNotes", 10, 2);