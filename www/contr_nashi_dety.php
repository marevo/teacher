<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
   <!-- <link rel="stylesheet" type="text/css" href="db_style.css">-->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script type="text/javascript" src="ajax_post_get.js"></script>
   
</head>
<?php
include_once 'class_My_class.php';
$obj=new My_class();//создали переменную класса через которую будем работать с запросами к базе и выводом на экран( в DOM )
$obj->DB_connect(); 
$obj_query_tag=new DB_oll(); 

if(isset($_POST['progress']) or isset($_POST['attendance'])or isset($_POST['name_parent_select'])){
    switch (key($_POST)){
		/*case name_parent_select:
				$id_child = mysqli_real_escape_string($obj->link_db, $_POST['name_parent_select']);
				$q ="select child.id_child,name_child from child,parents_childs where child.id_child=parents_childs.id_child and parents_childs.id_parent='$name_parent_select'";
				$res = mysqli_query($obj->link_db, $q);
				//print_r($res);
				//$options='';
				while($row=mysqli_fetch_assoc($res))
				{
					$options.='<option value='.$row["id_child"].'>'.$row["name_child"].'</option>';
				}
				//echo $options;
				   echo '<script>';                                                   //ВОЗВРАТ НЕ!!! ИЗ ajax-ОБЫЧНЫЙ js
				   echo "var elem=document.getElementById('sel_name_id_parent');";        //ВЫВОД В КОНКРЕТНЫЙ ЭЛЕМЕНТ $this->id_null(МЕНЮ, ТАБЛИЦА)
				   echo 'elem.innerHTML="'.$options.'";';
				   echo '</script>';
		break;*/
		
		case progress:
		$id_student = mysqli_real_escape_string($obj->link_db, $_POST['id_student']);
		$ot = mysqli_real_escape_string($obj->link_db, $_POST['ot']);
		$do = mysqli_real_escape_string($obj->link_db, $_POST['do']);
		$query = "select name_subject, score, date_score
					from scores, subjects, students
					where students.id_student='$id_student'
					and date_score between '$ot' and '$do'
					and scores.id_subject = subjects.id_subject
					;";
		$content="<tr><td>Предмет/Дата</td>";
		$begin = new DateTime($ot);
		$end = new DateTime($do);
		$end = $end->modify( '+1 day' ); 
		$interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);

		foreach($daterange as $date){
			$dat=$date->format("Y-m-d");
			$content.="<td>".$dat. "</td>";
			$dates[$dat]='';
		}
		$content.="</tr>";
		$res = mysqli_query($obj->link_db, $query);
		//$content.=$res;
		while($row  = mysqli_fetch_assoc($res))
	    {
			if(!$d[$row["name_subject"]])
			{
				$d[$row["name_subject"]]=$dates;
			}
			foreach($d[$row["name_subject"]] as $key=>$value)
			{
			    if($row["date_score"]==$key)
				{
					$d[$row["name_subject"]][$key]=$row["score"];
				}
			}
			//$dates[$row["date_score"]][$row["name_subject"]]=$row["score"];
		}
		foreach($d as $subject=>$dates)
		{
			$content.="<tr><td>".$subject."</td>";
			foreach($dates as $score)
			{
				$content.="<td>".$score."</td>";
			}
			$content.="</tr>";
		}
		echo '<script>';                                                  
			   echo "var elem=document.getElementById('tbody_scores');";       
			   echo 'elem.innerHTML="'.$content.'";';
			   echo '</script>';
		 echo '<script> var tr_mas=document.getElementById("tbody_scores").getElementsByTagName("tr");
			for(var i=0; i<tr_mas.length; i++) {
			   tr_mas[i].childNodes[0].style.textAlign="center";
			}</script>';		
		
		break;
		
		case attendance:
		$id_student = mysqli_real_escape_string($obj->link_db, $_POST['id_student']);
		$ot=mysqli_real_escape_string($obj->link_db, $_POST['ot']);
		$do=mysqli_real_escape_string($obj->link_db, $_POST['do']);
		$query = "select name_subject, date_absent,type_absent
					from be_absent,subjects,timetable
					where be_absent.id_student='$id_student'
					and date_absent between '$ot' and '$do'
					and be_absent.id_time_table=timetable.id_time_table
					and timetable.id_subject = subjects.id_subject";
		$content="<tr><td>Предмет/Дата</td>";
		$begin = new DateTime($ot);
		$end = new DateTime($do);
		$end = $end->modify( '+1 day' ); 
		$interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);

		foreach($daterange as $date){
			$dat=$date->format("Y-m-d");
			$content.="<td>".$dat. "</td>";
			$dates[$dat]='';
		}
		$content.="</tr>";
		$res = mysqli_query($obj->link_db, $query);
		//echo $res;
		//$content.=$res;
		while($row=mysqli_fetch_assoc($res))
	    {
			if(!$d[$row["name_subject"]])
			{
				$d[$row["name_subject"]]=$dates;
			}
			foreach($d[$row["name_subject"]] as $key=>$value)
			{
			    if($row["date_absent"]==$key)
				{
					$d[$row["name_subject"]][$key]=$row["type_absent"];
				}
			}
		}
		foreach($d as $subject=>$dates)
		{
			$content.="<tr><td>".$subject."</td>";
			foreach($dates as $attendance)
			{
				$content.="<td>".$attendance."</td>";
			}
			$content.="</tr>";
			//echo $content;
		}
		echo '<script>';                                                   
			   echo "var elem=document.getElementById('tbody_scores');";        
			   echo 'elem.innerHTML="'.$content.'";';
			   echo '</script>';
		 echo '<script> var tr_mas=document.getElementById("tbody_scores").getElementsByTagName("tr");
			for(var i=0; i<tr_mas.length; i++) {
			   tr_mas[i].childNodes[0].style.textAlign="center";
			}</script>';		
		
		break;
		
        default:
            echo('не сработало ни одно из условий case в обработчике contr_nashi.dety.php');
            break;
    }
}
else{

}





?>