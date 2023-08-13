$(document).ready(function () {
    bookLoadAjax();

    $("#searchValue").keypress(function (e) {
        if (e.keyCode === 13) {
            bookLoadAjax();
        }
    });

    $('#rbook_isbn').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#btnRequestList").click(function(){
        bookRequestLoad();
    });

    $("#bookManageTable").on("click", ".tc", function (e) {
        bookSelect(e);
    });

    $("#bookManageTable").on('page.dt', function () {
        clearForm();
    });

    $('#txtBookCount').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#btnSaveBookManage").click(function () {
        var book_idx = $("#bookManageIdx").val();
        var amount = $("#txtBookCount").val();

        if (!book_idx) {
            alert("도서를 선택해주세요");
            return false;
        }

        if (!amount || !amount.trim() || amount == '0') {
            alert('정확한 수량을 입력하세요.');
            return false;
        }

        $.ajax({
            url: '/center/ajax/bookControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'bookStatus',
                amount: amount,
                book_idx: book_idx,
                franchise_idx: franchise_idx
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    clearForm();
                    bookLoadAjax();
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

    $('#txtBookCount').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#btnRequestBook").click(function () {
        var rbook_name = $("#rbook_name").val();
        var rbook_isbn = $("#rbook_isbn").val();
        var rbook_writer = $("#rbook_writer").val();
        var rbook_publisher = $("#rbook_publisher").val();
        var rBookcategory1 = $("#rBookcategory1").val();
        var rBookcategory2 = $("#rBookcategory2").val();
        var rBookcategory3 = $("#rBookcategory3").val();

        if (!rbook_name || !rbook_name.trim()) {
            alert('도서명을 입력하세요');
            return false;
        }

        if (!rbook_isbn || !rbook_isbn.trim()) {
            alert('도서 ISBN을 입력하세요');
            return false;
        }

        if (rbook_isbn.length != 10 && rbook_isbn.length != 13) {
            alert('정확한 ISBN을 입력하세요');
            return false;
        }

        if (!rbook_writer || !rbook_writer.trim()) {
            alert('저자를 입력하세요');
            return false;
        }

        if (!rbook_publisher || !rbook_publisher.trim()) {
            alert('출판사를 입력하세요');
            return false;
        }

        if (!rBookcategory1) {
            alert('1차 분류를 선택하세요');
            return false;
        }

        if (!rBookcategory2) {
            alert('2차 분류를 선택하세요');
            return false;
        }

        if (!rBookcategory3) {
            alert('3차 분류를 선택하세요');
            return false;
        }

        $.ajax({
            url: '/center/ajax/bookControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'bookRequestInsert',
                franchise_idx: franchise_idx,
                teacher_idx: teacher_idx,
                rbook_name: rbook_name,
                rbook_isbn: rbook_isbn,
                rbook_writer: rbook_writer,
                rbook_publisher: rbook_publisher,
                rBookcategory1: rBookcategory1,
                rBookcategory2: rBookcategory2,
                rBookcategory3: rBookcategory3,
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#BookRequestModal").modal('hide');
                    $('#bookRequestForm')[0].reset();
                    bookCategoryChange(this.value, '2')
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
});

function bookLoadAjax() {
    var searchValue = $("#searchValue").val();
    searchValue = encodeURI(searchValue);
    $('#bookManageTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/center/ajax/bookListLoad.ajax.php?center_idx=' + franchise_idx + '&searchValue=' + searchValue,
            type: 'POST',
            dataSrc: function (res) {
                let data = res.data;
                return data;
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                $('#bookManageTable').DataTable().destroy();
            }
        },
        autoWidth: false,
        destroy: true,
        searching: false,
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
                data: 'book_writer'
            },
            {
                data: 'book_publisher'
            },
            {
                data: 'detail'
            },
            {
                data: 'amount'
            },
            {
                data: 'reg_date'
            }
        ],
        createdRow: function (row, data) {
            $("th").addClass('text-center align-middle');
            $(row).addClass('tc');
            $(row).attr('data-book-id', data.book_idx);
        },
        order: [[1, "asc"]],
        lengthChange: false,
        info: false,
        language: {
            url: "/json/ko_kr.json",
        }
    });
}

function bookSelect(e) {
    var book_id = $(e.target).parent('.tc').data('book-id');

    $.ajax({
        url: '/center/ajax/bookControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'bookSelect',
            book_idx: book_id,
            franchise_idx: franchise_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $("#bookManageIdx").val(book_id);
                setForm(result.data);
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
    $("#txtBookName").val(data.book_name);
    $("#txtBookISBN").val(data.book_isbn);
    $("#txtWriter").val(data.book_writer);
    $("#txtPublisher").val(data.book_publisher);
    $("#txtCategory").val(data.category);
    $("#txtBookCount").val(data.amount);
}

function clearForm() {
    $("#bookManageIdx").val('');
    $("#txtBookName").val('');
    $("#txtBookISBN").val('');
    $("#txtWriter").val('');
    $("#txtPublisher").val('');
    $("#txtCategory").val('');
    $("#txtBookCount").val('');
}

//도서 카테고리
function bookCategoryChange(category, position) {
    var category1 = '';
    var category2 = '';
    if (position == '2') {
        category1 = category;
    } else {
        category1 = $("#rBookcategory1").val();
        category2 = category;
    }

    $.ajax({
        url: '/center/ajax/bookCategoryArray.php',
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
                $("#rBookcategory" + position).html(result.data);
                if (position == '2') {
                    $("#rBookcategory3").html("<option value=\"\">선택</option>");
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

function bookRequestLoad() {
    $.ajax({
        url: '/center/ajax/bookControll.ajax.php',
        dataType: 'json',
        type: 'POST',
        data: {
            action: 'bookRequestLoad',
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#bookRequestTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    stripeClasses: [],
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'request_book_isbn'
                    },
                    {
                        data: 'request_book_name'
                    },
                    {
                        data: 'request_book_writer'
                    },
                    {
                        data: 'request_book_publisher'
                    },
                    {
                        data: 'request_state'
                    },
                    {
                        data: 'request_memo'
                    },
                    {
                        data: 'center_name'
                    },
                    {
                        data: 'reg_date'
                    }
                    ],
                    createdRow: function () {
                        $("th").addClass('text-center align-middle');
                    },
                    lengthChange: false,
                    info: false,
                    order: [[0, "desc"]],
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });

            } else {
                $('#bookRequestTable').DataTable().destroy();
                $('#bookRequestList').empty();
            }
        }
    });
}