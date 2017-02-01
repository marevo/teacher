<?php
//phpinfo();
?>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="db_style.css">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
        <script type="text/javascript" src="ajax_post_get.js"></script>

    </head>
<body style="background-color:#e9fff8">
<?php //echo $_SERVER['DOCUMENT_ROOT'];?>
<div id="div_0">
<div style=" width:100%; height:1px; clear:both;"></div>
    <div id="slider">надо вставить красивые картинки осень/школа
        <script>
            var mas = new Array("osennie_listya.jpg", "zolotaya_osen.jpg", "osen.jpg");
            var i=0;
            //setInterval('function_img()',1500);
            var sl = document.getElementById('slider');
            sl.innerHTML = "<img src='"+"images/"+ mas[mas.length-1] + "'/ id='img_slider' alt='img_osen' >";
            //setInterval('function_img_inner()',3000);
            function function_img(){
                if(i==mas.length){
                    i=0;
                }
                var sl = document.getElementById('slider');
                while(sl.childNodes.length){
                    sl.removeChild(sl.childNodes[0]);
                }
                var imgg = document.createElement('img');
                imgg.setAttribute('src',mas[i]);
                sl.appendChild(imgg);
                i++;
            }
            function function_img_inner(){
                var sl = document.getElementById('slider');
                if(i == mas.length){
                    i = 0;
                }
                sl.innerHTML = "<img src='"+"images/"+ mas[i] + "'/ id='img_slider' alt='img_osen' >";
                i++;
            }
           // function_img_inner();
        </script>
   </div>
    <div id="nad_menu_row">
        <span style="font-size: 20px; font-weight: bold; text-align: center;"> АРМ-школа</span><br/>
         <!-- Приступа 16.11.2016 Форма для входа -->
        <input type="button" class="forma" value="Ввойти" style="height: 20px;"/>

        <form name="login_password" style="padding-left: 5px; display: none;">
             Логин:<br/>
            <input name="login" type="text" size="25" maxlength="30" value="" /> <br/>
                 Пароль: <br />
            <input name="password" type="password" size="25" maxlength="30" value="" /> <br/>
            <input name="remember" type="checkbox" value="yes" /> Запомнить <br/>
                <!--Рево изменение 17.11.2016  добавка обработки входа по ролям -->
            <input type="button" name="enter" value="Вход" onclick="f_roles();" style="height: auto;"/>
        </form>
        <!--<span>  Наш коллектив          </span>
        <p>Выберите дату: <input type="date" name="calendar">-->
    </div>
    <div id="foto_teach"  style="float: right; width: 320px; height: 250px;">
        <img src="images/photo_teacher.jpg" alt="foto_teacher"/>
    </div>
       <!--
       <div id="menu_row">
        <input type="button" onclick='location.href="http://ksy/index.php";'    value="главная"/>
        <input type="button" onclick='location.href="index.php";'    value="главная  2"/>
        <input type="button" value="обо мне"/>
        <input type="button" name="but_nashi_dety" value="наши дети"
               onclick="jquery_send('#content_0', 'post', 'db_nashi_dety.php', ['marker_str_nashi_dety'], []);" />
        <input type="button" value="наши достижения"/>
        <input type="button" value="для родителей"/>
    </div>
      <iframe src="https://youtu.be/ssKwU36SYAo"></iframe>
     -->
    <div id="content_0">


    </div>
    <div style="clear: both;"></div>
	<div id="futer">futer</div>
</div>

<script>
    function func(elem){
        var this_el=elem;
        if(this_el.style.backgroundColor=="#e3fff4"){
            this_el.style.backgroundColor="red";
        }
        else {
            this_el.style.backgroundColor="#e3fff4"
        }
    }
    //Приступа 16.11.2016 Для открытия формы логина-пароля
    $('.forma').click(function()
    {
        login_password.style.display ="block";
    });
    function f_roles(){
        var login = document.forms.login_password.login.value;
        var password =document.forms.login_password.password.value;
        if(login!="" && password!=""){
            jquery_send('#content_0', 'post', 'contr_index.php', ['login','password'], [login,password]);
        }
        else{
            alert('для входа надо заполнить 2 поля логин и пароль');
        }
    }
</script>
</body>


</html>
