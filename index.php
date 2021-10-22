<?php 

require "controllers/Conexao.php";
$instanciaController = new Api();

echo "Olá, mundo!";



$update = $instanciaController->executeUpdateProduct(1, "Thermo Abdomen (120 tabs) - Body Action Sabor:Único", 120);

echo $update;