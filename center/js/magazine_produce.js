$(document).ready(function () {
    magazineProduceLoad();

    $("#selMagazineProduceYears, #selMagazineProduceSeason").change(function () {
        magazineProduceLoad();
    });

    $("#magazineFileList").on("click", ".magazine-pfile-upload", function () {
        var _idx = $(this).parent("td").parent("tr").index();
        $("input[name=magazineProduceFile]:eq(" + _idx + ")").trigger('click');
    });

    $("#magazineFileList").on("change", "[name=magazineProduceFile]", function () {
        var _idx = $(this).parent("td").parent("tr").index();
        var file = $("input[name=magazineProduceFile]")[_idx].files[0];
        var magazineProduceType = $(this).parent("td").parent("tr").find('.magazine-pfile-upload').data('produce-type');
        var selMagazineProduceYears = $("#selMagazineProduceYears").val();
        var selMagazineProduceSeason = $('#selMagazineProduceSeason').val();

        if (file) {
            if (confirm("파일을 업로드하시겠습니까?")) {

                datas = new FormData();
                datas.append('action', 'magazineProduceUpload');
                datas.append("center_idx", center_idx);
                datas.append("produce_type", magazineProduceType);
                datas.append("selMagazineProduceYears", selMagazineProduceYears);
                datas.append("selMagazineProduceSeason", selMagazineProduceSeason);
                datas.append("magazine_produce_file", file);

                $.ajax({
                    url: '/center/ajax/magazineProduceControll.ajax.php',
                    contentType: 'multipart/form-data',
                    dataType: 'JSON',
                    type: 'POST',
                    mimeType: 'multipart/form-data',
                    data: datas,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        if (result.success) {
                            alert(result.msg);
                            magazineProduceLoad();
                            return false;
                        } else {
                            alert(result.msg);
                            $("input[name=magazineProduceFile]")[_idx].val('');
                            return false;
                        }
                    },
                    error: function (request, status, error) {
                        alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                    }
                });
            }
        } else {
            $("input[name=magazineProduceFile]")[_idx].val('');
        }
    });

    $("#magazineFileList").on("click", ".produce-file-del", function () {
        var produce_idx = $(this).data('produce-idx');

        if (confirm("파일을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/magazineProduceControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'magazineProduceDelete',
                    produce_idx: produce_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        magazineProduceLoad();
                        return false;
                    } else {
                        alert(result.msg);
                        return false;
                    }
                },
                error: function (request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }
    });
});

function magazineProduceLoad() {
    var selMagazineProduceYears = $("#selMagazineProduceYears").val();
    var selMagazineProduceSeason = $('#selMagazineProduceSeason').val();

    $.ajax({
        url: '/center/ajax/magazineProduceControll.ajax.php',
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