<?php get_header();
pageBanner(array(
    "title" => "Past Events",
    "subtitle" => "See the events you missed"
)) ?>

<div class="container container--narrow page-section">
    <?php
    $today = date(format: 'Ymd');
    $pastEvents = new WP_Query(array(
        "post_type" => "event",
        "orderby" => "meta_value_num",
        "meta_key" => "event_date",
        "order" => "ASC",
        "meta_query" => array(
            array(
                "key" => "event_date",
                "compare" => "<",
                "value" => $today,
                "type" => "numeric"
            )
        ),
        "paged" => get_query_var('paged', 1)
    ));
    while ($pastEvents->have_posts()) {
        $pastEvents->the_post();
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
    <?php }
    echo paginate_links(array("total" => $pastEvents->max_num_pages));
    ?>
</div>

<?php get_footer(); ?>