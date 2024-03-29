<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{asset('favicon.ico')}}" rel="icon">
    <title> {{$title." - Viewer" ?? "Viewer"}} </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.rawgit.com/kimmobrunfeldt/progressbar.js/0.5.6/dist/progressbar.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>

        #progress-bar  {
            display: none;
            height: 100px;
            top:45%;
            position: fixed;
            z-index: 1001;
            margin: auto;
        }

        #progress-bar  > svg {
            height: 100%;
            display: block;
            margin:auto;
        }

        #leftSide {
            background-color: white;
            position: fixed;
            width: 25%;
            left: 0;
            overflow-y: scroll;
            top: 0;
            bottom: 0;
            padding: 20px;
            z-index: 999;
        }

        #thumbs {
            list-style: none;
            padding: 0px;
            margin: 0px;
            overflow-x: hidden;

        }

        /*#thumbs li::after {*/
        /*    content: attr(data-size_mb) "MB";*/
        /*    display: block;*/
        /*    width: 100px;*/
        /*    height: 100px;*/
        /*    position: relative;*/
        /*    top: -120px;*/
        /*    left: -20px;*/
        /*    color: white;*/
        /*    text-shadow: 1px 1px black;*/
        /*    font-size: 20px;*/
        /*}*/

        #thumbs li {
            position: relative;
        }

        #thumbs li span {
            position: absolute;
            padding:5px;
            color: white;
            text-shadow: 1px 1px black;
            font-size: 20px;
        }



        .img-element {
            padding: 10px;
        }

        .active {
            background-color: #ebebeb;
        }

        .img-element img {
            height: 120px;
        }

        #indexID {
            width: 40px;
            border-radius: 10px;
            border: solid 1px #b1b1b1;
            padding-left: 10px;
        }


        /* CENTER */
        .full {
            width: 105%;
            left: 0%;
        }

        .boxed {
            width: 75%;
            left: 25%;
        }

        #middleBox {
            background-color: #252525;
            display: grid;
            place-items: center;
            position: fixed;

            height: 100%;

        }

        #content_viewer {
            float: left;
            width: 800px;
            position: absolute;
            z-index: 9;
            cursor: move;
        }

        #content_viewer img {
            float: left;
        }

        .singleView img {
            width: 100%;
        }

        .bookView img {
            width: 50%;
        }

        #footer {
            position: absolute;
            right: auto;
            bottom: 50px;
            z-index: 999;
            place-items: center;
        }

        #header {
            position: absolute;
            right: auto;
            top: 50px;
            z-index: 999;
            place-items: center;
        }

        .btn-outline-primary {
            color: white;
            border: solid 1px white;
        }

        #leftOpenClose {
            position: absolute;
            top: 20px;
            left: 25%;
            background-color: white;
            color: black;
            font-weight: bold;
            padding: 10px 20px 10px 20px;
            z-index: 999;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .scrollpane {
            height: 600px;
            overflow: auto;
        }

        .scrollpaneNoMore {
            height: 600px;
            overflow: auto;
        }

        .loading {
            color: black;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="hold-transition layout-top-nav">
<div class="container-fluid">



    <button id="leftOpenClose" onclick="leftOpenClose()">
        <span id="pages">1</span> /<span class="totalCounter"></span>
    </button>
    <div id="maxImages" style="display:none;" maxImages=""></div>
    <div id="leftSide">
        <div class="col-12 text-center mb-2 pt-3 row">
            <div class="col-4 text-left" style="padding:0px;">
                <input type="text" value="1" id="indexID"> / <span class="totalCounter"></span>
            </div>
            <div class="col-4 text-center" style="padding:0px;">
                <button class="btn btn-default btn-sm  caps" data-toggle="collapse" data-target="#filtersBox">
                    ფილტრები
                </button>
            </div>
            <div class="col-4 text-right" style="padding:0px;">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-secondary active">
                        <input class="thumbSelector" type="radio" value="single" autocomplete="off" checked>
                        <i class="far fa-file-alt"></i>
                    </label>
                    <label class="btn btn-secondary">
                        <input class="thumbSelector" type="radio" value="double" autocomplete="off">
                        <i class="fas fa-book-open"></i>
                    </label>

                </div>
            </div>
            <div class="col-12 collapse pt-3 pb-3" id="filtersBox">
                <div class="sliders">
                    <form id="imageEditor">
                        <p>
                            <label for="gs">Grayscale</label>
                            <input id="gs" name="gs" type="range" min="0" max="100" value="0">
                        </p>

                        <p>
                            <label for="blur">Blur</label>
                            <input id="blur" name="blur" type="range" min="0" max="10" value="0">
                        </p>

                        <p>
                            <label for="br">Brightness</label>
                            <input id="br" name="br" type="range" min="0" max="200" value="100">
                        </p>

                        <p>
                            <label for="ct">Contrast</label>
                            <input id="ct" name="ct" type="range" min="0" max="200" value="100">
                        </p>

                        <p>
                            <label for="huer">Hue Rotate</label>
                            <input id="huer" name="huer" type="range" min="0" max="360" value="0">
                        </p>

                        <p>
                            <label for="opacity">Opacity</label>
                            <input id="opacity" name="opacity" type="range" min="0" max="100" value="100">
                        </p>

                        <p>
                            <label for="invert">Invert</label>
                            <input id="invert" name="invert" type="range" min="0" max="100" value="0">
                        </p>

                        <p>
                            <label for="saturate">Saturate</label>
                            <input id="saturate" name="saturate" type="range" min="0" max="500" value="100">
                        </p>

                        <p>
                            <label for="sepia">Sepia</label>
                            <input id="sepia" name="sepia" type="range" min="0" max="100" value="0">
                        </p>

                        <input type="reset" form="imageEditor" id="reset" value="სტანდარტზე დაბრუნება"
                               class="font-control caps" />

                    </form>
                </div>
            </div>
        </div>

        <div id="thumbView" class="scrollpane text-center pt-3 pb-3">
            <ul id="thumbs"> </ul>
        </div>
    </div>

    <div id="middleBox" class="boxed">
        <div id="progress-bar"></div>

        <div id="content_viewer" class="singleView"> </div>

        <div id="header">
            <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#modal-default"
                    id="infoButton" url="">
                <i class="fas fa-info"></i>
            </button>
            <a href="{{ URL::previous() }}" class="btn btn-outline-secondary">
                <i class="fas fa-power-off"></i>
            </a>
        </div>

        <div id="footer">
            <button type="button" class="btn btn-outline-secondary nextPrev" onclick="nextPrev('prev')">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary nextPrev" onclick="nextPrev('next')">
                <i class="fas fa-chevron-right"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary rotate" onclick="rotateContent('minus')">
                <i class="fas fa-undo"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary rotate" onclick="rotateContent('plus')">
                <i class="fas fa-redo"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary zoom" onclick="zoomContent('in')">
                <i class="fas fa-search-plus"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary zoom" onclick="zoomContent('reset')">
                <i class="fas fa-search"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary zoom" onclick="zoomContent('out')">
                <i class="fas fa-search-minus"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary fullscreen" onclick="fullscreenModeTrigger()"
                    title="მთელი ეკრანი">
                <i class="fas fa-expand"></i>
            </button>
        </div>
    </div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    let mode = 'single';
    let currentIndex = 0;
    var rotation = 0;
    var zoom = 1;
    let leftPanel = 'open';
    let fullscreenMode = 'open';

    var current_page = {!! $current_page ?? 1 !!};
    let per_page = {!! $per_page ?? 5 !!};
    let sakme_id = {!! json_encode($sakme_id ?? 1) !!};
    let url_ret = `/viewer/${sakme_id}/json`;
    let total_images = 0;
    let loading_text = "იტვირთება..."
    let no_more_files = "No more files..."

    loadResults(sakme_id, current_page, per_page);

    function leftOpenClose(){
        if(leftPanel === 'open'){
            $('#leftSide').hide();
            $('#middleBox').removeClass('boxed');
            $('#middleBox').addClass('full');
            leftPanel = 'closed';
            $('#leftOpenClose').css('left', '0%');
        }
        else{
            $('#leftSide').show();
            $('#middleBox').removeClass('full');
            $('#middleBox').addClass('boxed');
            leftPanel = 'open';
            $('#leftOpenClose').css('left', '25%');
        }
    };

    function thumbClick(index, url){
        alert(url);
        activateThumb(index);
        // Change Index
        $("#indexID").val(parseInt(index) + 1);

        // let urlToGo = 'files/details/' + url;
        let urlToGo = 'deepzoom/' + url;
        console.log(url)

        // Change URL DEPENDING ON THUMB
        $('#infoButton').attr('url', urlToGo);
    }

    $(document).on("click", '.img-element', function(event) {
        activateThumb($(this).attr('index'));
        // Change Index
        $("#indexID").val(parseInt($(this).attr('index')) + 1);

        let urlToGo = 'files/details/' + $(this).attr('elID');

        // Change URL DEPENDING ON THUMB
        $('#infoButton').attr('url', urlToGo);
    });

    // Open Details
    $(document).on("click", '#infoButton', function(event) {
        window.open('/' + $(this).attr('url'), '_blank');
    });

    // Change Mode
    $(".thumbSelector").click(function(){
        mode = $(this).val();
        $("#indexID").val();
        if(mode === 'double'){
            $('#content_viewer').removeClass('singleView');
            $('#content_viewer').addClass('bookView');
        }
        else{
            $('#content_viewer').addClass('singleView');
            $('#content_viewer').removeClass('bookView');
        }
        activateThumb(parseInt($("#indexID").val()) - 1);
    });

    // Index Change
    $(document).on("keyup", '#indexID', function(event) {
        if(parseInt(this.value) < $('#maxImages').attr('maxImages')){
            if ($.isNumeric(this.value)) {
                let newIndex = this.value - 1;
                activateThumb(newIndex);
            }
        }
        else{
            alert('ამ ინდექსის გვერდი არ არსებობს');
        }
    });

    function nextPrev(method){
        let newIndex = 0;
        if(method === 'next'){
            if(mode === 'double'){
                newIndex = parseInt($("#indexID").val()) + 2;
            }
            else{
                newIndex = parseInt($("#indexID").val()) + 1;
            }

            if(newIndex > $('#maxImages').attr('maxImages')){
                newIndex = 1;
            }
        }
        else{
            if(mode === 'double'){
                newIndex = parseInt($("#indexID").val()) - 2;
            }
            else{
                newIndex = parseInt($("#indexID").val()) - 1;
            }

            if(newIndex < 1){
                newIndex = $('#maxImages').attr('maxImages');
            }
        }
        $("#indexID").val(newIndex);
        activateThumb(newIndex -1);
    };

    function rotateContent(method){
        if(method === 'plus'){
            rotation += 90;
        }
        else{
            rotation -= 90;
        }
        $('#content_viewer').css({'transform' : 'rotate('+ rotation +'deg)'});
    };

    function zoomContent(method){
        if(method === 'in'){
            zoom += 0.3;
        }
        if(method === 'out'){
            zoom -= 0.3;
        }
        if(method === 'reset'){
            zoom = 1;
            let img_a = document.getElementById("content_viewer");
            img_a.style.top =  "auto";
            img_a.style.left =  "auto";
        }
        $("#content_viewer").animate({ 'zoom': zoom }, 0);
        $("#content_viewer").css("MozTransform", "scale(" + zoom + ")");
    };

    $(document).ready(function(){
        $('#middleBox').bind('wheel mousewheel', function(e){
            var delta;
            if (e.originalEvent.wheelDelta !== undefined)
                delta = e.originalEvent.wheelDelta;
            else
                delta = e.originalEvent.deltaY * -1;

            if(delta > 0) {
                zoom += 0.3;
                $("#content_viewer").animate({ 'zoom': zoom },0);
                $("#content_viewer").css("MozTransform", "scale(" + zoom + ")");
            }
            else{
                zoom -= 0.3;
                $("#content_viewer").animate({ 'zoom': zoom },0);
                $("#content_viewer").css("MozTransform", "scale(" + zoom + ")");
            }
        });
    });

    function activateThumb(index){
        // TODO: უნდა დაეწეროს რომ სურათი იტვირთება...
        console.log(index)
        // TODO: აქ ფუნქციით უნდა ჩაიტვირთოს დიდი ზომის სურათი.
        let large_img = ((async () => await get_full_image(index))()); // returns Promise
        large_img.then(img_src => {
            var html = '';

            $('.img-element').removeClass('active');
            if(mode === 'single'){
                $('#thumb-' + index).addClass('active');

                // let src = $('#thumb-' + index).children('img').attr('src');
                html = '<img src="'+img_src+'" class="img-fluid img-element-viewer" />';
                $('#pages').html(parseInt(index) + 1);
            }
            if(mode === 'double'){
                var nextID = parseInt(index) + 1;
                if($('#thumb-' + nextID).length){
                    let currentHTML = '';
                    let nextHTML = '';

                    $('#thumb-' + index).addClass('active');
                    let srcCurrent = $('#thumb-' + index).children('img').attr('src');
                    currentHTML = '<img src="'+srcCurrent+'" class="img-fluid img-element-viewer" />';


                    $('#thumb-' + nextID).addClass('active');
                    let srcNext = $('#thumb-' + nextID).children('img').attr('src');
                    nextHTML = '<img src="'+srcNext+'" class="img-fluid img-element-viewer"/>';

                    html = currentHTML + nextHTML;

                    let firstIndex = parseInt(index) + 1;
                    let secondIndex = parseInt(index) + 2;
                    $('#pages').html(firstIndex + ' - ' + secondIndex);

                }
                else{
                    let currentHTML = '';
                    let nextHTML = '';

                    $('#thumb-' + index).addClass('active');
                    let srcCurrent = $('#thumb-' + index).children('img').attr('src');
                    currentHTML = '<img src="'+srcCurrent+'" class="img-fluid img-element-viewer" />';


                    $('#thumb-0').addClass('active');
                    let srcNext = $('#thumb-0').children('img').attr('src');
                    nextHTML = '<img src="'+srcNext+'" class="img-fluid img-element-viewer"/>';
                    html = currentHTML + nextHTML;

                    let firstIndex = parseInt(index) + 1;
                    let secondIndex = 1;
                    $('#pages').html(firstIndex + ' - ' + secondIndex);

                }
            }
            $('#content_viewer').html(html);
        }) // async
    }


    function get_full_image(index){
        // Travler

        return new Promise((resolve, reject) =>{
            const document_id = $(`#thumb-${index}`).attr("elid");
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": jQuery('meta[name="csrf-token"]').attr("content")
                }
            });
            $.ajax({
                url: `/viewer/single/${document_id}`,
                type: "post",
                async: "false",
                data: {
                    // delay: 3,
                    sakme_id: sakme_id,
                    current_page: current_page,
                    per_page: per_page
                },
                beforeSend(jqXHR, settings) {
                    loading_svg();
                },
                success: function (data){
                    remove_loading_svg();

                    if (data.result === "success") {
                        let mime = data.data.mime_type;
                        let base64 = data.data.file_base_64;
                        resolve(`data:image/${mime};base64,${base64}`)

                    } else if (data.result === "deepzoom") {
                        let filename = data.data.filename;
                        let tiles_folder = filename.slice(0,filename.indexOf("."))
                        $('#infoButton').attr('url', `deepzoom/${tiles_folder}`);
                        window.open(`/deepzoom/${tiles_folder}`, '_blank');
                    }
                },
                fail: function(reason){
                    remove_loading_svg();
                    reject(reason)
                }
            });
        });
    }

    // FILTERS
    function editImage() {
        var gs = $("#gs").val(); // grayscale
        var blur = $("#blur").val(); // blur
        var br = $("#br").val(); // brightness
        var ct = $("#ct").val(); // contrast
        var huer = $("#huer").val(); //hue-rotate
        var opacity = $("#opacity").val(); //opacity
        var invert = $("#invert").val(); //invert
        var saturate = $("#saturate").val(); //saturate
        var sepia = $("#sepia").val(); //sepia

        $("#content_viewer img").css(
            "filter", 'grayscale(' + gs+
            '%) blur(' + blur +
            'px) brightness(' + br +
            '%) contrast(' + ct +
            '%) hue-rotate(' + huer +
            'deg) opacity(' + opacity +
            '%) invert(' + invert +
            '%) saturate(' + saturate +
            '%) sepia(' + sepia + '%)'
        );

        $("#content_viewer img").css(
            "-webkit-filter", 'grayscale(' + gs+
            '%) blur(' + blur +
            'px) brightness(' + br +
            '%) contrast(' + ct +
            '%) hue-rotate(' + huer +
            'deg) opacity(' + opacity +
            '%) invert(' + invert +
            '%) saturate(' + saturate +
            '%) sepia(' + sepia + '%)'
        );
    }
    //When sliders change image will be updated via editImage() function
    $("input[type=range]").change(editImage).mousemove(editImage);

    // Reset sliders back to their original values on press of 'reset'
    $('#imageEditor').on('reset', function () {
        setTimeout(function() {
            editImage();
        }, 0);
    });

    // DRAGGABLE
    dragElement(document.getElementById("content_viewer"));

    function dragElement(elmnt) {
        var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
        elmnt.onmousedown = dragMouseDown;
        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            // get the mouse cursor position at startup:
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            // calculate the new cursor position:
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            // set the element's new position:
            elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
            elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        }

        function closeDragElement() {
            /* stop moving when mouse button is released:*/
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }

    function updateCurrentPage(page){
        current_page = page;
    }

    let appendThumbs = function () {
        let html = '';
        html += `<li class="img-element" id="thumb-${this.index}" index="${this.index}" elID="${this.id}" data-size_mb="${this.size_mb}"> `;

            if(this.size_mb > {{env('MAX_IMAGE_SIZE')}}){
                // open in new (deep zoom) tab
                html += `<span>${this.size_mb}MB <span class="material-icons md-light">open_in_new</span> </span>`;
            }else{
                html += `<span>${this.size_mb}MB </span>`;
            }

        html += `<img src="data:image/${this.mime_type};base64,${this.file_base_64}" />`;
        html += '</li>'

        $('#thumbs').append(html);

    }

    function beforeSend(xhr) {
        $("#thumbs").after($(`<li class='loading'>${loading_text}</li>`).fadeIn('slow')).data("loading", true);
    }

    function setTotal(data) {
        total_images = data.total;
        $('.totalCounter').html(data.total);
        $('#maxImages').attr('maxImages', data.total);
    }

    // Load Thumbs
    function loadResults(sakme_id, current_page, per_page) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": jQuery('meta[name="csrf-token"]').attr("content")
            }
        });

        $.ajax({
            url: url_ret+"?page="+(parseInt(current_page)),
            type: "post",
            async: "false",
            data: {
                delay: 3,
                sakme_id: sakme_id,
                current_page: current_page,
                per_page: per_page
            },
            beforeSend: beforeSend(),
            success: function(data) {
                if(data.result === 'success'){
                    $.each(data.data, function() {
                        appendThumbs.call(this)
                    });
                    console.log(data);
                    updateCurrentPage(current_page + 1);
                    setTotal(data)
                } else {
                    if(data.message == no_more_files){
                        $('.scrollpane').removeClass('scrollpane');
                    }
                }

                $(".loading").fadeOut('fast', function() {
                    $(this).remove();
                });

            }
        });
    };

    function validateMaxImages() {
        let thumbView = $(this).get(0);
        if (current_page <= Math.ceil(total_images / per_page)) { // Don't loadResults if there is no images left.
            if(thumbView.scrollTop + thumbView.clientHeight >= thumbView.scrollHeight) {
                loadResults(sakme_id, current_page, per_page);
            }
        }
    }

    // Load More Content AJAX
    $('.scrollpane').on('scroll', function() {
        let pagesNeedForAllImages = Math.ceil(total_images / per_page)

        let thumbView = $(this).get(0);
        let clientIsOnBottom = thumbView.scrollTop + thumbView.clientHeight >= thumbView.scrollHeight
        let hasShownEverything = current_page > pagesNeedForAllImages

        setTimeout(function (){
            if (clientIsOnBottom && !hasShownEverything) {
                    loadResults(sakme_id, current_page, per_page);
                    console.log("Loaded more")
            }
        }, 1000)
    });

    // FULLSCREEN
    function fullscreenModeTrigger(){
        var elem = document.documentElement;
        if (fullscreenMode === 'open'){
            leftOpenClose();
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) { /* Safari */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) { /* IE11 */
                elem.msRequestFullscreen();
            }
            fullscreenMode = 'close'
        }
        else{
            leftOpenClose();
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) { /* Safari */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) { /* IE11 */
                document.msExitFullscreen();
            }
            fullscreenMode = 'open'
        }
    }

    const loading_svg = () => {
        $('middleBox #progress-bar').show()

        var circle = new ProgressBar.Circle('#progress-bar', {
            color: '#ffffff',
            strokeWidth: 6,
            duration: 5000,
            easing: 'easeInOut'
        });
        circle.animate(2);
    }

    const remove_loading_svg = () => {
        $('#progress-bar').fadeOut('slow');
        $('#progress-bar svg').remove()
    }


</script>
</body>

</html>
