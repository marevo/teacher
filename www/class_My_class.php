<?php
error_reporting(E_ERROR);

class My_class
{
    public $link_db, $query, $row_from_db; //переменные класса, link_db - ссылка на удачное подключение
                                       //$query - ЗАПРОС SQL, $row_db - МАССИВ РЕЗУЛЬТАТА(СТРОК) ПОСЛЕ ВЫПОЛНЕНИЯ ЗАПРОСА SQL
    public $id_null, $tag1, $tag11, $tag2, $tag22, $tag3, $tag33;//html теги для "оборачивания" id_null куда подтянуть (выводить) в первый раз
    public $login, $password, $role, $res;//переменные для проверки логина пароля и роли соответствующей этому логину-паролю
    function DB_connect(){
        $db_host='localhost';
        $db_user='root';
        $db_password='';
        $db_name='team_project_3';
        $link = mysqli_connect($db_host, $db_user, $db_password, $db_name);
        if(mysqli_connect_errno()){//если неудачное подключение
            echo '!!! ОШИБКА ПОДКЛЮЧЕНИЯ К БД:'.mysqli_connect_error();
            exit();
        }//иначе- подключились удачно
        $this->link_db=$link;//присвоим ссылку переменной класса на удачное подключение
    }
    function DB_close(){//закрытие соединения с базой данных
        mysqli_close($this->link_db);
    }
    function DB_query_to_row(){//зпрос к базе и в ответ строка двумерного массива $row_from_db
        $res=mysqli_query($this->link_db,$this->query);
        while ($row=mysqli_fetch_assoc($res)){
            $row_oll[]=$row;
        }
        $this->row_from_db = $row_oll;//получили строку двумерного массива из запроса базы данных
    }
    function DB_Dom(){
       $rrr='';//  если заранее не объявить, то на стр 44 ругается
        foreach ( $this->row_from_db as $roww1){
            $rr='';
            foreach ($roww1 as $roww2){
                $rr.="$this->tag1$roww2$this->tag11";
            }
            $rrr.="$this->tag2$rr$this->tag22";
            echo '<script>';
            echo 'var t_bod=document.getElementById("content_1");';
            echo 't_bod.innerHTML="'.$rrr.'";';
            echo'</script>';
        }
        $rrr="$this->tag3$rrr$this->tag33";
    }
	function DB_query_oll() {                                             // 16. МЕТОД БАЗОВОГО КЛАССА "ВЫПОЛНЕНИЕ ЗАПРОСА+ЗАПИСЬ РЕЗУЛЬТА В МАССИВ"
		$res=mysqli_query($this->link_db, $this->query);                      //ВЫПОЛНЕНИЕ ЗАПРОСА - "КЛАССНАЯ" ПЕРЕМЕННАЯ $this->link_db - ССЫЛКА НА "УДАЧНОЕ" ПОДКЛЮЧЕНИЕ, "КЛАССНАЯ" ПЕРЕМЕННАЯ $this->query - СТРОКА ЗАПРОСА SQL
		while($row=mysqli_fetch_assoc($res)) {                                //ПЕРЕБОР РЕЗУЛЬТАТА - ПОСТРОЧНО
		$row_oll[]=$row;                                                  //ФОРМИРОВАНИЕ $row_oll(НАКОПЛЕНИЕ ПРИ ПЕРЕБОРЕ) - РЕЗУЛЬТИРУЮЩИЙ ДВУМЕРНЫЙ ДВУМЕРНЫЙ МАССИВ
		}
		$this->row_db = $row_oll;                                               //РЕЗУЛЬТИРУЮЩИЙ ДВУМЕРНЫЙ ДВУМЕРНЫЙ МАССИВ В "КЛАССНУЮ" ПЕРЕМЕННУЮ $this->row_db
	}
    function check_log_pas(){//возвратит строку по логину-паролю
        $query ="select id_role, id_user from users where login='$this->login' and password='$this->password'";
        return mysqli_query($this->link_db, $query);
        //return($res_out);//вернем дискриптор на запрос
    }
	function DB_dom_tag() {                                               // 17. МЕТОД БАЗОВОГО КЛАССА "ВЫВОД В DOM"
			   $rrr='';
			foreach($this->row_db as $k1=>$mas1) {                                //ПЕРЕБОР $this->row_db - ФОРМИРОВАНИЕ СТРОК
			   $rr='';
			   foreach($mas1 as $k2=>$mas2) {
				   $mas2 = htmlspecialchars($mas2);
				  if($k2=='up') {$mas2="<a href='#' id='$mas2' style='color:blue;' onclick='return false;'>".$mas2."</a>";}
					if($k2=='image') {$mas2="<img src='".$mas2."' />";}
				  //ПРИ КАЖДОМ "НУЛЕВОМ" - В ЯЧЕЙКУ ТАБЛИЦЫ <a> С id=id_user/id_pokup (ДЛЯ select-МЕНЮ(ТОВАРЫ) - ПРОСТО НЕ БУДЕТ РАБОТАТЬ, Т.К. НЕТ $k2=='up')
				  $rr=$rr."$this->tag1$mas2$this->tag11";                         //ОБОРАЧИВАЕМ ТЕГАМИ-1(БУДЕТ ДЛЯ ВСЕХ (select-МЕНЮ(option) И ТАБЛИЦА (td))
			   }
			   $rrr=$rrr."$this->tag2$rr$this->tag22";                            //ОБОРАЧИВАЕМ ТЕГАМИ-2(МОГУТ ОТСУТСТВОВАТЬ, НАПРИМЕР, ДЛЯ select-МЕНЮ)
			}
			$rrr="$this->tag3$rrr$this->tag33";                                   //ОБОРАЧИВАЕМ ТЕГАМИ-3(МОГУТ ОТСУТСТВОВАТЬ, НАПРИМЕР, ДЛЯ ТАБЛИЦЫ, ИСПОЗЬЗУЮТСЯ ДЛЯ <option>Все---</option> - ДЛЯ "ДОБАВКИ ЛИШНЕГО" ПУНКТА МЕНЮ (0-ГО), ПО КОТОРОМУ ВЫБИРАЮТСЯ ВСЕ ЗАПИСИ (СМ.СООТВЕТСТВУЮЩИЙ js-КОД В db_poisk.php (tov_select='';))
			if(isset($_POST['name_select']) or isset($_POST['tov_insert']) or isset($_POST['tov_delete']) or isset($_POST['tov_update'])or (isset($_POST['views']) and $_POST['from']=='pokupki_tmp')) {                                    //ЕСЛИ НАЖАТА КНОПКА "Искать покупку"...
			   echo $rrr;                                                         //ВОЗВРАТ ИЗ ajax
			}
			else {                                                                //ЕСЛИ НЕТ НАЖАТИЯ КНОПКИ "Искать покупку"(ПЕРВАЯ ЗАГРУЗКА!!!)...
			   echo '<script>';                                                   //ВОЗВРАТ НЕ!!! ИЗ ajax-ОБЫЧНЫЙ js
			   echo "var elem=document.getElementById('$this->id_null');";        //ВЫВОД В КОНКРЕТНЫЙ ЭЛЕМЕНТ $this->id_null(МЕНЮ, ТАБЛИЦА)
			   echo 'elem.innerHTML="'.$rrr.'</span>";';
			   echo '</script>';
			}
}
}
class DB_oll extends My_class {                                       // 19. ПРОИЗВОДНЫЙ КЛАСС
function Query_Dom_oll($link, $query, $id_null, $tag1, $tag11, $tag2, $tag22, $tag3, $tag33) { //МЕТОД ПРОИЗВОДНОГО КЛАССА
$this->link_db=$link;                                                 //"КЛАССНЫЕ" ПЕРЕМЕННЫЕ ДЛЯ НЕОБХОДИМЫХ ТЕГОВ И ЭЛЕМЕНТА "0-ГО" ВЫВОДА...
$this->query=$query;
$this->tag1=$tag1;
$this->tag11=$tag11;
$this->tag2=$tag2;
$this->tag22=$tag22;
$this->tag3=$tag3;
$this->tag33=$tag33;
$this->id_null=$id_null;
$this->DB_query_oll();                                                //НАСЛЕДОВАНИЕ - "ЗАПРОС-МАССИВ"!!!
$this->DB_dom_tag();                                                  //НАСЛЕДОВАНИЕ - "ТЕГ-DOM"!!!
}
}
?>