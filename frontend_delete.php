<?php
session_set_cookie_params(60 * 60 * 24 * 365 * 20);
session_start();

unset($_SESSION["frontend"]);