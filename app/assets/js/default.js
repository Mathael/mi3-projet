/**
 * Created by LEBOC Philippe on 23/10/2016.
 */
$(document).ready(function(){
    console.log('JQuery ready.');

    albumId = $('#albumId').val();

    $('.url').click(function (event) {
        url = $(this).data('url');
        console.log(url+'&album='+albumId);
        route(url, 'GET');
    })
});

function route(url, type) {
    $.ajax({
        url: url,           // ?page=test&action=ajax
        type: type,         // GET, POST, PUT, DELETE, HEAD
        complete: function(xhr, textStatus) {
            //called when complete
            console.log('complete()');
        },
        success: function(data, textStatus, xhr) {
            //console.log(data)
            //called when successful
            console.log('success()');
            console.log(data);
        },
        error: function(xhr, textStatus, errorThrown) {
            //called when there is an error
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}