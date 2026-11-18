<?php
session_start();
session_destroy(); 
session_unset();

//error_reporting(0);

echo "<script>
alert('Gracias por visitarnos');
location.href='login.html';
</script>";
exit;
?>