<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
$permissionCls = new permissionCmp();
$infoClassCmp = new infoClassCmp();
$infomanage_data = $permissionCls->selectInfomanage($_SESSION['center_idx']);

$footer_text = '';
$footer_text2 = '';

if (!empty($infomanage_data)) {
    $footer_array = array(
        "<span>책읽기와 글쓰기 리딩엠 " . $infomanage_data['center_name'] . "</span>",
        "<span>" . $infomanage_data['address'] . "</span>",
        "<span><a class=\"text-white\" href=\"tel:" . htmlspecialchars(phoneFormat($infomanage_data['tel_num'], true)) . "\">Tel : " . htmlspecialchars(phoneFormat($infomanage_data['tel_num'], true)) . "</a></span>",
        "<span>FAX &#58; " . phoneFormat($infomanage_data['fax_num'], true) . "</span>"
    );

    $footer_array2 = array(
        "<span>대표 &#58; " . $infomanage_data['owner_name'] . "</span>",
        "<span>사업자등록번호 &#58; " . $infomanage_data['biz_no'] . "</span>",
        "<span>학원등록번호 &#58; " . $infomanage_data['center_no'] . " </span>",
        "<span>통신판매신고번호 &#58; 제2018&#45;서울서초&#45;2046호</span>"
    );

    $footer_text = implode(" &#124; ", $footer_array);
    $footer_text2 = implode(" &#124; ", $footer_array2);
}
?>
<div class="container-fluid p-4 bg-dark mt-auto align-items-end">
    <div class="d-flex align-items-center justify-content-between text-white small">
        <div class="text-muted">&copy; 리딩엠 2022</div>
        <div class="mb-2">
            <?= $footer_text ?> <br> <?= $footer_text2 ?>
            <div class="text-end mt-2">
                <button type="button" id="btnPrivacy" class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#PrivacyModal">이용약관&#47;개인정보처리방침</button>
                <button type="button" id="btnCenterContact" class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#CenterContactModal">교육센터 연락처</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="PrivacyModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">이용약관 / 개인정보처리방침</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php include_once('privacy.php'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="CenterContactModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">교육센터 연락처</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-hover table-bordered">
                    <thead class="align-middle text-center">
                        <th>센터명</th>
                        <th>연락처</th>
                        <th>주소</th>
                    </thead>
                    <tbody>
                        <?php
                        $franchiseeList = $infoClassCmp->searchFranchisee(1);
                        if (!empty($franchiseeList)) {
                            foreach ($franchiseeList as $key => $val) {
                        ?>
                                <tr class="align-middle text-center">
                                    <td><?= $val['center_name'] ?></td>
                                    <td><?= phoneFormat($val['tel_num'], true) ?></td>
                                    <td class="text-start"><?= $val['address'] ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>