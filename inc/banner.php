<?php
function pageBanner($args = NULL)
{

    if (!isset($args["title"])) {
        $args["title"] = get_the_title();
    }

    if (!isset($args["subtitle"])) {
        $args["subtitle"] = get_field("page_banner_subtitle");
    }

    if (!isset($args["photo"])) {
        if (get_field("page_banner_image")) {
            $args["photo"] = get_field("page_banner_image")["sizes"]["pageBanner"];
        } else {
            $args["photo"] = get_theme_file_uri("/images/ocean.jpg");
        }
    }
    ?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args["photo"]; ?>)">
        </div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args["title"]; ?></h1>
            <div class="page-banner__intro">
                <p style="text-transform: uppercase;"><?php echo $args["subtitle"]; ?></p>
            </div>
        </div>
    </div>

    <?php
}