<!DOCTYPE html>

<html lang="en">
<head>
    <title>API Documentation Example using Redoc</title>

    <!-- needed for adaptive design -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            padding: 5mm;
        }
    </style>
</head>
<body>
<div id="redoc-container"></div>

<script src="https://cdn.jsdelivr.net/npm/redoc@2.0.0-alpha.24/bundles/redoc.standalone.js"></script>
<script>
    Redoc.init('/v2/api-docs', {
        scrollYOffset: 50,
        nativeScrollbars: true,
        hideDownloadButton: true
    }, document.getElementById('redoc-container'))
</script>


</body>
</html>