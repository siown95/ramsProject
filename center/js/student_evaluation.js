$(document).ready(function () {
    $("#selEvalYear1 , #selEvalYear2").datepicker({
        language: 'ko-Kr',
        format: 'yyyy'
    });

    $("#selEvalYear1, #selSemiAnnual1, #selGrade").change(function() {
        evaluationLoad();
    })

    getStudentList();
    evaluationLoad();

    $("#txtEvaluationStudentName").click(function () {
        $('#btnEvaluationSearch').trigger('click');
    });

    $("#evaluationTable").on('page.dt', function () {
        clearEvaluationForm();
        $("#evaluationTable").removeClass('bg-light');
    });

    $("#btnEvalSave").click(function () {
        var selEvalYear = $("#selEvalYear2").val();
        var selSemiAnnual = $("#selSemiAnnual2").val();
        var selStudent = $("#targetStudentNo").val();
        var selStudent = $("#targetStudentNo").val();
        var txtSynthesis = $("#txtSynthesis").val();
        var txtReadScore = $("#txtReadScore").val();
        var txtRead = $("#txtRead").val();
        var txtDebateScore = $("#txtDebateScore").val();
        var txtDebate = $("#txtDebate").val();
        var txtWriteScore = $("#txtWriteScore").val();
        var txtWrite = $("#txtWrite").val();
        var txtTenacityScore = $("#txtTenacityScore").val();
        var txtTenacity = $("#txtTenacity").val();
        var txtGuide = $("#txtGuide").val();
        var txtNextGuide = $("#txtNextGuide").val();
        var txtRequests = $("#txtRequests").val();

        if (!selStudent) {
            alert('학생을 지정해주세요.');
            return false;
        }

        if (!txtSynthesis || !txtSynthesis.trim()) {
            alert('종합관찰 내용을 입력해주세요.');
            $("#txtSynthesis").focus();
            return false;
        }

        $.ajax({
            url: '/center/ajax/studentEvaluationControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'evaluationInsert',
                center_idx: center_idx,
                writer_no: userInfo.user_no,
                selStudent: selStudent,
                selEvalYear: selEvalYear,
                selSemiAnnual: selSemiAnnual,
                txtSynthesis: txtSynthesis,
                txtReadScore: txtReadScore,
                txtRead: txtRead,
                txtDebateScore: txtDebateScore,
                txtDebate: txtDebate,
                txtWriteScore: txtWriteScore,
                txtWrite: txtWrite,
                txtTenacityScore: txtTenacityScore,
                txtTenacity: txtTenacity,
                txtGuide: txtGuide,
                txtNextGuide: txtNextGuide,
                txtRequests: txtRequests,
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    clearEvaluationForm();
                    evaluationLoad();
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

    $("#btnEvalUpdate").click(function () {
        var eval_idx = $("#studentEvalutionIdx").val();
        var txtSynthesis = $("#txtSynthesis").val();
        var txtReadScore = $("#txtReadScore").val();
        var txtRead = $("#txtRead").val();
        var txtDebateScore = $("#txtDebateScore").val();
        var txtDebate = $("#txtDebate").val();
        var txtWriteScore = $("#txtWriteScore").val();
        var txtWrite = $("#txtWrite").val();
        var txtTenacityScore = $("#txtTenacityScore").val();
        var txtTenacity = $("#txtTenacity").val();
        var txtGuide = $("#txtGuide").val();
        var txtNextGuide = $("#txtNextGuide").val();
        var txtRequests = $("#txtRequests").val();

        if (!txtSynthesis || !txtSynthesis.trim()) {
            alert('종합관찰 내용을 입력해주세요.');
            $("#txtSynthesis").focus();
            return false;
        }

        $.ajax({
            url: '/center/ajax/studentEvaluationControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'evaluationUpdate',
                eval_idx: eval_idx,
                txtSynthesis: txtSynthesis,
                txtReadScore: txtReadScore,
                txtRead: txtRead,
                txtDebateScore: txtDebateScore,
                txtDebate: txtDebate,
                txtWriteScore: txtWriteScore,
                txtWrite: txtWrite,
                txtTenacityScore: txtTenacityScore,
                txtTenacity: txtTenacity,
                txtGuide: txtGuide,
                txtNextGuide: txtNextGuide,
                txtRequests: txtRequests,
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    clearEvaluationForm();
                    evaluationLoad();
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

    $("#btnEvalDelete").click(function () {
        var eval_idx = $("#studentEvalutionIdx").val();

        if (confirm("종합평가 내용을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/studentEvaluationControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'evaluationDelete',
                    eval_idx: eval_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        clearEvaluationForm();
                        evaluationLoad();
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

    $("#evaluationList").on("click", ".tc", function (e) {
        evaluationSelect(e);
    });

    $("#btnEvaluationSearch").click(function () {
        $("#SearchEvaluationModal").modal('show');
    });

    $("#txtSearchEvaluationName").keypress(function (e) {
        if (e.keyCode === 13) {
            $("#evaluationSearch").trigger('click');
        }
    });

    $("#evaluationSearch").click(function () {
        var student_name = $("#txtSearchEvaluationName").val();

        if (!student_name || !student_name.trim()) {
            alert("학생이름을 입력하세요");
            return false;
        }

        if (student_name.length < 2) {
            alert("2글자 이상 입력하세요");
            return false;
        }

        $.ajax({
            url: '/center/ajax/studentEvaluationControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'studentSearch',
                student_name: student_name,
                franchise_idx: center_idx
            },
            success: function (result) {
                if (result.success && result.data.table) {
                    $("#evaluationSearchList").html(result.data.table);
                    return false;
                } else {
                    alert(result.msg);
                    $("#evaluationSearchList").empty();
                    return false;
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });

    $("#evaluationSearchList").on("click", ".tc", function (e) {
        var student_no = $(e.target).parent('.tc').data("student-no");
        var student_name = $(e.target).parent('.tc').find("td:eq(0)").text();
        var student_grade = $(e.target).parent('.tc').find("td:eq(1)").text();
        var student_school = $(e.target).parent('.tc').find("td:eq(2)").text();
        var selEvalYear = $("#selEvalYear2").val();
        var selSemiAnnual = $("#selSemiAnnual2").val();

        $("#targetStudentNo").val(student_no);
        $("#txtEvaluationStudentName").val(student_name);
        $("#lblEvaluationGrade").text(student_grade);
        $("#lblEvaluationSchool").text(student_school);
        $("#evaluationSearchList").empty();
        $("#SearchEvaluationModal").modal('hide');

        $.ajax({
            url: '/center/ajax/studentEvaluationControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'getStudentScore',
                center_idx: center_idx,
                student_idx: student_no,
                selEvalYear: selEvalYear,
                selSemiAnnual: selSemiAnnual
            },
            success: function (result) {
                console.log(result.data.score_read);
                if (result.success && result.data) {
                    $("#txtReadScore").val(result.data.score_read);
                    $("#txtDebateScore").val(result.data.score_debate);
                    $("#txtWriteScore").val(result.data.score_write);
                    $("#txtTenacityScore").val(result.data.score_lead);
                } else {
                    $("#txtReadScore").val('');
                    $("#txtDebateScore").val('');
                    $("#txtWriteScore").val('');
                    $("#txtTenacityScore").val('');
                    return false;
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });
});

function getStudentList() {
    var grade = $("#selGrade").val();

    $.ajax({
        url: '/center/ajax/studentEvaluationControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getStudentList',
            grade: grade,
            center_idx: center_idx
        },
        success: function (result) {
            if (result.success) {
                $("#selStudent").html(result.data.studentSelect);
                $("#txtSynthesis").val('');
                $("#txtReadScore").val('');
                $("#txtRead").val('');
                $("#txtDebateScore").val('');
                $("#txtDebate").val('');
                $("#txtWriteScore").val('');
                $("#txtWrite").val('');
                $("#txtTenacityScore").val('');
                $("#txtTenacity").val('');
                $("#txtGuide").val('');
                $("#txtNextGuide").val('');
                $("#txtRequests").val('');

                $("#btnEvaluationSearch").show();
                $("#btnEvalDelete").hide();
                $("#btnEvalUpdate").hide();
                $("#btnEvalSave").show();

                $("#targetStudentNo").val('');
                $("#txtEvaluationStudentName").val('');
                $("#lblEvaluationGrade").text('');
                $("#lblEvaluationSchool").text('');

                $("#studentEvalutionIdx").val('');
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

function evaluationLoad() {
    var selEvalYear = $("#selEvalYear1").val();
    var selSemiAnnual = $("#selSemiAnnual1").val();
    var grade = $("#selGrade").val();
    var student_no = $("#selStudent").val();

    $.ajax({
        url: '/center/ajax/studentEvaluationControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'evaluationLoad',
            center_idx: center_idx,
            selEvalYear: selEvalYear,
            selSemiAnnual: selSemiAnnual,
            grade: grade,
            student_no: student_no
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#evaluationTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: '<"col-sm-12"f>t<"col-sm-12"p>',
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'grade'
                    },
                    {
                        data: 'student_name'
                    },
                    {
                        data: 'writer_name'
                    },
                    {
                        data: 'reg_date'
                    }
                    ],
                    createdRow: function (row, data) {
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-eval-idx', data.eval_idx);
                        $("th").addClass('text-center align-middle');
                    },
                    lengthChange: false,
                    info: false,
                    order: [[0, 'desc']],
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });
            } else {
                $('#evaluationTable').DataTable().destroy();
                $("#evaluationList").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function evaluationSelect(e) {
    var targetClass = $(e.target).parents('.tc');

    if ($(e.target).parents('.bg-light').length) {
        targetClass.removeClass('bg-light');
        $("#selEvalYear2").removeAttr("disabled");
        $("#selSemiAnnual2").removeAttr("disabled");
        clearEvaluationForm();
    } else {
        $('.tc').removeClass('bg-light');
        targetClass.addClass('bg-light');
        $("#selEvalYear2").attr("disabled", true);
        $("#selSemiAnnual2").attr("disabled", true);
        var eval_idx = targetClass.data('eval-idx');

        $.ajax({
            url: '/center/ajax/studentEvaluationControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'selectEvaluation',
                eval_idx: eval_idx
            },
            success: function (result) {
                if (result.success) {
                    $("#studentEvalutionIdx").val(eval_idx);
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

function setFormdata(data) {
    $("#btnEvalDelete").show();
    $("#btnEvalUpdate").show();
    $("#btnEvalSave").hide();
    $("#btnEvaluationSearch").hide();

    $("#txtSynthesis").val(data.eval_content);
    $("#txtReadScore").val(data.read_score);
    $("#txtRead").val(data.read_content);
    $("#txtDebateScore").val(data.debate_score);
    $("#txtDebate").val(data.debate_content);
    $("#txtWriteScore").val(data.write_score);
    $("#txtWrite").val(data.write_content);
    $("#txtTenacityScore").val(data.lead_score);
    $("#txtTenacity").val(data.lead_content);
    $("#txtGuide").val(data.guide_content);
    $("#txtNextGuide").val(data.next_guide_content);
    $("#txtRequests").val(data.parent_request);

    $("#txtEvaluationStudentName").val(data.user_name);
    $("#lblEvaluationGrade").text(data.grade);
    $("#lblEvaluationSchool").text(data.school_name);
}

function clearEvaluationForm() {
    $("#txtSynthesis").val('');
    $("#txtReadScore").val('');
    $("#txtRead").val('');
    $("#txtDebateScore").val('');
    $("#txtDebate").val('');
    $("#txtWriteScore").val('');
    $("#txtWrite").val('');
    $("#txtTenacityScore").val('');
    $("#txtTenacity").val('');
    $("#txtGuide").val('');
    $("#txtNextGuide").val('');
    $("#txtRequests").val('');

    $("#btnEvaluationSearch").show();
    $("#btnEvalDelete").hide();
    $("#btnEvalUpdate").hide();
    $("#btnEvalSave").show();

    $("#targetStudentNo").val('');
    $("#txtEvaluationStudentName").val('');
    $("#lblEvaluationGrade").text('');
    $("#lblEvaluationSchool").text('');

    $("#studentEvalutionIdx").val('');
}