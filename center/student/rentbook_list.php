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
</head>

<body class="size-14">
    <!-- 헤더 -->
    <?php include "navbar.php"; ?>
    <script>
        var center_idx = '<?=$_SESSION['center_idx']?>';
        var student_no = '<?=$_SESSION['logged_no']?>';
    </script>
    <!-- 컨텐츠 -->
    <main style="min-height: 79vh;">
        <div class="container-fluid">
            <div class="card border-left-primary shadow mt-2 mb-2">
                <div class="card-header">
                    <h6 class="card-title">대출도서</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-hover table-bordered" id="rentBookTable">
                        <thead class="align-middle text-center">
                            <th>번호</th>
                            <th>도서명</th>
                            <th>저자</th>
                            <th>출판사</th>
                            <th>대출일</th>
                            <th>반납예정일</th>
                            <th>반납일</th>
                            <th>연체일</th>
                            <th>읽음</th>
                        </thead>
                        <tbody class="align-middle text-center"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- 푸터 -->
    <?php include "footer.php"; ?>
</body>
<script>
    $(document).ready(function(){
        rentListLoad();
    });

    function rentListLoad() {
        $.ajax({
            url: '/center/student/ajax/bookRentControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'rentListLoad',
                center_idx: center_idx,
                student_no: student_no,
            },
            success: function(result) {
                if (result.success && result.data) {
                    $('#rentBookTable').DataTable({
                        autoWidth: false,
                        destroy: true,
                        data: result.data,
                        stripeClasses: [],
                        dom: "<'col-sm-12'f>t<'col-sm-12'p>",
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
                                data: 'rent_date'
                            },
                            {
                                data: 'ex_return_date'
                            },
                            {
                                data: 'return_date'
                            },
                            {
                                data: 'over_date'
                            },
                            {
                                data: 'readYn'
                            }
                        ],
                        createdRow: function(row, data) {
                            $("th").addClass('text-center align-middle');
                            $(row).addClass('tc text-center align-middle');
                        },
                        order: [
                            [0, 'desc']
                        ],
                        lengthChange: false,
                        displayLength: 20,
                        info: false,
                        language: {
                            emptyTable: '데이터가 없습니다.',
                            zeroRecords: '데이터가 없습니다.',
                            search: '검색 ',
                            paginate: {
                                "next": ">", // 다음 페이지
                                "previous": "<" // 이전 페이지
                            }
                        }
                    });
                } else {
                    $('#rentBookTable').DataTable().destroy();
                }
            },
            error: function(request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    }
</script>

</html>