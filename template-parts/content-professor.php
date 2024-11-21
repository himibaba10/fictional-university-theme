<div class="post-item">
    <div class="row group">
        <div class="one-third"><?php the_post_thumbnail("professorPortrait"); ?></div>
        <div class="two-thirds">
            <h2 class="headline headline--medium headline--post-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <?php the_content(); ?>
        </div>
    </div>
</div>