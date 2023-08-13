$(document).ready(function () {
    $('.emt').click(function (e) {
        var targetClass = $(e.target).parents('.emt');

        if ($(e.target).parents('.bg-light').length) {
            targetClass.removeClass('bg-light');
            $('#dataTable2').DataTable().destroy();
            $("#commute_log").empty();
        } else {
            var user_no = targetClass.data('user-no');
            var now_date = $("#now_date").val();

            $('.emt').removeClass('bg-light');
            targetClass.addClass('bg-light');
            commuteSelect(user_no, now_date);
        }
    });

    $("#now_date").on("change", function(){
        var now_date = $("#now_date").val();
        var user_no = $("#dataTable1").find(".emt.bg-light").data('user-no');
        
        commuteSelect(user_no, now_date);
    });

    $("#dataTable1").DataTable({
        autoWidth: false,
        stripeClasses: [],
        dom: '<"col-sm-12"f>t<"col-sm-12"p>',
        lengthChange: false,
        info: false,
        order: [[2, 'asc']],
        columnDefs: [{
            targets: [0, 1],
            className: 'dt-head-center'
        }],
        language: {
            emptyTable: '데이터가 없습니다.',
            zeroRecords:'데이터가 없습니다.',
            search: '',
            searchPlaceholder: '검색',
            paginate: {
                "next": ">", // 다음 페이지
                "previous": "<" // 이전 페이지
            }
        }
    });
});

function commuteSelect(user_no, now_date) {
    $.ajax({
        url: '/adm/ajax/commuteControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            action: 'commuteSelect',
            user_no: user_no,
            now_date: now_date
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#dataTable2').DataTable({
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
                        $(row).addClass('align-middle text-center');
                        $("th").addClass('align-middle text-center');
                    },
                    lengthChange: false,
                    info: false,
                    searching: false,
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });
            } else {
                $('#dataTable2').DataTable().destroy();
                $("#commute_log").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}