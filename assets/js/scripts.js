$(document).ready(function(){
    console.log('ready');
});

$(document).on("submit", "#form", function(event) {
    //предотварщение дефолтного события на сабмит
    event.preventDefault();
    let selected = [];
    //заполнение массива "Дополнительно" пунктами, отмеченными пользователями 
    $('input[id*="flexCheckChecked"]').each(function() {
        if($(this).is(':checked')){
            selected.push($(this).attr('value'));
        }
    });
    //заполнение переменной данных
    let data = {
        'product' : $('#product').val(),
        'days' : $('#customRange1').val(),
        'check' : selected,
    };
    //запрос ajax к серверу для логики расчета
    $.ajax({
        type: 'POST',
        url: 'backend/logic.php',
        data: data,
        dataType: 'json',
    })
    .done((dataReturn) => {
        $(".calulation-result").empty();
        $(".calulation-result").text("Результат калькуляции: " + dataReturn);
    })
    .fail((err) => {
        console.error(err);
    })
});
