<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();

include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$infoClassCmp = new infoClassCmp();
$banner = $infoClassCmp->getBanner();
$notice1 = $infoClassCmp->getMainNotice();
$notice2 = $infoClassCmp->getMainNoticeRecent();
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/css/styles.css" />
    <link rel="stylesheet" href="/css/main_styles.css" />
    <?php include_once $_SERVER["DOCUMENT_ROOT"] . "/common/common_script2.php" ?>
    <script type="text/javascript" src="/js/main_index.js?v=<?= date('YmdHis') ?>"></script>
</head>

<body class="bg-main-background">
    <div class="container-fluid">
        <div class="container mt-2">
            <main>
                <div class="bg-opacity">
                    <section class="bg-opactiy-white">
                        <h6 class="text-center text-white">과학이든 인문이든<span class="text-warning">책읽기와 글쓰기</span>로 판가름 나더라</h6>
                        <p class="text-center text-white">독서이력진단과 독서활동 매니지먼트 서비스를 통한 교육</p>
                    </section>
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="d-grid bg-opacity">
                            <a class="btn btn-outline-light" href="/adm/login.html">본사 로그인</a>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-grid bg-opacity">
                            <a class="btn btn-outline-light" href="/center/login2.html">직원 로그인</a>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-grid bg-opacity">
                            <a class="btn btn-outline-light" href="/center/student/login2.html">학생 로그인</a>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-8">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between">
                                <div class="align-self-center">
                                    <b>리딩엠 공지사항</b>
                                </div>
                                <div>
                                    <button type="button" id="btnNoticeMore" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#mainNoticeListModal"><i class="fa-solid fa-circle-exclamation me-1"></i>더보기</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="mainNoticeTable" class="table table-sm table-bordered table-hover">
                                    <thead class="align-middle text-center">
                                        <th width="15%">번호</th>
                                        <th width="70%">제목</th>
                                        <th width="15%">작성일</th>
                                    </thead>
                                    <tbody class="align-middle text-center">
                                        <?php
                                        $tbl = "";
                                        foreach ($notice2 as $key => $val) {
                                            $tbl .= "<tr data-board_idx=\"{$val['board_idx']}\">
                                            <td>" . ($key + 1) . "</td>
                                            <td class=\"text-start\">{$val['title']}</td>
                                            <td>{$val['reg_date']}</td>
                                            </tr>";
                                        }
                                        echo $tbl;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card h-100">
                            <img src="/img/img_main_diagnosis.png" class="card-img-top" alt="책읽기글쓰기진단">
                            <div class="card-body">
                                <b class="card-title">책읽기&#47;글쓰기 진단</b>
                                <p class="card-text">우리 아이의 문해력이 부족하다면? 글쓰기 실력은?</p>
                                <div class="text-end">
                                    <a href="/reading_analysis/index2.php" class="btn btn-primary"><i class="fa-regular fa-square-check me-1"></i>진단하기</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-2 mb-2">
                    <div id="MainImageCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-indicators mx-auto" style="width: fit-content; background-color: rgba(200, 200, 200, 0.8);">
                            <?php
                            foreach ($banner as $key => $val) {
                                if ($key == 0) {
                            ?>
                                    <button type="button" data-bs-target="#MainImageCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <?php
                                } else {
                                ?>
                                    <button type="button" data-bs-target="#MainImageCarousel" data-bs-slide-to="<?= $key ?>" aria-label="Slide <?= ($key + 1) ?>"></button>
                            <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="carousel-inner">
                            <?php
                            foreach ($banner as $key => $val) {
                            ?>
                                <div class="carousel-item <?php if ($key == 0) echo 'active'; ?>">
                                    <a href="<?= $val["banner_link"] ?>"><img class="banner-img rounded mx-auto d-block" src="/files/banner_file/<?= $val['banner_image'] ?>"></a>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#MainImageCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon text-bg-dark" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#MainImageCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon text-bg-dark" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="mainNoticeListModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">공지사항 목록</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="mainNoticeTable2" class="table table-sm table-bordered table-hover">
                        <thead class="align-middle text-center">
                            <th width="15%">번호</th>
                            <th width="70%">제목</th>
                            <th width="15%">작성일</th>
                        </thead>
                        <tbody class="align-middle text-center">
                            <?php
                            $tbl = "";
                            foreach ($notice1 as $key => $val) {
                                $tbl .= "<tr data-board_idx=\"{$val['board_idx']}\">
                                <td>" . ($key + 1) . "</td>
                                <td class=\"text-start\">{$val['title']}</td>
                                <td>{$val['reg_date']}</td>
                                </tr>";
                            }
                            echo $tbl;
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mainNoticeViewModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="boardForm">
                        <input type="hidden" id="board_idx">
                        <div class="form-floating align-items-center mb-2">
                            <input type="text" id="txtTitle" class="form-control bg-white" maxlength="50" placeholder="제목" disabled>
                            <label for="txtTitle">제목</label>
                        </div>
                        <div class="align-items-center mb-2">
                            <div id="txtContents" class="form-control bg-white overflow-auto border p-2"></div>
                        </div>
                        <div class="input-group align-items-center mt-2">
                            <div class="form-inline">
                                <a class="link-info me-2 d-none" href="" id="exfile" download><i class="fa-solid fa-file-arrow-down"></i></a>
                                <input type="hidden" id="file_name">
                                <input type="hidden" id="file_path">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>