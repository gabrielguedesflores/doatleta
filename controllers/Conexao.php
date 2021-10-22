<?php

class Api
{
 
    function executeUpdateProduct($codigo, $nomeProduto, $estoque){

        $url = "https://bling.com.br/Api/v2/produto/$codigo/";
        $xml = "
        <?xml version='1.0' encoding='UTF-8'?>
        <produto>
            <codigo>$codigo</codigo>
            <descricao>$nomeProduto</descricao>
            <situacao>Ativo</situacao>
            <estoque>$estoque,00</estoque>
         </produto>";

        $posts = array (
            "apikey" => "{apiKey}",
            "xml" => rawurlencode($xml)
        );

        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_POST, count($posts));
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $posts);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $response;
    }
    

}
