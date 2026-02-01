<?php
session_set_cookie_params(60 * 60 * 24 * 365 * 20);
session_start();

$req_uri = strtok($_SERVER["REQUEST_URI"], "?");

$parts = explode("/", $req_uri);
$parts = array_reverse($parts);

function hsc($text) {
    return htmlspecialchars($text, ENT_HTML5 + ENT_QUOTES);
}

$post = "";
$username = "";
foreach ($parts as $part) {
    if (!empty($part)) {
        if (substr($part, 0, 1) === "@") {
            $username = $part;
            break;
        }
        $post = $part;
    }
}

if (empty($username)) {
    require("link.html");
    die();
}

if (isset($_SESSION["frontend"]) and !isset($_GET["force_select"])) {
    if (!empty($post)) {
        switch ($_SESSION["frontend"]) {
            case "hive-blog":
                header("Location: https://hive.blog/" . hsc($username) . "/" . hsc($post));
                break;
            case "peakd":
                header("Location: https://peakd.com/" . hsc($username) . "/" . hsc($post));
                break;
            case "ecency":
                header("Location: https://ecency.com/" . hsc($username) . "/" . hsc($post));
                break;
            case "personal-community":
                header("Location: https://personal.community/@" . hsc(substr($username, 1)) . "/" . hsc($post));
                break;
        }
    } else {
        switch ($_SESSION["frontend"]) {
            case "hive-blog":
                header("Location: https://hive.blog/" . hsc($username));
                break;
            case "peakd":
                header("Location: https://peakd.com/" . hsc($username));
                break;
            case "ecency":
                header("Location: https://ecency.com/" . hsc($username));
                break;
            case "personal-community":
                header("Location: https://personal.community/?hive=" . hsc(substr($username, 1)));
                break;
        }
    }
}
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>HiveLink - Choose Your Hive Frontend</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Universal link service for Hive blockchain - choose your preferred frontend">
        <link rel="stylesheet" href="/dist/output.css">
        <script
                src="https://code.jquery.com/jquery-3.5.1.min.js"
                integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
                crossorigin="anonymous"></script>
    </head>

    <body class="bg-slate-100 dark:bg-slate-900 min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-8">
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-bold text-slate-800 dark:text-white mb-2">
                        HiveLink
                    </h1>
                    <?php if (!empty($post)) { ?>
                        <p class="text-gray-700 dark:text-gray-300">Link to a post by <span class="font-semibold text-purple-600 dark:text-purple-400"><?=hsc($username)?></span></p>
                    <?php } else { ?>
                        <p class="text-gray-700 dark:text-gray-300"><span class="font-semibold text-purple-600 dark:text-purple-400"><?=hsc($username)?></span>'s Profile</p>
                    <?php } ?>
                </div>

                <div class="space-y-3 mb-6">
                    <?php if (!empty($post)) { ?>
                        <a data-frontend="hive-blog" href="https://hive.blog/<?=hsc($username) . "/" . hsc($post)?>"
                           class="frontend-link block w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-all duration-200 text-center">
                            Hive.Blog
                        </a>
                        <a data-frontend="peakd" href="https://peakd.com/<?=hsc($username) . "/" . hsc($post)?>"
                           class="frontend-link block w-full px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-lg font-medium transition-all duration-200 text-center">
                            PeakD
                        </a>
                        <a data-frontend="ecency" href="https://ecency.com/<?=hsc($username) . "/" . hsc($post)?>"
                           class="frontend-link block w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200 text-center">
                            Ecency
                        </a>
                        <a data-frontend="personal-community" href="https://personal.community/@<?=hsc(substr($username, 1)) . "/" . hsc($post) ?>"
                           class="frontend-link block w-full px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-all duration-200 text-center">
                            Personal.Community
                        </a>
                    <?php } else { ?>
                        <a data-frontend="hive-blog" href="https://hive.blog/<?=hsc($username)?>"
                           class="frontend-link block w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-all duration-200 text-center">
                            Hive.Blog
                        </a>
                        <a data-frontend="peakd" href="https://peakd.com/<?=hsc($username)?>"
                           class="frontend-link block w-full px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-lg font-medium transition-all duration-200 text-center">
                            PeakD
                        </a>
                        <a data-frontend="ecency" href="https://ecency.com/<?=hsc($username)?>"
                           class="frontend-link block w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200 text-center">
                            Ecency
                        </a>
                        <a data-frontend="personal-community" href="<?="https://personal.community/?hive=" . hsc(substr($username,1))?>"
                           class="frontend-link block w-full px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-all duration-200 text-center">
                            Personal.Community
                        </a>
                    <?php } ?>
                </div>

                <label class="flex items-center gap-2 mb-4 cursor-pointer">
                    <input type="checkbox" <?=(isset($_GET["force_select"]) and isset($_SESSION["frontend"])) ? "disabled='disabled'" : "" ?>
                           id="always-frontend" class="w-4 h-4 text-purple-600 dark:text-purple-400 rounded focus:ring-purple-500 dark:focus:ring-purple-400">
                    <span class="text-gray-700 dark:text-gray-300 text-sm">Always use this frontend</span>
                </label>

                <?php if (isset($_GET["force_select"]) and isset($_SESSION["frontend"])) { ?>
                <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 p-3 mb-4 rounded">
                    <p class="text-red-800 dark:text-red-300 font-semibold text-sm">Why am I being asked to select?</p>
                    <p class="text-red-700 dark:text-red-400 text-xs">The link creator has asked us to show you this screen</p>
                </div>
                <?php } ?>

                <div class="text-center text-sm text-gray-600 dark:text-gray-400 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p>Tool by <a href="https://hivel.ink/@cadawg" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-medium">CADawg</a></p>
                    <p class="mt-1"><a class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-medium" href="https://vote.hive.uno/@cadawg">Vote me for witness</a></p>
                </div>
            </div>
        </div>

    <script>
            $(".frontend-link").on("click", function(event) {
                if (document.getElementById("always-frontend").checked) {
                    event.preventDefault();
                    $.get("/frontend_remember.php", "frontend=" + $(event.target).data("frontend"), function () {
                        window.location.href = $(event.target).attr("href");
                    });
                }
            })
    </script>
    <!-- Privacy-friendly analytics by Plausible -->
    <script async src="https://a.dbuidl.com/js/pa-3r06ZeR9fWME4rmDQ4LmT.js"></script>
    <script>
        window.plausible=window.plausible||function(){(plausible.q=plausible.q||[]).push(arguments)},plausible.init=plausible.init||function(i){plausible.o=i||{}};
        plausible.init()
    </script>
    </body>
</html>
