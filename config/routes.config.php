<?php

/**
 * An array of available API route rules describing all the different endpoints using regular expressions
 */
return [
    [
        "path"       => "/characters/(\\d+)$",
        "verb"       => "GET",
        "controller" => "CharacterController",
        "action"     => "fetch"
    ],
    [
        "path"       => "/characters$",
        "verb"       => "GET",
        "controller" => "CharacterController",
        "action"     => "fetchAll"
    ],
    [
        "path"       => "/characters$",
        "verb"       => "POST",
        "controller" => "CharacterController",
        "action"     => "create"
    ],
    [
        "path"       => "/characters/(\\d+)$",
        "verb"       => "PATCH",
        "controller" => "CharacterController",
        "action"     => "update"
    ],
    [
        "path"       => "/characters/(\\d+)$",
        "verb"       => "DELETE",
        "controller" => "CharacterController",
        "action"     => "delete"
    ],
];
