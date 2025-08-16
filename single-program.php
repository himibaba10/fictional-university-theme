<?php
get_header();
pageBanner();

while (have_posts()) {
    the_post(); ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link("program") ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> All Programs
                </a>
                <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>
        <div class="generic-content"><?php the_content(); ?></div>

        <?php
        $programID = get_the_ID();

        $relatedProfessors = new WP_Query(array(
            "post_type" => "professor",
            "posts_per_page" => -1,
            "orderby" => "title",
            "order" => "ASC",
            "meta_query" => array(
                array(
                    "key" => "related_programs",
                    "compare" => "LIKE",
                    "value" => '"' . $programID . '"'
                )
            )
        ));

        if ($relatedProfessors->have_posts()) { ?>
            <hr class="section-break">
            <h2 class="headline headline--medium"><?php the_title(); ?> Taught By</h2>
            <ul class="professor-cards">
                <?php while ($relatedProfessors->have_posts()) {
                    $relatedProfessors->the_post();
                    ?>
                    <li class="professor-card__list-item">
                        <a class="professor-card" href="<?php the_permalink(); ?>">
                            <img class="professor-card__image" src="<?php the_post_thumbnail_url("professorLandscape"); ?>"
                                alt="<?php the_title(); ?>">
                            <span class="professor-card__name"><?php the_title(); ?></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
            <?php wp_reset_postdata();
        }

        $today = date(format: 'Ymd');

        $relatedEvents = new WP_Query(array(
            "post_type" => "event",
            "posts_per_page" => -1,
            "meta_query" => array(
                array(
                    "key" => "event_date",
                    "compare" => ">=",
                    "value" => $today,
                    "type" => "numeric"
                ),
                array(
                    "key" => "related_programs",
                    "compare" => "LIKE",
                    "value" => '"' . $programID . '"'
                )
            )
        ));

        if ($relatedEvents->have_posts()) { ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Upcoming <?php the_title(); ?> Events</h2>
            <ul class="min-list">
                <?php while ($relatedEvents->have_posts()) {
                    $relatedEvents->the_post();
                    get_template_part("template-parts/content", "event");
                } ?>
            </ul>
            <?php wp_reset_postdata();
        }

        $relatedCampuses = get_field("related_campuses");

        if ($relatedCampuses) { ?>
            <hr class="section-break">
            <h2 class="headline headline--medium"><?php the_title(); ?> is Taught by:</h2>
            <ul class="link-list min-list">
                <?php foreach ($relatedCampuses as $program) { ?>
                    <li><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>
                <?php } ?>
            </ul>
        <?php }
        wp_reset_postdata(); ?>

    </div>

<?php }

get_footer();