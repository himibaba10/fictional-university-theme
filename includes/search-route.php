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
    $professors = new WP_Query(array(
        "post_type" => "professor",
        "s" => $data["term"] //s means search & $data is the array of the parameters that are used in url
    ));

    $professorsResult = array();

    while ($professors->have_posts()) {
        $professors->the_post();
        array_push($professorsResult, array(
            "title" => get_the_title(),
            "permalink" => get_the_permalink()
        ));
    }

    return $professorsResult;
}

add_action("rest_api_init", "universityRegisterSearch");