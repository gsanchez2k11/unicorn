<?php
namespace unicorn\clases\funciones\otras;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
?>
<?php
/**
 *
 */
class Mail
{
  //Enviamos correos
  public static function enviaCorreo($row)
  {
$para = '';
foreach ($row['destinatarios'] as $key => $destinatario) {
  $para .= $destinatario;
$coma = $key < count($row['destinatarios'])-1 ? ',': '';
$para .= $coma;
}

//    $para      = 'web@futura.es';
    $titulo    = $row['asunto'];
    $mensaje   = $row['cuerpo'];
    $cabeceras = 'From: web@futura.es' . "\r\n" .
        'Reply-To: web@futura.es' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($para, $titulo, $mensaje, $cabeceras);

  }

}


 ?>
