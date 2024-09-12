<?php get_header(); ?>

<div class="page-banner">
    <div class="page-banner__bg-image"
        style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>)"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">All Events</h1>
        <div class="page-banner__intro">
            <p>See what is going on in our world.</p>
        </div>
    </div>
</div>

<div class="container container--narrow page-section">
    <?php while (have_posts()) {
        the_post();
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
    echo paginate_links(); ?>
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