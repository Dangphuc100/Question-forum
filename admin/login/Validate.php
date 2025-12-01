<?php
$ActualPassword = "1";
if ($_POST["password"] == $ActualPassword) {
    session_start();
    $_SESSION["Authorised"] = "Y";
    header("Location:../Index.php");
} else {
    header("Location:Wrongpassword.php");
}
