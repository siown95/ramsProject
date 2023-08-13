$(document).ready(function(){
    magazineLoad();
});

function magazineLoad(){
    $.ajax({
        url: '/center/student/ajax/magazineControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'magazineLoad',
            magazine_year: $("#selYear").val(),
            season: $('#selSeason').val()
        },
        success: function (result) {
            if (result.success) {
                console.log(result);
                $("#magazineList").html(result.data.list);
            } else {
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}