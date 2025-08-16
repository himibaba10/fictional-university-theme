<?php
get_header();
pageBanner();

while (have_posts()) {
    the_post(); ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link("campus") ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> All Campuses
                </a>
                <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>
        <div class="generic-content"><?php the_content(); ?></div>

        <?php
        $campusID = get_the_ID();

        $relatedPrograms = new WP_Query(array(
            "post_type" => "program",
            "posts_per_page" => -1,
            "orderby" => "title",
            "order" => "ASC",
            "meta_query" => array(
                array(
                    "key" => "related_campuses",
                    "compare" => "LIKE",
                    "value" => '"' . $campusID . '"'
                )
            )
        ));

        if ($relatedPrograms->have_posts()) { ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Available Programs at <?php the_title(); ?></h2>

            <ul class="link-list min-list">
                <?php while ($relatedPrograms->have_posts()) {
                    $relatedPrograms->the_post();
                    ?>
                    <li><a href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php } ?>
            </ul>
            <?php wp_reset_postdata();
        } ?>

    </div>

<?php }

get_footer();