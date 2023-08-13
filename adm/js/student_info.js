$(document).ready(function () {
    $("#selFranchiseType").change(function () {
        if ($("#selFranchiseType option:selected").val() != 'all') {
            $("#selFranchise").removeAttr("disabled");
            
        } else {
            $("#selFranchise option:first").prop('selected', true);
            $("#selFranchise").attr("disabled", true);
        }

        getFranchiseList();
    });

    $("#btnSearch").click(function(){
        getChart1Data();
        getChart2Data();
    });

    $("#btnSearch").trigger('click');
});

function getFranchiseList(){
    var selFranchiseType = $("#selFranchiseType").val();

    $.ajax({
        url: '/adm/ajax/studentControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getFranchiseList',
            selFranchiseType: selFranchiseType
        },
        success: function (result) {
            if(result.success){
                $("#selFranchise").html(result.data.selOptionTxt);
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function getChart1Data() {
    var dtformDate = $("#dtformDate").val();
    var center_type = $("#selFranchiseType").val();
    var center_idx = $("#selFranchise").val();

    $.ajax({
        url: '/adm/ajax/studentControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getAnalysisStudentData1',
            dtformDate: dtformDate,
            center_type: center_type,
            center_idx: center_idx,
        },
        success: function (data) {
            drawChart1Data(data.data.dataset1, data.data.dataset2, data.data.dataset3, data.data.dataset4, data.data.dataset5, data.data.dataset6);
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function getChart2Data() {
    var dtformDate = $("#dtformDate").val();
    var center_type = $("#selFranchiseType").val();
    var center_idx = $("#selFranchise").val();

    $.ajax({
        url: '/adm/ajax/studentControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',

        data: {
            action: 'getAnalysisStudentData2',
            dtformDate: dtformDate,
            center_type: center_type,
            center_idx: center_idx,
        },
        success: function (data) {
            drawChart2Data(data.data.dataset1, data.data.dataset2);
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function drawChart1Data(dataset1, dataset2, dataset3, dataset4, dataset5, dataset6) {
    $("canvas#Chart1").remove();
    $("#div_c1").append('<canvas id="Chart1"></canvas>');

    var ctx1 = document.getElementById("Chart1");
    var data1 = {
        labels: ["유아", "초0", "초1", "초2", "초3", "초4", "초5", "초6", "중1", "중2", "중3", "고1", "고2", "고3"],
        datasets: [{
            label: '학생수',
            backgroundColor: "rgba(2,117,216, 0.85)",
            borderColor: "rgba(2,117,216,1)",
            yAxisID: 'y',
            data: dataset1,
        }, {
            label: '정규',
            backgroundColor: "rgba(255, 51, 51, 0.85)",
            borderColor: "rgba(255, 51, 51, 1)",
            yAxisID: 'y',
            data: dataset2,
        }, {
            label: '특강',
            backgroundColor: "rgba(206, 245, 132, 0.85)",
            borderColor: "rgba(206, 245, 132, 1)",
            yAxisID: 'y',
            data: dataset3,
        }, {
            label: '목적',
            backgroundColor: "rgba(211, 132, 245, 0.85)",
            borderColor: "rgba(211, 132, 245, 1)",
            yAxisID: 'y',
            data: dataset4,
        }, {
            label: '내신(국어)',
            backgroundColor: "rgba(245, 168, 36, 0.85)",
            borderColor: "rgba(245, 168, 36, 1)",
            yAxisID: 'y',
            data: dataset5,
        }, {
            label: 'JT',
            backgroundColor: "rgba(245, 251, 126, 0.85)",
            borderColor: "rgba(245, 251, 126, 1)",
            yAxisID: 'y',
            data: dataset6,
        }],
    };

    var crt1 = new Chart(ctx1, {
        type: 'bar',
        data: data1,
        options: {
            responsive: true,
            scales: {
                y: {
                    position: 'left',
                    ticks: {
                        callback: function (value, index, ticks) {
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '명';
                        },
                    }
                },
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.dataset.label + ' : ' + context.parsed.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '명';
                        }
                    }
                }
            },
            interaction: {
                mode: 'index',
                intersect: false,
            },
        },
    });
}

function drawChart2Data(dataset1, dataset2) {
    $("canvas#Chart2").remove();
    $("#div_c2").append('<canvas id="Chart2"></canvas>');

    var ctx2 = document.getElementById("Chart2");

    var data2 = {
        labels: ["초등합계", "중등합계", "고등합계", "합계"],
        datasets: [{
            label: '학생수 합계',
            backgroundColor: "rgba(2,117,216, 0.7)",
            borderColor: "rgba(2,117,216,1)",
            yAxisID: 'y',
            data: dataset1,
        }, {
            label: '정규 합계',
            backgroundColor: "rgba(255, 51, 51, 0.85)",
            borderColor: "rgba(255, 51, 51, 1)",
            yAxisID: 'y',
            data: dataset2,
        }],
    };

    var crt2 = new Chart(ctx2, {
        type: 'bar',
        data: data2,
        options: {
            responsive: true,
            scales: {
                y: {
                    position: 'left',
                    ticks: {
                        callback: function (value, index, ticks) {
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '명';
                        },
                    }
                },
            }, plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.dataset.label + ' : ' + context.parsed.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '명';
                        }
                    }
                }
            },
            interaction: {
                mode: 'index',
                intersect: false,
            },
        },
    });
}