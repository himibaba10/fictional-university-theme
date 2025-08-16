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
        get_template_part("template-parts/content", "event");
    }
    echo paginate_links(array("total" => $pastEvents->max_num_pages));
    ?>
</div>

<?php get_footer(); ?>