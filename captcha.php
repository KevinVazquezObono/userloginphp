<?php
  if (isset($_GET['idqlt']))
  {
    $idqlt = $_GET['idqlt'];
  }
  if (isset($_GET['idql']))
  {
    $idql = $_GET['idql'];
  }
  if (isset($idqlt) && isset($idql))
  {
    $conexion = new mysqli(HOST, USER, PASS, DATA);
    $captcha = $conexion->query("SELECT body FROM ".DATA.".transacciones WHERE idqlt = '".$idqlt."' AND subject = '".$idql."'");
    $conexion->close();
  }
  header("Content-Type: image/png");
  $img = imagecreate(200, 20)
  $fondo = imagecolorallocate($img, 64, 64, 64);
  $texto = imagecolorallocate($img, 250, 125, 0);
  imagestring($img, 3, 5, 5,  $captcha, $texto);
  imagepng($img);
  imagedestroy($img);
?>
