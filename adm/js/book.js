$(document).ready(function () {
    bookLoad();
    bookCategoryCnt();

    $("#searchValue").keypress(function (e) {
        if (e.keyCode === 13) {
            bookLoad();
        }
    });

    $("#btnAdd").click(function () {
        $("#book_id").val('');
        $('#bookForm')[0].reset();
        $('#btnBookDelete').hide();
        $('#BookModal').modal('show');
    });

    $("#dataTable").on("click", ".tc", function (e) {
        bookSelect(e);
    });

    $('#book_isbn').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#btnBookDelete").click(function () {
        var book_idx = $("#book_id").val();

        if (confirm("교재를 삭제하시겠습니까?")) {
            $.ajax({
                url: '/adm/ajax/bookControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',

                data: {
                    action: 'bookDelete',
                    book_idx: book_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        bookLoad();
                        $('#BookModal').modal('hide');
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
});

function bookLoad() {
    var searchType = $("#selSearchingType").val();
    var searchValue = $("#searchValue").val();
    searchType = encodeURI(searchType);
    searchValue = encodeURI(searchValue);
    $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/adm/ajax/bookListLoad.ajax.php?searchValue=' + searchValue + '&searchType=' + searchType,
            type: 'POST',
            dataSrc: function (res) {
                let data = res.data;
                return data;
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                $('#dataTable').DataTable().destroy();
            }
        },
        autoWidth: false,
        destroy: true,
        searching: false,
        // search: { regex: true },
        // searchCols: [
        //     { regex: true },
        //     { regex: true },
        //     { regex: true },
        //     { regex: true },
        //     { regex: true },
        //     { regex: true },
        // ],
        stripeClasses: [],
        columns: [
            {
                data: 'book_isbn'
            },
            {
                data: 'book_name',
                className: 'text-start'
            },
            {
                data: 'book_publisher'
            },
            {
                data: 'book_writer'
            },
            {
                data: 'book_category'
            },
            {
                data: 'reg_date'
            }
        ],
        createdRow: function (row, data) {
            $(row).addClass('tc');
            $(row).attr('data-book-id', data.book_idx);
            $("th").addClass('text-center align-middle');
        },
        order: [[1, "asc"]],
        lengthChange: false,
        info: false,
        language: {
            url: "/json/ko_kr.json",
        },
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

//Form 데이터 체크
function bookFormData() {
    var origin_book_id = $("#book_id").val();
    var action = '';
    var dataset = '';
    var book_name = $("#book_name").val();
    var book_isbn = $("#book_isbn").val();
    var book_writer = $("#book_writer").val();
    var book_publisher = $("#book_publisher").val();
    var book_category1 = $("#category1").val();
    var book_category2 = $("#category2").val();
    var book_category3 = $("#category3").val();

    if (!origin_book_id) {
        action = 'bookInsert';
    } else {
        action = 'bookUpdate';
    }

    if (!book_name || !book_name.trim()) {
        alert('교재 이름을 입력하세요.');
        $("#book_name").focus();
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

    dataset = {
        action: action,
        book_name: book_name,
        book_isbn: book_isbn,
        book_writer: book_writer,
        book_publisher: book_publisher,
        book_category1: book_category1,
        book_category2: book_category2,
        book_category3: book_category3
    }

    if (origin_book_id) {
        dataset.origin_book_id = origin_book_id;
    }

    bookAjax(dataset);
}

//Ajax 통신 함수
function bookAjax(data) {
    $.ajax({
        url: '/adm/ajax/bookControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: data,
        success: function (result) {
            if (result.success) {
                alert(result.msg);
                bookLoad();
                bookCategoryCnt();
                $('#BookModal').modal('hide');
                $('#bookForm')[0].reset();
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

function bookCategoryCnt() {
    $.ajax({
        url: '/adm/ajax/bookControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',

        data: {
            action: 'bookCategoryCnt'
        },
        success: function (result) {
            if (result.success) {
                $("#bookCategoryTable").html(result.data.tbl);
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

function bookSelect(e) {
    var book_id = $(e.target).parent('.tc').data('book-id');

    $.ajax({
        url: '/adm/ajax/bookControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',

        data: {
            action: 'bookSelect',
            book_idx: book_id
        },
        success: function (result) {
            if (result.success && result.data) {
                setForm(result.data);
                $('#BookModal').modal('show');
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

function setForm(data) {
    $("#book_id").val(data.book_idx);
    $("#book_name").val(data.book_name);
    $("#book_isbn").val(data.book_isbn);
    $("#book_writer").val(data.book_writer);
    $("#book_publisher").val(data.book_publisher);
    $("#category1 option[value=" + data.book_category1 + "]").prop("selected", true);
    bookCategoryChange(data.book_category1, '2');
    bookCategoryChange(data.book_category2, '3');
    $("#category2 option[value=" + data.book_category2 + "]").prop("selected", true);
    $("#category3 option[value=" + data.book_category3 + "]").prop("selected", true);
    $('#btnBookDelete').show();
}