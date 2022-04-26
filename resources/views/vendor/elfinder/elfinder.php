<!DOCTYPE html>
<html lang="<?= app()->getLocale() ?>">
    <head>
        <meta charset="utf-8">
        <title>elFinder 2.0</title>

        <!-- jQuery and jQuery UI (REQUIRED) -->
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

        <!-- elFinder CSS (REQUIRED) -->
        <link rel="stylesheet" type="text/css" href="<?= asset($dir.'/css/elfinder.min.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset($dir.'/css/theme.css') ?>">

        <!-- elFinder JS (REQUIRED) -->
        <script src="<?= asset($dir.'/js/elfinder.min.js') ?>"></script>

        <?php if($locale){ ?>
        <!-- elFinder translation (OPTIONAL) -->
        <script src="<?= asset($dir."/js/i18n/elfinder.$locale.js") ?>"></script>
        <?php } ?>

        <!-- elFinder initialization (REQUIRED) -->
        <script type="text/javascript" charset="utf-8">
            // Documentation for client options:
            // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
            $().ready(function() {
                $('#elfinder').elfinder({
                    // set your elFinder options here
                    <?php if($locale){ ?>
                        lang: '<?= $locale ?>', // locale
                    <?php } ?>
                    customData: {
                        _token: '<?= csrf_token() ?>'
                    },
                    url : '<?= route("elfinder.connector") ?>',  // connector URL
                    soundPath: '<?= asset($dir.'/sounds') ?>',
                    uiOptions: {
                        // toolbar configuration
                        toolbar: [
                            ['home', 'back', 'forward', 'up', 'reload'],
                            // ['mkdir', 'mkfile', 'upload'],
                            // ['open', 'download', 'getfile'],
                            // ['undo', 'redo'],
                            // ['copy', 'cut', 'paste'],
                            // ['duplicate', 'rename', 'edit', 'resize', 'chmod'],
                            // ['selectall', 'selectnone', 'selectinvert'],
                            // ['quicklook', 'info'],
                            // ['extract', 'archive'],
                            // ['search'],
                            // ['view', 'sort'],
                            // ['fullscreen']
                        ]
                    },
                    contextmenu: {
                        // navbarfolder menu
                        navbar: ['open', 'download', '|', 'upload', 'mkdir', '|', 'copy', 'cut', 'paste', 'duplicate', '|', '|', 'rename', '|', 'places', 'info', 'chmod', 'netunmount'],
                        // current directory menu
                        cwd: ['undo', 'redo', '|', 'back', 'up', 'reload', '|', 'upload', 'mkdir', 'mkfile', 'paste', '|', '|', 'view', 'sort', 'selectall', 'colwidth', '|', 'info', '|', 'fullscreen', '|'],
                        // current directory file menu
                        files: ['getfile', '|', 'open', 'download', 'opendir', 'quicklook', '|', 'upload', 'mkdir', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'empty', '|', 'rename', 'edit', 'resize', '|', 'archive', 'extract', '|', 'selectall', 'selectinvert', '|', 'places', 'info', 'chmod', 'netunmount']
                    },
                    width: '100%',
                    height: $(window).height() - 20
                });
            });
        </script>
    </head>
    <body>

        <!-- Element where elFinder will be created (REQUIRED) -->
        asdfasdfasdf
        <div id="elfinder"></div>

    </body>
</html>
