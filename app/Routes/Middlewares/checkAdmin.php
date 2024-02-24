<?php

function checkAdmin() {
    helpers(['auth/checkAuth']);
    
    if (!checkAuth([1])) return abort();
}