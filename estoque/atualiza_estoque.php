<?php 
set_time_limit(3000);
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('America/Sao_Paulo');
$date = date("d-m-Y-Hi");
$path = getcwd();
include "../controllers/connection.php";
$conn = getConnection();


##LIMPA AS TABELAS 

echo "Passo 1: limpeza de base. <br>";

#deleta produtos_franq
$sqlDeleteBling = "DELETE FROM produtos_bling";
$stmt = $conn->prepare($sqlDeleteBling);

if($stmt->execute()){
    echo "<br>Produtos_bling deletada com sucesso! <br>";
    $status2 = 1;
}else{
    echo "<br>Erro ao deletar produtos_bling<br>";
    $status2 = 0;
}

$sqlDeleteFranq = "DELETE FROM produtos_franq;";
$stmt = $conn->prepare($sqlDeleteFranq);
$status1 = "";

#deleta produtos_bling
if($stmt->execute()){
    echo "<br>Produtos_franq deletada com sucesso! <br>";
    $status1 = 1;
}else{
    echo "<br>Erro ao deletar produtos_bling<br>";
    $status1 = 0;
}

#valida delete
if($status1 === 1 && $status2 === 1){
    echo "<br>Passo 1 Status: OK <br>";
}else{
    echo "<br>Passo 1 Status: Executado com falhas!  <br>";
}

echo "<br>=========<br><br>";




echo "<br>";






##insert no banco dados da planilha

echo "Passo 2: percorrendo a planilha da franqueadora e inserindo no banco de dados. <br>";

$estoque = 0;
$file = fopen('../uploads/products.csv', 'r');
$count = 0;

while (($line = fgetcsv($file, 0, ';')) !== false){
    $count++;
    if ($count == 1){ continue; }
    $id = $line[0];

    $nome = utf8_decode($line[1]);
    $nome_alt = str_replace("'", "", $nome);
    
    if ($line[5] == null){
        $estoque = 0;
    }else{
        $estoque = $line[5];
    }    

    //$preco = $line[8];
    
    //echo $id . ' - ' . $nome_alt . ' - ' . $estoque . ' - Preço: ' . $preco . ' <br>';
    $sqlInsertFranq = "INSERT INTO produtos_franq (produto_id, nome, estoque) VALUES (:id, :nome, :estoque);";
    $stmt = $conn->prepare($sqlInsertFranq);

    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':nome', $nome_alt);
    $stmt->bindValue(':estoque', $estoque);
    //$stmt->bindValue(':preco', $preco);

    $stmt->execute();
    $status3 = 1;
}

if($status3 === 1){
    echo "<br>Produtos inseridos com sucesso<br>";
    echo "<br>Passo 2: Status: OK <br>";
}else{
    echo "<br>Passo 2 Status: Executado com falhas!  <br>";
}
fclose($file);
echo "<br>=========<br><br>";








echo "Passo 3:  inserindo no banco de dados os produtos do Bling. <br>";

 ## VALIDA SE OS REGISTROS FOREM IGUAIS E INSERE NO BANCO

for ($pages=1; $pages <= 16; $pages++) { 
    $dadosJson = file_get_contents("https://bling.com.br/Api/v2/produtos/page=$pages/json/&apikey={apiKey}&estoque=S");

    $dadosJsonDecodificados = json_decode($dadosJson);

    foreach ($dadosJsonDecodificados->retorno->produtos as $key) {

        foreach ($key->produto->depositos as $value) {

            $idBling = $key->produto->codigo;
            $nomeBling = $key->produto->descricao;
            $estoqueBling = $value->deposito->saldo;
            //$precoBling = number_format($key->produto->preco, 2, ',', ' ');

            $pos = strpos($idBling, 'BNS');

            if($pos !== FALSE){
               $dummy = "";
            }else{
                $sqlInsertBling = "INSERT INTO produtos_bling (produto_id, nome, estoque) VALUES (:id, :nome, :estoque);";
                
                $stmt = $conn->prepare($sqlInsertBling);

                $stmt->bindValue(':id', $idBling);
                $stmt->bindValue(':nome', $nomeBling);
                $stmt->bindValue(':estoque', $estoqueBling);
                //$stmt->bindValue(':preco', $precoBling);
                $status4 = 1;

                if($stmt->execute()){
                }else{
                }
            }
        }
    }    
}
if($status4 === 1){
    echo "<br>Produtos inseridos com sucesso<br>";
    echo "<br>Passo 3: Status: OK <br>";
}else{
    echo "<br>Passo 3 Status: Executado com falhas!  <br>";
}

echo "<br>=========<br><br>";


##valida o estoque 

echo "Passo 4:  Compara os estoques e atualiza Bling. <br>";

include "../controllers/Controller.php";
include "../controllers/Conexao.php";

$api = new Api();
$instanciaController = new Controller();

$sqlSelect = "
select 
produtos_franq.produto_id as id_franq,
produtos_franq.nome as nome_franq,
produtos_franq.estoque as estoque_franq,
produtos_bling.produto_id as id_bling,
produtos_bling.nome as nome_bling,
produtos_bling.estoque as estoque_bling
from produtos_franq	
inner join produtos_bling
	on produtos_franq.produto_id = produtos_bling.produto_id;";

$stmt = $conn->prepare($sqlSelect);
$stmt->execute();
$result = $stmt->fetchAll();

foreach ($result as $key) { 

    $idBlingProd = $key['id_bling'];
    $nomeBlingProd = $key['nome_bling'];
    $estoqueBlingProd = $key['estoque_bling'];
    $estoqueFranqProd = $key['estoque_franq'];

   if($estoqueBlingProd != $estoqueFranqProd){

    //echo $nomeBlingProd . " - Estoque Franq Bling: " . $estoqueFranqProd . ' - ' . $estoqueBlingProd . '<br>';
    $retorno = $api->executeUpdateProduct($idBlingProd, $nomeBlingProd, $estoqueFranqProd);
    //echo $retorno;

    }else{
    }
}

echo "Passo 4:  Status OK. <br>";

echo "<br>Finalizado.<br>";

