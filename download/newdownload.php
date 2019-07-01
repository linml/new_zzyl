<?php
if (!defined('zzyl')) {
    header('HTTP/1.1 404 Not Found');
    exit;
}
?>
<!DOCTYPE html>
<html>
<header>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,minimum-scale=1.0,user-scalable=0" />
    <title><?php echo $getHomeConfig['title']?></title>
    <style type="text/css">
        body {
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0;
            /*display: none;*/
        }
        #container {
            padding-bottom: 70px;
        }
        .bottom {
            bottom: 0px;
            position: fixed;
            width: 100%;
            background: #FFFFFF;
            /*max-height: 100px;*/
        }
        .bottom > span {
            display: block;
            max-width: 800px;
        }
        #head {
            position: relative;
        }
        #LiJiXiaZai {
            position: absolute;
            width: 100%;
            bottom: 10px;
        }
        .spanLiJiXiaZai {
            display: block;
            width: 75%;
            margin: auto;
        }
        .weixin-tip {
            display: none;
            height: 80px;
            line-height: 50px;
            font-size: 28px;
            font-family: '微软雅黑';
            color: #FFF;
            text-align: center;
            background: url(./images/weixin-tip.png);
            background-size: 100% 100%;
            font-weight: normal;
            position: absolute;
            width: 100%;
            left: 0;
            top: 0;
            z-index: 999;
            padding: 0px 0px 8px;
        }
    </style>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script src="js/lazyload.min.js"></script>
</header>
<body>
<div class="weixin-tip"></div>
<div id="container">
    <audio autoplay="autoplay" height="100" width="100" id="audio" loop="-1">
        <source src="./music.mp3" type="audio/mp3"/>
    </audio>
    <div id="head">
        <img style="width:100%;"
             srcs=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
             src=images/download/1.png
             onloads=lzld(this) />
        <div id="LiJiXiaZai">
  <span id="downloadButton" class="download_btn spanLiJiXiaZai">
  <img style="width:100%;"
       srcs=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
       src=images/download/LiJiXiaZai.png
       onloads=lzld(this) />
  </span>
        </div>
    </div>
    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/2.png
         onload=lzld(this) />
    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/3.png
         onload=lzld(this) />
    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/4.png
         onload=lzld(this) />

    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/5.png
         onload=lzld(this) />
    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/6.png
         onload=lzld(this) />
    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/7.png
         onload=lzld(this) />
    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/8.png
         onload=lzld(this) />

    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/9.png
         onload=lzld(this) />
    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/10.png
         onload=lzld(this) />
    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/11.png
         onload=lzld(this) />
    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/12.png
         onload=lzld(this) />

    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/13.png
         onload=lzld(this) />
    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/14.png
         onload=lzld(this) />
    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/15.png
         onload=lzld(this) />
    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/16.png
         onload=lzld(this) />

    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/17.png
         onload=lzld(this) />
    <img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/18.png
         onload=lzld(this) />
    <div id="downloadButton" class="download_btn bottom">
<span>
	<img style="width:100%;"
         src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
         data-src=images/download/download.png
         onload=lzld(this) />
</span>
    </div>

</div>
</body>
</html>

<!-- 以下为openinstall集成代码，建议在html文档中尽量靠前放置，加快初始化过程 -->
<!-- 强烈建议直接引用下面的cdn加速链接，以得到最及时的更新，我们将持续跟踪各种主流浏览器的变化，提供最好的服务；不推荐将此js文件下载到自己的服务器-->
<script type="text/javascript" charset="UTF-8" src="//res.cdn.openinstall.io/openinstall.js"></script>
<script type="text/javascript">
    //openinstall初始化时将与openinstall服务器交互，应尽可能早的调用
    /*web页面向app传递的json数据(json string/js Object)，应用被拉起或是首次安装时，通过相应的android/ios api可以获取此数据*/
    var data = OpenInstall.parseUrlParams();//openinstall.js中提供的工具函数，解析url中的所有查询参数
    console.log(data);
    new OpenInstall({
        /*appKey必选参数，openinstall平台为每个应用分配的ID*/
        appKey : "keoupx",
        /*可选参数，自定义android平台的apk下载文件名；个别andriod浏览器下载时，中文文件名显示乱码，请慎用中文文件名！*/
        //apkFileName : 'com.fm.openinstalldemo-v2.2.0.apk',
        /*可选参数，是否优先考虑拉起app，以牺牲下载体验为代价*/
        //preferWakeup:true,
        /*自定义遮罩的html*/
        //mask:function(){
        //  return "<div id='openinstall_shadow' style='position:fixed;left:0;top:0;background:rgba(0,255,0,0.5);filter:alpha(opacity=50);width:100%;height:100%;z-index:10000;'></div>"
        //},
        /*openinstall初始化完成的回调函数，可选*/
        onready : function() {
            var m = this, button = document.getElementById("downloadButton");
            button.style.visibility = "visible";

            /*在app已安装的情况尝试拉起app*/
            m.schemeWakeup();
            /*用户点击某个按钮时(假定按钮id为downloadButton)，安装app*/
            button.onclick = function() {
                m.wakeupOrInstall();
                return false;
            }
        }
    }, data);

</script>

<script type="text/javascript">
    function download() {
        // console.log('hello');return;
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == "micromessenger") {
//alert('请点击右上角选择在浏览器中打开');
            $('.weixin-tip').css('display', 'block');
        } else {
            var u = navigator.userAgent;
            var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1 || u.indexOf('android') > -1; //android终端
            if (isAndroid) {
                location.href = "<?php echo $gameConfig['android_packet_address'] ? $gameConfig['android_packet_address'] : 'anzhuo.apk';?>"
            } else {
                var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
                if (isiOS) {
                    location.href = "<?php echo $gameConfig['apple_packet_address'];?>";
                } else {
                    location.href = "<?php echo $gameConfig['android_packet_address'] ? $gameConfig['android_packet_address'] : 'anzhuo.apk';?>"
                }
            }

        }
    }
    $('.download_btn').click(function() {
        console.log('click');
        download();
    })
    $(window).load(function() {
        console.log('load');
        $('body').css({'opacity': 1});
    })
</script>