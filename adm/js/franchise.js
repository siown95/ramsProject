$(document).ready(function () {
    loadCenter();

    $('#btnaddr').click(function () {
        new daum.Postcode({
            oncomplete: function (data) {
                $('#txtAddr').val(data.roadAddress);
                $('#txtZipCode').val(data.zonecode);
                $('#txtAddr').focus();
            }
        }).open();
    });

    $("#btnSearch").click(function () {
        var user_name = $("#txtSearchId").val();
        var franchise_idx = $("#centerList").find('.bg-light').data('franchise-idx');

        if (!franchise_idx) {
            alert("가맹점 선택 후 입력하세요");
            return false;
        }

        if (!user_name || !user_name.trim()) {
            alert('검색어를 입력하세요');
            $("#txtSearchId").focus();
            return false;
        }

        $.ajax({
            url: "/adm/ajax/employeeControll.ajax.php",
            dataType: 'JSON',
            type: 'POST',

            data: {
                action: 'searchId',
                user_name: user_name,
                franchise_idx: franchise_idx
            },
            success: function (result) {
                if (result.success) {
                    $("#idSearchList").html(result.data.data);
                } else {
                    $("#idSearchList").empty();
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });

    $("#txtSearchId").keypress(function (e) {
        if (e.keyCode === 13) {
            $("#btnSearch").trigger('click');
        }
    });

    $("#idSearchList").on("click", ".idc", function (e) {
        var user_id = $(e.target).parent('tr').find('td:eq(2)').text();
        var user_name = $(e.target).parent('tr').find('td:eq(0)').text();
        $("#idSearchList").empty();
        $("#txtSearchId").val('');
        $("#owner_id").val(user_id);
        $("#owner_name").val(user_name);
        $("#searchModal").modal('hide');
    });

    $("#centerList").on("click", ".tc", function (e) {
        centerSelect(e);
    });

    $('#btnClear').click(function () {
        $('#franchiseeform')[0].reset();
    });

    $('#center_eng_name').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^a-z.]/g, ''));
    });

    $('#txtTel, #txtFax, #txtroomNo, #txtZipCode').on('propertychange change keyup paste', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $('#chkActive').click(function () {
        if ($('#chkActive').is(':checked') == true) {
            $('#lblActive').text('사용');
        } else {
            $('#lblActive').text('미사용');
        }
    });

    $("#btnRegisterCodeCreate").click(function () {
        $.ajax({
            url: "/adm/ajax/franchiseControll.ajax.php",
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'registerCodeInsert',
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg+"\n링크가 클립보드에 복사되었습니다.");
                    copy2Clipboard(result.data.data);
                } else {
                    alert(result.msg);
                }
                return false;
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });
});

function copy2Clipboard(data) {
    var t = document.createElement("textarea");
    document.body.appendChild(t);
    t.value = "https://192.168.35.156/franchisee/index.php?code=" + data;
    t.select();
    document.execCommand('copy');
    document.body.removeChild(t);
}

function loadCenter() {
    $.ajax({
        url: "/adm/ajax/franchiseControll.ajax.php",
        dataType: 'JSON',
        type: 'POST',

        data: {
            action: 'centerLoad',
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#dataTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: '<"col-sm-12"f>t<"col-sm-12"p>',
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'center_name'
                    },
                    {
                        data: 'owner_name'
                    },
                    {
                        data: 'useyn'
                    }
                    ],
                    createdRow: function (row, data) {
                        $(row).addClass('tc');
                        $(row).attr('data-franchise-idx', data.franchise_idx);
                        $("th").addClass('text-center align-middle');
                    },
                    lengthChange: false,
                    info: false,
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });
            } else {
                $('#dataTable').DataTable().destroy();
                $("#centerList").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function centerSelect(e) {
    var targetClass = $(e.target).parents('.tc');

    if ($(e.target).parents('.bg-light').length) {
        targetClass.removeClass('bg-light');
        $("#franchiseform")[0].reset();
        $("#btnSave").show();
        $("#btnUpdate").hide();
        $("#btnDelete").hide();

    } else {
        $('.tc').removeClass('bg-light');
        targetClass.addClass('bg-light');

        var franchise_idx = targetClass.data('franchise-idx');

        $.ajax({
            url: '/adm/ajax/franchiseControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',

            data: {
                action: 'centerSelect',
                franchise_idx: franchise_idx
            },
            success: function (result) {
                if (result.success) {
                    setForm(result.data);
                    $("#btnSave").hide();
                    $("#btnUpdate").show();

                    if ($("#centerList").children('.bg-light').find('td:eq(3)').text() == 'X') {
                        $("#btnDelete").show();
                    } else {
                        $("#btnDelete").hide();
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
    }
}

function centerInsert() {
    var franchise_type = $("#franchise_type").val();
    var center_name = $("#center_name").val();
    var center_eng_name = $("#center_eng_name").val();
    var owner_name = $("#owner_name").val();
    var owner_id = $("#owner_id").val();
    var useyn = ($('#chkActive').is(':checked')) ? 'Y' : 'N';
    var address = $("#txtAddr").val();
    var zipcode = $("#txtZipCode").val();
    var tel_num = $("#txtTel").val();
    var fax_num = $("#txtFax").val();
    var email = $("#txtEmail").val();
    var location = $("#location").val();
    var biz_reg_date = $("#txtBuisnessmanDate").val();
    var biz_no = $("#txtBuisnessmanNo").val();
    var class_no = $("#txtroomNo").val();
    var report_date = $("#report_date").val();
    var franchisee_start = $("#dtFranchiseeformDate").val();
    var franchisee_end = $("#dtFranchiseetoDate").val();
    var rams_fee = $("#txtRamspay").val();
    var sales_confirm = $("#dtSalesConfirmDate").val();
    var royalty = $("#txtRoyalty").val();
    var sms_fee = $("#txtSMS").val();
    var lms_fee = $("#txtLMS").val();
    var mms_fee = $("#txtMMS").val();
    var shop_id = $("#txtShopId").val();
    var shop_key = $("#txtShopKey").val();

    if (!center_name || !center_name.trim()) {
        alert('교육센터명을 입력해주세요.');
        $("#center_name").focus();
        return false;
    }

    if (!owner_name || !owner_name.trim()) {
        alert('대표자명을 입력해주세요');
        $("#owner_name").focus();
        return false;
    }

    if (!address || !address.trim()) {
        alert('주소를 입력해주세요');
        $("#txtAddr").focus();
        return false;
    }

    if (!zipcode || !zipcode.trim()) {
        alert('우편번호를 입력해주세요');
        $("#txtZipCode").focus();
        return false;
    }

    if (!tel_num || !tel_num.trim()) {
        alert('전화번호를 입력해주세요.');
        $("#txtTel").focus();
        return false;
    }

    if (!email || email == '') {
        alert('메일을 입력해주세요');
        $("#txtEmail").focus();
        return false;
    }

    if (!location || location == '') {
        alert('지역을 선택해주세요');
        $("#location").focus();
        return false;
    }

    $.ajax({
        url: "/adm/ajax/franchiseControll.ajax.php",
        dataType: 'JSON',
        type: 'POST',

        data: {
            action: 'centerInsert',
            franchise_type: franchise_type,
            center_name: center_name,
            center_eng_name: center_eng_name,
            owner_name: owner_name,
            owner_id: owner_id,
            useyn: useyn,
            address: address,
            zipcode: zipcode,
            tel_num: tel_num,
            fax_num: fax_num,
            email: email,
            location: location,
            biz_reg_date: biz_reg_date,
            biz_no: biz_no,
            class_no: class_no,
            report_date: report_date,
            franchisee_start: franchisee_start,
            franchisee_end: franchisee_end,
            rams_fee: rams_fee,
            sales_confirm: sales_confirm,
            royalty: royalty,
            sms_fee: sms_fee,
            lms_fee: lms_fee,
            mms_fee: mms_fee,
            shop_id: shop_id,
            shop_key: shop_key
        },
        success: function (result) {
            if (result.success) {
                alert(result.msg);
                loadCenter();
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

function centerUpdate() {
    var franchise_idx = $("#centerList").children('.bg-light').data('franchise-idx');
    var franchise_type = $("#franchise_type").val();
    var center_name = $("#center_name").val();
    var center_eng_name = $("#center_eng_name").val();
    var owner_name = $("#owner_name").val();
    var owner_id = $("#owner_id").val();
    var useyn = ($('#chkActive').is(':checked')) ? 'Y' : 'N';
    var zipcode = $("#txtZipCode").val();
    var address = $("#txtAddr").val();
    var tel_num = $("#txtTel").val();
    var fax_num = $("#txtFax").val();
    var email = $("#txtEmail").val();
    var location = $("#location").val();
    var biz_reg_date = $("#txtBuisnessmanDate").val();
    var biz_no = $("#txtBuisnessmanNo").val();
    var class_no = $("#txtroomNo").val();
    var report_date = $("#report_date").val();
    var franchisee_start = $("#dtFranchiseeformDate").val();
    var franchisee_end = $("#dtFranchiseetoDate").val();
    var rams_fee = $("#txtRamspay").val();
    var sales_confirm = $("#dtSalesConfirmDate").val();
    var royalty = $("#txtRoyalty").val();
    var sms_fee = $("#txtSMS").val();
    var lms_fee = $("#txtLMS").val();
    var mms_fee = $("#txtMMS").val();
    var shop_id = $("#txtShopId").val();
    var shop_key = $("#txtShopKey").val();

    if (!center_name || !center_name.trim()) {
        alert('교육센터명을 입력해주세요.');
        $("#center_name").focus();
        return false;
    }

    if (!owner_name || !owner_name.trim()) {
        alert('대표자명을 입력해주세요');
        $("#owner_name").focus();
        return false;
    }

    if (!address || !address.trim()) {
        alert('주소를 입력해주세요');
        $("#txtAddr").focus();
        return false;
    }

    if (!zipcode || !zipcode.trim()) {
        alert('우편번호를 입력해주세요');
        $("#txtZipCode").focus();
        return false;
    }

    if (!tel_num || !tel_num.trim()) {
        alert('전화번호를 입력해주세요.');
        $("#txtTel").focus();
        return false;
    }

    if (!email || email == '') {
        alert('메일을 입력해주세요');
        $("#txtEmail").focus();
        return false;
    }

    if (!location || location == '') {
        alert('지역을 선택해주세요');
        $("#location").focus();
        return false;
    }

    $.ajax({
        url: "/adm/ajax/franchiseControll.ajax.php",
        dataType: 'JSON',
        type: 'POST',

        data: {
            action: 'centerUpdate',
            franchise_idx: franchise_idx,
            franchise_type: franchise_type,
            center_name: center_name,
            center_eng_name: center_eng_name,
            owner_name: owner_name,
            owner_id: owner_id,
            useyn: useyn,
            address: address,
            zipcode: zipcode,
            tel_num: tel_num,
            fax_num: fax_num,
            email: email,
            location: location,
            biz_reg_date: biz_reg_date,
            biz_no: biz_no,
            class_no: class_no,
            report_date: report_date,
            franchisee_start: franchisee_start,
            franchisee_end: franchisee_end,
            rams_fee: rams_fee,
            sales_confirm: sales_confirm,
            royalty: royalty,
            sms_fee: sms_fee,
            lms_fee: lms_fee,
            mms_fee: mms_fee,
            shop_id: shop_id,
            shop_key: shop_key
        },
        success: function (result) {
            if (result.success) {
                alert(result.msg);
                window.top.location.reload();
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

function centerDelete() {
    var franchise_idx = $("#centerList").children('.bg-light').find('td:eq(0)').text();

    $.ajax({
        url: "/adm/ajax/franchiseControll.ajax.php",
        dataType: 'JSON',
        type: 'POST',

        data: {
            action: 'centerDelete',
            franchise_idx: franchise_idx
        },
        success: function (result) {
            if (result.success) {
                alert(result.msg);
                loadCenter();
                $("#franchiseform")[0].reset();
                $("#btnSave").show();
                $("#btnUpdate").hide();
                $("#btnDelete").hide();
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
    $("#franchise_type option[value=" + data.franchise_type + "]").prop("selected", true);
    $("#center_name").val(data.center_name);
    $("#center_eng_name").val(data.center_eng_name);
    $("#owner_name").val(data.owner_name);
    $("#owner_id").val(data.owner_id)

    if (data.useyn == 'Y') {
        $('#chkActive').prop('checked', true);
        $('#lblActive').text('사용');
    } else {
        $('#chkActive').prop('checked', false);
        $('#lblActive').text('미사용');
    }
    $("#txtAddr").val(data.address);
    $("#txtZipCode").val(data.zipcode);
    $("#txtTel").val(data.tel_num);
    $("#txtFax").val(data.fax_num);
    $("#txtEmail").val(data.email);

    $("#location option[value=" + data.location + "]").prop("selected", true);

    $("#txtBuisnessmanDate").val(data.biz_reg_date);
    $("#txtBuisnessmanNo").val(data.biz_no);
    $("#txtroomNo").val(data.class_no);

    if (data.report_date) {
        $("#report_date option[value=" + data.report_date + "]").prop("selected", true);
    }

    $("#dtFranchiseeformDate").val(data.franchisee_start);
    $("#dtFranchiseetoDate").val(data.franchisee_end);
    $("#txtRamspay").val(data.rams_fee);
    $("#dtSalesConfirmDate").val(data.sales_confirm);
    $("#txtRoyalty").val(data.royalty);
    $("#txtSMS").val(data.sms_fee);
    $("#txtLMS").val(data.lms_fee);
    $("#txtMMS").val(data.mms_fee);
    $("#txtShopId").val(data.shop_id);
    $("#txtShopKey").val(data.shop_key);
}