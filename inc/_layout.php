<?php

    require_once '_functions.php';

    $pageContent = getCurrentPageContent();

?>
<!DOCTYPE html>
<html>
<head>
    <title><?=$title?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="vendor/jep.js" type="text/javascript"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="/"><?=$siteName?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <?=getTopMenu($_SERVER['REQUEST_URI'])?>
        </div>
    </div>
</nav>

<div class="container">
    <div class="page-content-container">
        <?=getSiteHeader()?>
        <?=$pageContent?>
    </div>
</div>

<?=getSiteFooter()?>

</body>
</html>