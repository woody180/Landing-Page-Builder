<?php

function checkUser() {
    helpers(['auth/checkAuth']);
    
    if (!checkAuth([1, 2, 3])) return abort();
}