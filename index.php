<?php get_header();
pageBanner(array(
    'title' => 'Welcome to Blog page',
    'subtitle' => 'Keep up with the latest news'
));
?>

<div class="container container--narrow page-section">
    <?php while (have_posts()) {
        the_post(); ?>

        <div class="post-item">
            <h2 class="headline headline--medium headline--post-title"><a
                    href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="metabox">
                <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time("j.n.y"); ?> in
                    <?php echo get_the_category_list(", "); ?>
                </p>
            </div>

            <div class="generic-content">
                <?php the_excerpt(); ?>
                <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading &raquo;</a></p>
            </div>
        </div>
    <?php }
    echo paginate_links(); ?>
</div>

<?php get_footer(); ?>

<!-- 
Notes about the functions of this page: 
have_posts() & the_post() = used in while loop to show the posts

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