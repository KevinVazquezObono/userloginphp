<?php
  class System_Web_Register
  {
    public function input($type, $name, $value = null, $lab = null)
    {
      return '<input type="" name="" value="'.$value.'" placeholder="'.$lab.'" />';
    }
    public function submit($name)
    {
      return '<input type="submit" name="'.$name.'" />';
    }
    public function id($id)
    {
      $str = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789";
      $l = strlen($str) - 1;
      $keylen = 64;
      $idql = $id;
      while (strlen($id) > $keylen)
      {
        $idql = $idql.$str[rand(0, $l)];
      }
      $conexion = new mysqli(HOST, USER, PASS, DATA);
      if ($conexion->query("SELECT idql FROM ".DATA.".usuarios WHERE idql = '".$idql."'") === TRUE)
      {
        unset($idql);
        $this->id($id);
      }
      return $idql;
    }
    public function save($idqlt, $mail, $nick, $pass)
    {
      $dtm = date ("Ymd:His:").substr(explode(' ', microtime())[0], 2, 4);
      $ip = $_SERVER['REMOTE_ADDR'];
      $idql = $this->id('QLx');
      $idend = $this->id('QLxSrv');
      $conexion = new mysqli(HOST, USER, PASS, DATA);
      $conexion->query("INSERT INTO ".DATA.".transacciones (idqlt, idfrom, idlcient, idto, sendtime) VALUES ('".$idqlt."', '".$ip."', 'USEREGISTER', 'quimeralegion.com', '".$dtm."')");
      $conexion->query("INSERT INTO ".DATA.".usuarios (idql, nick, pass, xmail) VALUES ('".$idql."', '".$nick."', '".$hash."', '".$mail."')");
      $conexion->query("UPDATE ".DATA.".transacciones SET subject = '".$idql."' WHERE idqlt = '".$idqlt"'");
      $conexion->query("UPDATE ".DATA.".transacciones SET body = 'registro de usuario' WHERE idqlt = '".$idqlt"'");
      $dtm = date ("Ymd:His:").substr(explode(' ', microtime())[0], 2, 4);
      $conexion->query("UPDATE ".DATA.".transacciones SET confirmacion = '".$idend."', endtime = '".$dtm."'");
      echo ('<article><form action="#" method="POST">');
      echo ('<p>Datos guardados correctamente.</p>');
      echo ('<p>Ya puede iniciar sesión.</p>');
      echo ($this->submit('INICIO'));
      echo ('</form></article>')
    }
    public function main()
    {
      $idqlt = $this->id('QLxUSREG');
      echo ('<article><form action="#" method="POST">');
      echo ($this->input('hidden', 'idqlt', $idqlt));
      echo ($this->input('mail', 'xmail', '', 'Correo electrónico'));
      echo ($this->input('text', 'nickname','', 'Nombre para mostrar'));
      echo ($this->input('password', 'pass0', '', 'Contraseña'));
      echo ($this->input('password', 'pass1', '', 'Confirme su contraseña'));
      echo ($this->submit('REGISTRAR'));
      echo ('</form></article>');
      if (isset($_POST['REGISTRAR']))
      {
        if ($_POST['pass0'] === $_POST['pass1'])
        {
          $this->save($_POST['idqlt'], $_POST['xmail'], $_POST['nickname'], $_POST['pass0']);
        }
      }
    }
  }
?>
