<div class="app-message">
    <div class="app-message__logo"></div>
    <div class="app-message__close">X</div>
    <div class="app-message__block">
        <div class="app-message__tline"></div>
        <div class="app-message__text">Через приложение искать работу</div>
        <div class="app-message__text">и персонал быстрее и удобнее</div>
        <div class="clearfix"></div>
        <div class="app-message__bline"></div>
    </div>
    <div class="app-message__bottom">
        <a href="<?=MainConfig::$LINK_TO_PLAYMARKET?>" class="app-message__link">открыть в приложении</a> 
        <div class="app-message__continue">продолжить в мобильной версии</div>
    </div>
    <div class="app-message__play"></div>
</div>
<style type="text/css">
    @font-face{ 
        font-family: 'Roboto-Regular'; 
        src: url('/theme/fonts/Roboto-Regular.eot') format('truetype'); 
        src: local('Roboto-Regular'), 
        url(/theme/fonts/Roboto-Regular.ttf), 
        url(/theme/fonts/Roboto-Regular.otf), 
        url(/theme/fonts/Roboto-Regular.woff),
        url(/theme/fonts/Roboto-Regular.woff2); 
    }
    @font-face{ 
        font-family: 'RobotoCondensedBold'; 
        src: url(/theme/fonts/RobotoCondensedBold.eot); 
        src: local('RobotoCondensedBold'), 
        url(/theme/fonts/RobotoCondensedBold.ttf), 
        url(/theme/fonts/RobotoCondensedBold.woff), 
        url(/theme/fonts/RobotoCondensedBold.woff2); }

    html, body { width: 100%; height: 100% !important; }
    body { 
        position: relative; 
        font-family: Roboto-Regular, verdana, arial;
        color: #212121;
        margin: 0;
        padding: 0;
    }
    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    *:before, *:after{
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .clearfix{ clear: both }
    .app-message{
        width: 100%;
        height: 100%;
        min-width: 350px;
        min-height: 600px;
        position: absolute;
        left: 0;
        top: 0;
        background: #95AD01 url(/theme/pic/app-message/am-bg.jpg) 50% 100% no-repeat;
        background-size: cover;
        z-index: 1;
        -webkit-transition: top 0.5s ease-out;
        -moz-transition: top 0.5s ease-out;
        -o-transition: top 0.5s ease-out;
        transition: top 0.5s ease-out;
    }
    .app-message__logo{
        width: 100%;
        height: 120px;
        background: url(/theme/pic/app-message/am-logo.png) 13% 40px no-repeat;
    }
    .app-message__close{
        width: 40px;
        height: 40px;
        position: absolute;
        top: 30px;
        right: 30px;
        color: #BFDF04;
        border: 1px solid #BFDF04;
        border-radius: 20px;
        text-align: center;
        line-height: 38px;   
        cursor: pointer;
        font-size: 20px;
        font-family: 'RobotoCondensedBold', verdana, arial;
        background-color: rgba(191, 223, 4, .0);
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        -o-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
    }
    .app-message__close:hover{ background-color: rgba(191, 223, 4, .3) }
    .app-message__block{
        width: 100%;
        padding: 25px 15px 20px 15px;
        text-align: center;
        text-transform: uppercase;
        color: #FFFFFF;
        position: relative;
        font-size: 20px;
        font-family: 'RobotoCondensedBold', verdana, arial;
    }
    .app-message__tline,
    .app-message__bline{
        height: 1px;
        position: absolute;
        background-color: #FFFFFF;
    }
    .app-message__tline{
        top: 0;
        right: 20%;
        left: 0;
    }
    .app-message__bline{
        bottom: 0;
        right: 0;
        left: 20%;
    }
    .app-message__tline:before,
    .app-message__bline:before{
        content: '';
        width: 8px;
        height: 8px;
        background-color: #FFFFFF;
        border-radius: 4px;
        position: absolute;
        top: -3px;
    }
    .app-message__tline:before{ right: 0 }
    .app-message__bline:before{ left: 0 }
    .app-message__text{
        width: 85%;
        padding-bottom: 5px;
        white-space: nowrap;
    }
    .app-message__text:nth-child(3){ float: right }
    .app-message__play{
        width: 146px;
        height: 42px;
        position: absolute;
        right: 10px;
        bottom: 10px;
        background: rgba(171, 184, 32, 0.7) url(/theme/pic/app-message/am-play.png) 5px 5px no-repeat;
        border-radius: 5px;
    }
    .app-message__bottom{
        width: 100%;
        position: absolute;
        bottom: 60px;
        left: 0;
    }
    .app-message__link{
        width: 100%;
        max-width: 415px;
        margin: 0 auto 25px;
        padding: 16px 10px;
        display: block;
        color: #FFFFFF;
        text-transform: uppercase;
        font-size: 20px;
        text-decoration: none;
        background-color: #FF7800;
        text-align: center;
        position: relative;
        z-index: 1;
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        -o-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
    }
    .app-message .app-message__link:hover{ color: #FFFFFF }
    .app-message__link:before {
        content: '';
        position: absolute;
        z-index: -1;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #ABB820;
        -webkit-transform: scaleX(0);
        transform: scaleX(0);
        -webkit-transform-origin: 0 50%;
        transform-origin: 0 50%;
        -webkit-transition: all 0.3s ease-out;
        -moz-transition: all 0.3s ease-out;
        -o-transition: all 0.3s ease-out;
        transition: all 0.3s ease-out;
    }
    .app-message__link:hover:before{
        -webkit-transform: scaleX(1);
        transform: scaleX(1);
    }
    .app-message__continue{
        width: 100%;
        max-width: 380px;
        padding: 8px 10px;
        margin: 0 auto;
        color: #5f6708;
        background-color: rgba(255, 255, 255, 0.8);
        text-transform: uppercase;
        font-size: 18px;
        cursor: pointer;
        text-align: center;
    }
    @media (max-width: 400px){
        .app-message__block{ font-size: 18px }
    }
    @media (max-width: 350px){
        .app-message__block{
            font-size: 16px;
            text-align: left;
        }
        .app-message__link{ font-size: 16px }
        .app-message__continue{ font-size: 14px }
    }
    @media (min-width: 768px){
        .app-message{ 
            height: calc(100vh - 125px);
            top: 125px;
        }
    }
</style>