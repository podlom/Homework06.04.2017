<?php
/**
 * Created by PhpStorm.
 * User: Shkodenko
 * Date: 23.03.2017
 * Time: 19:12
 */

function getTopMenu($uri) {
    $menuHtml = '';
    if (isset($_SESSION['a']['login'])) {
        $lastItem = ['uri' => 'logout', 'linkText' => 'Log out'];
        $commentLink = ['uri' => 'comments', 'linkText' => 'Comments'];
        $galleryLink = ['uri' => 'gallery', 'linkText' => 'Gallery'];
        $test_page = ['uri' => 'test_page', 'linkText' => 'Test page'];
    } else {
        $lastItem = ['uri' => 'login', 'linkText' => 'Log in'];
        $commentLink = $galleryLink = $test_page = [];
    }
    $menuItems = [
        ['uri' => 'home', 'linkText' => 'Home'],
        ['uri' => 'about', 'linkText' => 'About'],
        ['uri' => 'projects', 'linkText' => 'Projects'],
        $commentLink,
        $galleryLink,
        $test_page,
        $lastItem,
    ];
    $currentPage = 'home';
    if (!empty($_SERVER['REQUEST_URI'])) {
        $currentPage = trim($_SERVER['REQUEST_URI'], '/');
    }
    // echo ' $currentPage: ' . $currentPage . '<br>';
    if (!empty($menuItems)) {
        $menuHtml .= '<ul class="nav navbar-nav">';
        foreach ($menuItems as $mi) {
            $class = '';
            if (isset($mi['uri']) && $mi['uri'] == $currentPage) {
                $class = ' class="active"';
            }
            if (isset($mi['uri'])) {
                $menuHtml .= '<li' . $class . '><a href="/' . $mi['uri'] . '">' . $mi['linkText'] . '</a></li>';
            }
        }
        $menuHtml .= '</ul>';
    }
    return $menuHtml;
}

function getCurrentPageContent() {
    ob_start();
    $allowInc = ['home', 'about', 'projects', 'login', 'logout', ];
    if (isset($_SESSION['a']['login'])) {
        array_push($allowInc, 'comments', 'gallery', 'test_page');
    }
    if (empty($_SERVER['REQUEST_URI'])) {
        $currentPage = 'home';
    } else {
        $currentPage = trim($_SERVER['REQUEST_URI'], '/');
    }

    // echo '$currentPage: ' . $currentPage . '<br>';
    if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . $currentPage . '.php') && in_array($currentPage, $allowInc)) {
        $fileName = dirname(__FILE__) . DIRECTORY_SEPARATOR . $currentPage . '.php';
    } else {
        $fileName = dirname(__FILE__) . DIRECTORY_SEPARATOR . '404.php';
    }

    // echo '$fileName: ' . $fileName . '<br>';
    require_once $fileName;
    $pageContent = ob_get_clean();

    return $pageContent;
}

function getSiteFooter() {
    $fileName = dirname(__FILE__) . DIRECTORY_SEPARATOR . '_footer.php';

    ob_start();
    require_once $fileName;
    $footerHtml = ob_get_clean();

    return $footerHtml;
}


function getSiteHeader() {
    $fileName = dirname(__FILE__) . DIRECTORY_SEPARATOR . '_header.php';

    ob_start();
    require_once $fileName;
    $headerHtml = ob_get_clean();

    return $headerHtml;
}

function readComments() {
    $commentsData = [];
    global $commentsDbFile;
    if (file_exists($commentsDbFile)) {
        $commentsData = file_get_contents($commentsDbFile);
        if ($commentsData !== false) {
            $commentsData = unserialize($commentsData);
        }
    }
    return $commentsData;
}

function addComment($data) {
    global $commentsDbFile;
    $aOld = readComments();
    $data['uname'] = htmlspecialchars($data['uname']);
    $data['ucomment'] = htmlspecialchars($data['ucomment']);
    $data['AddedDt'] = date('Y-m-d H:i:s');
    $newData = [];
    if (is_array($aOld)) {
        $newData = array_merge($aOld, [$data]);
    } else {
        $newData[] = $data;
    }
    $sData = serialize($newData);
    file_put_contents($commentsDbFile, $sData);
}

function antimat($str) {
    return str_replace([
        'Test',
        'Мат',
        // 'comment',
    ], [
        'Tost',
        'Анти-Мат',
        // '*compliement*',
    ], $str);
}

function getComments() {
    $aComm = readComments();
    if (!empty($aComm)) {
        foreach ($aComm as $c) {
            $eP = explode('@', $c['uemail']);
            echo '<dl><dt>Commented by: <script type="text/javascript"> jep_link("'. $eP[1] .
                '","' . $eP[0], '","' . $c['uname'] . '"); </script>' .
                ' on ' . $c['AddedDt'] . '</dt><dd>' .
                antimat($c['ucomment']) .
                '</dd></dl>';
        }
    }
}
