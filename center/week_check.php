<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
?>
<script>
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
    var userInfo = {
        user_no: '<?= $_SESSION['logged_no'] ?>',
        user_id: '<?= $_SESSION['logged_id'] ?>',
        user_name: '<?= $_SESSION['logged_name'] ?>',
        user_phone: '<?= phoneFormat($_SESSION['logged_phone']) ?>',
        user_email: '<?= $_SESSION['logged_email'] ?>'
    }
</script>
<div class="card border-left-primary shadow mt-2 size-14">
    <div class="card-header">
        <div class="row">
            <div class="col align-self-center">
                <h6>주차확인</h6>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <div class="form-floating">
                        <select id="selWeekYear" class="form-select">
                        </select>
                        <label for="selWeekYear">년도</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="WeekTable" class="table table-sm table-bordered table-hover">
            <thead class="align-middle text-center">
                <th>년도</th>
                <th>월</th>
                <th>주차</th>
                <th>주시작일</th>
                <th>주종료일</th>
                <th>설명</th>
            </thead>
            <tbody id="WeekList"></tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        getWeekYearData();
    });
    function getWeekYearData() {
        $.ajax({
            url: '/center/ajax/weekControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'getWeekYearData'
            },
            success: function(result) {
                if (result.success && result.data) {
                    $("#selWeekYear").html(result.data.data);
                    getWeekData();
                } else {
                    alert(result.msg);
                    $("#selWeekYear").empty();
                    return false;
                }
            },
            error: function(request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    }

    function getWeekData() {
        var year = $("#selWeekYear").val();

        $.ajax({
            url: '/center/ajax/weekControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'getWeekData',
                year: year
            },
            success: function(result) {
                if (result.success) {
                    $('#WeekTable').DataTable({
                        autoWidth: false,
                        destroy: true,
                        data: result.data,
                        stripeClasses: [],
                        columns: [{
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
                        createdRow: function(row, data) {
                            $("th").addClass('align-middle text-center');
                            $(row).addClass('align-middle text-center tc');
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
            error: function(request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    }
</script>