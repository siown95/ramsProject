<?php
include_once $_SERVER['DOCUMENT_ROOT']."/_config/session_start.php";
?>
<script src="/center/js/activitypaper.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var center_idx = '<?=$_SESSION['center_idx']?>';
</script>
<div class="mt-2 size-14">
    <!-- 콘텐츠 -->
    <div class="card border-left-primary shadow h-100">
        <div class="card-header">
            <h6>활동지</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-responsive" id="paperTable">
                <thead class="text-center align-middle">
                    <th>학년</th>
                    <th>대상월</th>
                    <th width="10%">도서이미지</th>
                    <th>도서명</th>
                    <th>저자</th>
                    <th>출판사</th>
                    <th>카테고리</th>
                    <th>뷰어</th>
                    <th class="d-none">뷰어</th>
                </thead>
                <tbody class="text-center">
                </tbody>
            </table>
        </div>
    </div>
</div>