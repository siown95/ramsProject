<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />

    <?php
    $stat = 'student';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.3.3/js/dataTables.buttons.min.js" integrity="sha512-8sSGWfEP0O2tSZiaGmlHw9YZ6fKrfVfuC6DG5/URxgL8otfSK6bRDuRp6rO2U+EN3lVKIUBOG9GE8ss3FVJ1vw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" integrity="sha512-XMVd28F1oH/O71fzwBnV7HucLxVwtxf26XV8P4wPk26EDxuGZ91N8bsOttmnomcCD3CS5ZMRL50H0GgOHvegtg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha512-a9NgEEK7tsCvABL7KqtUTQjl69z7091EVPpw5KxPlZ93T141ffe1woLtbXTX+r2/8TtTvRX/v4zTL2UlMUPgwg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js" integrity="sha512-P0bOMePRS378NwmPDVPU455C/TuxDS+8QwJozdc7PGgN8kLqR4ems0U/3DeJkmiE31749vYWHvBOtR+37qDCZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.3.3/js/buttons.html5.min.js" integrity="sha512-cBlHTLVISzl4A2An/1uQCqUq7MPJlCTqk/Uvwf1OU8lAB87V72oPdllhBD7hYpSDhmcOqY/PIeJ5bUN/EHcgpw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.3.3/js/buttons.print.min.js" integrity="sha512-b956UIE6Nx8REYgGGJEyAlCUPgei+JdTU41lrOIvH8LrH+REzjjQOeNhOatI2wOr1eC6+v1rhv5UqJ0GF6LMQQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<body class="size-14">
    <!-- 헤더 -->
    <?php include "navbar.php"; ?>
    <!-- 컨텐츠 -->
    <main style="min-height: 79vh;">
        <div class="container-fluid">
            <div class="card border-left-primary shadow mt-2 mb-2">
                <div class="card-header">
                    <h6 class="card-title">읽은책목록</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-hover table-bordered" id="readBookTable">
                        <thead class="align-middle text-center">
                            <th>번호</th>
                            <th>도서명</th>
                            <th>저자</th>
                            <th>출판사</th>
                            <th>대출일</th>
                            <th>반납일</th>
                        </thead>
                        <tbody class="align-middle text-center">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- 푸터 -->
    <?php include "footer.php"; ?>
</body>
<script>
    $(document).ready(function() {
        readListLoad();

        $("#btnExportExcel").click(function() {
            var instance = $("#readBookTable").tableExport({
                formats: ['xlsx'],
                exportButtons: false
            });
        });
    });

    function readListLoad() {
        $.ajax({
            url: '/center/student/ajax/bookRentControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'readListLoad',
                center_idx: center_idx,
                student_no: userInfo.user_no,
            },
            success: function(result) {
                if (result.success && result.data) {
                    $('#readBookTable').DataTable({
                        autoWidth: false,
                        destroy: true,
                        data: result.data,
                        stripeClasses: [],
                        dom: "B<'col-sm-12'f>t<'col-sm-12'p>",
                        buttons: [
                            {
                                extend: "excel",
                                text: "<i class=\"fa-solid fa-file-excel me-1\"></i>엑셀",
                                charset: "utf-8",
                                bom: true,
                                className: "btn btn-sm btn-outline-success"
                            },
                            {
                                extend: "print",
                                text: "<i class=\"fa-solid fa-print me-1\"></i>인쇄",
                                className: "btn btn-sm btn-outline-secondary"
                            },
                        ],
                        columns: [{
                                data: 'no'
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
                                data: 'last_rent_date'
                            },
                            {
                                data: 'last_return_date'
                            }
                        ],
                        createdRow: function(row, data) {
                            $("th").addClass('text-center align-middle');
                            $(row).addClass('text-center align-middle');
                        },
                        order: [
                            [0, 'desc']
                        ],
                        lengthChange: false,
                        displayLength: 15,
                        info: false,
                        language: {
                            url: '/json/ko_kr.json'
                        }
                    });
                } else {
                    $('#readBookTable').DataTable().destroy();
                }
            },
            error: function(request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    }
</script>

</html>