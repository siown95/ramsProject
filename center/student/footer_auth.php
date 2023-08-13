<div id="layoutAuthentication_footer">
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; 리딩엠 2022</div>
                <div>
                    <button type="button" class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#PrivacyModal">이용약관 &middot; 개인정보 취급방침</button>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- Modal -->
<div class="modal fade" id="PrivacyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php 
                include('privacy.php');
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>