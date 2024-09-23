<?php get_header();
pageBanner(array(
    'title' => 'All Programs',
    'subtitle' => 'There is something for everyone. Have a look around.'
));
?>

<div class="container container--narrow page-section">
    <ul class="link-list min-list">
        <?php while (have_posts()) {
            the_post(); ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php }
        echo paginate_links(); ?>
    </ul>
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