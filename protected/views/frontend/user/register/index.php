<!DOCTYPE html>
<html lang="ru">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <?php if(MOBILE_DEVICE): //mob device ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php endif;?>
  <title><?='Регистрация'?></title>
  <meta name="language" content="ru"/>
  <meta name="google-site-verification" content="c2duy0oE7VkxAtjVxH--abHQtP-aYvzCQERllgdLOOQ"/>
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  <meta property="og:image" content="https://prommu.com/images/logo.png" />
  <?php
  $bUrl = Yii::app()->baseUrl;
  $gcs = Yii::app()->getClientScript();
  $gcs->registerCoreScript('jquery');
  $gcs->registerCssFile($bUrl . MainConfig::$CSS  . 'register/style.css');
  $gcs->registerCssFile($bUrl . MainConfig::$CSS  . 'dist/cropper.min.css');
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'snap/snap.svg-min.js', CClientScript::POS_END);
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'dist/cropper.min.js', CClientScript::POS_END);
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'register/script.js', CClientScript::POS_END);

  // FANCYBOX
  $gcs->registerScriptFile(MainConfig::$JS . 'dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
  $gcs->registerCssFile(MainConfig::$JS . 'dist/fancybox/jquery.fancybox.css');
  ?>
</head>
<body>

<div class="login">

    <div class="login__logo">
        <span class="logo">

            <svg
                    xmlns:svg="http://www.w3.org/2000/svg"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 142 39"
                    id="svg2"
                    version="1.1">

                <defs
                        id="defs6" />
                <path
                        id="path24"
                        d="M 122.42188 0.14257812 A 19.5 19.5 0 0 0 102.92188 19.642578 A 19.5 19.5 0 0 0 122.42188 39.142578 A 19.5 19.5 0 0 0 141.92188 19.642578 A 19.5 19.5 0 0 0 122.42188 0.14257812 z M 111.0293 13.464844 C 112.34096 13.464844 113.3358 13.831465 114.01367 14.564453 C 114.69706 15.297442 115.086 16.348416 115.17969 17.720703 L 112.74023 17.720703 C 112.71819 16.87198 112.58624 16.288399 112.34375 15.96875 C 112.10677 15.64359 111.6686 15.480469 111.0293 15.480469 C 110.37898 15.480469 109.91849 15.71054 109.64844 16.167969 C 109.37839 16.619886 109.23528 17.366588 109.21875 18.408203 L 109.21875 20.681641 C 109.21875 21.877569 109.3507 22.698125 109.61523 23.144531 C 109.88528 23.590938 110.34577 23.814453 110.99609 23.814453 C 111.63539 23.814453 112.07587 23.660189 112.31836 23.351562 C 112.56085 23.037425 112.69935 22.471906 112.73242 21.65625 L 115.16211 21.65625 C 115.10149 23.028537 114.7168 24.068005 114.00586 24.773438 C 113.29492 25.47887 112.29122 25.832031 110.99609 25.832031 C 109.63483 25.832031 108.59076 25.385001 107.86328 24.492188 C 107.14132 23.593863 106.7793 22.315119 106.7793 20.65625 L 106.7793 18.630859 C 106.7793 16.977502 107.15477 15.705313 107.9043 14.8125 C 108.65382 13.914176 109.69559 13.464844 111.0293 13.464844 z M 120.79297 13.464844 C 122.13219 13.464844 123.18742 13.92108 123.95898 14.835938 C 124.73055 15.745284 125.12179 17.024029 125.13281 18.671875 L 125.13281 20.730469 C 125.13281 22.345248 124.74848 23.599374 123.98242 24.492188 C 123.22188 25.385001 122.16435 25.832031 120.80859 25.832031 C 119.45835 25.832031 118.39657 25.389603 117.625 24.507812 C 116.85343 23.620511 116.46219 22.381797 116.45117 20.789062 L 116.45117 18.730469 C 116.45117 17.077111 116.83551 15.787208 117.60156 14.861328 C 118.36762 13.929937 119.4317 13.464844 120.79297 13.464844 z M 126.87695 13.628906 L 130.05078 13.628906 L 132.32422 22.333984 L 134.58984 13.628906 L 137.76367 13.628906 L 137.76367 25.666016 L 135.32422 25.666016 L 135.32422 22.408203 L 135.54883 17.390625 L 133.14258 25.666016 L 131.49023 25.666016 L 129.08398 17.390625 L 129.30664 22.408203 L 129.30664 25.666016 L 126.87695 25.666016 L 126.87695 13.628906 z M 120.79297 15.498047 C 120.14816 15.498047 119.66995 15.755037 119.36133 16.267578 C 119.0527 16.774608 118.89383 17.548228 118.88281 18.589844 L 118.88281 20.730469 C 118.88281 21.783106 119.03938 22.560982 119.35352 23.0625 C 119.66765 23.558507 120.15276 23.806641 120.80859 23.806641 C 121.44238 23.806641 121.91403 23.56311 122.22266 23.078125 C 122.53128 22.587629 122.68785 21.829769 122.69336 20.804688 L 122.69336 18.712891 C 122.69336 17.627186 122.5391 16.82009 122.23047 16.291016 C 121.92184 15.761941 121.44329 15.498047 120.79297 15.498047 z "
                        style="fill:#abb837;fill-opacity:1" />
                <g
                        transform="matrix(1.0124951,0,0,1.0124951,-0.06111068,-0.208641)"
                        id="text28"
                        style="font-style:normal;font-weight:normal;font-size:26.08659554px;line-height:1.25;font-family:sans-serif;letter-spacing:0px;word-spacing:0px;fill:#000000;fill-opacity:1;stroke:none;stroke-width:0.65216494"
                        aria-label="PROMMU">
                    <path
                            id="path16"
                            style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:'Roboto Condensed';-inkscape-font-specification:'Roboto Condensed, ';stroke-width:0.65216494"
                            d="m 2.3913365,21.2827 v 7.260429 H 0.06035652 V 9.99719 H 5.9706008 q 2.5857319,0 4.0632932,1.553987 1.477561,1.541249 1.477561,4.114243 0,2.674895 -1.413873,4.126981 -1.4011357,1.439348 -3.9613924,1.490299 z m 0,-1.999803 h 3.5792643 q 1.5539867,0 2.3819304,-0.929844 0.8279437,-0.929845 0.8279437,-2.662158 0,-1.668625 -0.8534189,-2.674895 Q 7.4736371,12.00973 5.9833384,12.00973 H 2.3913365 Z" />
                    <path
                            id="path18"
                            style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:'Roboto Condensed';-inkscape-font-specification:'Roboto Condensed, ';stroke-width:0.65216494"
                            d="m 20.134807,21.040685 h -3.260824 v 7.502444 H 14.530265 V 9.99719 h 5.196939 c 1.817231,0 3.192891,0.475537 4.126981,1.426611 0.934091,0.942582 1.401136,2.326734 1.401136,4.152456 0,1.146383 -0.254752,2.148407 -0.764256,3.006072 -0.501012,0.849173 -0.893213,1.240551 -1.818812,1.673629 2.274429,0 3.270898,8.13432 3.270898,8.13432 0,0 0.05352,0.108253 0.178391,0.152851 -0.383541,-0.0089 -2.687698,0 -2.687698,0 0,0 -0.364561,-7.502444 -3.299037,-7.502444 z m -3.260824,-1.999802 h 2.827746 c 0.976549,0 1.753542,-0.314194 2.33098,-0.942582 0.585929,-0.628388 0.878894,-1.469069 0.878894,-2.522044 0,-2.377685 -1.07845,-3.566527 -3.235349,-3.566527 h -2.802271 z" />
                    <path
                            id="path20"
                            style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:'Roboto Condensed';-inkscape-font-specification:'Roboto Condensed, ';stroke-width:0.65216494"
                            d="m 40.451272,20.900572 q 0,3.795803 -1.617674,5.846556 -1.617675,2.050753 -4.598272,2.050753 -2.865959,0 -4.534584,-1.987065 -1.655888,-1.999803 -1.719576,-5.642755 V 17.67796 q 0,-3.719378 1.64315,-5.821081 1.64315,-2.114441 4.585535,-2.114441 2.929647,0 4.560059,2.025278 1.64315,2.01254 1.681362,5.757393 z m -2.33098,-3.248087 q 0,-2.942385 -0.968057,-4.368995 -0.955319,-1.439349 -2.942384,-1.439349 -1.923377,0 -2.91691,1.452086 -0.980795,1.452086 -0.993532,4.279832 v 3.324513 q 0,2.840483 0.980795,4.330782 0.993532,1.490299 2.955122,1.490299 1.96159,0 2.904172,-1.37566 0.942582,-1.375661 0.980794,-4.216145 z" />
                    <path
                            id="path22"
                            style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:'Roboto Condensed';-inkscape-font-specification:'Roboto Condensed, ';stroke-width:0.65216494"
                            d="M 47.087559,9.99719 51.927845,25.129453 56.768131,9.99719 h 3.006073 v 18.545939 h -2.33098 v -7.222217 l 0.216539,-7.234954 -4.853024,14.457171 h -1.783263 l -4.827549,-14.406221 0.216539,7.184004 v 7.222217 h -2.33098 V 9.99719 Z" />
                    <path
                            id="path25"
                            style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:'Roboto Condensed';-inkscape-font-specification:'Roboto Condensed, ';stroke-width:0.65216494"
                            d="M 66.805359,9.99719 71.645645,25.129453 76.485931,9.99719 h 3.006073 v 18.545939 h -2.33098 v -7.222217 l 0.216539,-7.234954 -4.853024,14.457171 h -1.783263 l -4.827549,-14.406221 0.216539,7.184004 v 7.222217 h -2.33098 V 9.99719 Z" />
                    <path
                            id="path27"
                            style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-family:'Roboto Condensed';-inkscape-font-specification:'Roboto Condensed, ';stroke-width:0.65216494"
                            d="m 94.573315,9.99719 v 13.183411 q -0.02548,2.636683 -1.528511,4.126981 -1.503036,1.490299 -4.190669,1.490299 -2.751321,0 -4.203406,-1.452086 -1.452086,-1.464823 -1.477561,-4.165194 V 9.99719 h 2.318242 v 13.094248 q 0,1.872427 0.789731,2.802271 0.802468,0.917107 2.572994,0.917107 1.783263,0 2.572994,-0.917107 0.802469,-0.929844 0.802469,-2.802271 V 9.99719 Z" />
                </g>
            </svg>

        </span>
    </div>
    <form id="register_form"><?php echo $content; ?></form>
    <div class="login__txt">
        <p>
            <a href="javascript:void(0)" id="my_fancybox" >ПРАВИЛА ИСПОЛЬЗОВАНИЯ САЙТА</a>
        </p>
    </div>
</div>
</body>
</html>
