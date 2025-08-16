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
                    $eventDate = new DateTime(get_field("event_date"));
                    $eventMonth = $eventDate->format("M");
                    $eventDate = $eventDate->format("d");
                    ?>
                    <div class="event-summary">
                        <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                            <span class="event-summary__month"><?php echo $eventMonth; ?></span>
                            <span class="event-summary__day"><?php echo $eventDate; ?></span>
                        </a>
                        <div class="event-summary__content">
                            <h5 class="event-summary__title headline headline--tiny"><a
                                    href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h5>
                            <p>
                                <?php echo wp_trim_words(get_the_excerpt(), 7) ?>
                                <a href="<?php the_permalink(); ?>" class="nu gray">Read more</a>
                            </p>
                        </div>
                    </div>
                <?php } ?>
            </ul>
            <?php wp_reset_postdata();
        } ?>

    </div>

<?php }

get_footer();