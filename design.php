<?php
    $dir = __DIR__ . DIRECTORY_SEPARATOR . "templates/";
    $templateUrl = array_diff(scandir($dir), array('..', '.'));
    $array = [];
    foreach ($templateUrl as $name) {
        $path = __DIR__ . DIRECTORY_SEPARATOR . "templates/" . $name;
        $files = glob($path . "/index.html");
        $content = file_get_contents($files[0]);
        $preg_matchs = preg_match_all('/(<title\>([^<]*)\<\/title\>)/i', $content, $m);
        $title = $m[2][0];
        array_push($array, $template = [
            'name' => $title,
            'url' => 'design.php?id='.$name.'',
            'thumbnail' => 'templates/'.$name.'/thumb.png',
        ]);
    }
?>

<!doctype html>
<html>
    <head>
        <title>BuilderJS 2.0</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="image/builderjs_color_logo.png" rel="icon" type="image/x-icon" />
        <!--<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>-->

        <link rel="stylesheet" href="builder/builder.css">
        <script src="builder/builder.js"></script>

        <script>
            switch(window.location.protocol) {
                case 'http:':
                case 'https:':
                  //remote file over http or https
                  break;
                case 'file:':
                  alert('Please put the sample/ folder into your document root and open it through a web URL');
                  window.location.href = "./index.php";
                  break;
                default:
                  //some other protocol
            }

            var editor;
            var params = new URLSearchParams(window.location.search);
            var templates = JSON.parse('<?php echo JSON_encode($array);?>');

            var tags = [
                {type: 'label', tag: '{CONTACT_FIRST_NAME}'},
                {type: 'label', tag: '{CONTACT_LAST_NAME}'},
                {type: 'label', tag: '{CONTACT_FULL_NAME}'},
                {type: 'label', tag: '{CONTACT_EMAIL}'},
                {type: 'label', tag: '{CONTACT_PHONE}'},
                {type: 'label', tag: '{CONTACT_ADDRESS}'},
                {type: 'label', tag: '{ORDER_ID}'},
                {type: 'label', tag: '{ORDER_DUE}'},
                {type: 'label', tag: '{ORDER_TAX}'},
                {type: 'label', tag: '{PRODUCT_NAME}'},
                {type: 'label', tag: '{PRODUCT_PRICE}'},
                {type: 'label', tag: '{PRODUCT_QTY}'},
                {type: 'label', tag: '{PRODUCT_SKU}'},
                {type: 'label', tag: '{AGENT_NAME}'},
                {type: 'label', tag: '{AGENT_SIGNATURE}'},
                {type: 'label', tag: '{AGENT_MOBILE_PHONE}'},
                {type: 'label', tag: '{AGENT_ADDRESS}'},
                {type: 'label', tag: '{AGENT_WEBSITE}'},
                {type: 'label', tag: '{AGENT_DISCLAIMER}'},
                {type: 'label', tag: '{CURRENT_DATE}'},
                {type: 'label', tag: '{CURRENT_MONTH}'},
                {type: 'label', tag: '{CURRENT_YEAR}'}
            ];

            $( document ).ready(function() {
                editor = new Editor({
                    root: '/builder/',
                    url: 'templates/' + params.get('id'),
                    urlBack: window.location.origin,
                    uploadAssetUrl: 'backend.php?action=asset',
                    uploadAssetMethod: 'POST',
                    uploadTemplateUrl: 'backend.php?action=upload',
                    saveUrl: 'backend.php?action=save',
                    saveMethod: 'POST',
                    templates: templates,
                    tags: tags,
                    changeTemplateCallback: function(url) {
                        window.location = url;
                    }
                });

            editor.init();
            });
        </script>
    </head>
    <body style="overflow: hidden;">
    </body>
</html>
