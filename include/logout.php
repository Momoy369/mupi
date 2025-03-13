<?php
session_start();
unset($_SESSION["admin_session"]);
session_destroy();
echo "<script language=javascript>location.href='../index';</script>";
?>
<script>
    window.location = '../index';
</script>
<?php
?>