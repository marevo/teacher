<?php
/**контроллер для обработки списка детей
 * Created by PhpStorm.
 * User: Михаил
 * Date: 28.09.2016
 * Time: 14:45
 */
?>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="db_style.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script type="text/javascript" src="ajax_post_get.js"></script>
    <script type="text/javascript" src="new_java_script.js"></script>
    
</head>
<form id="form">
    <div id="knopki" >
        <input type="button" name="n_but_add_child" value="добавить или изменить запись" onclick="but_form_add_child(this.name);"/>

    </div>
    <div id="pole" style="float:left ;display:table; display:none;background-color: antiquewhite;">
        <div id="kn_del_ren_up">
            <input type="button" name="n_but_save_child"   value="сохранить"
                   onclick="insert_child(this.name, fam.value, nam.value, otch.value, d_birth.value, adr.value, phone.value, email.value);"/>
            <input type="button" name="n_but_delete_child" value="удалить"
                   onclick="delete_child(this.name, ch_id.value);"   />
            <input type="button" name="n_but_rename_child" value="редактировать"
                   onclick="update_child(this.name, ch_id.value, fam.value, nam.value, otch.value, d_birth.value, adr.value, phone.value, email.value);"/>

        </div>
        <div style="display: table-row;display:none;"><span style="display: table-cell;">id (не вводится) &nbsp</span><input name="ch_id" type="text" style="width: 200px;font-size:medium;" value=""/></div>
        <div style="display: table-row;"><span style="display: table-cell;">введите фамилию&nbsp</span><input type="text" name="fam" style="width: 200px;font-size:medium;" value=""/></div>
        <div style="display: table-row;"><span style="display: table-cell;">введите имя&nbsp</span><input type="text"  name="nam" style="width: 200px;font-size:medium;" value=""/></div>
        <div style="display: table-row;"><span style="display: table-cell;">введите отчество&nbsp</span><input type="text"  name="otch" style="width: 200px;font-size:medium;" value=""/></div>
        <div style="display: table-row;"><span style="display: table-cell;">введите дата рождения&nbsp</span><input type="text"  name="d_birth" style="width: 200px;font-size:medium;" value=""/></div>
        <div style="display: table-row;"><span style="display: table-cell;">введите адрес проживания&nbsp</span><input type="text"  name="adr" style="width: 600px;font-size:medium;" value=""/></div>
        <div style="display: table-row;"><span style="display: table-cell;">введите телефон&nbsp</span><input type="text"  name="phone" style="width: 200px;font-size:medium;" value=""/></div>
        <div style="display: table-row;"><span style="display: table-cell;">введите почту&nbsp</span><input type="text"  name="email" style="width: 450px;font-size:medium;" value=""/></div>
    </div>

</form>
<?php
//echo"<h1> список наших детей<h1>";
echo '<table>
         <tbody id="shapka">
              <tr>
                  <td>id</td>
                  <td>фамилия</td>
                  <td>имя</td>
                  <td>отчество</td>
                  <td>дата рождения</td>
                  <td>адрес проживания</td>
                  <td>телефон</td>
                  <td>почта</td>
              </tr>
         </tbody>
         <tbody id="content_1">
         
          </tbody>
      </table>
      ';
include_once "contr_nashi_dety.php";
?>
<script>
    function but_form_add_child(but_name){//кнопка показа/не показа добавления/изменения еще одной записи в таблицу
        var polya_add=document.getElementById("pole");
        if(polya_add.style.display=="block"){
            polya_add.style.display="none";
        }
        else{
            polya_add.style.display="block";
        }
    }
    function update_child(button_name, id, fam, name, otch, d_birth, adr, phone, email){
         jquery_send('#content_1','post','contr_nashi_dety.php',
             ['marker_update_child','m_id','m_fam','m_name','m_otch','m_d_birth','m_adr','m_phone','m_email'],
             ['', id, fam, name, otch, d_birth, adr, phone, email]
         );
        clear_poly();
    }
    function delete_child(button_name, id){
        jquery_send('#content_1','post','contr_nashi_dety.php',
            ['marker_delete_child', 'm_id'],
            ['', id]
        );
        clear_poly();
    }
    function insert_child(button_name, fam, name, otch, d_birth, adr, phone, email){
        jquery_send('#content_1','post','contr_nashi_dety.php',
            ['marker_insert_child','m_fam','m_name','m_otch','m_d_birth','m_adr','m_phone','m_email'],
            ['', fam, name, otch, d_birth, adr, phone, email]
        );
        clear_poly();
    }
    function func(elem){
        var this_el=elem;
        for(var i=0; i<this_el.childNodes;i++){
            if(this_el.childNodes[i].style.backgroundColor != "blue"){
                this_el.childNodes[i].style.backgroundColor="blue";
            }
        }
        document.forms[0].ch_id.value=this_el.childNodes[0].textContent;
        document.forms[0].fam.value=this_el.childNodes[1].textContent;
        document.forms[0].nam.value=this_el.childNodes[2].textContent;
        document.forms[0].otch.value=this_el.childNodes[3].textContent;
        document.forms[0].d_birth.value=this_el.childNodes[4].textContent;
        document.forms[0].adr.value=this_el.childNodes[5].textContent;
        document.forms[0].phone.value=this_el.childNodes[6].textContent;
        document.forms[0].email.value=this_el.childNodes[7].textContent;

    }


    function clear_poly(){
        document.forms[0].ch_id.value=this_el.childNodes[0].value="";
        document.forms[0].fam.value=this_el.childNodes[1].value="";
        document.forms[0].nam.value=this_el.childNodes[2].value="";
        document.forms[0].otch.value=this_el.childNodes[3].value="";
        document.forms[0].d_birth.value=this_el.childNodes[4].value="";
        document.forms[0].adr.value=this_el.childNodes[5].value="";
        document.forms[0].phone.value=this_el.childNodes[6].value="";
        document.forms[0].email.value=this_el.childNodes[7].value="";
    }

</script>
