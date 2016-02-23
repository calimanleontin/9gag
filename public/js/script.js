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

function deleteResponse(id)
{
    if (confirm('Are you sure you want to delete the replay?')) {
        if (!location.origin)
            location.origin = location.protocol + "//" + location.host;
        var redirect = location.origin.concat('/gag /response/delete/');
        redirect = redirect.concat(id)
         window.location.replace(redirect);
    } else {
        // Do nothing!
    }
}
