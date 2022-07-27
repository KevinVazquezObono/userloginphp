<?php
  class System_Web_Access
  {
    public function idqlt($idqlt)
    {
      $str = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789";
      $l = strlen($str) - 1;
      $keylen = 64;
      while (strlen($idqlt) < $keylen)
      {
        $idqlt = $idqlt.$str[rand(0, $l)];
      }
      $conexion = new mysqli(HOST, USER, PASS, DATA);
      if ($conexion->query("SELECT FROM ".DATA.".usuarios WHERE idql = '".$idqlt."'"))
      {
        unset($idqlt);
        $this->idqlt();
      }
      return $idqlt;
    }
    public function input($type, $name, $value = null, $lab = null)
    {
      return '<input type="'.$type.'" name="'.$name.'" value="'.$value.'" placeholder="'.$lab.'" />';
    }
    public function submit($name)
    {
      return '<input type="submit" name="'.$name.'" />';
    }
    public function main()
    {
      $idqlt = $this->idqlt('QLxINITx');
      echo ('<article><form action="#" method="POST">');
      echo ($this->input('hidden', 'idqlt', $idqlt));
      echo ($this->input('mail', 'mail', '', 'Correo electrónico'));
      echo ($this->input('password', 'pass'));
      echo ($this->submit('USERLOG'));
      echo ('</form></article>');
      $this->logchk($idqlt, $mail, $pass);
    }
    public function captcha ()
    {
      $str = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789";
      $l = strlen($str) - 1;
      $captcha = null;
      while (strlen($captcha) > 16)
      {
        $captcha = $captcha.$str[rand(0, $l)];
      }
      return $captcha;
    }
    public function logchk($idqlt, $mail, $pass)
    {
      $conexion = new mysqli(HOST, USER, PASS, DATA);
      $idql = $conexion->query("SELECT FROM ".DATA.".usuarios WHERE xmail = '".$mail."' AND pass = '".$pass."'");
      $idfrom = $_SERVER['REMOTE_ADDR'];
      $idto = "quimeralegion.com";
      $idclient = "ACCESO";
      $dtm = date("Ymd:His:").substr(explode(' ', microtime())[0],2,4);
      $conexion->query("INSERT INTO ".DATA.".transacciones (idqlt, idfrom, idclient, idto, sendtime) VALUES ('".$idqlt."', '".$idfrom."', '".$idto."', '".$idclient."', '".$dtm."')");
      $conexion->query("UPDATE ".DATE.".transacciones SET subject = '".$idql."' WHERE idqlt = '".$idqlt."'");
      $check = $this->captcha();
      $conexion->query("UPDATE ".DATE.".transacciones SET body = '".$captcha."' WHERE idqlt = '".$check."'");
      echo ('<article><form action="#" method="POST">');
      echo ($this->input('hidden', 'idqlt', $idqlt));
      echo ($this->input('hidden', 'idql', $idql));
      echo ($this->input('hidden', 'mail', $mail));
      echo ($this->input('password', 'pass'));
      echo ('<iframe src="captcha.php">Debe activar los marcos para ver este contenido</iframe>');
      echo ($this->input('text', 'captcha'));
      echo ('</from></article>');
      echo ($this->submit('LAUNCH');
      $this->lauch();
    }
    public function launch()
    {
       if ($_POST['LAUNCH'])
       {
         $idqlt = $_POST['idqlt'];
         $idql = $_POST['idql'];
         $capcha = $_POST['captcha'];
         $conexion = new mysqli(HOST, USER, PASS, DATA);
         $access_trx = $conexion->query("SELECT body FROM ".DATA.".transaccioness WHERE idqlt = '".$idqlt."' AND subject = '".$idql."'");
         if ($access_trx === $captcha)
         {
           echo ('<article><form action="#" method="POST">');
           echo ($this->submit('ACCEPTAR'));
           echo ('</form></article>');
         }
         else
         {
           $this->error();
         }
       }
    }
    public function error()
    {
      echo ('<article><h5>Error</h5><form action="#" method="POST">');
      echo ('<p>Se produjo un error al comprobar los datos.</p>');
      echo ('<p>Revise el usuario o bien la contraseña introducidos.</p>');
      echo ($this->submit('INICIO'));
      echo ('</form></article>');
    }
    public function handler()
    {
      if (isset($_POST['USERLOG']))
      {
        $this->logchk($_POST['mail'], $_POST['pass']);
      }
    }
  }
?>
