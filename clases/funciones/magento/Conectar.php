<?php

namespace unicorn\clases\funciones\magento;

use unicorn\clases\funciones\unicorn_db\Config as config;
//require_once __DIR__ . '/../../config.php.inc';
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
/**
 *
 */
class Conectar
{
    //const URL = 'http://pruebas-tpl2.dev/';  //servidor donde tenemos instalado magento 2
    //const URL      = 'https://tiendaplotter.com/'; //servidor donde tenemos instalado magento 2
    //const username = 'gabi'; //Cuenta con permisos de administrardor
    //const password = 'chonoPa19'; //Contraseña de la cuenta

    /**
     * Obtenemos el token para trabajar
     * @return [String] [token para trabajar con la Api]
     */
    public static function getToken(int $nTienda = 1) //Pasamos como parámetro la tienda con la que vamos a conectar, por defecto va a ser la 1
    {
        $url = config::dameValorConfig('url_mage_2_' . $nTienda);
        $user = config::dameValorConfig('username_mage_2_' . $nTienda);
        $pass = config::dameValorConfig('password_mage_2_' . $nTienda);
        //   $data        = array("username" => self::username, "password" => self::password);
        $data        = array("username" => $user, "password" => $pass);
        $data_string = json_encode($data);
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string),
        );

        //   $ch = curl_init(self::URL . 'index.php/rest/V1/integration/admin/token');
        try {
            $ch = curl_init($url . 'index.php/rest/V1/integration/admin/token');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $token = curl_exec($ch);
            if ($token === false) {
                throw new \Exception(curl_error($ch), curl_errno($ch));
            } else {
                $token = json_decode($token);
                //Devolvemos un array con la url y el token
                $salida = array(
                    'url' => $url,
                    'token' => $token
                );
                return $salida;
            }
            $httpReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        } catch (\Exception $e) {

            trigger_error(
                sprintf(
                    'Curl failed with error #%d: %s',
                    $e->getCode(),
                    $e->getMessage()
                ),
                E_USER_ERROR
            );
        } finally {
            // Close curl handle unless it failed to initialize
            if (is_resource($ch)) {
                curl_close($ch);
            }
        }
    }
}
