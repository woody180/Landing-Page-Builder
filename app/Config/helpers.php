<?php


/*
* Loading custom helpers is possible from extending array with file names only, without extention name (php);
* Helpers loaded from this directory will be available globally
* Also, it is possible to load helpers from specific routes/controllers only by using function -
* helpers(['helperFileNameOne', 'helperFileNameTwo'])
*/


CONST CUSTOM_HELPERS = [
    'htmlHelper',
    'minifier/minifier',
    'auth/checkAuth',
    'tinyeditor/tinyClass'
];