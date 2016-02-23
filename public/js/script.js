$(document).ready(function(){
    $('[id^="id"]').hide();
    $('[id^="div"]').hide();
});

function showForm(id) {
    var form = "#id".concat(id);
    var button = "#show".concat(id);

    $(document).ready(function(){

            $(form).show();
            $(button).hide();
        });

}


function showResponses(id)
{
    var responses = "#div-".concat(id);
    var button = "#show-response-".concat(id);
    $(document).ready(function(){
        $(responses).show();
        $(button).hide();
    })

}

function hideResponses(id)
{
    var responses = '#div-'.concat(id);
    var show = "#show-response-".concat(id);

    $(document).ready(function(){
        $(responses).hide();
        $(show).show();
    })
}
