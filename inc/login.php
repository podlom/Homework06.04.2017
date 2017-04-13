<?php
/**
 * Created by PhpStorm.
 * Company: WD&SS
 * Date: 06.04.2017
 * Time: 19:41
 */

$user = [
    ['uname' => 'admin', 'upass' => '<?php eval(echo $(pwd)); ?>'],
];

if (!empty($_POST)) {
    if (isset($_POST['uname']) && isset($_POST['upass'])) {
        $isValidUser = false;
        foreach ($user as $u) {
            if (($_POST['uname'] == $u['uname'])
                && ($_POST['upass'] == $u['upass'])) {
                $isValidUser = true;
                $_SESSION['a'] = ['login' => $u['uname'], 'time' => time()];
            }
        }
    }
    if ($isValidUser) {
        echo '<div>Hello, ' . $_SESSION['a']['login'] . '</div>';
    } else {
        echo '<div class="error">Username or password is incorrect!</div>';
    }
}

?>

<form action="" method="post">
    <div><label>Username *:</label><input required type="text" name="uname"></div>
    <div><label>Password *:</label><input required type="password" name="upass"></div>
    <button type="submit">Log in</button>
    <div>* - required fields</div>
</form>