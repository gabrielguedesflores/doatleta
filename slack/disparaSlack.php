<?php

date_default_timezone_set('America/Sao_Paulo');
$date = date("d-m-Y-Hi");
include "../controllers/Controller.php";
$instanciaController = new Controller();

$dadosJson = file_get_contents("https://bling.com.br/Api/v2/pedidos/json/&filters=idSituacao[6]&apikey={apiKey}");
$dadosJsonDecodificados = json_decode($dadosJson);



foreach ($dadosJsonDecodificados->retorno->pedidos as $key) {
 
    if (count($key->pedido->itens) >= 2 ){

        foreach ($key->pedido->itens as $value) {

            ##envia mensagem slack 
            $slack_webhook = "https://hooks.slack.com/services/{apiKey}/{apiKey}/{apiKey}";

            $pedidoMKSlack = array(
                'text' => 'Pedido Canal de Venda: ' . $key->pedido->numeroPedidoLoja,
            );
            $pedidoSlack = array(
                'text' => 'Novo Pedido: ' . $key->pedido->numero . '                        Marketplace: ' . $key->pedido->tipoIntegracao,
            );
            $dataSlack = array(
                'text' => 'Data: ' . $key->pedido->data . '                 Cliente: ' . $key->pedido->cliente->nome,
            );
            $freteSlack = array(
                'text' => 'Frete: ' . $key->pedido->valorfrete . '                      Total de Produtos: ' . $key->pedido->totalprodutos,
            );
            $totalPedidoSlack = array(
                'text' => 'Total: ' . $key->pedido->totalvenda,
            );
            $itemCabecalhoSlack = array(
                'text' => 'Itens ',
            );

            $pedidoMKSlackJson = json_encode($pedidoMKSlack);
            $pedidoSlackJson = json_encode($pedidoSlack);
            $dataSlackJson = json_encode($dataSlack);
            $freteSlackJson = json_encode($freteSlack);
            $totalPedidoSlackJson = json_encode($totalPedidoSlack);
            $itemCabecalhoSlackJson = json_encode($itemCabecalhoSlack);

            echo $instanciaController->slackSendMessage($pedidoMKSlackJson, $slack_webhook) . '<br>';
            echo $instanciaController->slackSendMessage($pedidoSlackJson, $slack_webhook) . '<br>';
            echo $instanciaController->slackSendMessage($dataSlackJson, $slack_webhook) . '<br>';
            echo $instanciaController->slackSendMessage($clienteSlackJson, $slack_webhook) . '<br>';
            echo $instanciaController->slackSendMessage($freteSlackJson, $slack_webhook) . '<br>';
            echo $instanciaController->slackSendMessage($totalProdutosSlackJson, $slack_webhook) . '<br>';
            echo $instanciaController->slackSendMessage($totalPedidoSlackJson, $slack_webhook) . '<br>';
            echo $instanciaController->slackSendMessage($itemCabecalhoSlackJson, $slack_webhook) . '<br>';

            foreach ($key->pedido->itens as $value) {
                
                $itemSlack = array(
                    'text' => 'Descrição: ' . $value->item->descricao,                                                
                );
                $itemSkuSlack = array(
                    'text' => 'Sku: ' . $value->item->codigo,                                                
                );
                $qtdSlack = array(
                    'text' => 'Quantidade: ' . $value->item->quantidade . '                   Valor Unitário: ' . $value->item->valorunidade
                );
                
                $itemSlackJson = json_encode($itemSlack);
                $itemSkuSlackJson = json_encode($itemSkuSlack);
                $qtdSlackJson = json_encode($qtdSlack);                
                
                echo $instanciaController->slackSendMessage($itemSlackJson, $slack_webhook) . '<br>';
                echo $instanciaController->slackSendMessage($itemSkuSlackJson, $slack_webhook) . '<br>';
                echo $instanciaController->slackSendMessage($qtdSlackJson, $slack_webhook) . '<br>';
            }    

            $valorTotalSlack = array(
                'text' => 'Valor Total: ' . $key->pedido->totalvenda,
            );

            $separadorSlack = array(
                'text' => '=========================',
            );
            
            $valorTotalSlackJson = json_encode($valorTotalSlack);
            $separadorSlackJson = json_encode($separadorSlack);
            echo $instanciaController->slackSendMessage($valorTotalSlackJson, $slack_webhook) . '<br>';
            echo $instanciaController->slackSendMessage($separadorSlackJson, $slack_webhook) . '<br>';

            $url = 'https://bling.com.br/Api/v2/pedido/'.$key->pedido->numero.'/json';
            $xml = '<pedido> <idSituacao>24</idSituacao> </pedido>';
            $posts = array (
                'apikey' => '{apiKey}',
                'xml' => rawurlencode($xml)
            );

            $retorno = $instanciaController->executeUpdateOrder($url, $posts);
            echo $retorno . "<br>";

        }








    #ESTE ELSE É PARA CASO O PEDIDO TENHA APENAS UM ITEM 
    }else{

            foreach ($key->pedido->itens as $value) {

                ##envia mensagem slack 
                $pedidoSlack = array(
                    'text' => 'Pedido: ' . $key->pedido->numero . '                        Marketplace: ' . $key->pedido->tipoIntegracao,
                );
                $pedidoMKSlack = array(
                    'text' => 'Pedido Canal de Venda: ' . $key->pedido->numeroPedidoLoja,
                );
                $dataSlack = array(
                    'text' => 'Data: ' . $key->pedido->data . '                Cliente: ' . $key->pedido->cliente->nome,
                );
                $freteSlack = array(
                    'text' => 'Frete: ' . $key->pedido->valorfrete . '                           Total de Produtos: ' . $key->pedido->totalprodutos,
                );
                $totalPedidoSlack = array(
                    'text' => 'Total: ' . $key->pedido->totalvenda,
                );
                $itemSlack = array(
                    'text' => 'Item: ' . $value->item->descricao . '                   Quantidade: ' . $value->item->quantidade,
                );
                $itemSkuSlack = array(
                    'text' => 'Sku: ' . $value->item->codigo,                                                
                );
                $valorTotalSlack = array(
                    'text' => 'Valor Total: ' . $key->pedido->totalvenda,
                );
                $separadorSlack = array(
                    'text' => '=========================',
                );

                $pedidoMKSlackJson = json_encode($pedidoMKSlack);
                $pedidoSlackJson = json_encode($pedidoSlack);
                $dataSlackJson = json_encode($dataSlack);
                $freteSlackJson = json_encode($freteSlack);
                $totalPedidoSlackJson = json_encode($totalPedidoSlack);
                $itemSlackJson = json_encode($itemSlack);
                $itemSkuSlackJson = json_encode($itemSkuSlack);
                $valorTotalSlackJson = json_encode($valorTotalSlack);
                $separadorSlackJson = json_encode($separadorSlack);

                $slack_webhook = "https://hooks.slack.com/services/{apiKey}/{apiKey}/{apiKey}";
                
                echo $instanciaController->slackSendMessage($pedidoMKSlackJson, $slack_webhook) . '<br>';
                echo $instanciaController->slackSendMessage($pedidoSlackJson, $slack_webhook) . '<br>';
                echo $instanciaController->slackSendMessage($dataSlackJson, $slack_webhook) . '<br>';
                echo $instanciaController->slackSendMessage($freteSlackJson, $slack_webhook) . '<br>';
                //echo $instanciaController->slackSendMessage($totalPedidoSlackJson, $slack_webhook) . '<br>';
                echo $instanciaController->slackSendMessage($itemSlackJson, $slack_webhook) . '<br>';
                echo $instanciaController->slackSendMessage($itemSkuSlackJson, $slack_webhook) . '<br>';
                echo $instanciaController->slackSendMessage($valorTotalSlackJson, $slack_webhook) . '<br>';
                echo $instanciaController->slackSendMessage($separadorSlackJson, $slack_webhook) . '<br>';


                $url = 'https://bling.com.br/Api/v2/pedido/'.$key->pedido->numero.'/json';
                $xml = '<pedido> <idSituacao>24</idSituacao> </pedido>';
                $posts = array (
                    'apikey' => '{apiKey}',
                    'xml' => rawurlencode($xml)
                );

                $retorno = $instanciaController->executeUpdateOrder($url, $posts);
                echo $retorno . "<br>";
            }    
    }
}





