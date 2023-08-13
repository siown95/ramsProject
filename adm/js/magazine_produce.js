$(document).ready(function () {
    magazineProduceLoad();

    $("#selCenter, #selMagazineProduceYears, #selMagazineProduceSeason").change(function () {
        magazineProduceLoad();
    });
});

function magazineProduceLoad() {
    var center_idx = $("#selCenter").val();
    var selMagazineProduceYears = $("#selMagazineProduceYears").val();
    var selMagazineProduceSeason = $('#selMagazineProduceSeason').val();

    $.ajax({
        url: '/adm/ajax/magazineProduceControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'magazineProduceLoad',
            center_idx: center_idx,
            selMagazineProduceYears: selMagazineProduceYears,
            selMagazineProduceSeason: selMagazineProduceSeason
        },
        success: function (result) {
            if (result.success) {
                $("#magazineFileList").html(result.data.produceTable);
            } else {
                $("#magazineFileList").empty();
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}