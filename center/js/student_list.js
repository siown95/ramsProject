$(document).ready(function () {
    loadStudent('00'); //원생 리스트 호출
    colorCodeLoad();

    $("#studentTable").on('page.dt', function () {
        $("#studentTable > tbody > tr").removeClass('bg-light');
        $("#studentForm")[0].reset();
        $("#txtUserNo").val('');
        $('#btnSaveStudentInfo').hide();
        $("#txtId").removeAttr("readonly");
        $("#lblGender").text('');
        $("#lblGrade").text('');
    });

    $("#user_name").on("propertychange change keyup paste input", function () {
        $(this).val($(this).val().replace(/[0-9.]/g, ''));
    });

    $("#studentList").on("click", ".tc", function (e) {
        selectStudent(e);
    });

    $("#colorCodeList").on("click", ".ctc", function(e){
        selecColorCode(e);
    });

    $('#txtHp').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $('#btnaddr').click(function () {
        new daum.Postcode({
            oncomplete: function (data) {
                $('#txtZipCode').val(data.zonecode);
                $('#txtAddr').val(data.roadAddress);
                $('#txtAddr').focus();
            }
        }).open();
    });

    $("#selState").change(function(){
        if($("#selState").val() == '01' || $("#selState").val() == '02'){
            $("#divRestOutReason").show();
        }else{
            $("#divRestOutReason").hide();
            $("#divEtc").hide();
        }
    });

    $("#selRestOutReason").change(function () {
        if ($("#selRestOutReason").val() == '9') {
            $("#divEtc").show();
        } else {
            $("#divEtc").hide();
        }
    });

    $("#btnSaveStudentInfo").click(function () {
        var user_no = $("#txtUserNo").val();
        var user_name = $("#txtName").val();
        var birth = $("#dtBrithday").val();
        var user_phone = $("#txtHp").val();
        var email = $("#txtEmail").val();
        var school_name = $("#txtSchool").val();
        var teacher = $("#selTeacher").val();
        var state = $("#selState").val();
        var address = $("#txtAddr").val();
        var zipcode = $("#txtZipCode").val();
        var memo = $("#txtMemo").val();
        var color_tag = $("#selColor").val();

        if (!user_name || !user_name.trim()) {
            alert('이름을 입력하세요.');
            $("#txtName").focus();
            return false;
        }

        if (user_name.length < 2) {
            alert('이름을 2글자 이상 입력하세요');
            $("#txtName").focus();
            return false;
        }

        if (!validatBlank(user_name)) {
            alert('이름에 공백이 존재합니다.');
            $("#txtName").focus();
            return false;
        }

        if (validateNumber(user_name)) {
            alert('이름에 숫자가 존재합니다.');
            $("#txtName").focus();
            return false;
        }

        if (!email || !email.trim()) {
            alert('이메일을 입력하세요.');
            $("#txtEmail").focus();
            return false;
        }

        if (!validateEmail(email)) {
            alert('정확한 이메일을 입력하세요.');
            $("#txtEmail").focus();
            return false;
        }

        if (!validatBlank(email)) {
            alert('이메일에 공백이 존재합니다.');
            $("#txtEmail").focus();
            return false;
        }

        if (!school_name || !school_name.trim()) {
            alert('학교명을 입력하세요.');
            $("#txtSchool").focus();
            return false;
        }

        if (!birth || !birth.trim()) {
            alert('생년월일을 입력하세요.');
            $("#dtBirthday").focus();
            return false;
        }

        if (!user_phone || !user_phone.trim()) {
            alert('전화번호를 입력하세요.');
            $("#txtHp").focus();
            return false;
        }

        if (!validatePhone(user_phone)) {
            alert('정확한 전화번호를 입력하세요.');
            $("#txtHp").focus();
            return false;
        }

        if (!address || !address.trim()) {
            alert('주소를 입력하세요.');
            $("#txtAddr").focus();
            return false;
        }

        if (!zipcode || !zipcode.trim()) {
            alert('우편번호를 입력하세요.');
            $("#txtZipCode").focus();
            return false;
        }

        $.ajax({
            url: '/center/ajax/studentControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'updateStudent',
                user_no: user_no,
                user_name: user_name,
                birth: birth,
                user_phone: user_phone,
                email: email,
                school_name: school_name,
                teacher: teacher,
                state: state,
                address: address,
                zipcode: zipcode,
                memo: memo,
                color_tag: color_tag,
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#contents").load('student_list.php');
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

    $("#btnColorSave").click(function () {
        var txtDetail = $("#txtDetail").val();
        var selColor = $("#selColorCode").val();
        var colorIdx = $("#colorIdx").val();
        var action = '';

        if (!txtDetail || !txtDetail.trim()) {
            alert('설명을 입력하세요.');
            return false;
        }

        if (!colorIdx) {
            action = 'colorCodeInsert';
        } else {
            action = 'colorCodeUpdate';
        }

        $.ajax({
            url: '/center/ajax/colorCodeControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: action,
                center_idx: center_idx,
                colorIdx: colorIdx,
                txtDetail: txtDetail,
                selColor: selColor
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#ColorCodeModal").modal('hide');
                    $("#contents").load('student_list.php');
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

    $("#btnColorDelete").click(function () {
        var colorIdx = $("#colorIdx").val();

        $.ajax({
            url: '/center/ajax/colorCodeControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'colorCodeDelete',
                colorIdx: colorIdx
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#ColorCodeModal").modal('hide');
                    $("#contents").load('student_list.php');
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
});

function changeLableColor() {
    var color_code = $("#selColor option:selected").data('color-code');

    if (color_code) {
        $("#lblColor").attr('style', 'color:' + color_code + ";");
    } else {
        $("#lblColor").attr('style', 'display:none;');
    }
}

function loadStudent(state) {
    $.ajax({
        url: '/center/ajax/studentControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'loadStudent',
            center_idx: center_idx,
            state: state
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#studentTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: '<"col-sm-12"f>t<"col-sm-12"p>',
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'age'
                    },
                    {
                        data: 'user_name'
                    },
                    {
                        data: 'user_phone'
                    },
                    {
                        data: 'teacher_name'
                    }
                    ],
                    createdRow: function (row, data) {
                        $(row).addClass('tc');
                        $(row).attr('data-user-idx', data.user_no);
                        $("th").addClass('text-center align-middle')
                    },
                    lengthChange: false,
                    info: false,
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });
            } else {
                $('#studentTable').DataTable().destroy();
                $("#studentList").empty();
            }
        }
    });
}

//리스트 클릭 액션
function selectStudent(e) {
    var targetClass = $(e.target).parents('.tc');

    if ($(e.target).parents('.bg-light').length) {
        targetClass.removeClass('bg-light');
        $("#studentForm")[0].reset();
        $("#txtUserNo").val('');
        $('#btnSaveStudentInfo').hide();
        $("#txtId").removeAttr("readonly");
        $("#lblGender").text('');
        $("#lblGrade").text('');
    } else {
        $('.tc').removeClass('bg-light');
        targetClass.addClass('bg-light');
        $('#btnSaveStudentInfo').show();

        var user_no = targetClass.data('user-idx');

        $.ajax({
            url: '/center/ajax/studentControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'selectStudent',
                center_idx: center_idx,
                user_no: user_no
            },
            success: function (result) {
                if (result.success) {
                    setFormdata(result.data);
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

function colorCodeLoad() {
    $.ajax({
        url: '/center/ajax/colorCodeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'colorCodeLoad',
            centerIdx: center_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#colorCodeTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: '<"col-sm-12"f>t<"col-sm-12"p>',
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'color_code'
                    },
                    {
                        data: 'color_detail',
                        className: 'text-start'
                    }
                    ],
                    createdRow: function (row, data) {
                        $(row).addClass('ctc');
                        $(row).attr('data-color-idx', data.color_idx);
                        $("th").addClass('text-center align-middle');
                    },
                    lengthChange: false,
                    info: false,
                    order: [[0, 'desc']],
                    language: {
                        emptyTable: '데이터가 없습니다.',
                        zeroRecords: '데이터가 없습니다.',
                        search: '',
                        searchPlaceholder: '검색',
                        paginate: {
                            "next": ">", // 다음 페이지
                            "previous": "<" // 이전 페이지
                        }
                    }
                });
            } else {
                $('#colorCodeTable').DataTable().destroy();
                $("#colorCodeList").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function setFormdata(data) {
    //기본값
    $("#txtUserNo").val(data.user_no);
    $("#txtName").val(data.user_name);
    $("#dtBrithday").val(data.birth);
    $("#lblGender").text(data.gender);
    $("#lblGrade").text(data.grade);
    $("#txtHp").val(data.user_phone);
    $("#txtEmail").val(data.email);
    $("#txtSchool").val(data.school_name);
    $("#dtRegdate").val(data.reg_date);
    $("#selState option[value=" + data.state + "]").prop("selected", true);
    $("#txtId").val(data.user_id);
    $("#txtId").attr('readonly', true);

    if (data.teacher_no) {
        $("#selTeacher option[value=" + data.teacher_no + "]").prop("selected", true);
    } else {
        $("#selTeacher option[value='']").prop("selected", true);
    }

    if (data.color_tag) {
        $("#selColor option[value=" + data.color_tag + "]").prop("selected", true);
    } else {
        $("#selColor option[value='']").prop("selected", true);
    }

    $("#email").val(data.email);
    $("#txtZipCode").val(data.zipcode);
    $("#txtAddr").val(data.address);
    $("#txtMemo").val(data.user_memo);
}

function selecColorCode(e){
    var targetClass = $(e.target).parents('.ctc');

    if ($(e.target).parents('.bg-light').length) {
        targetClass.removeClass('bg-light');
        $("#colorIdx").val('');
        $("#txtDetail").val('');
        $("#selColorCode").val('#ffffff');
        $('#btnColorDelete').hide();
    } else {
        $('.ctc').removeClass('bg-light');
        targetClass.addClass('bg-light');

        var color_idx = targetClass.data('color-idx');

        $.ajax({
            url: '/center/ajax/colorCodeControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'colorCodeSelect',
                colorIdx: color_idx
            },
            success: function (result) {
                if (result.success) {
                    $("#colorIdx").val(color_idx);
                    $("#txtDetail").val(result.data.color_detail);
                    $("#selColorCode").val(result.data.color_code);
                    $('#btnColorDelete').show();
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

//전화번호 체크
function validatePhone(phone) {
    var check = /^[0-9]+$/;
    if (!check.test(phone)) {
        return false;
    } else {
        var regExp = /^01([0|1|6|7|8|9])([0-9]{3,4})([0-9]{4})$/;
        if (!regExp.test(phone)) {
            return false
        } else {
            return true;
        }
    }
}

// Email 유효성 검사
function validateEmail(email) {
    var check = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i;
    if (check.test(email)) {
        return true;
    } else {
        return false;
    }
}

//한글 체크
function validateKorean(text) {
    var check = /[\u3131-\u314e|\u314f-\u3163|\uac00-\ud7a3]/g;
    if (check.test(text) == true) {
        return false;
    } else {
        return true;
    }
}

//숫자 체크
function validateNumber(text) {
    var check = /[0-9]/g;
    if (check.test(text)) {
        return true;
    } else {
        return false;
    }
}

//공백 체크
function validatBlank(text) {
    var check = /[\s]/g;
    if (check.test(text) == true) {
        return false;
    } else {
        return true;
    }
}