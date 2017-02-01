<?php
/*
 * Created by PhpStorm.
 * User: marevo
 * Date: 17.11.2016
 * Time: 19:22
 */
//$id_user=$_GET['id_user'];
//echo '<span style="background-color: #ffe79b;">привет вы зашли как учитель с id= '.$obj->id_user.'</span>';
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="db_style.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/bootstrap-3.3.2-dist/css/bootstrap.min.css">

    <script type="text/javascript" src="ajax_post_get.js"></script>
    <script type="text/javascript" src="new_java_script.js"></script>
</head>
<body style="background-color:#e9fff8">
<div id="div_0">
    <span style="font-size: 20px; font-weight: bold; text-align: center; ">
        <h1 style="background-color: #e0f7ff; text-align: center; ">
            страница для работы учителя
                <input id="date_time_on_client" style="background-color: #ffffee; font-size: 40px; border-radius: 10px;" readonly="true" value="-1" size="16" >
            <span id="n_c" style="font-size: 40px; font-weight: bold; text-align: center;">  в расписании нет урока в этот день в это время<br>вы можете выбрать предмет и дату когда был урок, чтобы проставить оценки
            </span>
        </h1>
    </span>
    <div id="photo_teacher" style="background-color: #fff; float: left; ">
        <img src="/photo_teacher/teacher_2.jpg"  alt="foto_teacher" style="float:left; width: 150px;"/>

        <div  style="float: right;">
            <div id="div_teacher">здесь див для отображения всяких проверок
                <div  style="float: right;"></div>
            </div>

            <form name="form_teacher" style="float: left; background-color: aliceblue;font-size: 25px;">

                <div style="float: left;background-color:#e9fff8">
                    <input type="text" name="id_teacher" value="id teacher" style="display: block;">
                    <input type="text" name="name_teacher" value="Name Teacher" style="width: 420px;font-size: larger; display: none;">
                     <select name="select_name_class" id="sel_class" onchange="sel_name_class();return false;" style="font-size: 25px; "></select><br>
                    <select name="select_name_subject" id="sel_subject" style="display: none; font-size: 25px;" onchange="sel_name_subject();return false;"></select>
                    неделю назад было <input type="text" name ="month_age" >
                    <div style="width: 210px; background-color: #e3fff4; ">
                        <div onclick="week_ago();" style="height: 32px; width: 40px;float:left;">
                            <img style ="height:32px; vertical-align: middle;" src="/images/but_age.jpg">
                        </div> &nbsp &nbsp неделя
                        <div  style="height: 32px; width: 40px;float:right;" onclick="week_later();">
                            <img style ="height:32px; vertical-align: middle;" src="/images/but_later.jpg">
                        </div>
                    </div>
                    <select name="select_date" id="sel_date" style="display: block; font-size: 25px;" value="-1" onchange="sel_datee();return false;"></select>
                </div>
                <div style="float: right; display: block;">
                    id_time_table<input type="text" name="id_time_table" value="-1" readonly="true" style="display: block;">
                    id предмета<input type="text" name="subject_id" value="-1" readonly="true" style="display: block;">
                    name предмета<input type="text" name="subject_name" value="-1" readonly="true" style="display: block;">
                    id класса<input type="text" name="class_id" value="-1" readonly="true" style="display: block;">
                    name класса<input type="text" name="class_name" value="-1" readonly="true" style="display: block;">
                    digit класса<input type="text" name="class_digit" value="" readonly="true" style="display: block;">
                </div>
            </form>
        </div>
    </div>

    <div style="display: none;">
          <div style="background-color: bisque;"> передаем день на сервер <div id="day_now_post" style="background-color: #cccccc;" readonly="readonly" value="передаем день недели" size="6" ></div><br>
             <span style="background-color: bisque;"> получили день для проверки с сервера <div  id="day_now_response" style="float: right; width: 30%; background-color:  #cccccc;">  пока не получили день</div></span><br>
             <span style="background-color: bisque;">получили id урока из таблицы расписания <div  id="lesson_now" style="float: right; width: 30%; background-color: beige;" > пока не получили id урока</div></span>
          </div>
    </div>

    <div style="clear: both; background-color: #ffffee"  ></div>
    <div style=" /*width: 1024px;*/  float: left;overflow: hidden; background-color: cornsilk;">
        <form name="notice_st" style="display: block;">

               <div id="notice_st_shapka">
                   <div>id</div>
                   <div>фамилия</div>
                   <div>не был</div>
                   <div >оценка работы на уроке</div>
                   <div>самостоятельная</div>
                   <div>контрольная</div>
               </div>
               <div id="notice_st_body">
                   <div><input style="max-width: 30px" type="text" name="nsb_id" readonly="true"></div>
                   <div><input style="max-width: 130px" type="text" name="nsb_name" readonly="true"></div>
                   <div><input style="max-width: 30px" type="text" name="nsb_be_absent" readonly="true" onclick="check_absent(this);" ></div>
                   <div><input type="number" name="nsb_score_lesson" min="0" max="12" onchange="f_score_true(this);" ></div>
                   <div><input type="number" name="nsb_score_sam" min="0" max="12" onchange="f_score_true(this);" ></div>
                   <div><input type="number" name="nsb_score_kr" min="0" max="12" onchange="f_score_true(this);" ></div>
               </div>
          

             <!--   <tbody id="notice_st_body">
                <td><input type="text" name="nsb_id" readonly="true"></td>
                <td><input type="text" name="nsb_name" readonly="true"></td>
                <td><input type="text" name="nsb_be_absent" readonly="true" onclick="check_absent(this);" ></td>
                <td><input type="number" name="nsb_score_lesson" min="0" max="12" onchange="f_score_true(this);" ></td>
                <td><input type="number" name="nsb_score_sam" min="0" max="12" onchange="f_score_true(this);" ></td>
                <td><input type="number" name="nsb_score_kr" min="0" max="12" onchange="f_score_true(this);" ></td>
                </tbody>
                -->
            <input type="button" name="but_check_stud" onclick="check_student();" value="зафиксировать">
            <br><button type="button" class="btn btn-ttc">Сохранить изменения</button>
        </form>
        <table id="table_student" style="float:left; display:none; background-color: aliceblue;">
            <tbody id="shapka">
            <tr>
                <td>id</td>
                <td>фамилия</td>
                <td>не был</td>
                <td>оценка работы на уроке</td>
                <td>самостоятельная</td>
                <td>контрольная</td>
            </tr>
            </tbody>
            <tbody id="tablebod">

            </tbody>
        </table>
    </div>

</div>



<script>
    var day_now =new Date();
    var hh;  //=day_now.getHours();
    var mm; //=day_now.getMinutes();
    var sec; //=day_now.getSeconds();
    var time_now;
    var y;
    var m;
    var dd;
    var date_now;
    var day_w = day_now.getDay();//день недели js
    var day_now_post = document.getElementById("day_now_post");
    day_now_post.size="20";
    day_now_post.textContent=day_w;//для передачи номера дня(1-пнд) в запросы на сервер
    var id_class="";//id класса в котором ведется урок
    var opt_dat = -1;
    function d(){
        day_now=new Date();
        hh =day_now.getHours();
        mm =day_now.getMinutes();
        sec =day_now.getSeconds();
        day_w =day_now.getDay();//день недели js
        y = day_now.getFullYear();
        m = day_now.getMonth()+1;//получили месяц, но прибавили 1, так как январь равен 0;
        dd = day_now.getDate();
        if(dd< 10)
            dd = "0"+dd;
        if(m<10)
            m = "0"+m;
        date_now = y + "-" + m + "-" +dd;
        var t_t_s = document.getElementById("date_time_on_client");
        if( sec < 10)
            sec="0"+sec;
        if(mm<10)
            mm="0"+mm;
        time_now = hh+":"+mm+":"+sec;
        t_t_s.value = dd + "-"+ m +"-" +y+"    "+ time_now;
        //в селект select.date выберем текущую дату и день недели
        if(opt_dat == -1)
            document.form_teacher.select_date.innerHTML = "<option value='"+day_w+"'>"+ date_now +"</option>";//сегодняшний день для заполнения и просмотра оценок за сегодня
        opt_dat++;
    }

    setInterval("d()",1000);
    /* setTimeout("this_date_to_select_date()",1500);
     //в селект select.date выберем текущую дату и день недели
     function this_date_to_select_date(){

     var option="<option value='"+day_w+"'>"+ date_now +"</option>";//сегодняшний день для заполнения и просмотра оценок за сегодня
     document.form_teacher.select_date.innerHTML = option;
     }*/


    var now = new Date();
    var day = now.getDay();//взяли день недели
    var id_subj = -1;//предмет еще не определен
    var id_time_table = -1;//урок еще не подтянут из таблицы нарсписания
    var id_student = -1;// студент еще не выбран
    //alert( day+now );
    function f_score_true(elem){//функция проверки правильности поставленной оценки 0<оценка<13
        if(elem.value>12 || elem.value<0) elem.value="";
        if(elem.name == "nsb_score_kr"){
            document.notice_st.nsb_score_sam.value = "";
            document.notice_st.nsb_score_lesson.value = "";
        }
        else{
            document.notice_st.nsb_score_kr.value ="";
        }
    }
    function week_ago(){//посмотреть-проставить оценки прогулы по этому предмету на неделю раньше
        var this_date_p = document.form_teacher.select_date.options[document.form_teacher.select_date.selectedIndex].text;
        var this_date_minus_7 = date_minus_n_days(this_date_p,7);//новое число на неделю раньше
        var this_day_week = day_week_f(this_date_minus_7);//день недели 1-пнд  ... 7- воскресенье
        var dig_class= document.form_teacher.class_digit;
        //var this_day_week = document.form_teacher.select_date.options[document.form_teacher.select_date.selectedIndex].value;
        var id_subject = document.form_teacher.subject_id.value;//id subject
        var id_class = document.form_teacher.class_id.value;//id_class
        var id_time_table = document.form_teacher.id_time_table.value;
        clear_tables();//перед получением данных очистим таблицы от старых
        //для пробы выведем сначала в #div_teacher после проверки в #select_date
        jquery_send('#div_teacher','post', 'contr_teacher.php',['week_ago','date_ago', 'day_week', 'id_class','id_subj','id_time_table'],
            ['',this_date_minus_7,this_day_week,id_class, id_subject,id_time_table ]);
    }
    function week_later(){//посмотреть оценки прогулы по этому предмету на неделю позже
        var this_date_p = document.form_teacher.select_date.options[document.form_teacher.select_date.selectedIndex].text;
        var this_date_plus_7 = date_minus_n_days(this_date_p,-7);//новое число на неделю позже выбранной в селект дате дни набо передавать со знаком минус
        var this_day_week = day_week_f(this_date_plus_7);// передали дату в формате гггг-мм-дд получили день недели 1-пнд  ... 7- воскресенье
        var id_subject = document.form_teacher.subject_id.value;//id subject
        var id_class = document.form_teacher.class_id.value;//id_class
        var id_time_table = document.form_teacher.id_time_table.value;
        var date_today = date_now;
        //таблицы будем чистить если будут занятия через неделю
        jquery_send('#div_teacher','post','contr_teacher.php',['week_later','date_today', 'date_later','day_week','id_class','id_subj','id_time_table'],
        ['', date_today, this_date_plus_7, this_day_week,id_class, id_subject, id_time_table]);
        
    }
    function day_week_f(date){//передаем дату в виде гггг-мм-дд получаем день недели 1-понедельник ---7 воскресенье
        var this_date_copy = new Date(Date.parse(date));
         var this_day = this_date_copy.getDay();
        if(this_day == 0) //неделя начинается в воскресенье
            this_day=7;
        //console.log(this_day);
        return this_day;
    }
    function date_minus_n_days(date, days){//передаем дату в формате гггг-мм-дд, количество дней ( если с - то прибавит) получаем новую дату в виде гггг-мм-дд
        var this_date_copy = new Date( Date.parse(date));
        //console.log(this_date_copy);
        this_date_copy.setDate(this_date_copy.getDate()-days);
        var y=this_date_copy.getFullYear();
        var m=this_date_copy.getMonth()+1;
        if(m<10)
            m="0"+m;
        var d= this_date_copy.getDate();
        if(d<10)
            d="0"+d;
        var d_to_str_copy = y+"-"+m+"-"+d;
       // console.log(d_to_str_copy);
        return d_to_str_copy;
    }
    function date_plus_n_days(date, days){//передаем дату в формате гггг-мм-дд, количество дней получаем новую дату в виде гггг-мм-дд


    }
    //---------------------
    function check_student() {//отправка оценки на сервер не прогульщика надо чтобы был выбран урок
        if (document.notice_st.nsb_id.value == "")
            return;
        id_time_table = document.form_teacher.id_time_table.value;//считали урок по расписанию
        id_student = document.notice_st.nsb_id.value;// считали id_student
        var score_type = -1;//1- за урок, 2- по самостоятельной, 3- по контрольной
        //id_subj = document.form_teacher.subject_id.value;//сняли id_subject какой предмет
        //дата изменения отправляется в базу берем из селекта выбора дат занятий id="sel_date"
        var sd = document.getElementById("sel_date");
        var date_change = -1;
        if(sd.options[sd.selectedIndex].text !="")
           date_change = sd.options[sd.selectedIndex].text;
        var ss = document.getElementById("sel_subject");
        id_subj = ss.options[ss.selectedIndex].value;
        // document.form_teacher.subject_id.value = id_subj;//для проверки ставим
        var st_abs;
        var id_teacher= document.form_teacher.id_teacher.value;
        //var tabl_rows = document.getElementById("table_student").getElementsByTagName("tr");

        //найдем строку в таблице студентов с которой работаем, чтобы проще к ней обращаться
        var str_t_stud = -1;
        var tb = document.getElementById("tablebod");
        for (var i = 0; i < tb.childNodes.length; i++) {
            if (tb.childNodes[i].childNodes[0].textContent == id_student) {
                str_t_stud = i;//номер строки в боди таблицы отображающей студенотов класса и их прогулы, оценки- будем пересылать на сервер для быстрого заполнения изменений в таблице
            }
        }

        if (id_time_table > -1 && id_subj > -1 && id_student > -1 && str_t_stud > -1 ) {//если определен id занятия по расписанию, студента, предмета
    //console.log(tb.childNodes[str_t_stud].childNodes[2].textContent);
            if (document.notice_st.nsb_be_absent.value != tb.childNodes[str_t_stud].childNodes[2].textContent && document.notice_st.nsb_be_absent.value == "") {//значение поменялось и стало пусто - значит студент пришел на занятие ( наверно опоздал)
                //удаление из таблицы прогулов так как он пришел( но перед этим его не было( может опоздал)
                jquery_send('#div_teacher', 'post', 'contr_teacher.php', ['delete_absent', 'id_student', 'id_time_table', 'date_change', 'str_t_stud'], ['', id_student, id_time_table, date_change, str_t_stud]);
                //console.log(id_student +"  "+ id_time_table +"  "+ date_change);
            } else if (document.notice_st.nsb_be_absent.value != tb.childNodes[str_t_stud].childNodes[2].textContent && document.notice_st.nsb_be_absent.value == "н") {
                //добавляем прогульщика в таблицу be_absent
                jquery_send('#div_teacher', 'post', 'contr_teacher.php', ['insert_absent', 'id_student', 'id_time_table', 'date_change', 'str_t_stud', 'type_absent'], ['', id_student, id_time_table, date_change, str_t_stud, '1']);
            }
          /*  else if (document.notice_st.nsb_be_absent.value != tb.childNodes[str_t_stud].childNodes[2].textContent && document.notice_st.nsb_be_absent.value == "о") {
                //добавляем прогульщика в таблицу be_absent
                jquery_send('#div_teacher', 'post', 'contr_teacher.php', ['insert_absent', 'id_student', 'id_time_table', 'date_change', 'str_t_stud'], ['', id_student, id_time_table, date_change, str_t_stud]);
            }*/
            //это не прогульщик, можно передать поставить в базу scores ему оценки( если они есть)
            //оценка на уроке если она(score_lesson) есть и отличается от той что была будем insert_update_delete
            if (document.notice_st.nsb_score_lesson.value != "" && document.notice_st.nsb_score_lesson.value != tb.childNodes[str_t_stud].childNodes[3].textContent
                && document.notice_st.nsb_be_absent.value=="" && document.notice_st.nsb_score_kr.value =="") {//если не стоит "н" и нет оценки по контрольной можно ставить о по уроку
                score_type=1;
                jquery_send('#div_teacher', 'post', 'contr_teacher.php',
                    ['score_lesson', 'score_les','score_type','id_student', 'id_subj', 'date_change', 'str_t_stud', 'id_teacher'],
                    ['',document.notice_st.nsb_score_lesson.value, score_type, id_student, id_subj, date_change, str_t_stud, id_teacher]);
            }
            if (document.notice_st.nsb_score_sam.value != "" && document.notice_st.nsb_score_sam.value != tb.childNodes[str_t_stud].childNodes[4].textContent
                && document.notice_st.nsb_be_absent.value=="" && document.notice_st.nsb_score_kr.value =="") {////если не стоит "н" и нет оценки по контрольной можно ставить о по самостоятельной
                score_type=2;
                jquery_send('#div_teacher', 'post', 'contr_teacher.php',
                    ['score_lesson','score_les','score_type', 'id_student', 'id_subj', 'date_change', 'str_t_stud', 'id_teacher'],
                    ['',document.notice_st.nsb_score_sam.value, score_type, id_student, id_subj, date_change, str_t_stud, id_teacher]);
            }
            if (document.notice_st.nsb_score_kr.value != "" && document.notice_st.nsb_score_kr.value != tb.childNodes[str_t_stud].childNodes[5].textContent
                && document.notice_st.nsb_be_absent.value=="" && document.notice_st.nsb_score_lesson.value=="" && document.notice_st.nsb_score_sam.value =="" ) {//если нет прогула и нет оценок по уроку и самостоятельной можно ставить оценку по контрольной
                score_type=3;
                jquery_send('#div_teacher', 'post', 'contr_teacher.php',
                    ['score_lesson','score_les','score_type', 'id_student', 'id_subj', 'date_change', 'str_t_stud', 'id_teacher'],
                    ['', document.notice_st.nsb_score_kr.value, score_type, id_student, id_subj, date_change, str_t_stud, id_teacher]);
            }

        }
    }


    function check_absent(elem){//обработка пропуска занятия
        if(document.form_teacher.id_time_table.value == -1 ){//если не во время проведения урока то нельзя поставить прогул или убрать ранее поставленный
            return;
        }
        else {
            if(document.notice_st.nsb_id.value != ""){
                if(elem.value=="н") {// "н" русская буква н от слова не был
                    elem.value="";
                    document.notice_st.nsb_score_lesson.readOnly = false;
                    document.notice_st.nsb_score_sam.readOnly= false;
                    document.notice_st.nsb_score_kr.readOnly= false;
                    var tb= document.getElementById("tablebod");
                    for(var i=0 ; i< tb.childNodes.length;i++){
                        if(tb.childNodes[i].childNodes[0].textContent == document.notice_st.nsb_id.value ){
                            document.notice_st.nsb_score_lesson.value = tb.childNodes[i].childNodes[3].textContent;
                            document.notice_st.nsb_score_sam.value = tb.childNodes[i].childNodes[4].textContent;
                            document.notice_st.nsb_score_kr.value = tb.childNodes[i].childNodes[5].textContent;
                            return;
                        }
                    }
                }
                else{
                    elem.value="н";
                    document.notice_st.nsb_score_lesson.readOnly = true;
                    document.notice_st.nsb_score_lesson.value="";
                    document.notice_st.nsb_score_sam.value="";
                    document.notice_st.nsb_score_sam.readOnly= true;
                    document.notice_st.nsb_score_kr.value="";
                    document.notice_st.nsb_score_kr.readOnly= true;
                }
            }
        }
    }

    function notice_student(row_elem) {//снятие данных из выделенной строки таблицы класса и выделение кликнутой строки
       /* if(row_elem.style.backgroundColor=="#ffe"){
            console.log(row_elem.style.backgroundColor);
            row_elem.style.backgroundColor="aliceblue";
        }
        else if(row_elem.style.backgroundColor =="aliceblue"){
            console.log(row_elem.style.backgroundColor);
            row_elem.style.backgroundColor="#ffe";
        }
        */
        var row = row_elem;
        console.log( row );
        var tableBodStudent = document.getElementById("tablebod");
        for( var r = 0 ; r < tableBodStudent.childNodes.length; r++){
                for (var i=0; i < tableBodStudent.childNodes[r].childNodes.length; i++){
                    if(tableBodStudent.childNodes[r].childNodes[i].style.backgroundColor == "#ffe"){
                        tableBodStudent.childNodes[r].childNodes[i].style.backgroundColor =  "aliceblue";
                    }
                    else{
                        if(tableBodStudent.childNodes[r].childNodes[i].style.backgroundColor ==  "aliceblue")
                            tableBodStudent.childNodes[r].childNodes[i].style.backgroundColor = "#ffe";
                    }
                }
        }

        document.notice_st.nsb_id.value =   row_elem.childNodes[0].textContent;//id
        document.notice_st.nsb_name.value = row_elem.childNodes[1].textContent;//name
        document.notice_st.nsb_be_absent.value =  row_elem.childNodes[2].textContent;// если отсутствовал
        document.notice_st.nsb_score_lesson.value =  row_elem.childNodes[3].textContent;//оценка на уроке
        document.notice_st.nsb_score_sam.value =  row_elem.childNodes[4].textContent;//1 оценка по самостоятельной
        document.notice_st.nsb_score_kr.value =  row_elem.childNodes[5].textContent;//1 оценка по контрольной

        if(document.notice_st.nsb_be_absent.value == "н"){// если "н" нельзя ставить оценку за урок
            document.notice_st.nsb_score_lesson.readOnly = true;
            document.notice_st.nsb_score_sam.readOnly = true;
            document.notice_st.nsb_score_kr.readOnly = true;
        }
        else{
            document.notice_st.nsb_score_lesson.readOnly = false;
            document.notice_st.nsb_score_sam.readOnly = false;
            document.notice_st.nsb_score_kr.readOnly = false;
        }



    }

    var digit_class;//переменная для передачи в запрос чтобы подгрузить уроки именно для первого класса( или 12-го)
    function  GetDateAgo(date, days) {
        var date_copy = new  Date();
        date_copy.setDate(date.getDate()- days);
        var y = date_copy.getFullYear();
        //var yy= "y";
        //Console.log(yy);
        var m = date_copy.getMonth()+1;
        if( m <10 )
           m ="0"+m;
        //Console.log(m);
        var d = date_copy.getDate();
        if(d<10)
            d="0"+d;
        //Console.log(d);
        var dayyy= y+"-"+ m+"-"+ d;
        return dayyy;
    }
    function sel_name_subject() {//если сбой в выборе или не тот урок что по расписанию то можно сделать выбор предмета для урока принудительно
        if(document.form_teacher.select_name_subject.options[document.form_teacher.select_name_subject.selectedIndex].value == 0){
            document.form_teacher.id_time_table.value = -1;
            document.form_teacher.subject_id.value = -1;
            document.form_teacher.subject_name.value = -1;
        }
        clear_tables();

        var n_cl = document.getElementById("n_c");
        n_cl.textContent = "";
        n_cl.style.color = "black";
        if(document.form_teacher.select_name_subject.options[document.form_teacher.select_name_subject.selectedIndex].value>0){
            document.form_teacher.subject_id.value = document.form_teacher.select_name_subject.options[document.form_teacher.select_name_subject.selectedIndex].value;
            document.form_teacher.subject_name.value=document.form_teacher.select_name_subject.options[document.form_teacher.select_name_subject.selectedIndex].text;

            n_cl.textContent = document.form_teacher.subject_name.value+ " в "+document.form_teacher.class_name.value;//предмет выбран
            //загрузим в селект sel_date даты, когда был этом предмет за последний месяц
            var date_this_subject_for_month; // вычислим дату, с которой загрузим даты предмета до этого дня ( за 30 дней)
            var day_n = date_now ;//передаем сегодняшнюю дату с клиента
            //console.log(day_n);
            var d_age;
            var id_subject = document.form_teacher.subject_id.value;
            var id_class = document.form_teacher.class_id.value;
            //document.form_teacher.month_age.value = GetDateAgo(day_n,30);
            var d_from = day_n;
            //var d_to = GetDateAgo(day_n,30);
            if( document.form_teacher.month_age.value != -1){
                //d_age =  document.form_teacher.month_age.value; 
                jquery_send('#div_teacher','post','contr_teacher.php',
                    ['sel_name_subject','date_now','subj_id', 'id_class'],
                    ['', date_now, id_subject, id_class]);//row 172 in contr_teacher.php
            }

            //  = document.form_teacher.subject_id.value;//дата до которой загрузим даты предмета
        }
        else {
            n_cl.textContent = "не выбран урок!";
            n_cl.style.color="red";
            document.form_teacher.subject_id.value ='-1';
            document.form_teacher.subject_name.value ='-1';
        }

    }
    function sel_name_class() {//выбор класса для урока
       // document.getElementById("day_now_post").textContent=day_w;// для проверки день недели который передали в пост
        //отправили день недели на сервер
        document.form_teacher.id_time_table.value=-1;
        jquery_send('#day_now_response','post','contr_teacher.php',['day_week','day_w'],['',day_w] );//вернули день недели
        // в select даты  поставим сегодняшнюю дату а value - день недели (1-понедельник)
        //при загрузке класса( выбор в селект) если в это время идет урок то покажет название урока и напишет об этом в заголовок
        //если нет по времени урока в этот день в этом классе, то надо выбрать предмет из селекта предметы и покажет оценки-прогулы по текущей дате селекта даты и названию предмета
        ///если в этот день есть выбраный предмет, а если нет в этот день этого предмета в селект дат загрузит последние даты этого проведенного урока и по самомоу последнему проведенному
        //уроку загрузит оценки прогулы
        /*var option="<option value='"+day_w+"'>"+ date_now +"</option>";//сегодняшний день для заполнения и просмотра оценок за сегодня
        document.form_teacher.select_date.innerHTML = option;*/
        //получим название(значение) урока
        //alert("день недели "+day_w);
        id_class = document.form_teacher.select_name_class.options[document.form_teacher.select_name_class.selectedIndex].value;//id_class
        var name_class = document.form_teacher.select_name_class.options[document.form_teacher.select_name_class.selectedIndex].text; //название класса
        //alert("id класса "+id_class);
       // var time_now= document.getElementById("time_on_client").value; // теперь тут не нужно брать значение поля оно приходит из функции d()
        //alert("время на клиенте "+time_now);
        //1) при выборе класса сначала надо загрузить предметы для этого класса в селект name="select_name_subject" id="sel_subject"
        document.form_teacher.class_id.value = id_class;//покажем на форме id выбранного класса
        document.form_teacher.class_name.value = name_class;//покажем на форме name выбранного класса
        //узнаем какая цифра класса после выбора класса для 1а, 1б и тд будет 1 для 3а, 3б и тд будет 3
        var n_cl=document.getElementById("n_c");//n_cl цифра класса number class
        if(name_class != ""){
            digit_class="";
            var masChar=name_class.split('');
            for(var i=0; i<masChar.length;i++){
                if(! isNaN(masChar[i])){//если это число
                    digit_class += masChar[i];
                }
            }
            document.form_teacher.class_digit.value=digit_class;
        }

        // загрузка в select только предметов конкретно для 1 клвсса( или для 9 класса )
        jquery_send('#sel_subject','post','contr_teacher.php',['digit_class','digit'],['',digit_class]);
        document.form_teacher.select_name_subject.style.display="block";
        document.getElementById("table_student").style.display="block";
        //загрузка учеников в таблицу
        jquery_send('#tablebod','post','contr_teacher.php',['complete_table','id_class'],['',document.form_teacher.class_id.value]);
        var date_score = document.form_teacher.select_date.options[document.form_teacher.select_date.selectedIndex].text;
        // запросим на сервере предмет который идет в это время в этом классе в этот день и покажем оценки-прогулы по нему в эту дату
       jquery_send("#lesson_now",'post','contr_teacher.php',['lesson_now','day_w','id_class','time_now','date_score'],['', day_w, id_class, time_now, date_score]);
        //забъем в input subject_id subject_name
       

    }
    function clear_tables(){
        document.notice_st.nsb_id.value ="";
        document.notice_st.nsb_name.value ="";
        document.notice_st.nsb_be_absent.value ="";
        document.notice_st.nsb_score_lesson.value ="";
        document.notice_st.nsb_score_sam.value ="";
        document.notice_st.nsb_score_kr.value ="";
        var tb = document.getElementById("tablebod");
        for(var i=0; i< tb.childNodes.length; i++){
            for(var j=2; j< tb.childNodes[i].childNodes.length ; j++){
                tb.childNodes[i].childNodes[j].textContent = "";
            }
        }
    }
    function sel_datee(){//уже выбран класс, предмет теперь выберем дату урока из этого селекта и загрузим данные по ней и по пути найдем id_time_table
        clear_tables();
        var id_subject = document.form_teacher.select_name_subject.options[document.form_teacher.select_name_subject.selectedIndex].value;
        var id_class = document.form_teacher.select_name_class.options[document.form_teacher.select_name_class.selectedIndex].value;
        var date_score = document.form_teacher.select_date.options[document.form_teacher.select_date.selectedIndex].text;
        //var date_score_for_f_day_week = ""+date_score+"";
        var day_week = day_week_f(date_score);
       // var id_time_table  надо узнать из базы данных, так как он поменялся
        jquery_send('#div_teacher','post', 'contr_teacher.php',['sel_date','id_subject', 'day_week','id_class', 'date_score'],
                                                              ['',id_subject, day_week, id_class,date_score]);
    }
    function func(elem){
        var this_el=elem;
        if(this_el.style.backgroundColor=="#e3fff4"){
            this_el.style.backgroundColor="red";
        }
        else {
            this_el.style.backgroundColor="#e3fff4"
        }
    }

    </script>

  <?php
                include_once 'contr_teacher.php';
  ?>







<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>

</body>


    <div style="clear: both;"></div>
    <div id="futer">futer</div>
</div>
</html>
