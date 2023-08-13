<?php
$message = $_GET['message'];
$code = $_GET['code'];
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>결제 실패</title>
</head>

<body>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            alert("결제실패\n" + '<?= $message ?>\n\n' + '에러코드 : <?= $code ?>');
            window.close();
        });
    </script>
</body>

</html>