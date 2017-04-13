<?php

define('MAX_FILE_SIZE', 1024 * 1024 * 2);

global $galleryParentDir, $galleryParentWebDir, $galleryDir1, $allowExt, $allowType, $msg;


$galleryParentDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'files';
$galleryParentWebDir = 'files';
if (!empty($_POST)
    && is_dir($galleryParentDir . DIRECTORY_SEPARATOR . $_POST['gallery'])) {
    $galleryDir1 = $galleryParentDir . DIRECTORY_SEPARATOR . $_POST['gallery'] . DIRECTORY_SEPARATOR;
} else {
    $galleryDir1 = $galleryParentDir . DIRECTORY_SEPARATOR . 'gallery1' . DIRECTORY_SEPARATOR;
}
$allowExt = [
    'jpg',
    'jpeg',
    'png',
    'gif',
];
$allowType = [
    'image/jpeg',
    'image/png',
    'image/gif',
];
$msg = [];

if (!empty($_FILES)) {

    if (!isset($_FILES['file1'])) {
        die('Error: file not selected.');
    }

    if ($_FILES['file1']['error'] !== 0) {
        die('Error: uploading file: ' . $_FILES['file1']['error']);
    }

    $upExt = pathinfo($_FILES['file1']['name']);
    if (!in_array($upExt['extension'], $allowExt)) {
        die('Error: wrong file extension: ' . $upExt['extension'] . '. Allowed extensions: <pre>' . var_export($allowExt, 1) . '</pre>');
    }

    if ($_FILES['file1']['size'] > MAX_FILE_SIZE) {
        die('Uploaded file is too big: ' . $_FILES['file1']['size'] . ' bytes. Maximum possible size is: ' . MAX_FILE_SIZE . ' bytes.');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
    $upMimeType = finfo_file($finfo, $_FILES['file1']['tmp_name']);
    if (!in_array($upMimeType, $allowType)) {
        die('Uploaded file has wrlong mime type: ' . $upMimeType . '. Allowed mime types are: <pre>' . var_export($allowType, 1) . '</pre>');
    }

    $dstFileName = $galleryDir1 . $_FILES['file1']['name'];
    if (move_uploaded_file($_FILES['file1']['tmp_name'], $dstFileName)) {
        $msg[] = '<p>Uploaded file: ' . $dstFileName . '</p>';
    }
}

function getImgFiles($dir) {
    global $galleryParentWebDir, $galleryParentDir, $allowExt;

    $allImgReg = implode(',', $allowExt);
    $allImgReg = '*.{' . $allImgReg . '}';

    $imgFiles = glob($galleryParentDir . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $allImgReg, GLOB_BRACE);
    // echo 'glob: ' . $galleryParentDir . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $allImgReg . '<br>';
    $imgs = [];
    if (!empty($imgFiles)) {
        foreach ($imgFiles as $img) {
            $bName = basename($img);
            $imgs[] = '<img src="' . $galleryParentWebDir . '/' . $dir . '/' . $bName . '" width="150" alt="' . $img . '">';
        }
    }
    return $imgs;
}

function getGalleryFolders() {
    global $galleryParentDir;
    $subFolders = [];
    $dir = dir($galleryParentDir);
    while (false !== ($entry = $dir->read())) {
        if ($entry !== '.' && $entry !== '..') {
            if (is_dir($galleryParentDir . DIRECTORY_SEPARATOR . $entry)) {
                $subFolders[] = $entry;
            }
        }
    }
    $dir->close();
    return $subFolders;
}

?>
<section id="gallery">
<?php
    if (!empty($msg)) {
        foreach ($msg as $m) {
            echo $m . '<br>';
        }   
    }
?>
<form action="" method="post" enctype="multipart/form-data">
    <div>
        <label for="file1">Select image *:</label><input type="file" name="file1">
    </div>
    <div>
        Select the save folder *: <select name="gallery">
            <?php
                $subFldrs = getGalleryFolders();
                if (!empty($subFldrs)) {
                    foreach ($subFldrs as $f) {
                       echo '<option value="' . $f . '">' . $f . '</option>';
                    }
                }
            ?>
        </select>
    </div>

    <input type="submit" value="Upload file">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?=MAX_FILE_SIZE?>">
    <div>
        * - required field
    </div>
</form>
<hr>
<?php

    if (!empty($subFldrs)) {
        foreach ($subFldrs as $f1) {
            $imgs = getImgFiles($f1);
            echo '<div>Images in folder: ' . $f1 . '</div>';
            foreach ($imgs as $img) {
                echo $img;
            }
            echo '<hr>';
        }
    }

?>
</section>
