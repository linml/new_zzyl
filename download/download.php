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
    <div id="newdownloadButton" class="download_btn bottom">
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

<script type="text/javascript" src="//www.shareinstall.com.cn/js/page/jshareinstall.min.js?v=3cca38948e"></script>

<script type="text/javascript">
    //shareinstall初始化，初始化时将与shareinstall服务器交互，应尽可能早的调用
    /*web页面向app传递的json数据(json string/js Object)，应用被拉起或是首次安装时，通过相应的android/ios api可以获取此数据*/
    var data = ShareInstall.parseUrlParams();//shareinstall.js中提供的工具函数，解析url中的所有查询参数
    console.log(data);
    new ShareInstall({
        appKey : 'F7BK2B6KBEBBAE',
        //可选项，微信中打开自定义遮罩层
        //shadow : function(){
        //  return "<div id='shareinstall_shadow' style='position:fixed;left:0;top:0;background:rgba(0,255,0,0.5);filter:alpha(opacity=50);width:100%;height:100%;z-index:10000;'></div>"
        // },
        /*可选参数，是否优先考虑下载app，以牺牲唤醒为代价*/
        //preferInstall:true,
        /*可选参数，是否优先考虑拉起app，以牺牲下载体验为代价*/
        //preferWakeup:true,
        /*可选参数，微信中直接跳下载*/
        //weChatdownload:true,
        onready : function() {
            //shareinstall已成功回调
            var m = this, button = document.getElementById("downloadButton");
            button.style.visibility = "visible";

            /*用户点击某个按钮时(假定按钮id为downloadButton)，安装app*/
            var u = navigator.userAgent;
            button.onclick = function() {
                var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
                if (isiOS) {

                } else {
                    m.wakeupOrInstall();
                }
            }

            newbutton = document.getElementById("newdownloadButton");
            newbutton.style.visibility = "visible";

            /*用户点击某个按钮时(假定按钮id为downloadButton)，安装app*/
            newbutton.onclick = function() {
                var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
                if (isiOS) {

                } else {
                    m.wakeupOrInstall();
                }
            }
        }
    }, data);
</script>

<script type="text/javascript">
    function download() {
        // console.log('hello');return;
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == "micromessenger") {
//alert('请点击右上角选择在浏览器中打开');//
            $('.weixin-tip').css('display', 'block');
        } else {
            var u = navigator.userAgent;
            var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1 || u.indexOf('android') > -1; //android终端
            if (isAndroid) {
                //location.href = "<?php echo $gameConfig['android_packet_address'] ? $gameConfig['android_packet_address'] : 'anzhuo.apk';?>"
            } else {
                var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
                if (isiOS) {
                    location.href = "<?php echo $gameConfig['apple_packet_address'];?>";
                } else {
                    //location.href = "<?php echo $gameConfig['android_packet_address'] ? $gameConfig['android_packet_address'] : 'anzhuo.apk';?>"
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