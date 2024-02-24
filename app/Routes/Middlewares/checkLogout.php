<?php

function checkLogout() {
    if (isset($_SESSION['userid']))
        return header('Location: ' . baseUrl('users/profile/' . $_SESSION['userid']));
}