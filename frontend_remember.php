<?php
session_set_cookie_params(60 * 60 * 24 * 365 * 20);
session_start();

if (isset($_GET["frontend"])) {
    $_SESSION["frontend"] = $_GET["frontend"];
}