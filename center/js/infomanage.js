$(document).ready(function () {
    $("#btnSaveInfomanage").click(function () {
        var center_name = $("#txtCompanyName").val();
        var owner_name = $("#txtCeoName").val();
        var address = $("#txtAddr").val();
        var tel_num = $("#txtTel").val();
        var fax_num = $("#txtFax").val();
        var email = $("#txtEmail").val();
        var biz_no = $("#txtCompanyNo").val();
        var center_no = $("#txtCenterNo").val();

        if (!center_name || !center_name.trim()) {
            alert('교육센터명을 입력해주세요.');
            $("#txtCompanyName").focus();
            return false;
        }

        if (!owner_name || !owner_name.trim()) {
            alert('대표자명을 입력해주세요');
            $("#txtCeoName").focus();
            return false;
        }

        if (!address || !address.trim()) {
            alert('주소를 입력해주세요');
            $("#txtAddr").focus();
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

        $.ajax({
            url: "/center/ajax/franchiseControll.ajax.php",
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'infoManage',
                franchise_idx: franchise_idx,
                center_name: center_name,
                owner_name: owner_name,
                address: address,
                tel_num: tel_num,
                fax_num: fax_num,
                email: email,
                biz_no: biz_no,
                center_no: center_no,
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    return false;
                } else {
                    alert(result.msg);
                    return false;
                }
            },
            error: function () {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });
});