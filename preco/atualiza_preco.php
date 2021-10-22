<?php 
set_time_limit(2000);
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
$file = fopen('../uploadPreco/products.csv', 'r');
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

    $preco = $line[8];
    
    //echo $id . ' - ' . $nome_alt . ' - ' . $estoque . ' - Preço: ' . $preco . ' <br>';
    $sqlInsertFranq = "INSERT INTO produtos_franq (produto_id, nome, estoque, preco) VALUES (:id, :nome, :estoque, :preco);";
    $stmt = $conn->prepare($sqlInsertFranq);

    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':nome', $nome_alt);
    $stmt->bindValue(':estoque', $estoque);
    $stmt->bindValue(':preco', $preco);

    $stmt->execute();

}

    echo "<br>Produtos inseridos com sucesso<br>";
    echo "<br>Passo 2: Status: OK <br>";

fclose($file);
echo "<br>=========<br><br>";








echo "<br>";
//echo "<br> SLEEP <br>"; 
//sleep(2);






echo "Passo 3:  inserindo no banco de dados os produtos do Bling. <br>";

## VALIDA SE OS REGISTROS FOREM IGUAIS E INSERE NO BANCO

for ($pages=1; $pages <= 16; $pages++) {
    $dadosJson = file_get_contents("https://bling.com.br/Api/v2/produtos/page=$pages/json/&apikey={apiKey}");

    $dadosJsonDecodificados = json_decode($dadosJson);

    foreach ($dadosJsonDecodificados->retorno->produtos as $key) {

            $idBling = $key->produto->codigo;
            $nomeBling = $key->produto->descricao;

            // echo '<pre>';
            // print_r($key);
            // echo '</pre>';   
            //$precoBling = number_format($key->produto->preco, 2, ',', ' ');

            $pos = strpos($idBling, 'BNS');

            if($pos !== FALSE){
               $dummy = "";
            }else{
                $sqlInsertBling = "INSERT INTO produtos_bling (produto_id, nome) VALUES (:id, :nome);";
                
                $stmt = $conn->prepare($sqlInsertBling);

                $stmt->bindValue(':id', $idBling);
                $stmt->bindValue(':nome', $nomeBling);
                $status4 = 1;

                if($stmt->execute()){
                    $dummy2 = "";
                }else{
                    echo "Erro ao inserir $idBling <br>";
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

echo "<br> SLEEP <br>"; 
//sleep(3);

##valida o estoque 

echo "Passo 4:  Compara os estoques e atualiza preço dos produtos no Bling. <br>";

include "../controllers/Controller.php";
$instanciaController = new Controller();
$sqlSelect = "
select 
produtos_franq.preco as preco_franq,
produtos_bling.produto_id as id_bling,
produtos_bling.nome as nome_bling
from produtos_franq	
inner join produtos_bling
	on produtos_franq.produto_id = produtos_bling.produto_id;";

$stmt = $conn->prepare($sqlSelect);
$stmt->execute();
$result = $stmt->fetchAll();

foreach ($result as $key) { 

    $update = $instanciaController->executeUpdateProductPrice($key['id_bling'], $key['nome_bling'], $key['preco_franq']);
    //echo $update . '<br>';
}
$status5 = 1;
if($status5 === 1){
    echo "<br>Estoque atualizado com sucesso!<br>";
    echo "<br>Passo 4: Status: OK <br>";
}else{
    echo "<br>Passo 4 Status: Executado com falhas!  <br>";
}
echo "<br>=========<br><br>";


echo "<br>Finalizado.<br>";

