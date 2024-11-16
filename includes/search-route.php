<?php

function universityRegisterSearch()
{
    // register_rest_route is used to create custom routes
    register_rest_route("university/v1", "search", array(
        "methods" => WP_REST_Server::READABLE, //which means the "GET" method
        "callback" => "universitySearchResults"
    ));
}

function universitySearchResults()
{
    return "Yay! Our new URL!";
}

add_action("rest_api_init", "universityRegisterSearch");