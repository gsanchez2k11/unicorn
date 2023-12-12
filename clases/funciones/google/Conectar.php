<?php
namespace unicorn\clases\funciones\google;
require_once(RAIZ . '/vendor/autoload.php');
//namespace clases\funciones\google;
/**
 * Intentamos guardar la clase google
 *
 *
 *  [0] A =>    REFERENCIA
 *  [1] B =>    ALIAS
 *  [2] C =>    ALIAS
 *  [3] D =>    UNIDADES
 *  [4] E =>
 *  [5] F =>
 *  [6] G =>    ANCHO
 *  [7] H =>
 *  [8] I =>    LARGO
 *  [9] J =>
 *  [10] K =>
 *  [11] L =>   PVP
 *  [12] M =>   PRECIO
 *  [13] N =>   METRO CUADRADO
 *  [14] O =>   NUEVO PRECIO
 *  [15] P =>
 *  [16] Q =>   PVP METRO CUADRADO
 *  [17] R =>   DTO
 *  [18] S =>   PORTES
 *  [19] T =>   PROVEEDOR
 *  [20] U =>   MARGEN
 *  [21] V =>   COMPRA M2
 *  [22] W =>   COMPRA UNIDAD
 *  [23] X =>   COMPRA TOTAL
 *  [24] Y =>   FIJOS VENTA
 *  [25] Z =>   STOCK
 *
 *
 */

class Conectar
{
  // Prints the names and majors of students in a sample spreadsheet:
  // https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
  const  SPREADSHEETID    =    '1zVn9BKoljNXLaj4wQVhvsPN0edio0wLFYHjVeMJ-amA'; //Id del archivo
  const  RANGE            =    'Inkjet!A7:X';                                  //Rango que vamos a leer
  const RANGOS            =   array('Inkjet!A1:Z','Solvente!A1:Z','Sublimación!A1:Z','Comunes!A1:Z','Futura!A1:Z','Metacrilato!A1:Z');

  //Datos para pruebas
  const  SPREADSHEETID_PRUEBA    =    '1EmYGheb7-WC_AyUXHC7_ay481OgJWMlLmoLNIRKqWpI'; //Hoja de prueba
  const  RANGO_PRUEBA            =    'Hoja 1!A7:X';

//Tarifa 2022
const NUEVAID = '1vzK_HoInZE7ORaaRyobVgepkHl7k_hsRAxw867sunbc';
const NUEVARANGOS = array('Inkjet!A1:Z');

//Tarifa Epson
const TARIFAEPSONID = '1kwpE-w23GAZg5Zq_Au_CMYLzYok2gUq1W1x7GmER4qY';
const TARIFAEPSONRANGOS = array('Hoja 1!A1:Z');

//Tarifa Beinsen
const TARIFABEINSENID = '1zRGLdje5ISOhdStvVuQ24onlG8V1PX3spI7plLa14iA';
const TARIFABEINSENRANGOS = array('Accesorios!A1:Z','Repuestos!A1:Z');


  /**
   * Returns an authorized API client.
   * @return Google_Client the authorized client object
   */
  static function getClient()
  {
      $client = new \Google_Client();
      $client->setApplicationName('Google Sheets API PHP Quickstart');
      $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
      $client->setAuthConfig(__DIR__ . '/../../../credentials.json');
      $client->setAccessType('offline');
      $client->setPrompt('select_account consent');

      // Load previously authorized token from a file, if it exists.
      // The file token.json stores the user's access and refresh tokens, and is
      // created automatically when the authorization flow completes for the first
      // time.
      $tokenPath = __DIR__ . '/../../token.json';
      if (file_exists($tokenPath)) {
          $accessToken = json_decode(file_get_contents($tokenPath), true);
          $client->setAccessToken($accessToken);
      }

      // If there is no previous token or it's expired.
      if ($client->isAccessTokenExpired()) {
          // Refresh the token if possible, else fetch a new one.
          if ($client->getRefreshToken()) {
              $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
          } else {
              // Request authorization from the user.
              $authUrl = $client->createAuthUrl();
              printf("Open the following link in your browser:\n%s\n", $authUrl);
              print 'Enter verification code: ';
              $authCode = trim(fgets(STDIN));

              // Exchange authorization code for an access token.
              $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
              $client->setAccessToken($accessToken);

              // Check to see if there was an error.
              if (array_key_exists('error', $accessToken)) {
                  throw new Exception(join(', ', $accessToken));
              }
          }
          // Save the token to a file.
          if (!file_exists(dirname($tokenPath))) {
              mkdir(dirname($tokenPath), 0700, true);
          }
          file_put_contents($tokenPath, json_encode($client->getAccessToken()));
      }
      return $client;
  }

/**
 * Conexión con la tarifa 2022
 */

  static function Conectar()
  {
    $client = new \Google_Client();
    $client->setApplicationName('Google Sheets API PHP Quickstart');
    $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
    $client->setAuthConfig(__DIR__ . '/../../../credenciales.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = __DIR__ . '/../../tokennew.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
  }

  /**
   * Conexión con la tarifa 2022
   */

    static function ConectarEpson()
    {
      $client = new \Google_Client();
      $client->setApplicationName('Google Sheets API PHP Quickstart');
      $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
      $client->setAuthConfig(__DIR__ . '/../../../credenciales-epson.json');
      $client->setAccessType('offline');
      $client->setPrompt('select_account consent');

      // Load previously authorized token from a file, if it exists.
      // The file token.json stores the user's access and refresh tokens, and is
      // created automatically when the authorization flow completes for the first
      // time.
      $tokenPath = __DIR__ . '/../../tokenepson.json';
      if (file_exists($tokenPath)) {
          $accessToken = json_decode(file_get_contents($tokenPath), true);
          $client->setAccessToken($accessToken);
      }

      // If there is no previous token or it's expired.
      if ($client->isAccessTokenExpired()) {
          // Refresh the token if possible, else fetch a new one.
          if ($client->getRefreshToken()) {
              $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
          } else {
              // Request authorization from the user.
              $authUrl = $client->createAuthUrl();
              printf("Open the following link in your browser:\n%s\n", $authUrl);
              print 'Enter verification code: ';
              $authCode = trim(fgets(STDIN));

              // Exchange authorization code for an access token.
              $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
              $client->setAccessToken($accessToken);

              // Check to see if there was an error.
              if (array_key_exists('error', $accessToken)) {
                  throw new Exception(join(', ', $accessToken));
              }
          }
          // Save the token to a file.
          if (!file_exists(dirname($tokenPath))) {
              mkdir(dirname($tokenPath), 0700, true);
          }
          file_put_contents($tokenPath, json_encode($client->getAccessToken()));
      }
      return $client;
    }


    /**
   * Conexión con la tarifa 2022
   */

   static function ConectarBeinsen()
   {
     $client = new \Google_Client();
     $client->setApplicationName('Google Sheets API PHP Quickstart');
     $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
     $client->setAuthConfig(__DIR__ . '/../../../credenciales-beinsen.json');
     $client->setAccessType('offline');
     $client->setPrompt('select_account consent');

     // Load previously authorized token from a file, if it exists.
     // The file token.json stores the user's access and refresh tokens, and is
     // created automatically when the authorization flow completes for the first
     // time.
     $tokenPath = __DIR__ . '/../../tokenbeinsen.json';
     if (file_exists($tokenPath)) {
         $accessToken = json_decode(file_get_contents($tokenPath), true);
         $client->setAccessToken($accessToken);
     }

     // If there is no previous token or it's expired.
     if ($client->isAccessTokenExpired()) {
         // Refresh the token if possible, else fetch a new one.
         if ($client->getRefreshToken()) {
             $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
         } else {
             // Request authorization from the user.
             $authUrl = $client->createAuthUrl();
             printf("Open the following link in your browser:\n%s\n", $authUrl);
             print 'Enter verification code: ';
             $authCode = trim(fgets(STDIN));

             // Exchange authorization code for an access token.
             $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
             $client->setAccessToken($accessToken);

             // Check to see if there was an error.
             if (array_key_exists('error', $accessToken)) {
                 throw new Exception(join(', ', $accessToken));
             }
         }
         // Save the token to a file.
         if (!file_exists(dirname($tokenPath))) {
             mkdir(dirname($tokenPath), 0700, true);
         }
         file_put_contents($tokenPath, json_encode($client->getAccessToken()));
     }
     return $client;
   }


}

 ?>
