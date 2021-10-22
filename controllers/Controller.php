<?php

class Controller 
{
    function executeGetOrder($url){
        $ini = curl_init();
        curl_setopt($ini, CURLOPT_URL, $url);
        curl_setopt($ini, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ini);
        curl_close($ini);
        return $result;
    }

    function executeUpdateOrder($url, $data){
        $ini = curl_init();
        curl_setopt($ini, CURLOPT_URL, $url); //
        curl_setopt($ini, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ini, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($ini, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ini);
        curl_close($ini);
        return $response;
    }

    public function slackSendMessage($json_string, $slack_webhook_url)
    {
        $ini = curl_init();
        curl_setopt($ini, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ini, CURLOPT_POSTFIELDS, $json_string); 
        curl_setopt($ini, CURLOPT_CRLF, TRUE);
        curl_setopt($ini, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ini, CURLOPT_URL, $slack_webhook_url); 
        curl_setopt($ini, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length:'.strlen($json_string)
        ));
        $response = curl_exec($ini);
        curl_close($ini);
        return $response;
    }

    public function executeRequest($endpoint)
    {
        $chaveApi = "{apiKey}";
        $url = "https://bling.com.br/Api/v2/$endpoint/json/&apikey=$chaveApi";
        $retornoJson = file_get_contents($url);
        $retornoJsonDecode = json_decode($retornoJson);
        return $retornoJsonDecode;
    }

    public function insertProdutosFranq($id, $nome, $estoque, $preco) 
    {
            $dbst = $this->db->prepare("insert into produtos_franq (produto_id, nome, estoque, preco)
            values ($id, '$nome', $estoque, '$preco');");
            return $this->execute($dbst);
    }

    public function insertProdutosBling($id, $nome, $estoque, $preco) 
    {
            $dbst = $this->db->prepare("insert into produtos_bling (produto_id, nome, estoque, preco)
            values ($id, '$nome', $estoque, '$preco');");
            return $this->execute($dbst);
    }

    public function selectProdutosFranq() 
    {
            $dbst = $this->db->prepare("SELECT * FROM produtos_franq;");
            return $this->execute($dbst);
    }

    public function queryLivre($query) 
    {
            $dbst = $this->db->prepare($query);
            return $this->execute($dbst);
    }

    public function selectDatabase() 
    {
            $dbst = $this->db->prepare(" SELECT 
            produtos_franq.produto_id as id_franq,
            produtos_franq.nome as nome_franq,
            produtos_franq.estoque as estoque_franq,
            produtos_franq.preco as preco_franq,

            produtos_bling.produto_id as id_bling,
            produtos_bling.nome as nome_bling,
            produtos_bling.estoque as estoque_bling,
            produtos_bling.preco as preco_bling
            
            from produtos_franq	
                inner join produtos_bling
            	    on produtos_franq.produto_id = produtos_bling.produto_id;

            ");
            return $this->execute($dbst);
    }
 /*
 
 UPDATE PRODUTOS
 
 */
    public function executeUpdateProduct($codigo, $descricao, $estoque) 
    { 
        $chaveAplicação = "{apiKey}";
        $ch = curl_init();
        
        $xml = "<?xml version='1.0' encoding='UTF-8'?>
        <produto>
           <codigo>$codigo</codigo>
           <descricao>$descricao</descricao>
           <situacao>Ativo</situacao>
           <estoque>$estoque,00</estoque>   
        </produto>";

        $post = array(
           "apikey" => "{apiKey}", 
           "xml" => rawurlencode($xml)
        );
        curl_setopt($ch, CURLOPT_URL, "https://bling.com.br/Api/v2/produto/$codigo/");
        curl_setopt($curl_handle, CURLOPT_POST, count($post));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function executeUpdateEstoque($codigo, $descricao, $estoque) 
    { 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://bling.com.br/Api/v2/produto/$codigo/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $xml = "<?xml version='1.0' encoding='UTF-8'?>
        <produto>
           <codigo>$codigo</codigo>
           <descricao>$descricao</descricao>
           <situacao>Ativo</situacao>
           <estoque>$estoque,00</estoque>   
        </produto>";

        $post = array(
           "apikey" => "{apiKey}", 
           "xml" => rawurlencode($xml)
        );
        curl_setopt($ch, CURLOPT_URL, "https://bling.com.br/Api/v2/produto/$codigo/");
        curl_setopt($curl_handle, CURLOPT_POST, count($post));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function executeUpdateProductPrice($codigo, $descricao, $preco)
    { 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://bling.com.br/Api/v2/produto/$codigo/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $xml = "<?xml version='1.0' encoding='UTF-8'?>
        <produto>
           <codigo>$codigo</codigo>
           <descricao>$descricao</descricao>
           <situacao>Ativo</situacao>
           <vlr_unit>$preco</vlr_unit>   
         </produto>";

        $post = array(
           "apikey" => "{apiKey}", 
           "xml" => rawurlencode($xml)
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function updatePreco($id, $nome, $preco) 
    {
        $chaveAplicação = "{apiKey}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://bling.com.br/Api/v2/produto/$codigo/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $xml = "<?xml version='1.0' encoding='UTF-8'?>
        <produto>
           <codigo>$codigo</codigo>
           <descricao>$descricao</descricao>
           <situacao>Ativo</situacao>
           <vlr_unit>$preco</vlr_unit>   
         </produto>";

        $post = array(
           "apikey" => "{apiKey}", 
           "xml" => rawurlencode($xml)
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

 

}


// $instanciaController = new Controller(); 
// $update = $instanciaController->executeUpdateProduct("2453320", "Magnésio Treonato 500mg (60 caps) - BioNutrir Sabor:Único", "263");

// echo $update;

