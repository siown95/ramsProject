<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <?php
    $stat = 'student';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
    <script src="/center/student/js/counsel_list.js?v=<?=date("YmdHis")?>"></script>
</head>

<body class="size-14">
    <!-- 헤더 -->
    <?php include "navbar.php"; ?>

    <!-- 컨텐츠 -->
    <main style="min-height: 79vh;">
        <div class="container-fluid">
            <div class="card border-left-primary shadow mt-2 mb-2">
                <div class="card-header">
                    <h6 class="card-title">상담정보</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <table class="table table-sm table-hover table-bordered" id="counselTable">
                                <thead class="align-middle text-center">
                                    <th>번호</th>
                                    <th>작성자</th>
                                    <th>상담분류</th>
                                    <th>상담일자</th>
                                </thead>
                                <tbody class="align-middle text-center" id="counselList">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-8">
                            <div class="input-group mb-2">
                                <div class="form-inline me-2">
                                    <div class="form-floating">
                                        <input type="text" id="txtWriter" class="form-control bg-white" placeholder="작성자" readonly />
                                        <label for="txtWriter">작성자</label>
                                    </div>
                                </div>
                                <div class="form-inline me-2">
                                    <div class="form-floating">
                                        <input type="text" id="txtCounselType" class="form-control bg-white" placeholder="상담분류" readonly />
                                        <label for="txtCounselType">상담분류</label>
                                    </div>
                                </div>
                                <div class="form-inline">
                                    <div class="form-floating">
                                        <input type="text" id="txtCounselDate" class="form-control bg-white" placeholder="상담일자" readonly />
                                        <label for="txtCounselDate">상담일자</label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h6>상담내용</h6>
                            </div>
                            <div class="input-group mb-2">
                                <textarea id="CounselContents1" class="form-control bg-white" rows="5" readonly></textarea>
                            </div>
                            <div>
                                <h6>요청사항</h6>
                            </div>
                            <div class="input-group mb-2">
                                <textarea id="CounselRequest" class="form-control" rows="5"></textarea>
                            </div>
                            <div>
                                <h6>후속조치</h6>
                            </div>
                            <div class="input-group mb-2">
                                <textarea id="CounselContents2" class="form-control bg-white" rows="5" readonly></textarea>
                            </div>
                            <div class="text-end">
                                <input type="hidden" id="counsel_idx">
                                <button type="button" id="btnCounselRequestSave" class="btn btn-sm btn-primary" style="display: none;"><i class="fas fa-save me-1"></i>저장</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- 푸터 -->
    <?php include "footer.php"; ?>
</body>

</html>