/**
 * Created by Михаил on 28.09.2016.
 */
//################################################# ФОРМИРОВАНИЕ И ОТПРАВКА jQuery
//!!!ПРИ ИСПОЛЬЗОВАНИИ ПОДКЛЮЧИТЕ jQuery!!!
//!!!ВЫЗОВ - jquery_send(elemm, 'post', 'function.php', ['param'], [param.value])
var req;
var elem;                                                                  //!!! - ГЛОБАЛЬНАЯ (т.к. для post_send(get_send) и func_response) elem - ЭЛЕМЕНТ ДЛЯ ВЫВОДА
//################################################# ФОРМИРОВАНИЕ И ОТПРАВКА POST
function jquery_send(elemm, method, program, param_arr, value_arr) {
    var str='';                                                                //!!! - начальный str=''
    for(var i=0; i<param_arr.length; i++) {                                    //!!! - массивы - перебор
        str+=param_arr[i]+'='+encodeURIComponent(value_arr[i])+'&';             //!!! - накапливаем str
    }
    $.ajax({type: method, url: program, data: str, success: function(data){$(elemm).html(data);} //!!! - ВЫВОД В ЭЛЕМЕНТ ТУТ - function(data){$('elemm').html(data);
    });
}
//################################################# ПРИЕМ ОТ СЕРВЕРА И ВЫВОД В ЭЛЕМЕНТ
function func_response() {                                                 //!!! - функция проверки и приема
    if (req.readyState==4 && req.status==200) {                                //!!! - проверка условий "успех"
        elem_r=document.getElementById(elem);                                  //!!! - получаем элемен для вывода
        elem_r.innerHTML = req.responseText;                                   //!!! - ВЫВОД В ЭЛЕМЕНТ (переданный в post_send(get_send) elemm)
    }
}
