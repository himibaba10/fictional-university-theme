<?php get_header();
pageBanner(array(
    "title" => "Search Results",
    "subtitle" => 'You searched for "' . esc_html(get_search_query()) . '"'
));
?>

<div class="container container--narrow page-section">
    <?php while (have_posts()) {
        the_post();
        get_template_part("template-parts/content", get_post_type());
    }
    echo paginate_links();

    if (!have_posts()) { ?>
        <h2 class="headline headline--small-plus">No item found in this search.</h2>
    <?php }
    get_search_form();
    ?>
</div>

<?php get_footer(); ?>