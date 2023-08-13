$(document).ready(function () {
    $("#dtYear").datepicker({
        language: 'ko-Kr',
        format: 'yyyy'
    });
    getChartData();

    $("#btnSearch").click(function () {
        getChartData();
    })

    $("#selFranchiseType").change(function () {
        var centertype = $("#selFranchiseType").val();
        if (!centertype) {
            return false;
        } else if (centertype == '01' || centertype == '02') {
            $.ajax({
                url: '/adm/ajax/invoiceControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'getFranchiseData',
                    centertype: centertype,
                },
                success: function (result) {
                    if (result.success) {
                        $("#selCenter").empty();
                        $("#selCenter").append(result.data.options);
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

function getChartData() {
    var year = $("#dtYear").val();
    var centertype = $("#selFranchiseType").val();
    var center_idx = $("#selCenter").val();

    $.ajax({
        url: '/adm/ajax/invoiceControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getSalesInfoChartData',
            year: year,
            centertype: centertype,
            center_idx: center_idx
        },
        success: function (result) {
            if (result.success) {
                getTotalSalesChart(result.data.dataset1, result.data.dataset2, result.data.dataset3, result.data.dataset4, result.data.dataset5);
            } else {
                alert(result.msg);
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });

    $.ajax({
        url: '/adm/ajax/invoiceControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getSalesCenterInfoChartData',
            year: year,
            centertype: centertype,
            center_idx: center_idx
        },
        success: function (result) {
            if (result.success) {
                getCenterSalesChart(result.data.center, result.data.dataset1, result.data.dataset2, result.data.dataset3, result.data.dataset4, result.data.dataset5);
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

function getTotalSalesChart(dataset1, dataset2, dataset3, dataset4, dataset5) {
    $("canvas#Chart1").remove();
    $("div.chartreport1").append('<canvas id="Chart1"></canvas>');
    var ctx1 = document.getElementById("Chart1");
    var data1 = {
        labels: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
        datasets: [{
            label: '납부예정',
            backgroundColor: "rgba(244, 54, 1, 0.7)",
            borderColor: "rgba(244, 54, 1, 1)",
            yAxisID: 'y',
            data: dataset1,
        }, {
            label: '납부완료',
            backgroundColor: "rgba(244, 135, 1, 0.7)",
            borderColor: "rgba(244, 135, 1, 1)",
            yAxisID: 'y',
            data: dataset2,
        }, {
            label: '매출액',
            backgroundColor: "rgba(61, 214, 0, 0.7)",
            borderColor: "rgba(61, 214, 0, 1)",
            yAxisID: 'y',
            data: dataset3,
        }, {
            label: '교재교구비',
            backgroundColor: "rgba(30, 180, 160, 0.7)",
            borderColor: "rgba(30, 180, 160, 1)",
            yAxisID: 'y',
            data: dataset4,
        }, {
            label: '납부율',
            type: 'line',
            backgroundColor: "rgba(0, 132, 214, 0.7)",
            borderColor: "rgba(0, 132, 214, 1)",
            yAxisID: 'y2',
            data: dataset5,
        }],
    };
    var Chart1 = new Chart(ctx1, {
        type: 'bar',
        data: data1,
        options: {
            responsive: true,
            scales: {
                y: {
                    position: 'left',
                    ticks: {
                        callback: function (value, index, ticks) {
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '원';
                        },
                    }
                },
                y2: {
                    position: 'right',
                    ticks: {
                        callback: function (value, index, ticks) {
                            return value + '%';
                        }
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

function getCenterSalesChart(centerdata, dataset1, dataset2, dataset3, dataset4, dataset5) {
    $("canvas#Chart2").remove();
    $("div.chartreport2").append('<canvas id="Chart2"></canvas>');
    var ctx2 = document.getElementById("Chart2");
    var data2 = {
        labels: centerdata,
        datasets: [{
            label: '납부예정',
            backgroundColor: "rgba(244, 54, 1, 0.7)",
            borderColor: "rgba(244, 54, 1, 1)",
            yAxisID: 'y',
            data: dataset1,
        }, {
            label: '납부완료',
            backgroundColor: "rgba(244, 135, 1, 0.7)",
            borderColor: "rgba(244, 135, 1, 1)",
            yAxisID: 'y',
            data: dataset2,
        }, {
            label: '매출액',
            backgroundColor: "rgba(61, 214, 0, 0.7)",
            borderColor: "rgba(61, 214, 0, 1)",
            yAxisID: 'y',
            data: dataset3,
        }, {
            label: '교재교구비',
            backgroundColor: "rgba(30, 180, 160, 0.7)",
            borderColor: "rgba(30, 180, 160, 1)",
            yAxisID: 'y',
            data: dataset4,
        }, {
            label: '납부율',
            type: 'line',
            backgroundColor: "rgba(0, 132, 214, 0.7)",
            borderColor: "rgba(0, 132, 214, 1)",
            yAxisID: 'y2',
            data: dataset5,
        }],
    };
    var Chart2 = new Chart(ctx2, {
        type: 'bar',
        data: data2,
        options: {
            responsive: true,
            scales: {
                y: {
                    position: 'left',
                    ticks: {
                        callback: function (value, index, ticks) {
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '원';
                        },
                    }
                },
                y2: {
                    position: 'right',
                    ticks: {
                        callback: function (value, index, ticks) {
                            return value + '%';
                        }
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