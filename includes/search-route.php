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
            "title" => get_the_title(),
            "permalink" => get_the_permalink()
        );

        if (get_post_type() == "post" or get_post_type() == "page") {
            array_push($result['generalInfo'], $selectedData);
        }

        if (get_post_type() == "professor") {
            array_push($result['professors'], $selectedData);
        }

        if (get_post_type() == "program") {
            array_push($result['programs'], $selectedData);
        }

        if (get_post_type() == "campus") {
            array_push($result['campuses'], $selectedData);
        }

        if (get_post_type() == "event") {
            array_push($result['events'], $selectedData);
        }
    }

    return $result;
}

add_action("rest_api_init", "universityRegisterSearch");