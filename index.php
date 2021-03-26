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
                header("Location: https://personal.community/?post=" . hsc(substr($username, 1)) . "/" . hsc($post));
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
<html>
    <head>
        <title>HiveLink</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.2/css/bulma.min.css" integrity="sha256-O8SsQwDg1R10WnKJNyYgd9J3rlom+YSVcGbEF5RmfFk=" crossorigin="anonymous">
        <script
                src="https://code.jquery.com/jquery-3.5.1.min.js"
                integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
                crossorigin="anonymous"></script>
    </head>

    <body>
        <section class="hero is-dark is-fullheight">
            <div class="modal is-active">
                <div class="modal-background"></div>
                <div class="modal-content has-background-light has-text-dark p-5" style="border-radius: 5px; max-width: 400px;">
                    <h1 class="is-size-4">Hive Link</h1>
                    <?php if (!empty($post)) { ?>
                    <p>Link to a post by <?=hsc($username)?></p>
                    <a data-frontend="hive-blog" href="https://hive.blog/<?=hsc($username) . "/" . hsc($post)?>" class="button is-danger mt-3" style="width: 100%;">Hive.Blog</a>
                    <a data-frontend="peakd" href="https://peakd.com/<?=hsc($username) . "/" . hsc($post)?>" class="button is-dark mt-3" style="width: 100%;">PeakD</a>
                    <a data-frontend="ecency" href="https://ecency.com/<?=hsc($username) . "/" . hsc($post)?>" class="button is-info mt-3" style="width: 100%;">Ecency</a>
                    <a data-frontend="personal-community" href="https://personal.community/?post=<?=hsc(substr($username, 1)) . "/" . hsc($post) ?>" class="button is-primary mt-3" style="width: 100%;">Personal.Community</a>
                    <?php } else { ?>
                        <?="<p>" . hsc($username) ."'s Profile</p>"?>
                        <a data-frontend="hive-blog" href="https://hive.blog/<?=hsc($username)?>" class="button is-danger mt-3" style="width: 100%;">Hive.Blog</a>
                        <a data-frontend="peakd" href="https://peakd.com/<?=hsc($username)?>" class="button is-dark mt-3" style="width: 100%;">PeakD</a>
                        <a data-frontend="ecency" href="https://ecency.com/<?=hsc($username)?>" class="button is-info mt-3" style="width: 100%;">Ecency</a>
                        <a data-frontend="personal-community" href="<?="https://personal.community/?hive=" . hsc(substr($username,1))?>" class="button is-primary mt-3" style="width: 100%;">Personal.Community</a>
                    <?php } ?>
                    <label class="checkbox">
                        <input type="checkbox" <?=(isset($_GET["force_select"]) and isset($_SESSION["frontend"])) ? "disabled='disabled'" : "" ?> id="always-frontend">
                        Always use this frontend
                    </label>
                    <p class="has-text-danger has-text-weight-bold is-size-7"><?=(isset($_GET["force_select"]) and isset($_SESSION["frontend"])) ? "Why am I being asked to select even though I have a saved choice?" : ""?></p>
                    <p class="has-text-danger is-size-7"><?=(isset($_GET["force_select"]) and isset($_SESSION["frontend"])) ? "The links creator has asked us to show you this screen" : ""?></p>
                    <p>Tool by <a href="https://hivel.ink/@cadawg" class="has-text-info">CADawg</a>, <a class="has-text-info" href="https://vote.hive.uno/@cadawg">vote me for witness</a>!</p>
                </div>
            </div>
        </section>

    <script>
            $("a").on("click", function(event) {
                event.preventDefault();
                $.get("/frontend_remember.php", "frontend=" + $(event.target).data("frontend"), function () {
                    window.location.href = $(event.target).attr("href");
                });
            })
    </script>
    </body>
</html>
