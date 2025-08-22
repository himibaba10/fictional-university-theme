<?php

if (!is_user_logged_in()) {
    wp_redirect(site_url("/"));
    exit;
}

get_header();
pageBanner();
?>

<div class="container container--narrow page-section">
    <ul class="min-list link-list" id="my-notes">
        <?php
        $notes = new WP_Query(array(
            "post_type" => "note",
            "posts_per_page" => -1,
            "author" => get_current_user_id()
        ));

        if ($notes->have_posts()) {
            while ($notes->have_posts()) {
                $notes->the_post(); ?>

                <li data-id="<?php the_ID(); ?>">
                    <input class="note-title-field" type="text" value="<?php echo esc_attr(the_title()); ?>">
                    <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                    <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i>
                        Delete</span>
                    <textarea class="note-body-field"><?php echo esc_attr(wp_strip_all_tags(get_the_content())) ?> </textarea>
                </li>

            <?php }
        } else { ?>

        <?php } ?>
    </ul>
</div>


<?php get_footer();