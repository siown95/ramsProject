$(document).ready(function () {
    bookLoad();

    $("#dataTable").on("click", ".tc", function (e) {
        bookRequestSelect(e);
    });

    $('#book_isbn').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#btnBookRequestSave").click(function () {
        bookRequestUpdate();
    });

    $("#btnExpireDelete").click(function () {
        bookRequestExpireDelete();
    });
});

function bookLoad() {
    $.ajax({
        url: '/adm/ajax/bookRequestControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'loadBookRequest',
        },
        success: function (result) {
            if (result.success) {
                $('#dataTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: '<"col-sm-12"f>t<"col-sm-12"p>',
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'request_book_isbn'
                    },
                    {
                        data: 'request_book_name',
                        className: 'text-start'
                    },
                    {
                        data: 'request_book_writer'
                    },
                    {
                        data: 'request_book_publisher'
                    },
                    {
                        data: 'detail'
                    },
                    {
                        data: 'center_name'
                    },
                    {
                        data: 'request_state'
                    },
                    {
                        data: 'reg_date'
                    }
                    ],
                    createdRow: function (row, data) {
                        $(row).addClass('text-center align-middle tc');
                        $("th").addClass('text-center align-middle tc');
                        $(row).attr('data-request-idx', data.request_idx);
                    },
                    lengthChange: false,
                    info: false,
                    searching: true,
                    order: [[0, "desc"]],
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
                return false;
            } else {

                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

//도서 카테고리
function bookCategoryChange(category, position) {
    var category1 = '';
    var category2 = '';
    if (position == '2') {
        category1 = category;
    } else {
        category1 = $("#category1").val();
        category2 = category;
    }

    $.ajax({
        url: '/adm/ajax/bookCategoryArray.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            category1: category1,
            category2: category2,
            position: position
        },
        success: function (result) {
            if (result.success) {
                $("#category" + position).html(result.data);
                if (position == '2') {
                    $("#category3").html("<option value=\"\">선택</option>");
                }
                return false;
            } else {

                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function bookRequestSelect(e) {
    var requestTarget = $(e.target).parents('tr').data('request-idx');

    $.ajax({
        url: '/adm/ajax/bookRequestControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'bookRequestSelect',
            requestTarget: requestTarget
        },
        success: function (result) {
            if (result.success && result.data) {
                setForm(result.data);
                $("#requestTarget").val(requestTarget);
                $("#BookModal").modal('show');
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

function bookRequestUpdate() {
    var requestTarget = $("#requestTarget").val();
    var book_name = $("#book_name").val();
    var book_isbn = $("#book_isbn").val();
    var book_writer = $("#book_writer").val();
    var book_publisher = $("#book_publisher").val();
    var book_category1 = $("#category1").val();
    var book_category2 = $("#category2").val();
    var book_category3 = $("#category3").val();
    var state = $("input[name='request_state']:checked").val();
    var memo = $("#request_memo").val();

    if (!book_name || !book_name.trim()) {
        alert('교재 이름을 입력하세요.');
        $("#book_name").focus();
        return false;
    }

    if (!book_isbn || !book_isbn.trim()) {
        alert('도서 ISBN을 입력하세요');
        return false;
    }

    if (book_isbn.length != 10 && book_isbn.length != 13) {
        alert('정확한 ISBN을 입력하세요');
        return false;
    }

    if (!book_writer || !book_writer.trim()) {
        alert('저자를 입력하세요.');
        $("#book_writer").focus();
        return false;
    }

    if (!book_publisher || !book_publisher.trim()) {
        alert('출판사를 입력하세요.');
        $("#book_publisher").focus();
        return false;
    }

    if (!book_category1) {
        alert('1차 카테고리를 선택하세요.');
        return false;
    }

    if (!book_category2) {
        alert('2차 카테고리를 선택하세요.');
        return false;
    }

    if (!book_category3) {
        alert('3차 카테고리를 선택하세요.');
        return false;
    }

    if (!state) {
        alert('요청상태를 선택하세요.');
        return false;
    }

    $.ajax({
        url: '/adm/ajax/bookRequestControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'bookRequestUpdate',
            requestTarget: requestTarget,
            book_name: book_name,
            book_isbn: book_isbn,
            book_writer: book_writer,
            book_publisher: book_publisher,
            book_category1: book_category1,
            book_category2: book_category2,
            book_category3: book_category3,
            state: state,
            memo: memo,
        },
        success: function (result) {
            if (result.success) {
                alert(result.msg);
                $("#BookModal").modal('hide');
                location.reload();
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

function bookRequestExpireDelete() {
    var expire_day = $("#selExpireDays").val();
    if (confirm("요청일로부터 " + (expire_day / 30) + "개월(" + expire_day + "일)이 경과한 데이터를 삭제하시겠습니까?\n삭제 후 데이터는 복원되지 않습니다.")) {
        $.ajax({
            url: '/adm/ajax/bookRequestControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'bookRequestExpireDelete',
                expire_day: expire_day
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    location.reload();
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
}

function setForm(data) {
    $("#book_name").val(data.request_book_name);
    $("#book_isbn").val(data.request_book_isbn);
    $("#book_writer").val(data.request_book_writer);
    $("#book_publisher").val(data.request_book_publisher);
    $("#category1 option[value=" + data.request_book_category1 + "]").prop("selected", true);
    bookCategoryChange(data.request_book_category1, '2');
    bookCategoryChange(data.request_book_category2, '3');
    $("#category2 option[value=" + data.request_book_category2 + "]").prop("selected", true);
    $("#category3 option[value=" + data.request_book_category3 + "]").prop("selected", true);

    if (data.request_state == '01' || data.request_state == '09') {
        $("input:radio[name ='request_state']:input[value='" + data.request_state + "']").prop('checked', true);
        $("#btnBookRequestSave").hide();
    } else {
        $("input:radio[name='request_state']").prop('checked', false);
        $("#btnBookRequestSave").show();
    }
}