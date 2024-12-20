<?php get_header();
while (have_posts()) {
    the_post();
    pageBanner(); ?>

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

        <div class="generic-content">
            <form class="search-form" action="/">
                <label for="s" class="headline headline--medium">Perform a new search</label>
                <div class="search-form-row">
                    <input placeholder="What are you looking for?" type="search" class="s" name="s" id="s">
                    <input class="search-submit" type="submit" value="Search">
                </div>
            </form>
        </div>
    </div>
<?php }

get_footer(); ?>