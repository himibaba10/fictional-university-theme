<?php $eventDate = new DateTime(get_field("event_date")); ?>
<div class="event-summary">
    <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
        <span class="event-summary__month"><?php echo $eventDate->format("M") ?></span>
        <span class="event-summary__day"><?php echo $eventDate->format("d") ?></span>
    </a>
    <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny"><a
                href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>

        <?php echo has_excerpt() ? get_the_excerpt() : substr(get_the_content(), 0, 80); ?>
        <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
    </div>
</div>