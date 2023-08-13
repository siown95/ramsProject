<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$analysisClassCmp = new analysisClassCmp();
$sql = "SELECT position FROM member_centerM WHERE franchise_idx = '{$_SESSION['center_idx']}' AND user_no = '{$_SESSION['logged_no']}'";
$position = $db->sqlRowOne($sql);
?>
<div class="row mt-2 mb-2">
    <div class="col-4">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <?php
                        $month_sales = $analysisClassCmp->getMonthSales($_SESSION['center_idx']);
                        ?>
                        <i class="fa-solid fa-coins me-1"></i>월매출 <span><?= ($month_sales != 0 ? number_format($month_sales) : 0) ?></span> 원
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-md-12">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <?php
                        $year_sales = $analysisClassCmp->getYearSales($_SESSION['center_idx']);
                        ?>
                        <i class="fa-solid fa-money-bill me-1"></i>연매출 <span><?= ($year_sales != 0 ? number_format($year_sales) : 0) ?></span> 원
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-md-12">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <?php
                        $tot_Student = $analysisClassCmp->getAllStudent($_SESSION['center_idx']);
                        ?>
                        <i class="fa-solid fa-user me-1"></i>총원생 <span><?= ($tot_Student != 0 ? number_format($tot_Student) : 0) ?></span> 명
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-md-12">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <?php
                        $new_Student = $analysisClassCmp->getMonthStudent($_SESSION['center_idx']);
                        ?>
                        <i class="fa-solid fa-user me-1"></i>신규원생 <span><?= ($new_Student != 0 ? number_format($new_Student) : 0) ?></span> 명
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-md-12">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <?php
                        $notReturnBookCnt = $analysisClassCmp->getNotReturnBook($_SESSION['center_idx']);
                        ?>
                        <i class="fa-solid fa-book me-1"></i>미반납도서 <span><?= $notReturnBookCnt ?></span> 권
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col align-items-start align-self-center"><i class="fa-solid fa-credit-card me-1"></i>매출</div>
                </div>
            </div>
            <div class="card-body"><canvas id="BarChart1"></canvas></div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col align-items-start align-self-center"><i class="fa-solid fa-user me-1"></i>등록원생수</div>
                </div>
            </div>
            <div class="card-body"><canvas id="BarChart2"></canvas></div>
        </div>
    </div>
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header">공지사항</div>
            <div class="card-body">
                <table id="CenterNoticeMain" class="table table-sm table-bordered table-hover">
                    <thead>
                        <th>번호</th>
                        <th>제목</th>
                        <th>작성일</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function() {
            getSalesChartData();
            getStudentChartData();
            loadBoardMain();

            $("#CenterNoticeMain").on("click", ".tc", function() {
                $("#menuList > li:nth-child(8) > ul > li:nth-child(2) > a").trigger("click");
            });
        });

        function loadBoardMain() {
            $.ajax({
                url: '/center/ajax/boardControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'loadBoardMain',
                    center_idx: '<?= $_SESSION['center_idx'] ?>',
                    target_type: '<?= $position ?>'
                },
                success: function(result) {
                    if (result.success && result.data) {
                        $('#CenterNoticeMain').DataTable({
                            autoWidth: false,
                            destroy: true,
                            data: result.data,
                            stripeClasses: [],
                            dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                            columns: [{
                                    data: 'no'
                                },
                                {
                                    data: 'title'
                                },
                                {
                                    data: 'reg_date'
                                }
                            ],
                            lengthChange: false,
                            searching: false,
                            info: false,
                            order: [
                                [0, 'desc']
                            ],
                            createdRow: function(row, data) {
                                $("td:eq(0)", row).addClass('tc text-center align-middle');
                                $("td:eq(1)", row).addClass('tc text-start align-middle');
                                $("td:eq(2)", row).addClass('tc text-center align-middle');
                                $("th").addClass('text-center align-middle');
                                $(row).attr('data-board-idx', data.board_idx);
                            },
                            language: {
                                url: '/json/ko_kr.json'
                            }
                        });
                    } else {
                        $("#CenterNoticeMain").DataTable().destroy();
                        $("#CenterNoticeMain > tbody").empty();
                    }
                    return false;
                },
                error: function(request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }

        function getSalesChartData() {
            $.ajax({
                url: '/center/ajax/studentFeeControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'getSalesChartData',
                    center_idx: '<?= $_SESSION['center_idx'] ?>'
                },
                success: function(data) {
                    salesChart(data.data.dataset1, data.data.dataset2);
                },
                error: function(request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }

        function salesChart(dataset1, dataset2) {
            var ctx1 = document.getElementById("BarChart1");
            var data1 = {
                labels: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
                datasets: [{
                    label: '<?= date("Y", strtotime("-1 Years")) ?>년',
                    backgroundColor: "rgba(133, 235, 0, 0.8)",
                    borderColor: "rgba(133, 235, 0, 1)",
                    yAxisID: 'y',
                    data: dataset1,
                }, {
                    label: '<?= date("Y") ?>년',
                    backgroundColor: "rgba(255, 51, 51, 0.85)",
                    borderColor: "rgba(255, 51, 51, 1)",
                    yAxisID: 'y',
                    data: dataset2,
                }],
            }
            var myPieChart = new Chart(ctx1, {
                type: 'bar',
                data: data1,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            position: 'left',
                            ticks: {
                                beginAtZero: true,
                                callback: function(value, index, ticks) {
                                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '원';
                                },
                            }
                        },
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                },
            });
        }

        function getStudentChartData() {
            $.ajax({
                url: '/center/ajax/studentControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'getStudentData',
                    center_idx: '<?= $_SESSION['center_idx'] ?>'
                },
                success: function(data) {
                    studentChart(data.data.dataset1, data.data.dataset2);
                },
                error: function(request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }

        function studentChart(dataset1, dataset2) {
            var ctx2 = document.getElementById("BarChart2");
            var data2 = {
                labels: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
                datasets: [{
                    label: '<?= date("Y", strtotime("-1 Years")) ?>년',
                    backgroundColor: "rgba(133, 235, 0, 0.8)",
                    borderColor: "rgba(133, 235, 0, 1)",
                    yAxisID: 'y',
                    data: dataset1,
                }, {
                    label: '<?= date("Y") ?>년',
                    backgroundColor: "rgba(255, 51, 51, 0.85)",
                    borderColor: "rgba(255, 51, 51, 1)",
                    yAxisID: 'y',
                    data: dataset2,
                }],
            }
            var myPieChart = new Chart(ctx2, {
                type: 'bar',
                data: data2,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            position: 'left',
                            ticks: {
                                stepSize: 1,
                                beginAtZero: true,
                                callback: function(value, index, ticks) {
                                    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '명';
                                },
                            }
                        },
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                },
            });
        }
    </script>
</div>