$(document).ready(function () {
    loadDiagnosis1();
    loadDiagnosis2();
    getBookCategoryDetail();
    getRecommendBookList();
});

function loadDiagnosis1() {
    $.ajax({
        url: '/center/student/ajax/bookDiagnosisControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'loadDiagnosis1',
            center_idx: center_no,
            student_no: userInfo.user_no
        },
        success: function (result) {
            if (result.success) {
                $('#lDiagnosis').html(result.data.tbl);
                diagnosisChart1(result.data);
                $("#txtTotalResult1").html(result.data.txtResult);
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

function diagnosisChart1(data) {
    const ctx1 = document.getElementById("chart1");
    var data1 = {
        labels: [userInfo.user_name + '님 점수', '남학생평균', '여학생평균'],
        datasets: [{
            data: [data.cnt_user_l, data.avg_male_li, data.avg_female_li],
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 1,
            label: '문학'
        }, {
            data: [data.cnt_user_nl, data.avg_male_nonli, data.avg_female_nonli],
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1,
            label: '비문학'
        }]
    }

    var myPieChart = new Chart(ctx1, {
        type: 'bar',
        data: data1,
        options: {
            resonsive: false,
            plugins: {
                title: {
                    display: true,
                    text: '문학/비문학'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function loadDiagnosis2() {
    $.ajax({
        url: '/center/student/ajax/bookDiagnosisControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'loadDiagnosis2',
            center_idx: center_no,
            student_no: userInfo.user_no
        },
        success: function (result) {
            if (result.success) {
                $('#lnonDiagnosis').html(result.data.tbl);
                diagnosisChart2(result.data);
                $("#txtTotalResult2").html(result.data.txtResult);
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

function diagnosisChart2(data) {
    const ctx2 = document.getElementById("chart2");
    var data2 = {
        labels: [userInfo.user_name + '님 점수', '남학생평균', '여학생평균'],
        datasets: [{
            data: [data.cnt_user_c0, data.avg_male_0, data.avg_female_0],
            backgroundColor: [
                'rgba(255, 170, 31, 0.7)'
            ],
            borderColor: [
                'rgba(255, 170, 31, 1)'
            ],
            borderWidth: 1,
            label: '문학'
        }, {
            data: [data.cnt_user_c1, data.avg_male_1, data.avg_female_1],
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)'
            ],
            borderWidth: 1,
            label: '인문'
        }, {
            data: [data.cnt_user_c2, data.avg_male_2, data.avg_female_2],
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1,
            label: '사회'
        }, {
            data: [data.cnt_user_c3, data.avg_male_3, data.avg_female_3],
            backgroundColor: [
                'rgba(75, 192, 192, 0.7)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1,
            label: '과학수학'
        }, {
            data: [data.cnt_user_c4, data.avg_male_4, data.avg_female_4],
            backgroundColor: [
                'rgba(153, 102, 255, 0.7)'
            ],
            borderColor: [
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1,
            label: '체육예술'
        }, {
            data: [data.cnt_user_c5, data.avg_male_5, data.avg_female_5],
            backgroundColor: [
                'rgba(220, 255, 20, 0.7)'
            ],
            borderColor: [
                'rgba(220, 255, 20, 1)'
            ],
            borderWidth: 1,
            label: '진로'
        }, {
            data: [data.cnt_user_c6, data.avg_male_6, data.avg_female_6],
            backgroundColor: [
                'rgba(14, 18, 22, 0.7)'
            ],
            borderColor: [
                'rgba(14, 18, 22, 1)'
            ],
            borderWidth: 1,
            label: '기타'
        }]
    };

    var myPieChart2 = new Chart(ctx2, {
        type: 'bar',
        data: data2,
        options: {
            resonsive: false,
            plugins: {
                title: {
                    display: true,
                    text: '주제별'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        min: 0
                    }
                }
            }
        }
    });
}

function getBookCategoryDetail() {
    $.ajax({
        url: '/center/student/ajax/bookDiagnosisControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getReadBookCategoryDetail',
            center_idx: center_no,
            student_no: userInfo.user_no
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#BookCategoryDetailTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'code_name1'
                    },
                    {
                        data: 'code_name2'
                    },
                    {
                        data: 'detail'
                    },
                    {
                        data: 'cnt'
                    }
                    ],
                    displayLength: 5,
                    ordering: false,
                    lengthChange: false,
                    info: false,
                    createdRow: function (row, data) {
                        $("td:eq(0)", row).addClass('text-center align-middle');
                        $("td:eq(1)", row).addClass('text-center align-middle');
                        $("td:eq(2)", row).addClass('text-center align-middle');
                        $("td:eq(3)", row).addClass('text-center align-middle');
                    },
                    columnDefs: [{
                        targets: [0, 1, 2, 3],
                        className: 'dt-head-center'
                    }],
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

function getRecommendBookList() {
    $.ajax({
        url: '/center/student/ajax/bookDiagnosisControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getRecommendBookList',
            center_idx: center_no,
            student_no: userInfo.user_no
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#RecommandBookTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'book_name'
                    },
                    {
                        data: 'book_writer'
                    },
                    {
                        data: 'book_publisher'
                    },
                    {
                        data: 'cate1'
                    },
                    {
                        data: 'cate2'
                    },
                    {
                        data: 'cate3'
                    }
                    ],
                    displayLength: 10,
                    ordering: false,
                    lengthChange: false,
                    searching: false,
                    paging: false,
                    info: false,
                    createdRow: function (row, data) {
                        $("td:eq(0)", row).addClass('text-center align-middle');
                        $("td:eq(1)", row).addClass('text-start align-middle');
                        $("td:eq(2)", row).addClass('text-start align-middle');
                        $("td:eq(3)", row).addClass('text-start align-middle');
                        $("td:eq(4)", row).addClass('text-center align-middle');
                        $("td:eq(5)", row).addClass('text-center align-middle');
                        $("td:eq(6)", row).addClass('text-center align-middle');
                    },
                    columnDefs: [{
                        targets: [0, 1, 2, 3, 4, 5, 6],
                        className: 'dt-head-center'
                    }],
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