$(document).ready(function () {
    reportLoad();

    $('#btnAddReport').click(function () {
        $.ajax({
            url: '/center/ajax/reportControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'settingCheck',
                user_no: userInfo.user_no,
                franchise_idx: center_idx
            },
            success: function (result) {
                if (result.success) {
                    $("#ReportModal").modal('show');
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

    $("#btnSettingSave").click(function () {
        var title1 = $("#txtTitle1").val();
        var title2 = $("#txtTitle2").val();
        var title3 = $("#txtTitle3").val();
        var title4 = $("#txtTitle4").val();
        var title5 = $("#txtTitle5").val();
        var title6 = $("#txtTitle6").val();
        var title7 = $("#txtTitle7").val();
        var title8 = $("#txtTitle8").val();
        var title9 = $("#txtTitle9").val();
        var title10 = $("#txtTitle10").val();

        $.ajax({
            url: '/center/ajax/reportControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'reportSettingInsert',
                user_no: userInfo.user_no,
                franchise_idx: center_idx,
                title1: title1,
                title2: title2,
                title3: title3,
                title4: title4,
                title5: title5,
                title6: title6,
                title7: title7,
                title8: title8,
                title9: title9,
                title10: title10
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#ReportSettingModal").modal('hide');
                    reportLoad();
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

    $("#btnSaveReport").click(function () {
        var title_target = $("input[name='title_val']");
        var title_arr = new Array();

        for (var i = 0; i < title_target.length; i++) {
            title_arr[i] = title_target[i].value;
        }

        var contents_target = $("textarea[name='contents_val']");
        var contents_arr = new Array();

        for (var i = 0; i < contents_target.length; i++) {
            contents_arr[i] = contents_target[i].value;
        }

        $.ajax({
            url: '/center/ajax/reportControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'reportInsert',
                user_no: userInfo.user_no,
                franchise_idx: center_idx,
                title_arr: title_arr,
                contents_arr: contents_arr
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#ReportModal").modal('hide');
                    reportLoad();
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

    $("#btnUpdateReport").click(function () {
        var report_idx = $("#report_idx").val();
        var contents_target = $("textarea[name='origin_contents_val']");
        var contents_arr = new Array();

        for (var i = 0; i < contents_target.length; i++) {
            contents_arr[i] = contents_target[i].value;
        }

        $.ajax({
            url: '/center/ajax/reportControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'reportUpdate',
                report_idx: report_idx,
                contents_arr: contents_arr
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#ReportViewModal").modal('hide');
                    reportLoad();
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

    $("#btnDeleteReport").click(function () {
        var report_idx = $("#report_idx").val();

        if(confirm("보고서를 삭제하시겠습니까?")){
            $.ajax({
                url: '/center/ajax/reportControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'reportDelete',
                    report_idx: report_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#ReportViewModal").modal('hide');
                        reportLoad();
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

    $("#reportList").on("click", ".tc", function (e) {
        reportSelect(e);
    });

    $("#dtMonths").change(function () {
        reportLoad();
    });

    $("#btnPrintReport").click(function () {
        frm = document.getElementById("printFrom");
        window.open('', 'viewer', 'width=1000, height=700');
        frm.action = "/common/reportprint.php";
        frm.target = "viewer";
        frm.method = "post";
        frm.submit();
    });
});

function reportLoad() {
    var user_no = '';

    if( (userInfo.user_id == 'admin') || (userInfo.is_admin == 'Y')){
        user_no = $("#selEmployee").val();
    }else{
        user_no = userInfo.user_no;
    }
    var months = $("#dtMonths").val();

    $.ajax({
        url: '/center/ajax/reportControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'reportLoad',
            user_no: user_no,
            franchise_idx: center_idx,
            months: months
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#dataTable').DataTable().destroy();
                $('#dataTable').DataTable({
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
                            data: 'user_name'
                        },
                        {
                            data: 'reg_date'
                        }
                    ],
                    order: [[0, 'desc']],
                    lengthChange: false,
                    info: false,
                    createdRow: function (row, data) {
                        $(row).addClass('tc align-middle');
                        $("td:eq(0)", row).addClass('text-center');
                        $("td:eq(1)", row).addClass('text-center');
                        $(row).attr('data-report-idx', data.report_idx);
                    },
                    language: {
                        emptyTable: '데이터가 없습니다.',
                        zeroRecords:'데이터가 없습니다.',
                        search: '검색 ',
                        paginate: {
                            "next": ">", // 다음 페이지
                            "previous": "<" // 이전 페이지
                        }
                    }
                });
            } else {
                $('#dataTable').DataTable().destroy();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function reportSelect(e) {
    var user_no = '';

    if( (userInfo.user_id == 'admin') || (userInfo.is_admin == 'Y')){
        user_no = $("#selEmployee").val();
    }else{
        user_no = userInfo.user_no;
    }
    var report_idx = $(e.target).parent('.tc').data('report-idx');
    $("#pre_data").html('');
    $("#report_data").html('');

    $.ajax({
        url: '/center/ajax/loadReport.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            user_no: user_no,
            franchise_idx: center_idx,
            report_idx: report_idx
        },
        success: function (result) {
            if (result.success) {
                $("#user_no").val(user_no);
                $("#report_idx").val(report_idx);
                $("#pre_data").html(result.data.pre_data);
                $("#report_data").html(result.data.report_data);
                $("#ReportViewModal").modal('show');
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