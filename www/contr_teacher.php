<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 17.11.2016
 * Time: 20:25
 */
include_once 'class_My_class.php';
$obj = new My_class();//создали переменную класса через которую будем работать с запросами к базе и выводом на экран( в DOM )
$obj->DB_connect();
if(isset($_POST['day_week'])){
    if(isset($_POST['day_w']))
    $day_week = intval($_POST['day_w']);

    if($day_week==1){
        echo "понедельник";return;
    }
    elseif ($day_week==2){
        echo "вторник"; return;
    }
    elseif($day_week==3){
        echo "среда";return;
    }
    elseif ($day_week==4){
        echo "четверг";return;
    }
    elseif ($day_week==5){
        echo "пятница"; return;
    }
    elseif ($day_week==6){
        echo "суббота"; return;
    }
    elseif ($day_week==0){
        echo "воскресенье";
    }
    else echo"ошибка дня недели при передаче";
}

if(isset($_POST['lesson_now'])){//получим id урока по классу, дню недели, времени row 477 from teacher.php
     //echo "зашли через lesson_now";
    if(isset($_POST['day_w'])){
        $day_week = intval($_POST['day_w'] );
    }
    else echo 'error day_w';
    if(isset($_POST['id_class']))
        $id_class = intval($_POST['id_class']);
    else echo 'error id_class';
    if(isset($_POST['time_now'])){
        $time_now = mysqli_real_escape_string($obj->link_db,$_POST['time_now'] );
    }
    else echo 'error time_now';
    echo "
        <script>
    document.form_teacher.select_name_subject.style.display = 'block';
    </script>";
    $query=" SELECT  `id_subject`,`id_time_table` FROM  `timetable` WHERE  `lesson_num` = ( SELECT  `lesson_num` FROM  `number_lesson` WHERE 
                    ( '$time_now' >=  `time_start_lesson` AND  '$time_now' <=  `time_end_lesson`)  AND  `id_class` =  '$id_class'
                     AND  `week_day` =  '$day_week' );";
                                                                                                                
    $res = mysqli_query($obj->link_db, $query);
   //echo $query;//показать запрос который вернет id_subject ( id предмета id урока по расписанию из таблицы расписания
    if(mysqli_num_rows($res) >0 and mysqli_num_rows($res)==1 ) {
        $row = mysqli_fetch_row($res);
        $id_subj = $row[0];//получили id урока в этом классе в это время в этот день
        $id_time_table = $row[1];//получим id_time_table для поиска прогулов
    }
    if($id_subj > 0){ //если вернуло значение > 0 значит есть такой id урока
        //покажем прогулы и оценки по этому уроку в этом классе 
        if(isset($_POST['date_score']))
            $date_score = mysqli_real_escape_string($obj->link_db,$_POST['date_score'] );
        else{
            echo'<script>
             document.getElementById("div_teacher").textContent="!!! ОШИБКА !!! НЕ МОЖЕМ ПОКАЗАТЬ ОЦЕНКИ ПРОГУЛЫ <br> обратитесь к разработчику";
            </script>';
        }
        //выведем оценки и прогулы по этому дню в этом классе по этому предмету
        $query_abs = "SELECT id_student
                      FROM be_absent  
                      WHERE date_absent= '$date_score' AND id_time_table='$id_time_table' AND id_student IN
                      (SELECT id_student FROM students WHERE id_class= '$id_class') ;
                      ";
        //echo $query_abs;
        $res_abs = mysqli_query($obj->link_db, $query_abs);
        if($count = mysqli_num_rows($res_abs)){//если не пустой ответ значит по этому уроку были прогульщики
            // $row_abs = mysqli_fetch_assoc($res_abs);
            while ($row_abs = mysqli_fetch_assoc($res_abs)){
                $id_st_abs = $row_abs['id_student'];
                echo '<script>
                 var tb = document.getElementById("tablebod");
                 for(var i = 0; i< tb.childNodes.length; i++){
                    if(tb.childNodes[i].childNodes[0].textContent == "'.$id_st_abs.'")
                        tb.childNodes[i].childNodes[2].textContent = "н";
                 }
                // document.form_teacher.id_time_table.value= "'.$id_time_table.'";
                 </script>';
            }
        }
//на основе даты, id_subject, id_class надо выбрать оценки и показать их на клиенте

        $query_score = "SELECT id_student, score, score_type 
                  FROM scores 
                  WHERE  date_score = '$date_score' AND id_subject = '$id_subj' AND id_student IN(SELECT id_student FROM students WHERE id_class='$id_class');";
        //echo $query_score;
        $res_score = mysqli_query($obj->link_db, $query_score);
        if($count = mysqli_num_rows($res_score)){//если не пустой ответ - то есть по этому уроку в этот день в этом классе есть оценки
            //echo printf( "count %d .\n",$count);
            //printf( "count %d .\n",$count);
            while ($row = mysqli_fetch_assoc($res_score)){
                $st_id = $row['id_student'];
                $sc = $row['score'];
                $sc_t = $row['score_type'];
                echo '<script>
                  var tb = document.getElementById("tablebod");
                  for(var i =0; i < tb.childNodes.length; i++){
                      if( tb.childNodes[i].childNodes[0].textContent == "'.$st_id.'" ){
                          if("'.$sc_t.'" == 1)
                               tb.childNodes[i].childNodes[3].textContent = "'.$sc.'";
                          if("'.$sc_t.'" == 2)     
                                tb.childNodes[i].childNodes[4].textContent = "'.$sc.'";
                          if("'.$sc_t.'" == 3)
                                tb.childNodes[i].childNodes[5].textContent = "'.$sc.'";
                      }
                  }
                  </script>';
            }
        }
        //покажем на странице название урока что идет
        echo"
            <script>
               document.form_teacher.id_time_table.value='$id_time_table';
               document.form_teacher.subject_id.value='$id_subj';
               // var sel_subj = document.getElementById('sel_subject');
                var sel_subj = document.form_teacher.select_name_subject;
              
                for( var i=0; i< sel_subj.options.length; i++){
                  if( sel_subj.options[i].value == '$id_subj' ){
                  
                    sel_subj.options.selectedIndex = i; //выбор оптиона или этого или другого
                    sel_subj.options[i].style.fontWeight = 'bolder';
                    document.form_teacher.subject_name.value = sel_subj.options[i].text;
                     // console.log( sel_subj.options[i].text );
                   //document.form_teacher.select_name_subject.options.selectedIndex = i; //выбор оптиона или этого или другого
                   var n_cl = document.getElementById(\"n_c\");
                   var t = n_cl.textContent;
                   n_cl.textContent = document.form_teacher.subject_name.value+ \" идет в \"+document.form_teacher.class_name.value;
                  }
                  else sel_subj.options[i].style.fontWeight = 'lighter'  ;
                }
            </script>
        ";
    }
    else {
        echo " нет в расписании урока в этот день в этом классе в это время ";
        echo"
            <script>
            document.form_teacher.id_time_table.value ='-1';
             document.form_teacher.subject_id.value ='-1';
             document.form_teacher.subject_name.value ='-1';
          </script>
        ";
    }
}
if(isset($_GET['id_user'])){
   //echo 'зашли в гет id_user';

    $obj->id_user=mysqli_real_escape_string($obj->link_db,$_GET['id_user'] );
    $query="select name_teacher, id_teacher from teachers WHERE id_user=".$obj->id_user;
    $res=mysqli_query($obj->link_db, $query);
    $row = mysqli_fetch_row($res);
   // echo $row[0]; echo $row[1];
    echo '
        <script>
         form_teacher.id_teacher.value="'.$row[1].'";
         form_teacher.name_teacher.value="'.$row[0].'"; 
         form_teacher.name_teacher.style.display="block";
        </script>
    ';
    //выведем в селект select_name_class value и text

    $query="select id_class, number_class from class ;";
    $res=mysqli_query($obj->link_db, $query);
    $options_class='<option value=0>выберите класс</option>';
    while($row=mysqli_fetch_assoc($res)){
        $options_class.='<option value='.$row["id_class"].'>'.$row["number_class"].'</option>';
    }
    echo '
       <script>
       form_teacher.select_name_class.innerHTML="'.$options_class.'";
       </script>
    ';
}

 if(isset($_POST['digit_class'])){//есть передача номера класса для подгрузки предметов (для 1-го класса свои, а для 8-свои from row400 teacher.php
    $digit_class=mysqli_real_escape_string($obj->link_db,$_POST['digit'] );
    $digit_class='class_'.$digit_class;
    $query="select id_subject, name_subject from subjects WHERE $digit_class > 0";
    //echo $query;
    $res=mysqli_query($obj->link_db, $query);
   // print_r($res);
    $options_subject='<option value=0>выберите предмет</option>';
    while ($row = mysqli_fetch_row($res)){
        $options_subject.='<option value="'.$row[0].'">'.$row[1].'</option>';
    }
    echo $options_subject;
}
if(isset($_POST['complete_table'])){//заполнение таблицы учениками from row 435 teacher.php
    if(isset($_POST['id_class'])){//если передали id класса
        $id_class=intval($_POST['id_class']);
    }
    else echo "что-то не так, нет такого класса";
    $query = "select id_student, name_student from students WHERE id_class='$id_class' order by name_student;";
    $res = mysqli_query($obj->link_db, $query);
    //$rr='<tr onclick="notice_student();">';
    while ($row =mysqli_fetch_row($res) ){
       // $rr='<tr>';
        $rr='<tr onclick="notice_student(this);">';
        //$td='<td>'.$row[0].'</td><td>'.$row[1].'</td><td onclick="f_absent();"></td><td onclick="f_lose();"></td><td onclick="f_ill();"></td><td onclick="f_score_hw();"></td><td onclick="f_score_leccon();"></td>';
        $td='<td>'.$row[0].'</td><td>'.$row[1].'</td><td></td><td></td><td></td><td></td>';
        $rr.=$td.'</tr>';
        echo $rr;
    }

}

if(isset($_POST['sel_name_subject'])){//вызов из строки 356 teacher.php
    if(isset($_POST['date_now'])){
        $date_now = mysqli_real_escape_string ($obj->link_db, $_POST['date_now']);
    }
    $date_now_mas = explode('-',$date_now ) ;// год месяц число
    //часы минуты секунды месяц число год
    $date_n = mktime( null, null, null, $date_now_mas[1], $date_now_mas[2], $date_now_mas[0] );
    $format = "%d-%m-%Y";
    $date_from_client = strftime($format, $date_n );
   // echo $date_from_client ;
    $format = "%w";//w день недели 0 воскресенье 6 суббота
    $day_week = strftime($format, $date_n );
    $format = "%W"; //W number week in Year
    $number_week = date($format,$date_n );
    if(isset($_POST['subj_id'])){
        $subj_id = intval( $_POST['subj_id'] );
    }
    if(isset($_POST['id_class'])){
        $id_class = intval( $_POST['id_class'] );
    }

    //выберем дни в какие идут предметы пример труды ( 1-понд, 2- среда)
    $query = "SELECT `week_day` FROM `timetable` WHERE `id_subject` = '$subj_id' AND `id_class`= '$id_class';";
    $rr = "/";
    $res = mysqli_query($obj->link_db, $query);
    if( mysqli_num_rows($res)){ //если есть записи
        while($row = mysqli_fetch_assoc($res)){
            $roww[]=$row;//закинули все дни когда бывает этот урок в ассоциативный массив
       }
        //$rr = '/';
      foreach($roww as $k1=> $roww1 ){//превратим массив дней уроков на этой неделе в строку
           foreach ($roww1 as $k2 => $roww2) {
                $rr.="$roww2/";
           }
       }
        //echo $rr;
    }
    $array_days_this_subject_this_class = explode('/',$rr );//дни когда есть данный предмет на этой неделе в этом классе(from 0 to 5 )
    $array_days_this_subject_this_class = array_slice($array_days_this_subject_this_class, 1, -1 );//удалили последний пустой член массива
    $rrr ='';
    //проверим есть были ли уроки на этой неделе (сегодня и раньше) или надо перейти на предыдущую
    foreach ( $array_days_this_subject_this_class as $k=> $d) {
        if ($d <= $day_week ) {
            $this_week = true;//если был хотябы урок на этой неделе значит будем смотреть только эту неделю иначе надо уйти в предыдущую неделю
            break;
        }
        else{
            $this_week = false;
        }
    }
    $options_date = "";
    $ar_dayss="";
    if($this_week){ //найдем все даты этого предмета на этой неделе
        foreach ( $array_days_this_subject_this_class as $k=> $d){
            if( $d <= $day_week  ){//если на этой неделе былы  эти уроки
                $difference_day = $day_week - $d;//разность дат надо отнять секунды это дни переведем их в секунды
                $date_this_lesson = mktime( null, null, null, $date_now_mas[1], $date_now_mas[2]- $difference_day, $date_now_mas[0] );
                $ar_days[] = $date_this_lesson ;
                $format = "%Y-%m-%d";
                $dd = strftime($format, $date_this_lesson  );
                $ar_dayss[] = "$d/$dd ";// строковый массив
                $options_date.='<option value='.$d.'>'.$dd.'</option>';
            }
            else{
                break;
            }
        }
    }
    else{//надо искать числа этого предмета в прошлой неделе, так как на этой неделе еще не было этого предмета
        foreach ( $array_days_this_subject_this_class as $k=> $d) { //найдем все даты этого предмета на прошлой неделе
            $difference_day = $d - $day_week;//
            //часы минуты секунды месяц число год
            $date_this_lesson = mktime( null, null, null, $date_now_mas[1], $date_now_mas[2]+ $difference_day - 7, $date_now_mas[0] );
            $ar_days[] = $date_this_lesson ;
            $format = "%Y-%m-%d";
            $dd = strftime($format, $date_this_lesson  );
            $ar_dayss[] = "$d/$dd ";// строковый массив
            $options_date.='<option value='.$d.'>'.$dd.'</option>';
        }
    }
    $max_day_week = -1;
    $date_select = "";
    if($options_date !="" && $ar_dayss !=""){
        //$mas_day_date = explode("",$ar_dayss );
        foreach ( $ar_dayss as $d_w_date  ){
            $d_w = explode("/",$d_w_date );
            if($d_w[0] > $max_day_week ){//ищем для первоначальной загрузки из базы оценок дату ( по пути день недели )
                $max_day_week = $d_w[0];//нашли день недели который будет выделен в селекте
                $date_select = $d_w[1];//нашли дату которая будет выделена в селекте
            }
        }
    }
    echo '
   <script>
       form_teacher.select_date.innerHTML="'.$options_date.'";
       max_day = -1;//для выбора выделеного дня в селекте дат(максимальное число и будет выделеный день)
       for( var i = 0; i< form_teacher.select_date.options.length; i++){
          if( form_teacher.select_date.options[i].value > max_day ){
              max_day = form_teacher.select_date.options[i].value;
          }
       }
       if(max_day > -1){
            for( var i = 0; i< form_teacher.select_date.options.length; i++){
               if(max_day == form_teacher.select_date.options[i].value){
               form_teacher.select_date.options.selectedIndex = i;
               form_teacher.select_date.options[i].style.fontWeight = \'bolder\';
               }
               else{
                   form_teacher.select_date.options[i].style.fontWeight = \'lighter\' ;
               }
            }
       }
   </script>
   ';
    //на основе даты, id_subject, id_class надо выбрать оценки и показать их на клиенте
    if($date_select != ""){
        $query = "SELECT st.id_student, st.name_student, score, score_type, id_subject 
                  FROM scores AS sc, students AS st 
                  WHERE  `date_score` = '$date_select' AND id_class='$id_class' AND sc.id_student = st.id_student AND id_subject = '$subj_id';";
        //echo $query;
        //на основе даты, id_subject, id_class надо выбрать прогулы и показать их на клиенте
        $query_abs = "SELECT ba.id_student, t.id_time_table 
                      FROM be_absent AS ba ,students AS s,timetable AS t
                      WHERE `date_absent`= '$date_select' AND s.id_class='$id_class'
                             AND ba.id_student = s.id_student AND t.id_subject = '$subj_id' AND t.id_time_table = ba.id_time_table ;
                      ";
        //подтянем из базы id_time_table
        if($day_week != $max_day_week)//если в этот день нет урока мы берем предыдущий максимальный(ближайший к нам прошлый)
            $day_week = $max_day_week;
        $query_tt = "SELECT id_time_table 
                     FROM timetable 
                      WHERE  id_subject ='$subj_id' AND id_class ='$id_class' AND week_day ='$day_week'
        ";
    }
    $res_tt = mysqli_query($obj->link_db, $query_tt);
    if($c_tt = mysqli_num_rows($res_tt)){//тут должен быть один ответ - то есть 1 строка
       // while ($row_tt = mysqli_fetch_assoc($res_tt) ){//должна быть 1 строка(1 значение)
            $row_tt = mysqli_fetch_assoc($res_tt);
            $id_time_table = $row_tt['id_time_table'];
            echo '<script>
                 document.form_teacher.id_time_table.value = "'.$id_time_table.'";
             </script>';
       // }

    }
    $res_abs = mysqli_query($obj->link_db, $query_abs);
    if($count = mysqli_num_rows($res_abs)){//если не пустой ответ значит по этому уроку были прогульщики
       // $row_abs = mysqli_fetch_assoc($res_abs);
        while ($row_abs = mysqli_fetch_assoc($res_abs)){
            $id_st_abs = $row_abs['id_student'];
            echo '<script>
                 var tb = document.getElementById("tablebod");
                 for(var i = 0; i< tb.childNodes.length; i++){
                    if(tb.childNodes[i].childNodes[0].textContent == "'.$id_st_abs.'")
                        tb.childNodes[i].childNodes[2].textContent = "н";
                 }
                // document.form_teacher.id_time_table.value= "'.$id_time_table.'";
                 </script>';
        }
    }

    $res = mysqli_query($obj->link_db, $query);
    if($count = mysqli_num_rows($res)){//если не пустой ответ - то есть по этому уроку в этот день в этом классе есть оценки
       //echo printf( "count %d .\n",$count);
        //printf( "count %d .\n",$count);
        while ($row = mysqli_fetch_assoc($res)){
            $st_id = $row['id_student'];
            $sc = $row['score'];
            $sc_t = $row['score_type'];
            $id_s = $row['id_subject'];
            echo '<script>
                  var tb = document.getElementById("tablebod");
                  for(var i =0; i < tb.childNodes.length; i++){
                      if( tb.childNodes[i].childNodes[0].textContent == "'.$st_id.'" ){
                          if("'.$sc_t.'" == 1)
                               tb.childNodes[i].childNodes[3].textContent = "'.$sc.'";
                          if("'.$sc_t.'" == 2)     
                                tb.childNodes[i].childNodes[4].textContent = "'.$sc.'";
                          if("'.$sc_t.'" == 3)
                                tb.childNodes[i].childNodes[5].textContent = "'.$sc.'";
                      }
                  }
                  </script>';
        }
    }
    //echo "<script> document.form_teacher.month_age.value = '$rows'; </script>";
}
if(isset($_POST['sel_date'])){//from row 497 teacher.php
    //id_subject, day_week, id_class,date_score
    if(isset($_POST['id_subject']))
        $id_subject = intval($_POST['id_subject']);
    if(isset($_POST['day_week']))
        $day_week = intval($_POST['day_week']);
    if(isset($_POST['id_class']))
        $id_class = intval($_POST['id_class']);
    if(isset($_POST['date_score']))
       $date_score = mysqli_real_escape_string($obj->link_db,$_POST['date_score'] );
    $query_tt = "SELECT id_time_table 
                 FROM timetable 
                 WHERE  id_subject ='$id_subject' AND id_class ='$id_class' AND week_day ='$day_week';
        ";
    $res_tt = mysqli_query($obj->link_db, $query_tt);
    if($c_tt = mysqli_num_rows($res_tt)) {//тут должен быть один ответ - то есть 1 строка
        // while ($row_tt = mysqli_fetch_assoc($res_tt) ){//должна быть 1 строка(1 значение)
        $row_tt = mysqli_fetch_assoc($res_tt);
        $id_time_table = $row_tt['id_time_table'];
        echo '<script>
                     document.form_teacher.id_time_table.value = "'.$id_time_table.'";
                 </script>';
    }
     //на основе даты, id_subject, id_class надо выбрать прогулы и показать их на клиенте

    $query_abs = "SELECT id_student
                      FROM be_absent  
                      WHERE date_absent= '".$date_score."' AND id_time_table='".$id_time_table."' AND id_student IN
                      (SELECT id_student FROM students WHERE id_class= '".$id_class."');";
    //echo $query_abs;
    $res_abs = mysqli_query($obj->link_db, $query_abs);
    if($count = mysqli_num_rows($res_abs)){//если не пустой ответ значит по этому уроку были прогульщики
        // $row_abs = mysqli_fetch_assoc($res_abs);
        while ($row_abs = mysqli_fetch_assoc($res_abs)){
            $id_st_abs = $row_abs['id_student'];
            echo '<script>
                 var tb = document.getElementById("tablebod");
                 for(var i = 0; i< tb.childNodes.length; i++){
                    if(tb.childNodes[i].childNodes[0].textContent == "'.$id_st_abs.'")
                        tb.childNodes[i].childNodes[2].textContent = "н";
                 }
                 </script>';
        }
    }
//на основе даты, id_subject, id_class надо выбрать оценки и показать их на клиенте

        $query_score = "SELECT id_student, score, score_type 
                  FROM scores 
                  WHERE  date_score = '$date_score' AND id_subject = '$id_subject' AND id_student IN(SELECT id_student FROM students WHERE id_class='$id_class');";
     //echo $query_score;
    $res_score = mysqli_query($obj->link_db, $query_score);
    if($count = mysqli_num_rows($res_score)){//если не пустой ответ - то есть по этому уроку в этот день в этом классе есть оценки
        //echo printf( "count %d .\n",$count);
        //printf( "count %d .\n",$count);
        while ($row = mysqli_fetch_assoc($res_score)){
            $st_id = $row['id_student'];
            $sc = $row['score'];
            $sc_t = $row['score_type'];
            echo '<script>
                  var tb = document.getElementById("tablebod");
                  for(var i =0; i < tb.childNodes.length; i++){
                      if( tb.childNodes[i].childNodes[0].textContent == "'.$st_id.'"){
                          if("'.$sc_t.'" == 1)
                               tb.childNodes[i].childNodes[3].textContent = "'.$sc.'";
                          if("'.$sc_t.'" == 2)     
                                tb.childNodes[i].childNodes[4].textContent = "'.$sc.'";
                          if("'.$sc_t.'" == 3)
                                tb.childNodes[i].childNodes[5].textContent = "'.$sc.'";
                      }
                  }
                  </script>';
        }
    }
}
if(isset($_POST['week_ago'])){//выбор дат предмета на неделю раньше from row 197 teacher.php function week_ago() 'date_ago', 'day_week', 'id_class','id_subj'
    //сначала выберем все дни в которые бывает этот предмет (1-пнд 5-пятница)
    if(isset($_POST['date_ago']))
        $date_ago = mysqli_real_escape_string($obj->link_db,$_POST['date_ago']);
    if(isset($_POST['id_subj']))
        $id_subject = intval($_POST['id_subj']);
    if(isset($_POST['day_week']))
        $day_week = intval($_POST['day_week']);
    if(isset($_POST['id_class']))
        $id_class = intval($_POST['id_class']);
    if(isset($_POST['id_time_table']))
        $id_time_table = intval($_POST['id_time_table']);
    //так как дата и все остальное известно можем выбрость сразу оценки-прогулы на клиент
    //на основе даты, id_time_table, id_class надо выбрать прогулы и показать их на клиенте

    $query_abs = "SELECT id_student
                  FROM be_absent  
                  WHERE date_absent= '".$date_ago."' AND id_time_table='".$id_time_table."' AND id_student IN
                 (SELECT id_student FROM students WHERE id_class= '".$id_class."');";
    //echo $query_abs;
    $res_abs = mysqli_query($obj->link_db, $query_abs);
    if($count = mysqli_num_rows($res_abs)){//если не пустой ответ значит по этому уроку были прогульщики
        // $row_abs = mysqli_fetch_assoc($res_abs);
        while ($row_abs = mysqli_fetch_assoc($res_abs)){
            $id_st_abs = $row_abs['id_student'];
            echo
                '<script>
                         var tb = document.getElementById("tablebod");
                         for(var i = 0; i< tb.childNodes.length; i++){
                             if(tb.childNodes[i].childNodes[0].textContent == "'.$id_st_abs.'")
                                   tb.childNodes[i].childNodes[2].textContent = "н";
                         }
                </script>';
        }
    }
    //на основе даты, id_subject, id_class надо выбрать оценки и показать их на клиенте
    $query_score = "SELECT id_student, score, score_type 
                    FROM scores 
                    WHERE  date_score = '$date_ago' AND id_subject = '$id_subject' AND id_student IN(SELECT id_student FROM students WHERE id_class='$id_class');";
    //echo $query_score;
    $res_score = mysqli_query($obj->link_db, $query_score);
    if($count = mysqli_num_rows($res_score)){//если не пустой ответ - то есть по этому уроку в этот день в этом классе есть оценки
        while ($row = mysqli_fetch_assoc($res_score)){
            $st_id = $row['id_student'];
            $sc = $row['score'];
            $sc_t = $row['score_type'];
            echo '<script>
                          var tb = document.getElementById("tablebod");
                          for(var i =0; i < tb.childNodes.length; i++){
                              if( tb.childNodes[i].childNodes[0].textContent == "'.$st_id.'"){
                                 if("'.$sc_t.'" == 1)
                                    tb.childNodes[i].childNodes[3].textContent = "'.$sc.'";
                                 if("'.$sc_t.'" == 2)     
                                    tb.childNodes[i].childNodes[4].textContent = "'.$sc.'";
                                 if("'.$sc_t.'" == 3)
                                    tb.childNodes[i].childNodes[5].textContent = "'.$sc.'";
                              }
                          }
                </script>';
        }
    }
    $query_day_week = "SELECT `week_day` FROM `timetable` WHERE `id_subject` = '$id_subject' AND `id_class`= '$id_class';";
   $res_day_week = mysqli_query($obj->link_db, $query_day_week);
    $options = "";
   //надо получить вид <option value="1" style="font-weight: lighter;">2017-01-09</option>
    
    if($cont_day = mysqli_num_rows($res_day_week)){ //если есть записи то есть есть выборка дней недели по этому предмету в этом классе
        $date_n = $date_ago;//дата-строка с которой будем работать
        $ar_date_n = explode('-',$date_n );//год - месяц - день
        $date_n = mktime( null, null, null, $ar_date_n[1], $ar_date_n[2], $ar_date_n[0] );//месяц , день, год
        $format = "%Y-%m-%d";
        $date_from_client = strftime($format, $date_n );

        while($row_day_week = mysqli_fetch_assoc($res_day_week)) {//для каждого дня недели создадим свою дату для заброса в тегах <option> </option>
            $d_w = $row_day_week['week_day'];//получили номер дня недели
            $difference_day = $day_week - $d_w;
            $dd = $date_n - $difference_day*86400;
            //$dd = mktime(null, null, null, $ar_date_n[1], $ar_date_n[2] - $diffenrence_day, $ar_date_n[0]);//получили дату на разницу дней
            $format = "%Y-%m-%d";
            $ddd = strftime($format, $dd);
            $options .= "<option value = '" . $d_w . "'>" . $ddd . "</option>";
        }
        echo '<script>
                    document.form_teacher.select_date.innerHTML = "'.$options.'";
                    var s_d = document.form_teacher.select_date;
                    for(var i=0; i< s_d.options.length; i++){
                       if(s_d.options[i].value == "'.$day_week.'" ){
                          s_d.options.selectedIndex = i;
                          document.form_teacher.select_date.options[i].style.fontWeight = "bolder";
                          //s_d.options[i].style.fontWeight = \'bolder\';
                       }
                       s_d.options[i].style.fontWeight = \'lighter\';
                     }
             </script>';
       // form_teacher.select_date.options.selectedIndex = i;
       // form_teacher.select_date.options[i].style.fontWeight = 'bolder';

    }

}
if(isset($_POST['week_later'])){ //выбор дат предмета на неделю раньше from row 202 teacher.php function week_later() 'date_later','day_week','id_class','id_subj','id_time_table'
    //сначала выберем все дни в которые бывает этот предмет (1-пнд 5-пятница)
    if(isset($_POST['date_later']))
        $date_later = mysqli_real_escape_string($obj->link_db,$_POST['date_later'] );//дата на которую мы перешли на неделю вперед и так перескочили в другую неделю
    if(isset($_POST['day_week']))
        $day_week = intval($_POST['day_week']);
    if(isset($_POST['id_class']))
        $id_class = intval($_POST['id_class']);
    if(isset($_POST['id_subj']))
        $id_subject = intval($_POST['id_subj']);
    if(isset($_POST['id_time_table']))
        $id_time_table = intval($_POST['id_time_table']);
    if(isset($_POST['date_today'])){
        $date_today = mysqli_real_escape_string($obj->link_db,$_POST['date_today']);
        $date_t = $date_today;//дата-строка дата сегодня для сравнения чтобы будущие даты не былы больше с которой будем работать
        $ar_date_t = explode('-', $date_t);//год - месяц - день
        $date_tt = mktime(null, null, null, $ar_date_t[1], $ar_date_t[2], $ar_date_t[0]);//месяц , день, год //сегодняшний день на клиенте
        $format = "%Y-%m-%d";
        $date_today_from_client = strftime($format, $date_tt);//для проверки должно быть такое же как и $date_today
    }


    //выберем в базе дни недели этого предмета
    $query_day_week = "SELECT `week_day`, `id_time_table` FROM `timetable` WHERE `id_subject` = '$id_subject' AND `id_class`= '$id_class';";
    $res_day_week = mysqli_query($obj->link_db, $query_day_week);
    $res_day_week_for_max = $res_day_week;
    $options = "";
    //надо получить вид <option value="1" style="font-weight: lighter;">2017-01-09</option>
    //но в оптион будем собирать только те дни даты которых меньше текущей даты
    if($cont_day = mysqli_num_rows($res_day_week)) { //если есть записи то есть есть выборка дней недели по этому предмету в этом классе
        $date_l = $date_later;//дата-строка дата сегодня для сравнения чтобы будущие даты не былы больше с которой будем работать
        $ar_date_later = explode('-', $date_l);//год - месяц - день
        $date_ll = mktime(null, null, null, $ar_date_later[1], $ar_date_later[2], $ar_date_later[0]);//месяц , день, год
        $format = "%Y-%m-%d";
        $date_lll = strftime($format, $date_ll);//день из выборки на будущей неделе в понятном для человека формате
        $max_day_week = -1;
        $date_for_score ="";
        $row_day_week = NULL;//
        while($row_day_week = mysqli_fetch_assoc($res_day_week)) {//для каждого дня недели создадим свою дату для заброса в тегах <option> </option>
            $d_w = $row_day_week['week_day'];//получили номер дня недели
            $difference_day = $day_week - $d_w;
            $dd = $date_ll - $difference_day*86400;//получили дату будущего дня дни*количество секунд в сутках
            if($dd <= $date_tt ){//если будущий день не больше настоящего дня в календаре на сегодня день добавляем в селект
                $format = "%Y-%m-%d";
                $ddd = strftime($format, $dd);
                $options .= "<option value = '" . $d_w . "'>" . $ddd . "</option>";
                if($max_day_week < $d_w){ //последний день недели ( макс по числу) выделим для показа оценок
                    $max_day_week = $d_w;//день для показа оценок прогулов
                    $date_for_score = $ddd;//дата для показа оценок-прогулов
                }
            }
        }
            if($options !=""){//если тут не пусто значит есть дата за которую надо показать оценки
                //сначала очистим таблицы
                echo '<script>
                       clear_tables();//перед получением данных очистим таблицы от старых
                      </script>';

                //потом найдем id_time_table по строке в запросе row 609 $query_day_week = "SELECT `week_day`, `id_time_table` FROM `timetable` WHERE `id_subject` = '$id_subject' AND `id_class`= '$id_class';";
                $id_ttt = -1;
                $query_day_week_for_max = "SELECT `week_day`, `id_time_table` FROM `timetable` WHERE `id_subject` = '$id_subject' AND `id_class`= '$id_class';";
                $res_day_week_for_max = mysqli_query($obj->link_db, $query_day_week_for_max);
                if($cont_day_for_max = mysqli_num_rows($res_day_week_for_max)) { //если есть записи то будем искать id_time_table который будет отображаться в поле id_time_table
                    while($row_day_week_for_max = mysqli_fetch_assoc($res_day_week_for_max)) {//для каждого дня недели создадим найдем id_time_table , А для максимального запомним
                        $d_ww = $row_day_week_for_max['week_day'];//получили номер дня недели
                        $id_tt = $row_day_week_for_max['id_time_table'];
                        if($d_ww == $max_day_week){//если это тот день недели что будем показывать значит и возьмем его id_time_table
                            $id_ttt = $id_tt;//получили id_time_table
                            //можно дальше не перебирать дни недели
                            break;//выйдем из цикла while
                        }
                    }
                }
                echo '<script>
                    document.form_teacher.select_date.innerHTML = "'.$options.'";
                    var s_d = document.form_teacher.select_date;
                    for(var i=0; i< s_d.options.length; i++){
                       if(s_d.options[i].value == "'.$max_day_week.'" ){
                          s_d.options.selectedIndex = i;
                          document.form_teacher.select_date.options[i].style.fontWeight = "bolder";
                          //s_d.options[i].style.fontWeight = \'bolder\';
                       }
                       s_d.options[i].style.fontWeight = \'lighter\';
                     }
                     //пошлем на клиент id_time_table
                     document.form_teacher.id_time_table.value = "'.$id_ttt.'"
                   </script>';
                //покажем оценки прогулы за эту дату
                //на основе даты, id_time_table, id_class надо выбрать прогулы и показать их на клиенте

                $query_abs = "SELECT id_student
                  FROM be_absent  
                  WHERE date_absent= '".$date_for_score."' AND id_time_table='".$id_ttt."' AND id_student IN
                 (SELECT id_student FROM students WHERE id_class= '".$id_class."');";
                //echo $query_abs;
                $res_abs = mysqli_query($obj->link_db, $query_abs);
                if($count = mysqli_num_rows($res_abs)){//если не пустой ответ значит по этому уроку были прогульщики
                    // $row_abs = mysqli_fetch_assoc($res_abs);
                    while ($row_abs = mysqli_fetch_assoc($res_abs)){
                        $id_st_abs = $row_abs['id_student'];
                        echo
                            '<script>
                         var tb = document.getElementById("tablebod");
                         for(var i = 0; i< tb.childNodes.length; i++){
                             if(tb.childNodes[i].childNodes[0].textContent == "'.$id_st_abs.'")
                                   tb.childNodes[i].childNodes[2].textContent = "н";
                         }
                </script>';
                    }
                }
                //на основе даты, id_subject, id_class надо выбрать оценки и показать их на клиенте
                $query_score = "SELECT id_student, score, score_type 
                    FROM scores 
                    WHERE  date_score = '$date_for_score' AND id_subject = '$id_subject' AND id_student IN(SELECT id_student FROM students WHERE id_class='$id_class');";
                //echo $query_score;
                $res_score = mysqli_query($obj->link_db, $query_score);
                if($count = mysqli_num_rows($res_score)){//если не пустой ответ - то есть по этому уроку в этот день в этом классе есть оценки
                    while ($row = mysqli_fetch_assoc($res_score)){
                        $st_id = $row['id_student'];
                        $sc = $row['score'];
                        $sc_t = $row['score_type'];
                        echo
                            '<script>
                                  var tb = document.getElementById("tablebod");
                                  for(var i =0; i < tb.childNodes.length; i++){
                                      if( tb.childNodes[i].childNodes[0].textContent == "'.$st_id.'"){
                                         if("'.$sc_t.'" == 1)
                                            tb.childNodes[i].childNodes[3].textContent = "'.$sc.'";
                                         if("'.$sc_t.'" == 2)     
                                            tb.childNodes[i].childNodes[4].textContent = "'.$sc.'";
                                         if("'.$sc_t.'" == 3)
                                            tb.childNodes[i].childNodes[5].textContent = "'.$sc.'";
                                      }
                                  }
                             </script>';
                    }
                }
            }
    }
}

if(isset($_POST['delete_absent'])){//вызов из строки 202 teacher.php

    //id_student','id_time_table','date_change'
   if(isset($_POST['id_student']))
       $id_student = intval($_POST['id_student']);
   if(isset($_POST['id_time_table']))
       $id_time_table = intval($_POST['id_time_table']);
   if(isset($_POST['date_change']))
       $date_change = mysqli_real_escape_string($obj->link_db,$_POST['date_change'] );
    /*echo '<script>
 console.log("'.$id_student.'");
 console.log("'.$id_time_table.'");
 console.log("'.$date_change.'");
</script>';*/
   $query = "DELETE FROM be_absent WHERE id_time_table ='".$id_time_table."' AND id_student ='".$id_student."' AND date_absent = '".$date_change."';";
   //echo $query;
    mysqli_query($obj->link_db, $query);
   //проверим удалилась ли запись
    $query_contr = "SELECT NULL FROM be_absent WHERE id_time_table ='".$id_time_table."' AND id_student ='".$id_student."' AND date_absent = '".$date_change."';";
   //echo $query_contr;
    $res_contr = mysqli_query($obj->link_db, $query_contr);
    if( $res = mysqli_num_rows($res_contr) ){
        echo "не смогли удалить прогул у этого ученика";
    }
    else{
        echo '<script>
              console.log("'.$res.'");
             var str_t_stud = -1;
        var tb = document.getElementById("tablebod");
        for (var i=0; i< tb.childNodes.length; i++){
            if(tb.childNodes[i].childNodes[0].textContent == "'.$id_student.'"){
                str_t_stud = i;
               tb.childNodes[i].childNodes[2].textContent = "";
            }
        }
          </script>';
    }
}
if(isset($_POST['insert_absent'])){
    if(isset($_POST['id_student']))
        $id_student = intval($_POST['id_student']);
    if(isset($_POST['id_time_table']))
        $id_time_table = intval($_POST['id_time_table']);
    if(isset($_POST['date_change']))
        $date_change = mysqli_real_escape_string($obj->link_db,$_POST['date_change']);
    if(isset($_POST['str_t_stud']))
        $str_t_stud = intval($_POST['str_t_stud']);
    if(isset($_POST['type_absent']))
        $type_absent = intval($_POST['type_absent']);
    $query = "INSERT INTO be_absent(`id_time_table` ,  `id_student` ,  `date_absent`, `type_absent`) VALUES('".$id_time_table."','".$id_student."','".$date_change."','". $type_absent."');";
    //echo $query;
    mysqli_query($obj->link_db, $query);
    $query_contr = "SELECT NULL FROM be_absent WHERE id_time_table ='".$id_time_table."' AND id_student ='".$id_student."' AND date_absent = '".$date_change."';";
   // echo $query_contr;
    $res_contr = mysqli_query($obj->link_db, $query_contr);
    if($res = mysqli_num_rows($res_contr)){
        echo '<script>
            document.getElementById("tablebod").childNodes["'.$str_t_stud.'"].childNodes[2].textContent="н";
        </script>';
    }else{
        echo "не удалось зафиксировать в базе прогул <br> это может быть если есть оценка на уроке<br> удалите сначала оценки выставив их на0";
    }
}
if(isset($_POST['score_lesson'])){
    if(isset($_POST['score_les']))
         $score_lesson = intval($_POST['score_les']);
    if(isset($_POST['score_type']))
        $score_type = intval($_POST['score_type']);
    //по типу оценки узнаем куда в клиенте ставить оцунку( в какую колонку - в 3 - если за урок, в 4-за самостоят, в 5-за контрольн
    if($score_type == 1)
        $col_score=3;
    if($score_type == 2)
        $col_score = 4;
    if($score_type == 3)
        $col_score = 5;
    if(isset($_POST['id_student']))
        $id_student = intval($_POST['id_student']);
    if(isset($_POST['id_subj']))
        $id_subject = intval($_POST['id_subj']);
    if(isset($_POST['date_change']))
        $date_score = mysqli_real_escape_string($obj->link_db,$_POST['date_change']);
    if(isset($_POST['str_t_stud']))
        $str_t_stud=intval($_POST['str_t_stud']);
    if(isset($_POST['id_teacher']))
        $id_teacher = intval($_POST['id_teacher']);

    if($score_lesson == 0){//надо удалить оценку из базы
        $query_del_score = "DELETE FROM `scores` WHERE `id_student`='".$id_student."' AND `id_subject`='".$id_subject."' AND `date_score`='".$date_score."' AND `score_type`='".$score_type."' AND  id_teacher='".$id_teacher."';";
        //echo $query_del_score;
        mysqli_query($obj->link_db, $query_del_score);
        $query_contr_del_score = "SELECT NULL FROM scores WHERE `id_student`='".$id_student."' AND `id_subject`='".$id_subject."' AND `date_score`='".$date_score."' AND `score_type`='".$score_type."'AND  id_teacher='".$id_teacher."';";
        $res_contr_del_score = mysqli_query($obj->link_db, $query_contr_del_score);
        if(mysqli_num_rows($res_contr_del_score)){
            echo "не удалось удалить оценку в базе((( ";
        }
        else{
            echo '<script>
                   document.getElementById("tablebod").childNodes["'.$str_t_stud.'"].childNodes["'.$col_score.'"].textContent="";
                  </script>';
        }
    }
    else{//оценку удалять не надо надо или вставить если небыло или update
        $query_contr_score_be = "SELECT NULL FROM scores WHERE `id_student`='".$id_student."' AND `id_subject`='".$id_subject."' AND `date_score`='".$date_score."' AND `score_type`='".$score_type."' AND  id_teacher='".$id_teacher."';";
        $res_contr_score_be = mysqli_query($obj->link_db, $query_contr_score_be);
        if(mysqli_num_rows($res_contr_score_be)){//если true значит оценка есть и её надо update
            $query_upd_score = "UPDATE `scores` SET `score` ='".$score_lesson."' WHERE `id_student`='".$id_student."' AND `id_subject`='".$id_subject."' AND `date_score`='".$date_score."' AND `score_type`='".$score_type."' AND  `id_teacher`='".$id_teacher."';";
            //echo $query_upd_score;
            mysqli_query($obj->link_db, $query_upd_score);
            $query_score ="SELECT  score FROM scores   WHERE `id_student`='".$id_student."' AND `id_subject`='".$id_subject."' AND `date_score`='".$date_score."' AND `score_type`='".$score_type."' AND  id_teacher='".$id_teacher."';";
            $res_score = mysqli_query($obj->link_db, $query_score);
            if(mysqli_num_rows($res_score)){
                $row = mysqli_fetch_assoc($res_score);
                $sc= $row['score'];
                echo '<script>
                       document.getElementById("tablebod").childNodes["'.$str_t_stud.'"].childNodes["'.$col_score.'"].textContent="'.$sc.'";
                      </script>';
            }
        }
        else{
            $query_ins = "INSERT INTO scores(id_student, id_subject, date_score, score, score_type, id_teacher) VALUES ('".$id_student."','".$id_subject."','".$date_score."','".$score_lesson."','".$score_type."','".$id_teacher."');";
            mysqli_query($obj->link_db, $query_ins);
            $query_contr = "SELECT  score FROM scores   WHERE `id_student`='".$id_student."' AND `id_subject`='".$id_subject."' AND `date_score`='".$date_score."' AND `score_type`='".$score_type."' AND  id_teacher='".$id_teacher."';";
            $res_contr = mysqli_query($obj->link_db, $query_contr);
            if(mysqli_num_rows($res_contr)) {
                $row = mysqli_fetch_assoc($res_contr);
                $sc = $row['score'];
                echo '<script>
                       document.getElementById("tablebod").childNodes["'.$str_t_stud.'"].childNodes["'.$col_score.'"].textContent="'.$sc.'";
                      </script>';
            }
        }
    }
}

if(isset($_POST['marker_str_nashi_dety']) or isset($_POST['marker_insert_child']) or isset($_POST['marker_delete_child']) or isset($_POST['marker_update_child']) ){
    switch (key($_POST)){
        case marker_update_child:
            //апдейт данных ребенка в базе
            $obj->DB_connect();
            if(isset($_POST['m_id'])){$id=intval($_POST['m_id']);}
            if(isset($_POST['m_fam'])){$fam=mysqli_real_escape_string($obj->link_db,$_POST['m_fam']);}
            if(isset($_POST['m_name'])){$name=mysqli_real_escape_string($obj->link_db,$_POST['m_name']);}
            if(isset($_POST['m_otch'])){$otch=mysqli_real_escape_string($obj->link_db,$_POST['m_otch']);}
            if(isset($_POST['m_d_birth'])){$d_birth=mysqli_real_escape_string($obj->link_db,$_POST['m_d_birth']);}
            if(isset($_POST['m_adr'])){$adr=mysqli_real_escape_string($obj->link_db,$_POST['m_adr']);}
            if(isset($_POST['m_phone'])){$phone=mysqli_real_escape_string($obj->link_db,$_POST['m_phone']);}
            if(isset($_POST['m_email'])){$email=mysqli_real_escape_string($obj->link_db,$_POST['m_email']);}
            $obj->query="update child set fam='".$fam."', name='".$name."', otch='".$otch."', day_birth='".$d_birth."', adress='".$adr."', phon='".$phone."', email='".$email."' where id_child =".$id;
            $obj->DB_query_to_row();//выполнили запрос апдейта и получили получили строку двумерного массива из запроса базы данных
            $obj->query="select * from child  order by name";
            $obj->DB_query_to_row();
            $obj->tag1='<td>';
            $obj->tag11='</td>';
            $obj->tag2='<tr onclick=\"func(this);\">';
            $obj->ta22='</tr>';
            $obj->DB_Dom();
            $obj->DB_close();
            //str_new-строка в ДОМ новая ее надо выделить
            //очистим поля и спрячем кнопки
            echo '<script>
                     var tb=document.getElementById("content_1");
                     
                     for(var i=0; i<tb.childNodes.length; i++){
                        if(tb.childNodes[i].childNodes[0].textContent=='.$id.'){
                            tb.insertBefore(tb.childNodes[i],tb.firstChild);
                            //tb.insertBefore(tb.childNodes[i],tb.childNodes[0]);//выделить и поднять на самый верх в табице
                            break;
                        }
                     }
                     
                     for(var i=0;i<tb.firstChild.childNodes.length; i++){
                         tb.firstChild.childNodes[i].style.background="#F2FFB6";
                     }
                     
                 </script>';
            break;
        case marker_delete_child:
            //удалить из базы данных
            $obj->DB_connect();
            if(isset($_POST['m_id'])){$id=intval($_POST['m_id']);}
            $obj->query= "delete from child where id_child =".$id;
            $obj->DB_query_to_row();//выполнили запрос-удаление в базу, теперь надо отобразить на странице
            $obj->query="select * from child  order by fam";
            $obj->DB_query_to_row();//выполнили запрос из базы для отображения, теперь надо отобразить на странице
            $obj->tag1='<td>';
            $obj->tag11='</td>';
            $obj->tag2='<tr onclick=\"func(this);\">';
            $obj->ta22='</tr>';
            $obj->DB_Dom();
            $obj->DB_close();
            break;
        case marker_insert_child://добавление непосредственно в таблицу на сервер, а потом дописка в дом на самуу верхнюю строку в tbgody='content_1'
            // echo('зашли в case добавки child в таблицу на сервер');
            $obj->DB_connect();
            if(isset($_POST['m_fam'])){$fam=mysqli_real_escape_string($obj->link_db,$_POST['m_fam']);}
            if(isset($_POST['m_name'])){$name=mysqli_real_escape_string($obj->link_db,$_POST['m_name']);}
            if(isset($_POST['m_otch'])){$otch=mysqli_real_escape_string($obj->link_db,$_POST['m_otch']);}
            if(isset($_POST['m_d_birth'])){$d_birth=mysqli_real_escape_string($obj->link_db,$_POST['m_d_birth']);}
            if(isset($_POST['m_adr'])){$adr=mysqli_real_escape_string($obj->link_db,$_POST['m_adr']);}
            if(isset($_POST['m_phone'])){$phone=mysqli_real_escape_string($obj->link_db,$_POST['m_phone']);}
            if(isset($_POST['m_email'])){$email=mysqli_real_escape_string($obj->link_db,$_POST['m_email']);}

            $obj->query= "insert into child (fam, name, otch, day_birth, adress, phon, email) VALUES('".$fam."','".$name."','".$otch."','".$d_birth."','".$adr."','".$phone."','".$email."');";

            $obj->DB_query_to_row();//выполнили запрос-добавление в базу, теперь надо отобразить на странице

            $obj->query="select * from child  order by id_child desc";
            $obj->DB_query_to_row();//выполнили запрос в базу подтянули из базы данные для отображения, теперь надо отобразить на странице
            $obj->tag1='<td>';
            $obj->tag11='</td>';
            $obj->tag2='<tr onclick=\"func(this);\">';
            $obj->ta22='</tr>';
            $obj->DB_Dom();
            $obj->DB_close();                                  // в клиенте новую добавленую строку с самого верха и другим цветом
            break;
        case marker_str_nashi_dety:
            //при нажатии кнопки наши дети из index.php передали маркер marker_str_nashi_dety и вернули резултьтат в content_1
            //echo ('первый раз зашли по нажатию кнопки but_nashi_dety и передали маркер marker_str_nashi_dety');
            $obj->DB_connect();
            $obj->query="select * from child  order by fam";
            $obj->DB_query_to_row();
            $obj->tag1='<td>';
            $obj->tag11='</td>';
            $obj->tag2='<tr onclick=\"func(this);\" >';
            $obj->ta22='</tr>';
            $obj->DB_Dom();

            $obj->DB_close();
            break;
        case n_but_save_child:
            echo (n_but_save_child);

            break;

        case add_child:
            echo ('зашли в контроллер наши дети. нажата кнопка add_child');
            // $name=mysqli_real_escape_string($link, )

            break;
        case spisok_klassa:



            break;

        default:
            echo('не сработало ни одно из условий case в обработчике contr_nashi.dety.php');
            break;
    }


}
else{//первоначальная загрузка страницы учителя
   // $obj=new My_class();//создали переменную класса через которую будем работать с запросами к базе и выводом на экран( в DOM )
    
    
}



$obj->DB_close();

?>