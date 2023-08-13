$(document).ready(function () {
    magazineLoad();

    $('#btnImageUpload').click(function () {
        $('#fileMagazineImage').trigger('click');
    });

    $('#fileMagazineImage').change(function () {
        var fileNm = $('#fileMagazineImage')[0].files[0];
        var fileExt = '';

        if (fileNm) {
            fileExt = fileNm.name.split('.').pop().toLowerCase();
            if ($.inArray(fileExt, ['png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG']) == -1) {
                alert('png,jpg,jpeg 파일만 업로드 할수 있습니다.');
                $("#txtImageFileName").val('');
                $('#fileMagazineImage').val('');
                $('#sample_image').attr('src', '/adm/img/noImg_view.png');
                return false;
            } else {
                $("#txtImageFileName").val(fileNm.name);
                readURL(this);
            }
        } else {
            $('#sample_image').attr('src', '/adm/img/noImg_view.png');
            $("#txtImageFileName").val('');
            $('#fileMagazineImage').val('');
        }
    });

    $("#magazineList").on("click", ".tc", function (e) {
        magazineSelect(e);
    });

    $("#btnMagazineSave").click(function () {
        var title = $("#txtMagazineName").val();
        var magazine_year = $("#selYear").val();
        var season = $("#selSeason").val();
        var pdf_link = $("#txtMagazineFileLink").val();
        var action = 'magazineInsert';

        if (!title || !title.trim()) {
            alert('제목을 입력하세요');
            return false;
        }

        if (!pdf_link) {
            alert('파일링크를 입력하세요');
            return false;
        }

        datas = new FormData();
        datas.append('file1', $('#fileMagazineImage')[0].files[0]); //미리보기
        datas.append('title', title);
        datas.append('magazine_year', magazine_year);
        datas.append('season', season);
        datas.append('pdf_link', pdf_link); //pdf

        if ($("#editMagazine").val() != '') {
            action = 'magazineUpdate';
            datas.append('magazine_idx', $("#editMagazine").val());
        }

        datas.append('action', action);

        $.ajax({
            url: '/adm/ajax/magazineControll.ajax.php',
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
                    location.reload();
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
    });

    $("#btnMagazineDelete").click(function () {
        var magazine_idx = $("#editMagazine").val();
        var txtImageFileName = $("#txtImageFileName").val();

        if (confirm("등록된 매거진 정보를 삭제하시겠습니까?")) {
            $.ajax({
                url: '/adm/ajax/magazineControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'magazineDelete',
                    magazine_idx: magazine_idx,
                    txtImageFileName: txtImageFileName
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        location.reload();
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

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#sample_image').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function magazineLoad() {
    $.ajax({
        url: '/adm/ajax/magazineControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'magazineLoad',
            magazine_year: $("#selViewYear").val(),
            season: $("#selViewSeason").val()
        },
        success: function (result) {
            if (result.success && result.data) {
                $("#editMagazine").val('');
                $('#dataTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'magazine_year'
                    },
                    {
                        data: 'season_name'
                    },
                    {
                        data: 'title'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    createdRow: function (row, data) {
                        $("th").addClass('text-center align-middle');
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-magazine-idx', data.magazine_idx);
                    },
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });
            } else {
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function magazineSelect(e) {
    var targetClass = $(e.target).parents('.tc');

    if ($(e.target).parent('.bg-light').length) {
        targetClass.removeClass('bg-light');
        $("#editMagazine").val('');
        $("#txtMagazineName").val('');
        $("#txtImageFileName").val('');
        $("#txtMagazineFileLink").val('');
        $("#btnMagazineDelete").hide();
        $("#sample_image").attr('src', '/adm/img/noImg_view.png');
    } else {
        $('.tc').removeClass('bg-light');
        targetClass.addClass('bg-light');

        var magazine_idx = targetClass.data('magazine-idx');
        $("#editMagazine").val(magazine_idx);

        $.ajax({
            url: '/adm/ajax/magazineControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'magazineSelect',
                magazine_idx: magazine_idx
            },
            success: function (result) {
                if (result.success && result.data) {
                    $("#txtMagazineName").val(result.data.title);
                    $("#selYear option[value=" + result.data.magazine_year + "]").prop("selected", true);
                    $("#selSeason option[value=" + result.data.season + "]").prop("selected", true);
                    $("#txtMagazineFileLink").val(result.data.pdf_link);

                    if (result.data.thumbnail_name) {
                        $("#txtImageFileName").val(result.data.thumbnail_name);
                        $("#sample_image").attr('src', '/files/magazine_file/' + result.data.thumbnail_name);
                    } else {
                        $("#txtImageFileName").val('');
                        $("#sample_image").attr('src', '/adm/img/noImg_view.png');
                    }

                    $("#btnMagazineDelete").show();
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    }
}