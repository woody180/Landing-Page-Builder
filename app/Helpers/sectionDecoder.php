<?php

function sectionDecoder($sections)
{
    $decodedBodies = new stdClass();
    foreach($sections as $key => $section) {
        $body = json_decode($section->body);
        $section->body = $body;
        $decodedBodies->{$key} = $section;
    }

    return $decodedBodies;
}