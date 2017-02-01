<?php
    include_once 'class_My_class.php';
    $obj=new My_class();
    $obj->DB_connect();
    $id_user = $_GET["user"];
	$query ="select id_parent,name_parent from parents where id_user='$id_user';";
    $res = mysqli_query($obj->link_db, $query);
	while($row = mysqli_fetch_assoc($res))
	{
		//$options.='<option value='.$row["id_parent"].'>'.$row["name_student"].'</option>';
		$id_parent = $row["id_parent"];
		$nam = $row["name_parent"];
	}
	//echo $options;
	
	/*while($row=mysqli_fetch_assoc($res))
				{
					$roww[]=$row;
				}
				foreach($roww as $k1=>$row1 ){
					$rr1='';
					foreach($row1 as $k2=>$row2){
						$rr1.=$row2;
					}	
				} */
				//$rr1;
				//echo $id_parent;
				echo '<h1  style="text-align:center;">Страница для родителя"'.$nam.'"</h1>';
?>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="db_style.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script type="text/javascript" src="ajax_post_get.js"></script>
    <script type="text/javascript" src="new_java_script.js"></script>
</head>
<div id="div_0">
  <div id="left" style="display:none;">

  </div>
  <br>
  <form name = "form_parent" style="margin:0px; text-align:center;">
	<div style"display:table-raw;text-align:center;">
	<div style="display:table-cell; width:100px; text-align:center;"></div><br>Дети
	<div style="display:table-cell; width:360px; text-align:center;"></div>
	  <select name="sel_name_parent" id="sel_name_id_parent" onchange="cng_name();"
			  style="width:219px;" onkeypress="if(event.which==13) {return false;}">
		 <?php
			 $query ="select students.id_student, name_student 
					  from students,parent_student
					  where students.id_student = parent_student.id_student and parent_student.id_parent='$id_parent'";
			 $res = mysqli_query($obj->link_db, $query);
			 while($row = mysqli_fetch_assoc($res))
			{
				$options.='<option value='.$row["id_student"].'>'.$row["name_student"].'</option>';
			}
			echo $options;
		 ?>
	  </select>
	</div>
	<br>
	Выберите дату для отображения
	<br>
</form>
<form  name = "name_calendar" style="text-align:center;">от
	<input type="date" name="calendar_ot" id="ot" onchange="fun_ot(this);">
	до
	<input type="date" name="calendar_do" id="do" onchange="fun_do(this);">
	<br>
	<input type="text" id="start" style="display:none;"/>
	<input type="text" id="end" style="display:none;"/>
	<script>
		document.getElementById("ot").valueAsDate = new Date();
		document.getElementById("do").valueAsDate = new Date();
	function fun_ot(elem)
	{
		var t_n = elem.value;
		//var t_n = $("#ot").datepicker();
		//alert (t_n);
		document.getElementById("start").value = t_n;
	}
	function fun_do(elem)
	{
		var t_n = elem.value;
		$("#do").datepicker();
		//alert (t_n);
		document.getElementById("end").value = t_n;
	}
	</script>
</form>
<br>
<br>
	<form id="id_button" style="text-align:center;">
		<input type="button" value="Успеваемость" onclick = "progress()">
		<input type="button" value="Посещаемость" onclick= "attendance()">
	</form>

<br>
<div style="overflow:auto;">
	<table style="width=300px; margin:0px auto; align:center; ">
		<tbody id="tbody_scores">
		</tbody>
	</table>
</div>

</div>

<script>
function cng_name()
 { 
    //pokup_error.innerHTML="&nbsp";
	/*name_parent_select=<?php echo $id_parent?>;
    if(form_parent.sel_tov_pokup.selectedIndex!=0 && form_parent.sel_name_parent.selectedIndex!=0) {
       maska.style.zIndex=-1;
       form_parent.elements[5].style.backgroundColor='whitesmoke';    //ОТКРЫТИЕ КНОПКИ
       form_parent.elements[5].disabled=false;
    }
    else {
       maska.style.zIndex=1;
       form_parent.elements[5].style.backgroundColor='silver';        //ЗАКРЫТИЕ КНОПКИ
       form_parent.elements[5].disabled=true;
    }
   jquery_send('#sel_name_id_parent', 'post', 'contr_nashi_dety.php', ['name_parent_select'], ['name_parent_select']);*/
}
function progress()
{
	id_student=document.getElementById("sel_name_id_parent").value;
	start=document.getElementById("ot").value;
	end=document.getElementById("do").value;
	jquery_send('#tbody_scores', 'post', 'contr_nashi_dety.php', ['progress','id_student','ot','do'], ['',id_student,start,end]);
}
function attendance()
{
	id_student=document.getElementById("sel_name_id_parent").value;
	start=document.getElementById("ot").value;
	end=document.getElementById("do").value;
	jquery_send('#tbody_scores', 'post', 'contr_nashi_dety.php', ['attendance','id_student','ot','do'], ['',id_student,start,end]);
}
</script>