<?php
  class System_Web_Home
  {
    public function handler()
    {
      if (isset($_POST['ACCESO']))
      {
        include 'acceso.php';
        $log = new System_Web_Access();
        $log->main();
      }
      if (isset($_POST['REGISTRO']))
      {
        include 'registro.php';
        $reg = new System_Web_Register();
        $reg->main();
      }
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
      echo ('<article><form action="#" method="POST">');
      echo ($this->input('submit', 'ACCESO', 'Acceder'));
      echo ($this->input('submit', 'REGISTRO', 'Registro'));
      echo ('</form></article>');
      $this->handler();
    }
  }
?>
