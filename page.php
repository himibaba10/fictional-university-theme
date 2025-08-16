<?php get_header();
pageBanner();

while (have_posts()) {
    the_post();
    $parent_id = wp_get_post_parent_id();
    $parent_title = get_the_title($parent_id);
    $parent_permalink = get_the_permalink($parent_id);
    ?>

    <div class="container container--narrow page-section">
        <?php if ($parent_id) { ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo $parent_permalink; ?>">
                        <i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo $parent_title; ?>
                    </a>
                    <span class="metabox__main"><?php the_title(); ?></span>
                </p>
            </div>
        <?php } ?>

        <?php

        $childIDOrParentID = NULL;
        if ($parent_id) {
            $childIDOrParentID = $parent_id;
        } else {
            $hasChildPages = get_pages(array(
                "child_of" => get_the_ID()
            ));

            if ($hasChildPages) {
                $childIDOrParentID = get_the_ID();
            }
        }

        if ($childIDOrParentID) {

            ?>
            <div class="page-links">
                <h2 class="page-links__title"><a
                        href="<?php echo get_the_permalink($parent_id) ?>"><?php echo get_the_title($parent_id) ?></a></h2>
                <ul class="min-list">
                    <?php wp_list_pages(array(
                        "title_li" => NULL,
                        "child_of" => $childIDOrParentID,
                    )); ?>
                </ul>
            </div>
        <?php } ?>

        <div class="generic-content"><?php the_content(); ?></div>
    </div>

<?php }
get_footer();