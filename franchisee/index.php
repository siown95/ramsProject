<?php
if(empty($_GET['code'])){
    header("Location : /index.php");
}else{
?>
<form id="fd" method="post" action="register.html">
<input type="hidden" name="code" value="<?=$_GET['code']?>">
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
$(document).ready(function() {
    setTimeout(() => {
        $('#fd').submit();
    }, 100);
});
</script>
<?php
}
?>