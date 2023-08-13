<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="AlertToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fa-regular fa-bell text-primary me-2"></i><strong class="me-auto">알림</strong><small><?=date('Y-m-d H:i:s')?></small>
            <button type="button" id="btnToastClose" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?=$alertMsg?>
        </div>
    </div>
</div>