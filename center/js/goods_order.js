var selectedGoodsList = new Array();
var selectedGoodsQuantitys = new Array();

$(document).ready(function () {
    fnCheck();
    loadOrderGoodsList();

    $("#dtOrderMonth").change(function () {
        loadOrderGoodsList();
    });

    $("#btnOrderCancel").click(function () {
        var order_num = $("#hdgoods_order_num").val();

        if (!order_num) {
            alert("잘못된 접근입니다.");
            return false;
        }

        $.ajax({
            url: '/center/ajax/paymentControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'getPaymentKey',
                order_num: order_num
            },
            success: function (result) {
                if (result.success && result.data) {
                    if (confirm("결제를 취소하시겠습니까?\n결제를 취소한 후 수정이 불가능합니다.")) {
                        loadRefundPayment(result.data.paymentKey);
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

    });

    $('#goodsOrderTable > tbody').on('propertychange change keyup paste', '.goodsNo', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#txtOrderHp, #txtOrderZipCode").on('propertychange change keyup paste', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#goodsOrderTable > tbody").on('focusout', '.goodsNo', function (e) {
        var min_quantity = $(e.target).parents('tr').find('td:eq(2)').text();
        var quantity = $(e.target).val();
        var price = $(e.target).parents('tr').find('td:eq(5)').text().replace(',', '');

        if (quantity <= 0) {
            alert('주문 수량을 정확히 입력하세요');
            $(e.target).val(min_quantity);
            return false;
        }

        if (quantity < Number(min_quantity)) {
            alert('최소주문 수량보다 적은 수량은 입력 불가능합니다.');
            $(e.target).val(min_quantity);
            return false;
        }

        $(e.target).parents('tr').find('td:eq(6)').text(number_format(price * quantity));

        sumPrice();
    });

    goodsListLoad();

    $("#btnOrderItemAdd").click(function () {
        $('input:checkbox[name="goodsChk"]:checked').each(function () {
            selectedGoodsList.push(this.value);
        });

        $.ajax({
            url: '/center/ajax/goodsControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'goodsListAdd',
                goods_idxs: selectedGoodsList
            },
            success: function (result) {
                if (result.success) {
                    $("#goodsOrderTable > tbody").html(result.data.tbl);
                    sumPrice();
                    return false;
                } else {
                    return false;
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });

    $(".btnOrderItemAllCancel").click(function () {
        $("#chkAllCheck").prop('checked', false);
        $("input:checkbox[name='goodsChk']").each(function () {
            if (selectedGoodsList.includes($(this).val())) {
                $(this).prop('checked', false);
            }
        });
        selectedGoodsList = [];
        $("#goodsOrderTable > tbody").empty();
        $("#txtOrderAmount").val('');
        return false;
    });

    $("#goodsListTable").on('page.dt', function () {
        $('#chkAllCheck').prop('checked', false);
        $('input:checkbox[name="goodsChk"]').prop('checked', false);
    });

    $("#goodsOrderTable > tbody").on("click", ".btnOrderItemCancel", function (e) {
        $('#chkAllCheck').prop('checked', false);
        selectedGoodsList = selectedGoodsList.filter((element) => element !== $(e.target).parents('tr').data('goods-idx').toString());

        $("input:checkbox[name='goodsChk']").each(function () {
            if ($(this).val() === $(e.target).parents('tr').data('goods-idx').toString()) {
                $(this).prop('checked', false);
            }
        });

        $(e.target).parents('tr').remove();
        sumPrice();
    });

    $("#btnOrderAddr").click(function () {
        new daum.Postcode({
            oncomplete: function (data) {
                if (!data.buildingname || !(data.buildingname).trim()) {
                    $('#txtOrderAddr').val(data.roadAddress + " " + data.buildingName);
                } else {
                    $('#txtOrderAddr').val(data.roadAddress);
                }
                $('#txtOrderZipCode').val(data.zonecode);
                $('#txtOrderAddr').focus();
            }
        }).open();
    });


    $("#btnOrderPayment").click(function () {
        var txtOrderHp = $("#txtOrderHp").val();
        var txtDeliveryFee = $("#txtDeliveryFee").val();
        var txtOrderAddr = $("#txtOrderAddr").val();
        var txtOrderZipCode = $("#txtOrderZipCode").val();
        var txtOrderEtc = $("#txtOrderEtc").val();
        if ($("#hdgoods_order_num").val() !== '') {
            loadPayment($("#hdgoods_order_num").val());
            return false;
        }
        if (selectedGoodsList.length === 0) {
            alert('물품 등록 후 주문하세요');
            return false;
        }

        if (!txtOrderHp || !txtOrderHp.trim()) {
            alert('전화번호를 입력하세요');
            return false;
        }

        if (!txtOrderAddr || !txtOrderAddr.trim()) {
            alert('주소를 입력하세요');
            return false;
        }

        if (!txtOrderZipCode || !txtOrderZipCode.trim()) {
            alert('우편번호를 입력하세요');
            return false;
        }

        countQuantitys();

        $.ajax({
            url: '/center/ajax/goodsControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'goodsOrderInsert',
                goods_idxs: selectedGoodsList,
                goods_quantitys: selectedGoodsQuantitys,
                franchise_idx: centerInfo.center_idx,
                teacher_idx: userInfo.user_no,
                teacher_name: userInfo.user_name,
                teacher_phone: txtOrderHp,
                txtDeliveryFee: txtDeliveryFee,
                txtOrderAddr: txtOrderAddr,
                txtOrderZipCode: txtOrderZipCode,
                txtOrderEtc: txtOrderEtc,
            },
            success: function (result) {
                if (result.success) {
                    if (confirm(result.msg)) {
                        loadPayment(result.data.order_num);
                    }
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

    $("#orderListTable").on('page.dt', function () {
        $("#orderListTable > tbody > tr").removeClass('bg-light');
        $("#btnOrderCancel").hide();
        $("#txtOrderDate").val('');
        $("#txtSendDate").val('');
        $("#txtDeliveryCompany").val('');
        $("#txtDeliveryNo").val('');
        $("#txtCancelDate").val('');
        $("#txtOrderState").val('');
        $("#txtOrderAmount").val('');
        $("#txtDeliveryFee").val('');
        $("#txtOrderHp").val(centerInfo.center_phone);
        $("#txtOrderAddr").val(centerInfo.center_addr);
        $("#txtOrderZipCode").val(centerInfo.center_zipcode);
        $("#txtOrderEtc").val('');
    });

    $("#orderListTable > tbody").on('click', '.tc', function (e) {
        if ($(e.target).parents('.bg-light').length) {
            $(e.target).parents('tr').removeClass('bg-light');
            $("#btnOrderCancel").hide();
            $("#txtOrderDate").val('');
            $("#txtSendDate").val('');
            $("#txtDeliveryCompany").val('');
            $("#txtDeliveryNo").val('');
            $("#txtCancelDate").val('');
            $("#txtOrderState").val('');
            $("#txtOrderAmount").val('');
            $("#txtDeliveryFee").val('');
            $("#txtOrderHp").val(centerInfo.center_phone);
            $("#txtOrderAddr").val(centerInfo.center_addr);
            $("#txtOrderZipCode").val(centerInfo.center_zipcode);
            $("#txtOrderEtc").val('');
            $("#hdgoods_order_num").val('');
            $("#goodsOrderTable > tbody").empty();
            $("#btnOrderPayment").show();
            $("#selGoodsPaymentMethod").show();
        } else {
            $("#orderListTable > tbody > tr").removeClass('bg-light');
            $(e.target).parents('tr').addClass('bg-light');

            var order_num = $(e.target).parents('tr').find("td:eq(0)").text();
            $("#hdgoods_order_num").val(order_num);
            $.ajax({
                url: '/center/ajax/goodsControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'selectOrderInfo',
                    order_num: order_num
                },
                success: function (result) {
                    if (result.success && result.data) {
                        setOrderInfo(result.data.order_info);
                        $("#goodsOrderTable > tbody").html(result.data.order_list);
                        if (result.data.order_info.order_state_code == '01') {
                            $("#btnOrderCancel").show();
                            $("#btnOrderPayment").show();
                            $("#selGoodsPaymentMethod").show();
                        } else if (result.data.order_info.order_state_code == '02') {
                            $("#btnOrderCancel").show();
                            $("#btnOrderPayment").hide();
                            $("#selGoodsPaymentMethod").hide();
                        } else {
                            $("#btnOrderCancel").hide();
                            $("#btnOrderPayment").hide();
                            $("#selGoodsPaymentMethod").hide();
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
    });
});

function loadOrderGoodsList() {
    var dtOrderMonth = $("#dtOrderMonth").val();

    $.ajax({
        url: '/center/ajax/goodsControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'loadOrderGoodsList',
            center_idx: centerInfo.center_idx,
            dtOrderMonth: dtOrderMonth
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#orderListTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'order_num'
                    },
                    {
                        data: 'order_state'
                    },
                    {
                        data: 'order_money'
                    },
                    {
                        data: 'order_method'
                    },
                    {
                        data: 'pay_money'
                    },
                    {
                        data: 'order_date'
                    },
                    {
                        data: 'pay_date'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    createdRow: function (row) {
                        $(row).addClass('tc');
                        $("th").addClass('text-center align-middle');
                    },
                    order: [[0, 'desc']],
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });
            } else {
                $('#orderListTable').DataTable().destroy();
                $("#orderListTable > tbody").empty();
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function sumPrice() {
    var sum = 0;
    $(".goodsNo").each(function () {
        sum += parseInt($(this).parents('tr').find("td:eq(6)").text().replace(',', ''));
    });

    if (sum >= '100000') {
        $("#txtDeliveryFee").val('0');
    } else {
        $("#txtDeliveryFee").val('5000');
    }

    $("#txtOrderAmount").val(number_format(sum));
}

function countQuantitys() {
    selectedGoodsQuantitys = [];
    $(".goodsNo").each(function () {
        var goods_idx = $(this).parents('tr').data('goods-idx');
        selectedGoodsQuantitys.push({ goods_idx: goods_idx, goods_quantity: $(this).val() });
    });
}

function goodsListLoad() {
    $.ajax({
        url: '/center/ajax/goodsControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'goodsListLoad'
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#goodsListTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'check_goods',
                        orderable: false
                    },
                    {
                        data: 'goods_name'
                    },
                    {
                        data: 'goods_type'
                    },
                    {
                        data: 'sel_price'
                    },
                    {
                        data: 'order_unit'
                    },
                    {
                        data: 'min_quantity'
                    },
                    {
                        data: 'img_link'
                    },
                    {
                        data: 'goods_idx',
                        className: 'd-none'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    createdRow: function (row, data) {
                        $(row).attr('data-goods-idx', data.goods_idx);
                        $(row).addClass('tc');
                        $("th").addClass('text-center align-middle');
                    },
                    displayLength: 12,
                    order: [[7, 'asc']],
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });
            } else {
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

var imgObj = new Image();
function showImgWin(imgName) {
    imgObj.src = imgName;
    setTimeout("createImgWin(imgObj)", 100);
}
function createImgWin(imgObj) {
    if (!imgObj.complete) {
        setTimeout("createImgWin(imgObj)", 100);
        return;
    }
    imageWin = window.open("", "_blank", "width=" + imgObj.width + ",height=" + imgObj.height);
    imageWin.document.write("<html><body style='margin:0'>");
    imageWin.document.write("<img src='" + imgObj.src + "'>");
    imageWin.document.write("</body><html>");
    imageWin.document.title = imgObj.src;
}

function fnCheck() {
    $("#goodsList").on("click", "input:checkbox[name='goodsChk']", function () {
        if ($('input:checkbox[name="goodsChk"]').length == $('input:checkbox[name="goodsChk"]:checked').length) {
            $('#chkAllCheck').prop('checked', true);
        } else {
            $('#chkAllCheck').prop('checked', false);
        }
    });

    $('#chkAllCheck').on('click', function () {
        if ($('#chkAllCheck').is(':checked') == true) {
            $('input:checkbox[name="goodsChk"]').prop('checked', true);
        } else {
            $('input:checkbox[name="goodsChk"]').prop('checked', false);
        }
    });
}

function setOrderInfo(data) {
    console.log(data);
    $("#txtOrderDate").val(data.order_date);
    $("#txtSendDate").val(data.shipping_date);
    $("#txtDeliveryCompany").val(data.shipping_company);
    $("#txtDeliveryNo").val(data.invoice_number);
    $("#txtCancelDate").val('');
    $("#txtOrderState").val(data.order_state);
    $("#txtOrderAmount").val(number_format(data.order_money));

    if (data.order_money < 100000) {
        $("#txtDeliveryFee").val('5000');
    } else {
        $("#txtDeliveryFee").val('0');
    }

    if (data.order_state == '결제대기') {
        $("#btnOrderCancel").show();
    } else {
        $("#btnOrderCancel").hide();
    }

    $("#txtOrderHp").val(data.teacher_phone);
    $("#txtOrderAddr").val(data.shipping_address);
    $("#txtOrderZipCode").val(data.shipping_zipcode);
    $("#txtOrderEtc").val(data.shipping_memo);
}