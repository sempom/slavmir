<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="/bitrix/templates/slavmir/js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="https://cdn.jsdelivr.net/npm/clappr@latest/dist/clappr.min.js"></script>
<!--    <script type="text/javascript" charset="utf-8" src="j/main.js"></script>-->
<!--    <script type="text/javascript" charset="utf-8" src="j/editor/ace.js"></script>-->
<!--    <link rel="stylesheet" href="stylesheets/bootstrap.min.css" type="text/css" media="screen" charset="utf-8">-->
<!--    <link rel="stylesheet" href="stylesheets/bootstrap-theme.min.css" type="text/css" media="screen" charset="utf-8">-->
<!--    <link rel="stylesheet" href="stylesheets/style.css" type="text/css" media="screen" charset="utf-8">-->
<!--    <link rel="shortcut icon" type="image/png" href="i/favico.png">-->
    <script>
        window.clappr = window.clappr || {}
        window.clappr.externals = []

        function addExternal() {
            var url = document.getElementById('js-link')
            window.clappr.externals.push(url.value)
            addTag(url.value)
            url.value = ''
        }

        function addTag(url) {
            var colors = ["aliceblue", "antiquewhite", "azure", "black", "blue", "brown", "yellow", "teal"]
            var color = colors[Math.floor(Math.random() * colors.length)]
            var span = document.createElement('span')

            span.style.backgroundColor = color
            span.className = "external-js"
            span.innerText = url.split(/\//).pop().split(/\./)[0]

            document.getElementById('external-js-panel').appendChild(span)
        }

    </script>
</head>
<body>
<header class="header"></header>
<section class="container">
    <div class="main">
        <div id="output">
            <div id="player-wrapper" class="player"></div>
        </div>
    </div>
    <div class="sidebar">
        <div id="console"> </div>
    </div>
</section>
<footer class="footer"></footer>
<?php
$t=time();
$str=str_replace(PHP_EOL,',', file_get_contents('http://83.217.203.202:1935/live/slavmir/playlist.m3u8'));
$tRq=time()-$t;
?>
<script>
    $.post("/ajax/log-player.php", {
        name: <?=$t?>,
        m3u8_data: '<?=$str?>',
        m3u8_req: <?=$tRq?>,
        client: navigator.userAgent,
        step: 1
    });
//    var urlParams;
//    (function() {
//        window.onpopstate = function () {
//            var match,
//                pl     = /\+/g,  // Regex for replacing addition symbol with a space
//                search = /([^&=]+)=?([^&]*)/g,
//                decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
//                query  = window.location.search.substring(1);
//
//            urlParams = {};
//            while (match = search.exec(query))
//                urlParams[decode(match[1])] = decode(match[2]);
//        }
//        window.onpopstate();
//    })();

    var playerElement = document.getElementById("player-wrapper");
    var err;
    var player = new Clappr.Player({
        source: 'http://83.217.203.202:1935/live/slavmir/playlist.m3u8',
//        poster: 'http://clappr.io/poster.png',
        mute: true,
        height: 360,
        width: 640,
        events: {
            onError: function (e) {
                err=e;
            }
        }
    });

    player.attachTo(playerElement);
    player.play();
    $.post('/ajax/log-player.php', {
        name: <?=$t?>,
        m3u8_start: '<?=time()-$t?>',
        m3u8_error: <?=$tRq?>,
        error: err,
        step: 2
    });

    //editor
//    window.onload = function() {
//        var editor = ace.edit("editor");
//        var session = editor.getSession();
//
//        editor.setTheme("ace/theme/katzenmilch");
//        session.setMode("ace/mode/javascript");
//        session.setTabSize(2);
//        session.setUseSoftTabs(true);
//
//        var parser = new Parser($('#output'));
//        var load = function(fn) {
//            if (window.clappr.externals.length > 0) {
//                var lastScript = window.clappr.externals.length
//                window.clappr.externals.forEach(function(url, index) {
//                    var script = document.createElement('script')
//
//                    script.setAttribute("type", "text/javascript")
//                    script.setAttribute("src", url)
//                    if (index === (lastScript - 1)) {
//                        script.onload = fn
//                    }
//                    script.onerror = function(e){alert('we cant load ' + url + ': e' + e)}
//
//                    document.body.appendChild(script)
//                })
//            } else {
//                fn()
//            }
//        }
//
//        $('.run').click(function() {
//            var code = ace.edit('editor').getSession().getValue();
//            load(function(){parser.parse(code)})
//        });
//    }
</script>
</body>
</html>
