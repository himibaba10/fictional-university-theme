<?php

function universitySearchResults($data)
{

    $mainQuery = new WP_Query(array(
        "post_type" => ["professor", "post", "page", "event", "campus", "program"],
        "s" => sanitize_text_field($data["term"])
    ));

    $results = array(
        "generalInfo" => array(),
        "professors" => array(),
        "programs" => array(),
        "events" => array(),
        "campuses" => array()
    );

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();
        $currentID = get_the_ID();

        if (get_post_type() == "post" or get_post_type() == "page") {
            array_push($results["generalInfo"], array(
                "title" => get_the_title(),
                "permalink" => get_the_permalink(),
                "postType" => get_post_type(),
                "authorName" => get_the_author()
            ));
        }

        if (get_post_type() == "professor") {
            $relatedPrograms = get_field("related_programs");

            if ($relatedPrograms) {
                foreach ($relatedPrograms as $program) {
                    array_push($results["programs"], array(
                        "title" => get_the_title($program),
                        "permalink" => get_the_permalink($program),
                    ));
                }
            }

            array_push($results["professors"], array(
                "title" => get_the_title(),
                "permalink" => get_the_permalink(),
                "thumbnail" => get_the_post_thumbnail_url(get_the_ID(), "professorLandscape")
            ));
        }

        if (get_post_type() == "campus") {
            array_push($results["campuses"], array(
                "title" => get_the_title(),
                "permalink" => get_the_permalink(),
            ));
        }

        if (get_post_type() == "program") {
            $relatedCampuses = get_field("related_campuses");

            if ($relatedCampuses) {
                foreach ($relatedCampuses as $campus) {
                    array_push($results["campuses"], array(
                        "title" => get_the_title($campus),
                        "permalink" => get_the_permalink($campus),
                    ));
                }
                wp_reset_postdata();
            }

            array_push($results["programs"], array(
                "title" => get_the_title(),
                "permalink" => get_the_permalink(),
            ));
        }

        if (get_post_type() == "event") {
            $eventDate = new DateTime(get_field("event_date"));
            $eventMonth = $eventDate->format("M");
            $eventDate = $eventDate->format("d");
            array_push($results["events"], array(
                "title" => get_the_title(),
                "permalink" => get_the_permalink(),
                "excerpt" => wp_trim_words(get_the_excerpt(), 7),
                "eventMonth" => $eventMonth,
                "eventDate" => $eventDate
            ));

            $relatedPrograms = get_field("related_programs");

            if ($relatedPrograms) {
                foreach ($relatedPrograms as $program) {
                    array_push($results["programs"], array(
                        "title" => get_the_title($program),
                        "permalink" => get_the_permalink($program),
                    ));
                }
            }
        }

        $relatedProfessors = new WP_Query(array(
            "post_type" => "professor",
            "meta_query" => array(
                array(
                    "key" => "related_programs",
                    "compare" => "LIKE",
                    "value" => '"' . $currentID . '"'
                )
            )
        ));

        if ($relatedProfessors->have_posts()) {
            while ($relatedProfessors->have_posts()) {
                $relatedProfessors->the_post();

                array_push($results["professors"], array(
                    "title" => get_the_title(),
                    "permalink" => get_the_permalink(),
                    "thumbnail" => get_the_post_thumbnail_url(get_the_ID(), "professorLandscape")
                ));

            }
            wp_reset_postdata();
        }

        $relatedPrograms = new WP_Query(array(
            "post_type" => "program",
            "posts_per_page" => -1,
            "orderby" => "title",
            "order" => "ASC",
            "meta_query" => array(
                array(
                    "key" => "related_campuses",
                    "compare" => "LIKE",
                    "value" => '"' . $currentID . '"'
                )
            )
        ));

        if ($relatedPrograms->have_posts()) {
            while ($relatedPrograms->have_posts()) {
                $relatedPrograms->the_post();

                array_push($results["programs"], array(
                    "title" => get_the_title(),
                    "permalink" => get_the_permalink(),
                ));
            }
            wp_reset_postdata();
        }

        $today = date(format: 'Ymd');
        $relatedEvents = new WP_Query(array(
            "post_type" => "event",
            "meta_query" => array(
                array(
                    "key" => "event_date",
                    "compare" => ">=",
                    "value" => $today,
                    "type" => "numeric"
                ),
                array(
                    "key" => "related_programs",
                    "compare" => "LIKE",
                    "value" => '"' . $currentID . '"'
                )
            )
        ));

        if ($relatedEvents->have_posts()) {
            while ($relatedEvents->have_posts()) {
                $relatedEvents->the_post();

                $eventDate = new DateTime(get_field("event_date"));
                $eventMonth = $eventDate->format("M");
                $eventDate = $eventDate->format("d");

                array_push($results["events"], array(
                    "title" => get_the_title(),
                    "permalink" => get_the_permalink(),
                    "excerpt" => wp_trim_words(get_the_excerpt(), 7),
                    "eventMonth" => $eventMonth,
                    "eventDate" => $eventDate
                ));

                // if (!itemExists($results["events"], get_the_title())) {

                // }
            }
            wp_reset_postdata();
        }

    }

    $results['programs'] = array_unique($results['programs'], SORT_REGULAR);
    $results['campuses'] = array_unique($results['campuses'], SORT_REGULAR);
    $results['events'] = array_unique($results['events'], SORT_REGULAR);
    $results['generalInfo'] = array_unique($results['generalInfo'], SORT_REGULAR);
    $results['professors'] = array_unique($results['professors'], SORT_REGULAR);

    return $results;
}

function universityRegisterSearch()
{
    register_rest_route("university/v1", "search", array(
        "methods" => WP_REST_SERVER::READABLE,
        "callback" => "universitySearchResults"
    ));
}

add_action("rest_api_init", "universityRegisterSearch");