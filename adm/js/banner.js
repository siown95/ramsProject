$(document).ready(function () {
    loadBanner();

    $('#fileBannerImage').change(function () {
        imgFileCheck($(this).val());
    });

    $("#btnSave").click(function () {
        bannerInsert();
    });

    $("#dataTable > tbody").on("click", ".delbanner", function () {
        var banner_idx = $(this).parents("tr").find("td:eq(0)").text();
        var file_name = $(this).parents("tr").find("td:eq(3) > img").data('img-name');

        if (confirm('배너를 삭제하시겠습니까?')) {
            $.ajax({
                url: '/adm/ajax/bannerControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'bannerDelete',
                    banner_idx: banner_idx,
                    file_name: file_name
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        location.reload();
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

    $("#dataTable > tbody").on("click", ".editbanner", function () {
        var banner_idx = $(this).parents("tr").find("td:eq(0)").text();
        var flag = $(this).parents("tr").data('vyn');

        $.ajax({
            url: '/adm/ajax/bannerControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'bannerUpdate',
                banner_idx: banner_idx,
                flag: flag
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    location.reload();
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

    $('input:text[numberOnly]').on('propertychange change keyup paste', function () {
        $(this).val($(this).val().replace(/[^0-9]/g, ""));
    });
});

function loadBanner() {
    $.ajax({
        url: '/adm/ajax/bannerControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'loadBanner'
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#dataTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'banner_idx'
                    },
                    {
                        data: 'from_date'
                    },
                    {
                        data: 'to_date'
                    },
                    {
                        data: 'banner_image'
                    },
                    {
                        data: 'banner_link'
                    },
                    {
                        data: 'orders'
                    },
                    {
                        data: 'mainYn'
                    },
                    {
                        data: 'edit_btn'
                    },
                    {
                        data: 'del_btn'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    order: [[5, 'asc'], [6, 'asc']],
                    createdRow: function (row, data) {
                        $(row).attr('data-vyn', data.banner_visible);
                        $("td:eq(0)", row).addClass('text-center align-middle');
                        $("td:eq(1)", row).addClass('text-center align-middle');
                        $("td:eq(2)", row).addClass('text-center align-middle');
                        $("td:eq(3)", row).addClass('text-start align-middle');
                        $("td:eq(4)", row).addClass('text-start align-middle');
                        $("td:eq(5)", row).addClass('text-center align-middle');
                        $("td:eq(6)", row).addClass('text-center align-middle');
                        $("td:eq(7)", row).addClass('text-center align-middle');
                        $("td:eq(8)", row).addClass('text-center align-middle');
                        $("th").addClass('text-center align-middle');
                    },
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function bannerInsert() {
    var banner_link = $("#txtBannerLink").val();
    var from_date = $("#dtFromDate").val();
    var to_date = $("#dtToDate").val();
    var banner_visible = $("#chkUseYn").is(":checked") ? 'Y' : '';
    var mainYn = $("#chkMainYn").is(":checked") ? 'Y' : '';
    var orders = $("#txtOrders").val();
    var banner_image = $('#txtImageName').val();

    if (!banner_link || !banner_link.trim()) {
        alert("링크 주소를 입력해주세요.");
        $("#txtBannerLink").focus();
        return false;
    } else if (!from_date) {
        alert("시작일을 선택해주세요.");
        $("#dtFromDate").focus();
        return false;
    } else if (to_date != "") {
        if (from_date > to_date) {
            alert("시작일 또는 종료일이 잘못되었습니다.");
            return false;
        }
    } else if (!orders) {
        alert("배너 노출 순서를 입력해주세요.");
        $("#txtOrders").focus();
        return false;
    } else if (Number(orders) > 10) {
        alert("배너 노출은 최대 10개까지 가능합니다. 기존 항목을 제거 후 사용해주세요.");
        $("#txtOrders").focus();
        return false;
    } else if (!banner_image) {
        alert("배너 이미지를 첨부해주세요.");
        return false;
    }

    datas = new FormData();

    datas.append('banner_link', banner_link);
    datas.append('from_date', from_date);
    datas.append('to_date', to_date);
    datas.append('banner_visible', banner_visible);
    datas.append('mainYn', mainYn);
    datas.append('orders', orders);
    datas.append('banner_image', banner_image);
    datas.append('file1', $('#fileBannerImage')[0].files[0]);
    var action = 'bannerInsert';

    datas.append('action', action);

    $.ajax({
        url: '/adm/ajax/bannerControll.ajax.php',
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
            } else {
                alert(result.msg);
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function imgFileCheck(fileValue) {
    let fileNm = document.getElementById('fileBannerImage').files[0].name;
    let fileExt = /(.*?)\.(jpg|jpeg|png|gif|bmp)$/;

    if (!fileValue.match(fileExt)) {
        alert("지원되지 않는 확장자입니다.");
        $("#fileBannerImage").val("");
        return false;
    } else {
        $('#txtImageName').val(fileNm);
    }
}