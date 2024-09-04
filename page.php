<?php get_header();
while (have_posts()) {
    the_post(); ?>
    <div class="page-banner">
        <div class="page-banner__bg-image"
            style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title(); ?></h1>
            <div class="page-banner__intro">
                <p>THIS WILL BE A SUBTITLE</p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">

        <?php
        // wp_get_post_parent_id takes an id and returns the id of it's parent page. If the page does not have a parent page then it will return 0.
    
        //get_the_ID() return the id of the page
        $theParent = wp_get_post_parent_id(get_the_ID());
        $parentTitle = $theParent ? get_the_title($theParent) : get_the_title();
        $parentID = $theParent ?: get_the_ID();
        $isParent = get_pages(array(
            "child_of" => get_the_ID()
        ));

        if ($theParent) { ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home"
                            aria-hidden="true"></i> Back to
                        <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span>
                </p>
            </div>
        <?php } ?>

        <?php if ($isParent or $theParent) { ?>
            <div class="page-links">
                <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent); ?>"><?php echo $parentTitle ?></a>
                </h2>
                <ul class="min-list">
                    <?php wp_list_pages(array(
                        "title_li" => NULL, //to hide the list title
                        "child_of" => $parentID, //to get the child navigation link of the parent page
                        "sort_column" => "menu_order" //to order navigation menus customly from wordpress 
                    )); ?>
                </ul>
            </div>
        <?php } ?>

        <div class="generic-content"><?php the_content(); ?></div>
    </div>
<?php }

get_footer(); ?>