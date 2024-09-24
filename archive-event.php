<?php get_header();
pageBanner(array(
    'title' => 'All Events',
    'subtitle' => 'See what is going on in our world.'
));
?>

<div class="container container--narrow page-section">
    <?php while (have_posts()) {
        the_post();
        get_template_part("template-parts/content", "event");
    }
    echo paginate_links(); ?>

    <hr class="section-break">

    <p>Looking for a recap of past events? <a href="<?php echo site_url("/past-events") ?>">Check out our past events
            archive</a>.</p>
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