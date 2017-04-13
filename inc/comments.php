<?php
global $siteHeader, $commentsDbFile, $errorMessages;

$siteHeader = 'Comments';
$commentsDbFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'comments.dat';
$errorMessages = [];

require_once '_functions.php';

if (!empty($_POST)) {
    /* echo '<pre>' . var_export($_POST, 1) . '</pre>';
    exit; */
    $canAddComment = false;
    if (empty($_POST['uname'])) {
        $errorMessages[] = '<p class="err">Username can`t be empty</p>';
    }
    if (empty($_POST['uemail'])) {
        $errorMessages[] = '<p class="err">Email can`t be empty</p>';
    }
    if (!filter_var($_POST['uemail'], FILTER_VALIDATE_EMAIL)) {
        $errorMessages[] = '<p class="err">Email has wrong format</p>';
    }
    if (empty($_POST['ucomment'])) {
        $errorMessages[] = '<p class="err">Comment text can`t be empty</p>';
    }
    if (empty($_POST['g-recaptcha-response'])) {
        $errorMessages[] = '<p class="err">Captcha text can`t be empty</p>';
    } else {
        $reqData = http_build_query([
            'secret' => '6LdCsBoUAAAAABK8ivUCIPNIMCCiDyGOtUfAQiZ1',
            'response' => $_POST['g-recaptcha-response'],
            // remoteip => $_SERVER['REMOTE_ADDR'],
        ]);
        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $reqData,
            ]
        ]);
        $checkCaptchaRes = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        // echo '$checkCaptchaRes: ' . var_export($checkCaptchaRes, 1) . '<br>';
        // exit;
        // '{ "success": true, "challenge_ts": "2017-03-28T17:48:12Z", "hostname": "localhost" }'
        $jsObj = json_decode($checkCaptchaRes);
        if (!empty($jsObj) && $jsObj->success) {
            // reCaptcha is OK
            $canAddComment = true;
        } else {
            $errorMessages[] = '<p class="err">Wrong reCaptcha code</p>';
        }

    }
    if (empty($errorMessages) && $canAddComment) {
        addComment($_POST);
    }
}

    if (!empty($errorMessages)) {
        foreach ($errorMessages as $msg) {
            echo $msg . '<br>';
        }
    }
?>
<form id="commentForm1" action="" method="post">
    <div>
        <label for="uname">Username *:</label>
        <input required type="text" name="uname">
    </div>
    <div>
        <label for="uemail">Email *:</label>
        <input required type="email" name="uemail">
    </div>
    <div>
        <label for="ucomment">Comment *:</label><br>
        <textarea required name="ucomment" placeholder="Input your comment"></textarea>
    </div>
    <div>
        <button
                class="g-recaptcha"
                data-sitekey="6LdCsBoUAAAAAFjvPvA7xSFg576uRPISksKIzTJj"
                data-callback="myCommentSubmit">
            Add comment
        </button>
        <script>
            function myCommentSubmit(token) {
                document.getElementById("commentForm1").submit();
            }
        </script>
    </div>
    <div>* - required fields</div>
</form>
<hr>
<?php
    getComments();
?>
