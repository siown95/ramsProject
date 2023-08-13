$(document).ready(function () {
    curriculum_load();

    $("#curriculumInfoTable").on('page.dt', function () {
        $("#curriculumInfoTable > tbody > tr").removeClass('bg-light');
        clearFormData();
    });

    $('#txtOrder').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#txtBookName").click(function () {
        $('#btnBookSearch').trigger('click');
    });

    $('#btnBookSearch').click(function () {
        $('#BookModal').modal('show');
        $('#txtSearchBookName').val($('#txtBookName').val());
    });

    $("#BookModal").keypress(function (e) {
        if (e.keyCode === 13) {
            book_search();
        }
    });

    $("#bookSearchList").on("click", ".booktc", function (e) {
        book_select(e);
    });

    $("#btnSaveCurriculum").click(function () {
        var book_idx = $("#SearchBookNo").val();
        var odr = $("#txtOrder").val();
        var month = $("#sel_month").val();
        var grade = $("#sel_grade").val();
        var curriculum_idx = $("#curriculum_idx").val();
        var action = '';

        if (!book_idx) {
            alert('교재를 선택하세요');
            return false;
        }

        if (!odr) {
            alert('순서를 지정 하세요');
            return false;
        }

        if (odr == '0') {
            alert('0 이상의 수를 입력하세요');
            return false;
        }

        if (month == '00') {
            alert('커리큘럼 월을 지정 하세요');
            return false;
        }

        if (grade == '00') {
            alert('커리큘럼 학년을 지정 하세요');
            return false;
        }

        if (curriculum_idx) {
            action = 'curriculumUpadte';
        } else {
            action = 'curriculumInsert';
        }

        $.ajax({
            url: '/center/ajax/curriculumControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: action,
                curriculum_idx: curriculum_idx,
                book_idx: book_idx,
                franchise_idx: franchise_idx,
                odr: odr,
                month: month,
                grade: grade
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    clearFormData();
                    curriculum_load();
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

    $("#btnDeleteCurriculum").click(function () {
        var curriculum_idx = $("#curriculum_idx").val();

        if (!curriculum_idx) {
            alert('커리큘럼을 선택 후 삭제해주세요.');
            return false;
        }

        if (confirm('커리큘럼을 삭제하시겠습니까?')) {
            $.ajax({
                url: '/center/ajax/curriculumControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'curriculumDelete',
                    curriculum_idx: curriculum_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        clearFormData();
                        curriculum_load();
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

    $("#sel_month, #sel_grade").on("change", function () {
        $("#curriculum_idx").val('');
        $("#SearchBookNo").val('');
        $("#txtBookName").val('');
        $("#txtOrder").val('');
        $("#btnDeleteCurriculum").hide();
        curriculum_load();
    });

    $("#curriculumInfoList").on("click", ".tc", function (e) {
        curriculum_select(e);
    });

    $("#btnBatchCurriculum").click(function () {
        var month = $("#sel_month").val();
        var grade = $("#sel_grade").val();

        if (confirm('커리큘럼 배치시 기존 센터에 등록된 커리큘럼이 모두 삭제됩니다.\n본사 지정 커리큘럼을 내려받으시겠습니까?')) {
            $.ajax({
                url: '/center/ajax/curriculumControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'curriculumBatch',
                    month: month,
                    grade: grade,
                    franchise_idx: franchise_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        clearFormData();
                        curriculum_load();
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

    $("#btnClearCurriculum").click(function () {
        $("#setting-form")[0].reset();
        $("#SearchBookNo").val('');
        $("#curriculum_idx").val('');
        curriculum_load();
    });
});

function book_search() {
    var book_name = $('#txtSearchBookName').val();

    if (!book_name || !book_name.trim()) {
        alert('검색어를 입력하세요');
        return false;
    }

    $.ajax({
        url: '/center/ajax/bookControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'bookSearch',
            book_name: book_name
        },
        success: function (result) {
            if (result.success) {
                $('#bookSearchTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: '<"col-sm-12"f>t<"col-sm-12"p>',
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'book_isbn'
                    },
                    {
                        data: 'book_name',
                        className: 'text-start'
                    },
                    {
                        data: 'book_writer'
                    },
                    {
                        data: 'book_publisher'
                    },
                    {
                        data: 'detail'
                    }
                    ],
                    createdRow: function (row, data) {
                        $(row).addClass('align-middle booktc');
                        $(row).attr('data-book-idx', data.book_idx);
                    },
                    lengthChange: false,
                    info: false,
                    searching: false,
                    order: [[0, 'desc']],
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
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

function book_select(e) {
    var book_idx = $(e.target).parents(".booktc").data('book-idx');
    var book_name = $(e.target).parents(".booktc").find("td:eq(2)").text();

    $('#txtBookName').val(book_name);
    $("#SearchBookNo").val(book_idx);
    $('#BookModal').modal('hide');

    $('#bookSearchTable').DataTable().destroy();
    $("#bookSearchList").empty();
}

function curriculum_load() {
    var month = $("#sel_month").val();
    var grade = $("#sel_grade").val();

    $.ajax({
        url: '/center/ajax/curriculumControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'curriculumLoad',
            month: month,
            grade: grade,
            franchise_idx: franchise_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#curriculumInfoTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: '<"col-sm-12"f>t<"col-sm-12"p>',
                    columns: [{
                        data: 'months'
                    },
                    {
                        data: 'code_name'
                    },
                    {
                        data: 'orders'
                    },
                    {
                        data: 'book_name',
                        className: 'text-start'
                    },
                    {
                        data: 'book_writer'
                    },
                    {
                        data: 'book_publisher'
                    }
                    ],
                    createdRow: function (row, data) {
                        $(row).addClass('align-middle tc');
                        $(row).attr('data-curriculum-idx', data.curriculum_idx);
                        $(row).attr('data-book-idx', data.book_idx);
                        $("th").addClass('text-center align-middle');
                    },
                    lengthChange: false,
                    info: false,
                    searching: false,
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            } else {
                $('#curriculumInfoTable').DataTable().destroy();
                $("#curriculumInfoList").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function curriculum_select(e) {
    var targetClass = $(e.target).parents(".tc");

    if ($(e.target).parents('.bg-light').length) {
        targetClass.removeClass('bg-light');
        clearFormData();
    } else {
        var curriculum_idx = targetClass.data('curriculum-idx');
        var book_idx = targetClass.data('book-idx');
        var months = targetClass.find("td:eq(0)").text();
        var grade = targetClass.find("td:eq(1)").text();
        var orders = targetClass.find("td:eq(2)").text();
        var book_name = targetClass.find("td:eq(3)").text();

        $('.tc').removeClass('bg-light');
        targetClass.addClass('bg-light');

        $("#curriculum_idx").val(curriculum_idx);
        $("#SearchBookNo").val(book_idx);
        $("#sel_month option[value=" + months + "]").prop("selected", true);
        $("#sel_grade option:contains(" + grade + ")").prop('selected', true);
        $("#txtBookName").val(book_name);
        $("#txtOrder").val(orders);
        $("#btnDeleteCurriculum").show();
    }
}

function clearFormData() {
    $("#curriculum_idx").val('');
    $("#SearchBookNo").val('');
    $("#sel_grade option:first").prop('selected', true);
    $("#txtBookName").val('');
    $("#txtOrder").val('');
    $("#btnDeleteCurriculum").hide();
}