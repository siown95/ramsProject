$(document).ready(function () {
    bookStatusLoad();

    $("#btnBookBarcodePrint").click(function () {
        var form = document.getElementById("barcodeForm");
        form.submit();
    });

    $("#btnBookBarcodeCreate").click(function () {
        if (confirm("바코드 번호를 자동으로 부여하시겠습니까?\n자동으로 부여할 때 마지막 바코드 번호에서 1씩 증가하여 생성됩니다.")) {
            $.ajax({
                url: '/center/ajax/bookStatusControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'BookBarcodeCreate',
                    center_idx: center_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        bookStatusLoad();
                        return false;
                    }
                },
                error: function (request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                    return false;
                }
            });
        }
    });

    $('#txtBookStatusNo').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#bookStatusTable").on('page.dt', function () {
        $("#bookStatusTable > tbody > tr").removeClass('bg-light');
        setFormClear();
    });

    $("#bookStatusList").on("click", ".tc", function (e) {
        if ($(e.target).parents('.bg-light').length) {
            $(e.target).parent('.tc').removeClass('bg-light');
            setFormClear();
        } else {
            $('.tc').removeClass('bg-light');
            $(e.target).parent('.tc').addClass('bg-light');

            var status_idx = $(e.target).parent('.tc').data("status-idx");

            $.ajax({
                url: '/center/ajax/bookStatusControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'statusSelect',
                    status_idx: status_idx
                },
                success: function (result) {
                    if (result.success) {
                        $("#statusIdx").val(status_idx);
                        $("#txtBookStatusName").val(result.data.book_name);
                        $("#txtBookStatusNo").val(result.data.book_barcode);
                        $("#btnBookStatusSave").show();
                    }
                },
                error: function (request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }
    });

    $("#btnBookStatusSave").click(function () {
        var status_idx = $("#statusIdx").val();
        var book_barcode = $("#txtBookStatusNo").val();

        if (!book_barcode || !book_barcode.trim()) {
            alert('정확한 관리번호를 입력하세요.');
            return false;
        }

        $.ajax({
            url: '/center/ajax/bookStatusControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'statusUpdate',
                status_idx: status_idx,
                center_idx: center_idx,
                book_barcode: book_barcode
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    setFormClear();
                    bookStatusLoad();
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
});

function bookStatusLoad() {
    $.ajax({
        url: '/center/ajax/bookStatusControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'bookStatusLoad',
            center_idx: center_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#bookStatusTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [
                        {
                            data: 'no'
                        },
                        {
                            data: 'book_barcode'
                        },
                        {
                            data: 'book_name',
                            className: 'text-start'
                        },
                        {
                            data: 'teacher_name'
                        },
                        {
                            data: 'student_name'
                        },
                        {
                            data: 'last_rent_date'
                        },
                        {
                            data: 'last_return_date'
                        }
                    ],
                    createdRow: function (row, data) {
                        $("th").addClass('text-center align-middle');
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-status-idx', data.status_idx);
                    },
                    order: [[0, 'desc']],
                    lengthChange: false,
                    info: false,
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            } else {
                $('#bookStatusTable').DataTable().destroy();
                $("#bookStatusList").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function setFormClear() {
    $("#statusIdx").val('');
    $("#txtBookStatusName").val('');
    $("#txtBookStatusNo").val('');
    $("#btnBookStatusSave").hide();
}