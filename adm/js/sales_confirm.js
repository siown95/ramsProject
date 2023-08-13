$(document).ready(function () {
    salesInfoLoad();
    
    $("#dtSalesMonths").change(function () {
        salesInfoLoad();
    });
});

function salesInfoLoad() {
    var months = $("#dtSalesMonths").val();
    if (!months) {
        alert("매출년월을 선택해주세요.");
        return false;
    }
    $.ajax({
        url: '/adm/ajax/salesConfirmControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'salesInfoLoad',
            months: months
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#SalesInfoTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'center_name'
                    },
                    {
                        data: 'franchise_fee_ym'
                    },
                    {
                        data: 'franchise_fee_date'
                    },
                    {
                        data: 'franchise_fee_money'
                    },
                    {
                        data: 'refund_date'
                    },
                    {
                        data: 'refund_money'
                    },
                    {
                        data: 'franchise_fee_state'
                    }],
                    lengthChange: false,
                    searching: false,
                    info: false,
                    createdRow: function (row, data) {
                        $(row).addClass('tc text-center align-middle');
                        $("td:eq(4)", row).addClass('text-end align-middle');
                        $("td:eq(6)", row).addClass('text-end align-middle');
                        $("th").addClass('text-center align-middle');
                        $(row).attr('data-franchise-fee-idx', data.franchise_fee_idx);
                    },
                    language: {
                        url: "/json/ko_kr.json"
                    }
                });
            } else {
                $('#SalesInfoTable').DataTable().destroy();
                $('#SalesInfoList').empty();
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}