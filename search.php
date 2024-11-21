<?php get_header();
pageBanner(array(
    'title' => 'Search Results',
    'subtitle' => 'You searched for "' . esc_html(get_search_query(false)) . '"' //get_search_query() returns the search input and esc_html prevents cross site scripting attacks
));
?>

<div class="container container--narrow page-section">
    <?php while (have_posts()) {
        the_post();
        get_template_part("template-parts/content", "professor");
    }
    echo paginate_links(); ?>
</div>

<?php get_footer(); ?>