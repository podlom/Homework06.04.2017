<?php
/**
 * Created by PhpStorm.
 * Company: WD&SS
 * Date: 06.04.2017
 * Time: 19:56
 */

if (isset($_SESSION['a'])) {
    unset($_SESSION['a']);
    header('Location: /login');
    exit;
}
