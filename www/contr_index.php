<?php
//error_reporting(E_ERROR);
include_once 'class_My_class.php';
$obj=new My_class();
$obj->DB_connect();
if(isset($_POST['password']) and  isset($_POST['login'])) {//проверка на логин и пароль если есть такие надо вернуть роль и запустить страницу с нужной ролью
            $obj->login = mysqli_real_escape_string($obj->link_db,$_POST['login'] );
            $obj->password = mysqli_real_escape_string($obj->link_db,$_POST['password']);
            $res_in=$obj->check_log_pas();//вызвали ф запроса роли по логину-паролю
    if(mysqli_num_rows($res_in)){//если вернуло строку значит логин-пароль совпал
        $row=mysqli_fetch_row($res_in);//получили id роли
        if($row[0]!='' && $row[1]!=''){//если получили не пустые значения по логин-пароль
            switch ($row[0]){
                case 2://enter teacher
                    echo
                        '<br/>ваш <br/>логин= '.$obj->login.'<br/>пароль='.$obj->password.'<br/>роль='.$row[0].'<br/>id_user='.$row[1].';'
                    ;
                    echo '<script>window.open("teacher.php?id_user='.$row[1].'", "_self");</script>';
                    break;
                case 1://enter admin

                    break;
                case 3://enter parent
                    echo '<script>window.open("db_parents.php?user='.$row[1].'", "parent");</script>';
                    break;
                default:
                    break;
            }

        }
        else{
            echo
            'такого логина-пароля нет( <br>
                 проверьте правильность ввода<br>
                 или обратитесь к администратору
                 ';
        }
    }
    else{
        echo '<span>такого нет в базе <br> 1) проверьте правильность ввода <br> 2) обратитесь в администрацию</span>';
    }
   
}
      
$obj->DB_close();



?>