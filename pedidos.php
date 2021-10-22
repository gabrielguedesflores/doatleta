<?php

date_default_timezone_set('America/Sao_Paulo');
$date = date("d-m-Y-Hi");
$path = getcwd();
include "controllers/Controller.php";
$instanciaController = new Controller();

$dadosJson = file_get_contents("https://bling.com.br/Api/v2/pedidos/json/&filters=idSituacao[24]&apikey={apiKey}");

$dadosJsonDecodificados = json_decode($dadosJson);

#INSERE O CABEÇALHO DO ARQUIVO
$cabecalho = array(
    'header' => "numeroPedido|data|tipoIntegracao|numeroPedidoLoja|codigoProduto|qtdProduto|nomeProduto|nomeCliente|foneCliente|cpfcnpjCliente|cepCliente|enderecoCliente|numeroCliente|complementoCliente|bairroCliente|cidadeCliente|emailCliente|totalProdutos|valorFrete|totalVenda|situacao",
);

$file = "/pedidos/pedido" . $date . ".csv";
$fp = fopen($path . $file, "w");

fputcsv($fp , $cabecalho); 

#LAÇO PARA PERCORRER O JSON COM OS PEDIDOS 
#OBS: no if foram 2 laços para pegar os itens caso tenha mais de 1 
foreach ($dadosJsonDecodificados->retorno->pedidos as $key) {
 
    if (count($key->pedido->itens) >= 2 ){

        $row = array( 
                
            'numeroPedido' => $key->pedido->numero,  
            'data' => $key->pedido->data,
            'tipoIntegracao' => $key->pedido->tipoIntegracao,
            'numeroPedidoLoja' => $key->pedido->numeroPedidoLoja,
            'codigoProduto' => null,
            'qtdProduto' => null,
            'nomeProduto' => 'OS ' . count($key->pedido->itens) . ' PRODUTOS SEGUEM NAS LINHAS ABAIXO**********************************',
            'nomeCliente' => $key->pedido->cliente->nome,
            'foneCliente' => $key->pedido->cliente->fone,
            'cpfcnpjCliente' => $key->pedido->cliente->cnpj,
            'cepCliente' => $key->pedido->cliente->cep,
            'enderecoCliente' => $key->pedido->cliente->endereco,
            'numeroCliente' => $key->pedido->cliente->numero,
            'complementoCliente' => $key->pedido->cliente->complemento, 
            'bairroCliente' => $key->pedido->cliente->bairro,
            'cidadeCliente' => $key->pedido->cliente->cidade,
            'emailCliente' => $key->pedido->cliente->email,       
            'totalProdutos' => $key->pedido->totalprodutos,
            'valorFrete' => $key->pedido->valorfrete,
            'totalVenda' => $key->pedido->totalvenda,
            'situacao' => $key->pedido->situacao,
        );

        fputcsv($fp, $row, "|");


        foreach ($key->pedido->itens as $value) {

            $itens = array( 
        
                'numeroPedido' => $key->pedido->numero,  
                'data' => null,
                'tipoIntegracao' => null,
                'numeroPedidoLoja' => null,
                'codigoProduto' => $value->item->codigo,
                'qtdProduto' => $value->item->quantidade,
                'nomeProduto' => $value->item->descricao,
                'nomeCliente' => null,
                'foneCliente' => null,
                'cpfcnpjCliente' => null,
                'cepCliente' => null,
                'enderecoCliente' => null,
                'numeroCliente' => null,
                'complementoCliente' => null, 
                'bairroCliente' => null,
                'cidadeCliente' => null,
                'emailCliente' => null,        
                'totalProdutos' => null,
                'valorFrete' => null,
                'totalVenda' => null,
                'situacao' => null,
            );
            
            fputcsv($fp, $itens, "|");

            $url = 'https://bling.com.br/Api/v2/pedido/'.$key->pedido->numero.'/json';
            $xml = '<pedido> <idSituacao>9</idSituacao> </pedido>';
            $posts = array (
                'apikey' => '{apiKey}',
                'xml' => rawurlencode($xml)
            );

            $retorno = $instanciaController->executeUpdateOrder($url, $posts);
            echo $retorno . "<br>";
            sleep(1);
        }

    #ESTE ELSE É PARA CASO O PEDIDO TENHA APENAS UM ITEM 
    }else{

            foreach ($key->pedido->itens as $value) {

                $row = array( 

                    'numeroPedido' => $key->pedido->numero,  
                    'data' => $key->pedido->data,
                    'tipoIntegracao' => $key->pedido->tipoIntegracao,
                    'numeroPedidoLoja' => $key->pedido->numeroPedidoLoja,
                    'codigoProduto' => $value->item->codigo,
                    'qtdProduto' => $value->item->quantidade,
                    'nomeProduto' => $value->item->descricao,
                    'nomeCliente' => $key->pedido->cliente->nome,
                    'foneCliente' => $key->pedido->cliente->fone,
                    'cpfcnpjCliente' => $key->pedido->cliente->cnpj,
                    'cepCliente' => $key->pedido->cliente->cep,
                    'enderecoCliente' => $key->pedido->cliente->endereco,
                    'numeroCliente' => $key->pedido->cliente->numero,
                    'complementoCliente' => $key->pedido->cliente->complemento, 
                    'bairroCliente' => $key->pedido->cliente->bairro,
                    'cidadeCliente' => $key->pedido->cliente->cidade,
                    'emailCliente' => $key->pedido->cliente->email,       
                    'totalProdutos' => $key->pedido->totalprodutos,
                    'valorFrete' => $key->pedido->valorfrete,
                    'totalVenda' => $key->pedido->totalvenda,
                    'situacao' => $key->pedido->situacao,
                    //'uf_cliente' => $key->pedido->cliente->uf,
                    //'loja' => $key->pedido->loja,      
                );     
                
                fputcsv($fp, $row, "|");

                $url = 'https://bling.com.br/Api/v2/pedido/'.$key->pedido->numero.'/json';
                $xml = '<pedido> <idSituacao>9</idSituacao> </pedido>';
                $posts = array (
                    'apikey' => '{apiKey}',
                    'xml' => rawurlencode($xml)
                );

                $retorno = $instanciaController->executeUpdateOrder($url, $posts);
                echo $retorno . "<br>";
                sleep(1);
            }    
    }
}

