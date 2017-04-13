<?php
/**
 * Created by PhpStorm.
 * User: Shkodenko
 * Date: 23.03.2017
 * Time: 19:08
 */

global $siteHeader;

if (!empty($siteHeader)) {

    ?>
    <header>
        <h1><?=$siteHeader?></h1>
    </header>
    <?php
}
