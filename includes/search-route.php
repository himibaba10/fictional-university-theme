<?php

function universityRegisterSearch()
{
    // register_rest_route is used to create custom routes
    register_rest_route("university/v1", "search", array(
        "methods" => WP_REST_Server::READABLE, //which means the "GET" method
        "callback" => "universitySearchResults"
    ));
}

function universitySearchResults($data)
{
    $uniqueEvents = [];
    $uniqueCampuses = [];

    $data = new WP_Query(array(
        "post_type" => array("post", "page", "professor", "program", "campus", "event"),
        "s" => sanitize_text_field($data["term"]) //s means search & $data is the array of the parameters that are used in url. And the sanitize_text_field is a security layer
    ));

    $result = array(
        "generalInfo" => array(),
        "professors" => array(),
        "programs" => array(),
        "campuses" => array(),
        "events" => array()
    );

    while ($data->have_posts()) {
        $data->the_post();

        $selectedData = array(
            "id" => get_the_ID(),
            "title" => get_the_title(),
            "permalink" => get_the_permalink()
        );

        if (get_post_type() == "post" or get_post_type() == "page") {
            array_push($result['generalInfo'], $selectedData);
            continue;
        }

        if (get_post_type() == "professor") {
            $relatedPrograms = get_field("related_programs");
            foreach ($relatedPrograms as $program) {
                array_push($result['programs'], [
                    "id" => $program->ID,
                    "title" => get_the_title($program),
                    "permalink" => get_the_permalink($program)
                ]);
            }

            array_push($result['professors'], [
                ...$selectedData,
                "thumbnail" => get_the_post_thumbnail_url(get_the_ID(), 'professorLandscape')
            ]);
            continue;
        }

        if (get_post_type() == "program") {
            array_push($result['programs'], $selectedData);
            $relatedCampuses = get_field("related_campuses");

            // Related Professors
            $relatedProfessors = new WP_Query(array(
                'posts_per_page' => -1,
                'post_type' => 'professor',
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE',
                        'value' => "$selectedData[id]",
                    )
                )
            ));

            while ($relatedProfessors->have_posts()) {
                $relatedProfessors->the_post();
                array_push($result['professors'], [
                    "id" => get_the_ID(),
                    "title" => get_the_title(),
                    "permalink" => get_the_permalink(),
                    "thumbnail" => get_the_post_thumbnail_url(get_the_ID(), 'professorLandscape')
                ]);
            }

            // Related Events
            $today = date('Ymd');
            $relatedEvents = new WP_Query(array(
                'posts_per_page' => -1,
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
                        'value' => "$selectedData[id]"
                    )
                )
            ));

            while ($relatedEvents->have_posts()) {
                $relatedEvents->the_post();
                if (in_array(get_the_ID(), $uniqueEvents)) {
                    continue;
                }
                $uniqueEvents[] = get_the_ID();
                $eventDate = new DateTime(get_field("event_date"));
                $excerpt = has_excerpt() ? get_the_excerpt() : substr(get_the_content(), 0, 80);
                $month = $eventDate->format("M");
                $day = $eventDate->format("d");
                array_push($result['events'], [
                    "id" => get_the_ID(),
                    "title" => get_the_title(),
                    "permalink" => get_the_permalink(),
                    "excerpt" => $excerpt,
                    "month" => $month,
                    "day" => $day,
                ]);
            }

            // Related Campuses
            foreach ($relatedCampuses as $campus) {
                if (in_array($campus->ID, $uniqueCampuses)) {
                    continue;
                }
                $uniqueCampuses[] = $campus->ID;
                array_push($result['campuses'], [
                    "id" => get_the_ID(),
                    "title" => get_the_title($campus),
                    "permalink" => get_the_permalink($campus)
                ]);
            }
            continue;
        }

        if (get_post_type() == "campus") {
            if (!in_array(get_the_ID(), $uniqueCampuses)) {
                $uniqueCampuses[] = get_the_ID();
                array_push($result['campuses'], $selectedData);
            }

            // Related Programs
            $relatedPrograms = new WP_Query(array(
                'posts_per_page' => -1,
                'post_type' => 'program',
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'related_campuses',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"',
                    )
                )
            ));

            while ($relatedPrograms->have_posts()) {
                $relatedPrograms->the_post();
                array_push($result['programs'], [
                    "id" => get_the_ID(),
                    "title" => get_the_title(),
                    "permalink" => get_the_permalink(),
                ]);
            }

            continue;
        }

        if (get_post_type() == "event") {
            if (in_array(get_the_ID(), $uniqueEvents)) {
                return;
            }
            $uniqueEvents[] = get_the_ID();
            $eventDate = new DateTime(get_field("event_date"));
            $excerpt = has_excerpt() ? get_the_excerpt() : substr(get_the_content(), 0, 80);
            $month = $eventDate->format("M");
            $day = $eventDate->format("d");

            array_push($result['events'], [
                "postType" => get_post_type(),
                "id" => get_the_ID(),
                "title" => get_the_title(),
                "permalink" => get_the_permalink(),
                "excerpt" => $excerpt,
                "month" => $month,
                "day" => $day,
            ]);

            $relatedPrograms = get_field("related_programs");
            foreach ($relatedPrograms as $program) {
                array_push($result['programs'], [
                    "id" => $program->ID,
                    "title" => get_the_title($program),
                    "permalink" => get_the_permalink($program)
                ]);
            }
            continue;
        }
    }

    // These are for removing duplicates
    $result['programs'] = array_values(array_unique($result['programs'], SORT_REGULAR));
    $result['professors'] = array_values(array_unique($result['professors'], SORT_REGULAR));
    $result['campuses'] = array_values(array_unique($result['campuses'], SORT_REGULAR));
    $result['events'] = array_values(array_unique($result['events'], SORT_REGULAR));
    $result['generalInfo'] = array_values(array_unique($result['generalInfo'], SORT_REGULAR));

    return $result;
}

add_action("rest_api_init", "universityRegisterSearch");