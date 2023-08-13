$(document).ready(function () {
    getWeekYearData();

    $("#selYear").change(function () {
        getWeekData();
    });

    $("#dataTable").on('page.dt', function () {
        $('.tc').removeClass('bg-light');
    });

    $("#btnSave").click(function () {
        var week_idx = $("#week_idx").val();
        var weekyear = $("#txtCurriculumYear").val();
        var weekmonth = $("#txtCurriculumMonth").val();
        var weekcount = $("#txtCurriculumWeek").val();
        var weekstartdate = $("#txtCurriculumFromDate").val();
        var weekenddate = $("#txtCurriculumToDate").val();
        var weekdetail = $("#txtWeekDetail").val();
        if (!week_idx) {
            if (!weekyear) {
                alert('주차해당연도를 입력해주세요.');
                $("#txtCurriculumYear").focus();
                return false;
            }
            if (!weekmonth) {
                alert('주차해당월을 입력해주세요.');
                $("#txtCurriculumMonth").focus();
                return false;
            }
            if (!weekcount) {
                alert('주차를 입력해주세요.');
                $("#txtCurriculumWeek").focus();
                return false;
            }
            if (!weekstartdate) {
                alert('주차시작일을 입력해주세요.');
                $("#txtCurriculumFromDate").focus();
                return false;
            }
            if (!weekenddate) {
                alert('주차종료일을 입력해주세요.');
                $("#txtCurriculumToDate").focus();
                return false;
            }

            $.ajax({
                url: '/adm/ajax/weekControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'insertWeekData',
                    weekyear: weekyear,
                    weekmonth: weekmonth,
                    weekcount: weekcount,
                    weekstartdate: weekstartdate,
                    weekenddate: weekenddate,
                    weekdetail: weekdetail
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        getWeekData();
                        $("#weekForm")[0].reset();
                        $("#week_idx").val('');
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
        } else {
            $.ajax({
                url: '/adm/ajax/weekControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'updateWeekData',
                    week_idx: week_idx,
                    weekyear: weekyear,
                    weekmonth: weekmonth,
                    weekcount: weekcount,
                    weekstartdate: weekstartdate,
                    weekenddate: weekenddate,
                    weekdetail: weekdetail
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        getWeekData();
                        $("#weekForm")[0].reset();
                        $("#week_idx").val('');
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

    $("#btnDelete").click(function () {
        var week_idx = $("#week_idx").val();
        if (!week_idx) {
            alert("삭제하려는 주차를 선택해주세요.");
            return false;
        } else {
            $.ajax({
                url: '/adm/ajax/weekControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'deleteWeekData',
                    week_idx: week_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        getWeekData();
                        $("#weekForm")[0].reset();
                        $("#week_idx").val('');
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

    $('#btnCreate').click(function () {
        if ($('#dtCurriculumWeek').val() == '') {
            alert('주차의 시작일을 선택해주세요.');
            $('#dtCurriculumWeek').focus();
            return false;
        }
        $.ajax({
            dataType: 'JSON',
            type: 'POST',
            url: 'calc_week.php',
            data: {
                week: $('#dtCurriculumWeek').val()
            },
            success: function (data) {
                if (data) {
                    if (data.msg) {
                        alert(data.msg);
                        return false;
                    }
                    getWeekData();
                    $("#selYear").append('<option value="' + $('#dtCurriculumWeek').val().substring(0, 4) + '">' + $('#dtCurriculumWeek').val().substring(0, 4) + '</option>');
                }
                return false;
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                return false;
            }
        });
    });

    $("#dataTable > tbody").on("click", ".tc", function (e) {
        var targetClass = $(e.target).parents("tr");
        if ($(e.target).parent('.bg-light').length) {
            targetClass.removeClass('bg-light');
            $("#weekForm")[0].reset();
            $("#week_idx").val('');
        } else {
            $('.tc').removeClass('bg-light');
            targetClass.addClass('bg-light');
            var week_idx = targetClass.data("week_idx");

            $.ajax({
                url: '/adm/ajax/weekControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'selectWeekData',
                    week_idx: week_idx
                },
                success: function (result) {
                    if (result.success && result.data) {
                        setFormData(result.data.week_data);
                    } else {
                        return false;
                    }
                },
                error: function (request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }

    });

    $('input:text[numberOnly]').on('propertychange change keyup paste', function () {
        $(this).val($(this).val().replace(/[^0-9]/g, ""));
    });
});

function getWeekYearData() {
    $.ajax({
        url: '/adm/ajax/weekControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getWeekYearData'
        },
        success: function (result) {
            if (result.success && result.data) {
                $("#selYear").html(result.data.data);
                getWeekData();
            } else {
                alert(result.msg);
                $("#selYear").empty();
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function getWeekData() {
    var year = $("#selYear").val();

    $.ajax({
        url: '/adm/ajax/weekControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getWeekData',
            year: year
        },
        success: function (result) {
            if (result.success) {
                $('#dataTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    columns: [
                        {
                            data: 'weekYear'
                        },
                        {
                            data: 'weekMonth'
                        },
                        {
                            data: 'weekCount'
                        },
                        {
                            data: 'weekStartDate'
                        },
                        {
                            data: 'weekEndDate'
                        },
                        {
                            data: 'weekDetail'
                        },
                    ],
                    createdRow: function (row, data) {
                        $(row).addClass('align-middle tc');
                        $(row).attr('data-week_idx', data.week_idx);
                    },
                    lengthChange: false,
                    info: false,
                    order: [[0, 'asc'], [1, 'asc'], [2, 'asc']],
                    dom: '<"col-sm-12"f>t<"col-sm-12"p>',
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

function setFormData(data) {
    $("#week_idx").val(data.week_idx);
    $("#txtCurriculumYear").val(data.weekYear);
    $("#txtCurriculumMonth").val(data.weekMonth);
    $("#txtCurriculumWeek").val(data.weekCount);
    $("#txtCurriculumFromDate").val(data.weekStartDate);
    $("#txtCurriculumToDate").val(data.weekEndDate);
    $("#txtWeekDetail").val(data.weekDetail);
}