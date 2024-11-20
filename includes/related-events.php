<?php

function get_related_events($post_id = null)
{
    if ($post_id === null) {
        $post_id = get_the_ID();
    }

    $today = date('Ymd');

    return new WP_Query(array(
        'posts_per_page' => 2,
        'post_type' => 'event',
        'meta_key' => 'event_date',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            ),
            array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . $post_id . '"',
            )
        )
    ));
}