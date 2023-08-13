$(document).ready(function () {
    codeLoad(1);

    $("#codeTable1").on('page.dt', function () {
        clearTalble('2');
        clearTalble('3');
    });

    $("#codeTable2").on('page.dt', function () {
        clearTalble('3');
    });

    $('#Codeform1').on('propertychange change keyup paste input', '#txtCode1', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $('#Codeform2').on('propertychange change keyup paste input', '#txtCode2', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $('#Codeform2').on('propertychange change keyup paste input', '#txtCode3', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#degree1").on("click", ".emt1", function (e) {
        degreeClick(e, 1);
    });

    $("#degree2").on("click", ".emt2", function (e) {
        degreeClick(e, 2);
    });

    $("#degree3").on("click", ".emt3", function (e) {
        degreeClick(e, 3);
    });

    $("#chkCode3").click(function () {
        if ($("#chkCode3").is(':checked')) {
            $("#code3Div").show();
        } else {
            $("#code3Div").hide();
        }
    });

    $("#codeTable1").on('page.dt', function () {
        $("#codeTable1 > tbody > tr").removeClass('bg-light');
        clearTalble('2');
        clearTalble('3');
    });

    $("#codeTable2").on('page.dt', function () {
        $("#codeTable2 > tbody > tr").removeClass('bg-light');
        clearTalble('3');
    });

    $("#codeTable3").on('page.dt', function () {
        $("#codeTable3 > tbody > tr").removeClass('bg-light');
    });
});

//코드목록 선택
function degreeClick(e, degree) {
    var targetClass = $(e.target).parents('.emt' + degree);

    if ($(e.target).parent('.bg-light').length) {
        targetClass.removeClass('bg-light');
        if (degree == '1') {
            clearTalble('2');
            clearTalble('3');
        } else if (degree == '2') {
            clearTalble('3');
        }
    } else {
        $('.emt' + degree).removeClass('bg-light');
        targetClass.addClass('bg-light');

        if (degree == '1') {
            clearTalble('2');
            clearTalble('3');
            $("#codeNumber1").val(targetClass.find('td:eq(0)').text())
            codeLoad('2', targetClass.find('td:eq(0)').text());
        } else if (degree == '2') {
            var code_num1 = $("#codeNumber1").val();
            codeLoad('3', code_num1, targetClass.find('td:eq(0)').text());
        }
    }
}

//코드 불러오기
function codeLoad(degree, code_num1, code_num2) {
    $.ajax({
        url: '/center/ajax/codeControll.ajax.php',
        dataType: 'json',
        type: 'POST',

        data: {
            action: 'codeLoad',
            degree: degree,
            code_num1: code_num1,
            code_num2: code_num2
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#codeTable' + degree).DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    displayLength: 10,
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    stripeClasses: [],
                    columns: [{
                        data: 'code_num'
                    },
                    {
                        data: 'code_name'
                    }
                    ],
                    createdRow: function (row, data) {
                        $("th").addClass('text-center align-middle');
                        $(row).addClass('emt' + degree);
                        $(row).attr('data-necessary', data.necessary);
                        $(row).attr('data-code-num', data.code_num);
                    },
                    lengthChange: false,
                    info: false,
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });

            } else {
                $('#codeTable' + degree).DataTable().destroy();
                $('#degree' + degree).empty();
            }
        }
    });
}

function clearTalble(idx) {
    $("#codeTable" + idx).DataTable().destroy();
    $("#degree" + idx).empty();
}