<?php

function saveAsset() {
    $assetName = uniqid();
    $targetPath = __DIR__ . DIRECTORY_SEPARATOR . "tmp/" . $assetName;


    // check file type (zip, image, abc)
    //validateAsset();

    if ($_POST['type'] == 'upload') {
        //upload file by upload image
        move_uploaded_file( $_FILES['file']['tmp_name'], $targetPath );
    } else if ($_POST['type'] == 'url'){
        //upload file by link url
        $content = file_get_contents($_POST['url']);
        file_put_contents($targetPath, $content);
    } else if ($_POST['type'] == 'base64') {
        //upload file by link base64
        file_put_contents($targetPath, base64_decode($_POST['url_base64']));
    }

    return '/tmp/' . $assetName;
}

function uploadTemplate() {
    if (isset($_POST) && empty($_FILES['file'])) {
        return json(['error' =>  [ 'file' => 'No file upload' ] ], 404);
    }
    
    $assetName = uniqid();
    $filename = $_FILES['file']['name'];
    //$targetPath = __DIR__ . DIRECTORY_SEPARATOR . "tmp/" . $assetName;
    $targetPath = __DIR__ . DIRECTORY_SEPARATOR . "tmp/original";
    $uploadPath = __DIR__ . DIRECTORY_SEPARATOR . "templates/" . $assetName ."/";
    $movethumbnail = __DIR__ . DIRECTORY_SEPARATOR . "templates/";
    $thumbnail = __DIR__ . DIRECTORY_SEPARATOR . "image/thumb.png";

    if (isset($_POST) && !empty($_FILES['file'])) {
        $file = $_FILES['file']['tmp_name'];

        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
        
        $zip = new ZipArchive();

        $zip->open($targetPath);
        $zip->extractTo($uploadPath);
        $zip->close();
        
        $files = glob($uploadPath. "/index.html"); /*search index.html in folder*/
        $content = file_get_contents($files[0]);
        
        copy($thumbnail, $uploadPath. 'thumb.png');
    }
    return $assetName;
}

function validateAsset() {
    if (false) {
        return json(['error' =>  [ 'file' => 'Cannot upload asset file' ] ], 404);
        die();
    }
}

function json($message, $code = 200) {
    header("HTTP/1.1 {$code}");
    header('Content-Type: application/json');
    echo json_encode($message);
}

// DONE
if ($_GET['action'] == 'asset') { // 2 case asset-image và asset-video
    $url = saveAsset();
    return json([ 'url' => $url ], 200);
}

if ($_POST['action'] == 'asset') { // 2 case asset-image và asset-video
    $url = saveAsset();
    return json([ 'url' => $url ], 200);
}

// Luôn trả về error cho cho bản demo này
if ($_GET['action'] == 'save') {
    return json(['error' => 'DEMO content is not configured for saving. See BuilderJS documentation for more integration guideline'], 403);
}

if ($_POST['action'] == 'upload') { // case upload template
    $url = uploadTemplate();
    return json([ 'url' => $url ], 200);
}

