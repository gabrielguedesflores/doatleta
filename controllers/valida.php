<?php
ob_start();
session_start();
include "Controller.php";
$instanciaController = new Controller;
include "connection.php";
$conn = getConnection();


echo '<pre>';
print_r($_POST);
echo '</pre>';
$preco = str_replace(',', '.', $_POST['preco_bling']);
echo 'Atualizar Bling com o preço R$ ' . $preco . '<br>';

$result = $instanciaController->executeUpdateProductPrice($_POST['id_produto'], $_POST['nome_bling'], $preco);
echo $result;

$sqlUpdate = "UPDATE produtos_bling SET preco = :preco WHERE produto_id = :id_produto;";
$stmt = $conn->prepare($sqlUpdate);

$stmt->bindValue(':id_produto', $_POST['id_produto']);
$stmt->bindValue(':preco', $_POST['preco_bling']);

if($stmt->execute()){
    $status3 = 1;
}else{
    $status3 = 0;
}

$_SESSION["atualizaPreco"] = "O preço do produto " . $_POST['nome_bling'] . " foi atualizado com sucesso!";

header("Location: ../atualiza_preco.php");



