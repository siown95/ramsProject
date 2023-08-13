$(document).ready(function () {
    newspaperLoad();

    $("#btnAddNewsPaper").click(function () {
        $("#mdNewsIdx").val('');
        $("#txtTitle").val('');
        $("#selNewspaperCompany option[value='']").prop("selected", true);
        $("#selSubject option[value='']").prop("selected", true);
        $("#dtDate").val('');

        $("#originColumn").addClass("d-none");
        $("#originColumn").attr("href", "");

        $("#originTeaching").addClass("d-none");
        $("#originTeaching").attr("href", "");

        $("#txtColumnName").val('');
        $("#fileColumn").val('');
        $("#txtTeachingName").val('');
        $("#fileTeaching").val('');

        $("#btnSaveNewsPaper").show();
        $("#btnUpdateNewsPaper").hide();
        $("#btnDeleteNewsPaper").hide();
        $("#NewspaperModal").modal('show');
    });

    $('#btnColumnUpload').click(function () {
        $('#fileColumn').trigger('click');
    });

    $('#fileColumn').change(function () {
        var fileNm = $('#fileColumn')[0].files[0];
        var ext = $('#fileColumn').val().split('.').pop().toLowerCase();

        if (fileNm) {
            if ($.inArray(ext, ['pdf', 'PDF']) == -1) {
                alert('pdf 파일만 업로드 할수 있습니다.');
                $("#txtColumnName").val('');
                $("#fileColumn").val('');
                return false;
            } else {
                $("#txtColumnName").val(fileNm.name);
            }
        } else {
            $("#txtColumnName").val('');
            $('#fileColumn').val('');
        }
    });

    $('#btnTeachingUpload').click(function () {
        $('#fileTeaching').trigger('click');
    });

    $('#fileTeaching').change(function () {
        var fileNm = $('#fileTeaching')[0].files[0];
        var ext = $('#fileTeaching').val().split('.').pop().toLowerCase();

        if (fileNm) {
            if ($.inArray(ext, ['pdf', 'PDF']) == -1) {
                alert('pdf 파일만 업로드 할수 있습니다.');
                $("#txtTeachingName").val('');
                $("#fileTeaching").val('');
                return false;
            } else {
                $("#txtTeachingName").val(fileNm.name);
            }
        } else {
            $("#txtTeachingName").val('');
            $('#fileTeaching').val('');
        }
    });

    $("#btnSaveNewsPaper").click(function () {
        var title = $("#txtTitle").val();
        var newscompany = $("#selNewspaperCompany").val();
        var subject = $("#selSubject").val();
        var newsdate = $("#dtDate").val();
        var column_file = $('#fileColumn')[0].files[0];
        var teaching_file = $('#fileTeaching')[0].files[0];

        if (!title || !title.trim()) {
            alert('표제를 입력하세요');
            $("#txtTitle").focus();
            return false;
        }

        if (!newscompany) {
            alert('신문사를 선택하세요');
            return false;
        }

        if (!subject) {
            alert('주제를 선택하세요');
            return false;
        }

        if (!newsdate) {
            alert('일자를 선택하세요');
            return false;
        }

        if (!column_file) {
            alert('칼럼파일을 등록하세요');
            return false;
        }

        if (!teaching_file) {
            alert('교안파일을 등록하세요');
            return false;
        }

        datas = new FormData();
        datas.append('action', 'newsPaperInsert');
        datas.append('user_no', userInfo.user_no);
        datas.append('franchise_idx', center_idx);
        datas.append('title', title);
        datas.append('newscompany', newscompany);
        datas.append('subject', subject);
        datas.append('newsdate', newsdate);
        datas.append('column_file', column_file);
        datas.append('teaching_file', teaching_file);

        $.ajax({
            url: '/center/ajax/newsPaperControll.ajax.php',
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
                    newspaperLoad();
                    $("#NewspaperModal").modal('hide');
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

    $("#btnUpdateNewsPaper").click(function () {
        var news_idx = $("#mdNewsIdx").val();
        var title = $("#txtTitle").val();
        var newscompany = $("#selNewspaperCompany").val();
        var subject = $("#selSubject").val();
        var newsdate = $("#dtDate").val();
        var column_file = $('#fileColumn')[0].files[0];
        var teaching_file = $('#fileTeaching')[0].files[0];

        var column_origin = $('#file_name1').val();
        var teaching_origin = $('#file_name2').val();

        if (!title || !title.trim()) {
            alert('표제를 입력하세요');
            $("#txtTitle").focus();
            return false;
        }

        if (!newscompany) {
            alert('신문사를 선택하세요');
            return false;
        }

        if (!subject) {
            alert('주제를 선택하세요');
            return false;
        }

        if (!newsdate) {
            alert('일자를 선택하세요');
            return false;
        }

        if (!column_file && !column_origin) {
            alert('칼럼파일을 등록하세요');
            return false;
        }

        if (!teaching_file && !teaching_origin) {
            alert('교안파일을 등록하세요');
            return false;
        }

        datas = new FormData();
        datas.append('action', 'newsPaperUpdate');
        datas.append('news_idx', news_idx);
        datas.append('title', title);
        datas.append('newscompany', newscompany);
        datas.append('subject', subject);
        datas.append('newsdate', newsdate);
        datas.append('column_file', column_file);
        datas.append('teaching_file', teaching_file);
        datas.append('column_origin', column_origin);
        datas.append('teaching_origin', teaching_origin);

        $.ajax({
            url: '/center/ajax/newsPaperControll.ajax.php',
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
                    newspaperLoad();
                    $("#NewspaperModal").modal('hide');
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

    $("#btnDeleteNewsPaper").click(function () {
        var news_idx = $("#mdNewsIdx").val();
        var column_origin = $('#file_name1').val();
        var teaching_origin = $('#file_name2').val();

        if (confirm("게시글을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/newsPaperControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'newsPaperDelete',
                    news_idx: news_idx,
                    column_origin: column_origin,
                    teaching_origin: teaching_origin
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        newspaperLoad();
                        $("#NewspaperModal").modal('hide');
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

    $("#newsPaperList").on('click', '.nt', function (e) {
        var targetClass = $(e.target).parents('.nt');
        var news_idx = targetClass.data('news-idx');

        if (targetClass.data('center-idx') == center_idx) {
            newspaperSelect(news_idx);
        }
    });
});

function newspaperLoad() {
    $.ajax({
        url: '/center/ajax/newsPaperControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'newspaperLoad'
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#newsPaperTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    displayLength: 10,
                    columns: [
                        {
                            data: 'no'
                        },
                        {
                            data: 'news_company'
                        },
                        {
                            data: 'news_subject'
                        },
                        {
                            data: 'newspaper_title',
                            className: "text-start"
                        },
                        {
                            data: 'writer'
                        },
                        {
                            data: 'news_date'
                        },
                        {
                            data: 'column_link'
                        },
                        {
                            data: 'teaching_link'
                        },
                        {
                            data: 'reg_date'
                        }
                    ],
                    createdRow: function (row, data) {
                        $(row).addClass('nt align-middle');
                        $(row).attr('data-news-idx', data.news_idx);
                        $(row).attr('data-center-idx', data.franchise_idx);
                        $("th").addClass("text-center align-middle")
                    },
                    order: [[0, 'desc']],
                    lengthChange: false,
                    info: false,
                    language: {
                        emptyTable: '데이터가 없습니다.',
                        zeroRecords: '데이터가 없습니다.',
                        search: '검색 ',
                        paginate: {
                            "next": ">", // 다음 페이지
                            "previous": "<" // 이전 페이지
                        }
                    }
                });
            } else {
                $('#newsPaperTable').DataTable().destroy();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function newspaperSelect(news_idx) {
    $.ajax({
        url: '/center/ajax/newsPaperControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'newspaperSelect',
            news_idx: news_idx
        },
        success: function (result) {
            if (result.success) {
                $("#mdNewsIdx").val(news_idx);
                $("#txtTitle").val(result.data.newspaper_title);
                $("#selNewspaperCompany option[value=" + result.data.news_code + "]").prop("selected", true);
                $("#selSubject option[value=" + result.data.column_code + "]").prop("selected", true);
                $("#dtDate").val(result.data.news_date);

                if (result.data.column_file) {
                    $("#originColumn").removeClass("d-none");
                    $("#originColumn").attr("href", "/files/newspaper/" + result.data.column_file);
                    $("#originColumn").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + result.data.column_origin);
                    $("#file_name1").val(result.data.column_file);
                } else {
                    $("#originColumn").addClass("d-none");
                    $("#originColumn").attr("href", "");
                    $("#file_name1").val('');
                }

                if (result.data.teaching_file) {
                    $("#originTeaching").removeClass("d-none");
                    $("#originTeaching").attr("href", "/files/newspaper/" + result.data.teaching_file);
                    $("#originTeaching").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + result.data.teaching_origin);
                    $("#file_name2").val(result.data.teaching_file);
                } else {
                    $("#originTeaching").addClass("d-none");
                    $("#originTeaching").attr("href", "");
                    $("#file_name2").val('');
                }

                $("#btnSaveNewsPaper").hide();
                $("#btnUpdateNewsPaper").show();
                $("#btnDeleteNewsPaper").show();
                $("#NewspaperModal").modal('show');
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function fileDelete(type) {
    var news_idx = $("#mdNewsIdx").val();
    var file_name = '';

    if(type == 'column'){
        file_name = $("#file_name1").val();
    }else{
        file_name = $("#file_name2").val();
    }

    $.ajax({
        url: '/center/ajax/newsPaperControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'fileDelete',
            news_idx: news_idx,
            type: type,
            file_name: file_name
        },
        success: function (result) {
            if (result.success) {
                alert(result.msg);
                if (type == 'column') {
                    $("#originColumn").addClass("d-none");
                    $("#originColumn").attr("href", "");
                    $("#file_name1").val('');
                } else {
                    $("#originTeaching").addClass("d-none");
                    $("#originTeaching").attr("href", "");
                    $("#file_name2").val('');
                }
            }else{
                alert(result.msg);
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}