<?php

function getCaptcha()
{
    $table = '<div class="card">
    <div class="card-body">
            <label id="tooltip" for="captcha" data-bs-placement="right" title="이미지 클릭 시 자동입력방지 문구가 변경됩니다.">자동입력방지 문구<i class="fa-solid fa-circle-info small ms-1"></i></label>
            <div class="input-group justify-content-center mt-2">
                <img id="img_captcha" class="img img-thumbnail" src="/captcha/img.php" alt="captcha"/>
                <div class="form-group-append align-self-center ms-2">
                    <input id="captcha" class="form-control" name="captcha" type="text" placeholder="자동입력방지 문구" autocomplete="off" />
                </div>
            </div>
        </div>
    </div>';

    return $table;
}