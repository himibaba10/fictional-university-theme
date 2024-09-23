<?php get_header();
pageBanner(array(
    'title' => 'Past Events',
    'subtitle' => 'A recap of our past events.'
));
?>

<div class="container container--narrow page-section">
    <?php
    $pastEvents = new WP_Query(array(
        'paged' => get_query_var("paged", 1),
        'post_type' => 'event',
        'order' => 'DESC',
        'meta_key' => 'event_date',
        'orderby' => 'meta_value_num',
        'meta_query' => array(
            array(
                'key' => 'event_date',
                'compare' => '<',
                'value' => date('Ymd'),
            )
        )
    ));

    while ($pastEvents->have_posts()) {
        $pastEvents->the_post();
        $eventDate = new DateTime(get_field('event_date'));
        ?>
        <div class="event-summary">
            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                <span class="event-summary__month">
                    <?php echo $eventDate->format("M") ?>
                </span>
                <span class="event-summary__day">
                    <?php echo $eventDate->format("d") ?>
                </span>
            </a>
            <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h5>
                <?php echo substr(get_the_content(), 0, 50) ?>
                <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
            </div>
        </div>
    <?php }
    echo paginate_links(array(
        "total" => $pastEvents->max_num_pages
    )); ?>
</div>

<?php get_footer(); ?>

<!-- 
Notes about the functions of this page: 
have_posts() & the_post() = used in while loop to show the posts

the_archive_title() = shows the archive page title
the_archive_description() = shows the archive page description

the_permalink() = post link
the_title() = post title
the_excerpt() = post excerpt
the_date() = shows only one post of the same day (Should not use for that)
the_time() = shows the date of the posts

the_author() = Displays just the author's name.
the_author_link() = Displays the author's name with a link to their website (if applicable).
the_author_posts_link() = Displays the author's name with a link to their posts archive page.

paginate_links() = adds pagination (we have to echo)
-->