<?php


function tinyClass(string $pc)  {
    if (checkAuth([1])) {
        if ($pc === 'parent') return 'ld-editable';
        if ($pc === 'child') return 'ld-editable-cage';
    }
    
    return '';
}



function background($section, $sectionName, $isTrue = false)
{
    if ( $isTrue ) return assetsUrl("tinyeditor/filemanager/files/" . $sectionName);
    
    if (!is_null($section->{$sectionName}->background))
        return assetsUrl("tinyeditor/filemanager/files/" . $section->{$sectionName}->background);
    
    return "";
}



function elementEdit($type) {
    if (checkAuth([1])) return '<a href="#" uk-icon="icon: pencil;" class="ld-element-manage" data-type="'.$type.'"></a>';
    return '';
}