<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Deepzoom</title>
    <!-- Deepzoom image viewer-->
    <script src="https://cdn.jsdelivr.net/npm/openseadragon@3.1/build/openseadragon/openseadragon.min.js"></script>
    <style>
        #deepzoom{
            height: 80vh;
            width: 100vw;
        }
    </style>
</head>
<body>
<div id="deepzoom"></div>

<script>

    let viewer = new OpenSeadragon({
        id:"deepzoom",
        tileSources:   '{{asset("storage/tiles/{$path}.dzi")}}',
        showNavigator: true,
    });

</script>
</body>
</html>
