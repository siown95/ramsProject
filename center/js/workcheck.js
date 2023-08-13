$(document).ready(function () {
    $('.emt').click(function (e) {
        var targetClass = $(e.target).parents('.emt');

        if ($(e.target).parents('.bg-light').length) {
            targetClass.removeClass('bg-light');
            $('#commuteLogTable').DataTable().destroy();
            $("#commuteLogList").empty();
        } else {
            var user_no = targetClass.data('user-no');
            var now_date = $("#now_date").val();

            $('.emt').removeClass('bg-light');
            targetClass.addClass('bg-light');
            commuteSelect(user_no, now_date);
        }
    });

    $("#workCheckTable").on('page.dt', function () {
        $("#workCheckTable > tbody > tr").removeClass('bg-light');
        $('#commuteLogTable').DataTable().destroy();
        $("#commuteLogList").empty();
    });

    $("#now_date").on("change", function () {
        var now_date = $("#now_date").val();
        var user_no = $("#workCheckTable").find(".emt.bg-light").data('user-no');

        commuteSelect(user_no, now_date);
    });

    $("#workCheckTable").DataTable({
        autoWidth: false,
        stripeClasses: [],
        dom: '<"col-sm-12"f>t<"col-sm-12"p>',
        lengthChange: false,
        info: false,
        order: [[2, 'asc']],
        createdRow: function () {
            $("th").addClass('text-center align-middle');
        },
        language: {
            url: "/json/ko_kr.json",
        }
    });
});

function commuteSelect(user_no, now_date) {
    $.ajax({
        url: '/center/ajax/commuteControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'commuteSelect',
            user_no: user_no,
            center_idx: center_idx,
            now_date: now_date
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#commuteLogTable').DataTable({
                    autoWidth: false,
                    destroy: true,

                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [
                        {
                            data: 'date'
                        },
                        {
                            data: 'in_time'
                        },
                        {
                            data: 'out_time'
                        },
                        {
                            data: 'break_time'
                        },
                        {
                            data: 'work_time'
                        },
                        {
                            data: 'break_day'
                        }
                    ],
                    createdRow: function (row) {
                        $(row).addClass('text-center align-middle');
                        $("th").addClass('text-center align-middle');
                    },
                    lengthChange: false,
                    info: false,
                    searching: false,
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });
            } else {
                $('#commuteLogTable').DataTable().destroy();
                $("#commuteLogList").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}