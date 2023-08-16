<?php

include "controllers/Conexao.php";

$api = new Api();

// function executeUpdateProduct($url, $data){
//     $curl_handle = curl_init();
//     curl_setopt($curl_handle, CURLOPT_URL, $url);
//     curl_setopt($curl_handle, CURLOPT_POST, count($data));
//     curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
//     curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
//     $response = curl_exec($curl_handle);
//     curl_close($curl_handle);
//     return $response;
// }

$url = 'https://bling.com.br/Api/v2/produto/2447703/';
$xml = "
<?xml version='1.0' encoding='UTF-8'?>
<produto>
    <codigo>2447703</codigo>
    <descricao>3 Whey Protein (900g) - Probiótica Sabor:Baunilha</descricao>
    <situacao>Ativo</situacao>
    <estoque>0,00</estoque>
 </produto>";


$retorno = $api->executeUpdateProduct("2447703", "3 Whey Protein (900g) - Probiótica Sabor:Baunilha", "1");
echo $retorno;

