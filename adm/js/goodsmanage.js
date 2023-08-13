$(document).ready(function () {
    goodsListLoad();

    $('#txtCost, #txtPrice, #txtMinQuantity').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#goodsList").on("click", ".tc", function (e) {
        goodsSelect(e);
    });

    $('#chkActive').click(function () {
        if ($('#chkActive').is(':checked') == true) {
            $('#lblActive').text('사용');
        } else {
            $('#lblActive').text('미사용');
        }
    });

    $('#btnGoodsImageUpload').click(function () {
        $('#fileGoodsImage').trigger('click');
    });

    $('#txtImageName').click(function () {
        $('#fileGoodsImage').trigger('click');
    });

    $('#fileGoodsImage').change(function () {
        var fileNm = $('#fileGoodsImage')[0].files[0];
        var fileExt = '';

        if (fileNm) {
            fileExt = fileNm.name.split('.').pop().toLowerCase();
            if ($.inArray(fileExt, ['png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG']) == -1) {
                alert('png,jpg,jpeg 파일만 업로드 할수 있습니다.');
                $("#txtImageName").val('');
                $('#fileGoodsImage').val('');
                $('#imgGoodsThumbnail').attr('src', '/img/noImg_view.png');
                return false;
            } else {
                $("#txtImageName").val(fileNm.name);
                readURL(this);
            }
        } else {
            $('#imgGoodsThumbnail').attr('src', '/img/noImg_view.png');
            $("#txtImageName").val('');
            $('#fileGoodsImage').val('');
        }
    });

    // $('#chkFirstGoods').click(function () {
    //     if ($('#chkFirstGoods').is(':checked') == true) {
    //         $('#lblFirstGoods').text('포함');
    //         $('#txtFirstGoodsQuantity').val('');
    //         $('#txtFirstGoodsQuantity').attr('disabled', false);
    //     } else {
    //         $('#lblFirstGoods').text('미포함');
    //         $('#txtFirstGoodsQuantity').val('0');
    //         $('#txtFirstGoodsQuantity').attr('disabled', true);
    //     }
    // });

    $("#btnClear").click(function () {
        $("#goodsForm")[0].reset();

        if (!$("#editGoods").val()) {
            $("#origin_file").attr("href", '');
            $("#origin_file").html("");
            $("#origin_file").addClass("d-none");
            $("#imgdel").addClass("d-none");

            $("#origin_file_name").val('');
            $("#btnDelete").hide();
        } else {
            $("#editGoods").val('');
        }
    });

    $("#btnSave").click(function () {
        var txtGoodsName = $("#txtGoodsName").val();
        var selCategory = $("#selCategory").val();
        var txtUnit = $("#txtUnit").val();
        var txtCost = $("#txtCost").val();
        var txtPrice = $("#txtPrice").val();
        var txtMinQuantity = $("#txtMinQuantity").val();
        var txtMemo = $("#txtMemo").val();
        var useYn = '';
        var action = 'goodsInsert';

        if (!txtGoodsName || !txtGoodsName.trim()) {
            alert('물품명을 입력하세요.');
            $("#txtGoodsName").focus();
            return false;
        }

        if (!selCategory) {
            alert('물품 분류를 선택하세요.');
            return false;
        }

        if (!txtUnit || !txtUnit.trim()) {
            alert('물품 단위를 입력하세요.');
            $("#txtUnit").focus();
            return false;
        }

        if (!txtCost || !txtCost.trim()) {
            alert('원가를 입력하세요.');
            $("#txtCost").focus();
            return false;
        }

        if (!txtPrice || !txtPrice.trim()) {
            alert('판매단가를 입력하세요.');
            $("#txtPrice").focus();
            return false;
        }

        if (!txtMinQuantity || !txtMinQuantity.trim()) {
            alert('최소 주문수량을 입력하세요.');
            $("#txtMinQuantity").focus();
            return false;
        }

        if($('#chkActive').is(':checked')){
            useYn = 'Y';
        }else{
            useYn = 'N';
        }

        datas = new FormData();
        datas.append('fileGoodsImage', $('#fileGoodsImage')[0].files[0]); //미리보기
        datas.append('txtGoodsName', txtGoodsName);
        datas.append('selCategory', selCategory);
        datas.append('txtUnit', txtUnit);
        datas.append('txtCost', txtCost);
        datas.append('txtPrice', txtPrice);
        datas.append('txtMinQuantity', txtMinQuantity);
        datas.append('txtMemo', txtMemo);
        datas.append('useYn', useYn);

        if ($("#editGoods").val()) {
            action = 'goodsUpdate';
            datas.append('goods_idx', $("#editGoods").val());

            if ($("#origin_file_name").val()) {
                datas.append('origin_file_name', $("#origin_file_name").val());
            }
        }
        datas.append('action', action);

        $.ajax({
            url: '/adm/ajax/goodsControll.ajax.php',
            contentType: 'multipart/form-data',
            dataType: 'JSON',
            type: 'POST',
            mimeType: 'multipart/form-data',
            data: datas,
            cache: false,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    location.reload();
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

    $("#imgdel").click(function () {
        var goods_idx = $("#editGoods").val();
        var origin_file_name = $("#origin_file_name").val();

        if (confirm("물품 이미지를 삭제하시겠습니까?")) {
            $.ajax({
                url: '/adm/ajax/goodsControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'goodsImageDelete',
                    goods_idx: goods_idx,
                    origin_file_name: origin_file_name,
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        location.reload();
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

function goodsListLoad() {
    $.ajax({
        url: '/adm/ajax/goodsControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'goodsLoad'
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#goodsTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'goods_name'
                    },
                    {
                        data: 'goods_type'
                    },
                    {
                        data: 'cost_price'
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
                        data: 'memo',
                        className: 'text-start'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    createdRow: function (row, data) {
                        $(row).attr('data-goods-idx', data.goods_idx);
                        $(row).addClass('tc text-center align-middle');
                        $("th").addClass('text-center align-middle');
                    },
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

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#imgGoodsThumbnail').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function goodsSelect(e) {
    var targetClass = $(e.target).parents('.tc');

    if ($(e.target).parent('.bg-light').length) {
        targetClass.removeClass('bg-light');

        $("#txtGoodsName").val('');
        $("#selCategory option[value='']").prop("selected", true);
        $("#txtUnit").val('');
        $("#txtCost").val('');
        $("#txtPrice").val('');
        $("#txtMinQuantity").val('');
        $("#txtMemo").val('');
        $("#imgGoodsThumbnail").attr('src', '/img/noImg_view.png');

        $("#editGoods").val('');

        $("#origin_file").attr("href", '');
        $("#origin_file").html("");
        $("#origin_file").addClass("d-none");
        $("#imgdel").addClass("d-none");

        $("#origin_file_name").val('');

        $("#btnDelete").hide();
    } else {
        $('.tc').removeClass('bg-light');
        targetClass.addClass('bg-light');

        var goods_idx = targetClass.data('goods-idx');
        $("#editGoods").val(goods_idx);

        $.ajax({
            url: '/adm/ajax/goodsControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'goodsSelect',
                goods_idx: goods_idx
            },
            success: function (result) {
                if (result.success && result.data) {
                    $("#txtGoodsName").val(result.data.goods_name);
                    $("#selCategory option[value=" + result.data.goods_type + "]").prop("selected", true);
                    $("#txtUnit").val(result.data.order_unit);
                    $("#txtCost").val(result.data.cost_price);
                    $("#txtPrice").val(result.data.sel_price);
                    $("#txtMinQuantity").val(result.data.min_quantity);
                    $("#txtMemo").val(result.data.memo);

                    if(result.data.useYn == 'Y'){
                        $('#chkActive').prop("checked", true);
                        $('#lblActive').text('사용');
                    }else{
                        $('#chkActive').prop("checked", false);
                        $('#lblActive').text('미시용');
                    }

                    if (result.data.img_link) {
                        $("#imgGoodsThumbnail").attr('src', '/files/goods_image/' + result.data.img_link);

                        $("#origin_file").attr("href", '/files/goods_image/' + result.data.img_link);
                        $("#origin_file").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + result.data.img_link);
                        $("#origin_file").removeClass("d-none");
                        $("#imgdel").removeClass("d-none");

                        $("#origin_file_name").val(result.data.img_link);
                    } else {
                        $("#imgGoodsThumbnail").attr('src', '/img/noImg_view.png');

                        $("#origin_file").attr("href", '');
                        $("#origin_file").html("");
                        $("#origin_file").addClass("d-none");
                        $("#imgdel").addClass("d-none");

                        $("#origin_file_name").val('');
                    }

                    $("#btnDelete").show();
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    }
}