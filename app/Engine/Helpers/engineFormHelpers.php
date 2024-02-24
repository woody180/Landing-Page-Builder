<?php

function csrf_field() {
    if (isset($_SESSION['csrf_token']))
        return "<input type=\"hidden\" name=\"csrf_token\" value=\"".$_SESSION['csrf_token']."\" />";
    else
        return '<p style="color: red;">CSRF PROTECTION IS OFF!!!</p>';
}

function csrf_hash() {
    if (isset($_SESSION['csrf_token']))
        return $_SESSION['csrf_token'];
    else
        return 0;
}

// Forms
function getForm(string $val) {
        
    if (hasFlashData('form'))
        return getFlashData('form')->{$val} ?? null;
    else 
        return null;
}

function setForm(array $data) {
    
    setFlashData('form', $data);
}


// Method spoofing
function setMethod(string $method) {
    return '<input name="_method" type="hidden" value="'.$method.'" />';
}


function show_error(string $error_name, string $field_name) {

    if (hasFlashData($error_name)) {
        return '<p style="color: #f0506e; font-size: .875rem; line-height: 1.5; margin-top: 0; margin-bottom: 0;">'.implode(', ', getFlashData($error_name)->{$field_name} ?? []).'</p>';
    }

    return '';
}