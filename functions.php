<?php

function university_main_rest()
{
    //register_rest_field is used to add a new field
    register_rest_field("post", "authorName", array(
        "get_callback" => function () {
            return get_author_name();
        }
    ));
}

// rest_api_init action is used to manipulate the rest api
add_action("rest_api_init", "university_main_rest");

function pageBanner($args = NULL)
{
    if (!isset($args['title'])) {
        $args['title'] = get_the_title();
    }

    if (!isset($args['subtitle'])) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if (!isset($args['photo'])) {
        if (get_field('page_banner_background_image') and !is_archive() and !is_home()) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }
    ?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args["photo"] ?>)">
        </div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle']; ?></p>
            </div>
        </div>
    </div>

<?php }

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

    wp_localize_script("university_main_js", "universityData", array(
        "root_url" => get_site_url()
    ));
}

//wp_enqueue_scripts is for adding files like css and js files
add_action("wp_enqueue_scripts", "university_files");

function university_features()
{
    add_theme_support("title-tag");

    //For registering the navmenu to control from wp dashboard
    register_nav_menu("headerNavMenu", "Header Nav Menu");

    //To add featured images
    add_theme_support('post-thumbnails');

    //to resize extra image size (shortName, width, height, crop)
    add_image_size("professorLandscape", 400, 260, true);
    add_image_size("professorPortrait", 480, 650, true);
    add_image_size("pageBanner", 1500, 350, true);
}

// after_setup_theme is for adding extra features like adding tab title
add_action("after_setup_theme", "university_features");

function university_adjust_queries($query)
{
    //For Event post type
    if (!is_admin() and is_post_type_archive("event") and is_main_query()) {
        $query->set("meta_key", "event_date");
        $query->set("orderby", "meta_value");
        $query->set("order", "ASC");
        $query->set("meta_query", array(
            array(
                "key" => "event_date",
                "compare" => ">=",
                "value" => current_time("Ymd"),
                "type" => "numeric"
            )
        ));
    }

    //For Program post type
    if (!is_admin() and is_post_type_archive("program") and is_main_query()) {
        $query->set("orderby", "title");
        $query->set("order", "ASC");
    }
}

add_action("pre_get_posts", "university_adjust_queries");
