<?php get_header();
pageBanner(); ?>

<div class="container container--narrow page-section">
    <div class="generic-content">
        <form class="search-form" action="<?php echo esc_url(site_url("/")); ?>">
            <label for="s" class="headline headline--medium">Perform a new search:</label>
            <div class="search-form-row">
                <input class="s" type="search" name="s" id="s" placeholder="What are you looking for?" required>
                <input class="search-submit" type="submit" value="Search">
            </div>
        </form>
    </div>
</div>

<?php get_footer();