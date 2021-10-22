<?php

$target_dir = "../uploadPreco/";
$target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
$uploadOk = 1;

$dir = "../uploadPreco/";
$di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
$ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

foreach ( $ri as $file ) {
    $file->isDir() ?  rmdir($file) : unlink($file);
}

##verifica se a imagem é real e passa para a pasta temporária
if(isset($_POST['submit'])) {
    $check = getimagesize($_FILES['fileToUpload']['tmp_name']); //nome do arquivo temporario, antes de upar o arquivo no server
}

##verifico se o dir existe $target_dir existe, caso contrário crio  
    if(!file_exists('uploads')){
        mkdir('uploads');
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <title>Send File</title>
</head>
<body>
    <br>
    <div style="margin-left: 10px; margin-right: 10px; margin-top: 150px; ">
    <p class="fs-1">Atualização de Preço</p>
        <?php

         // movo o arquivo da pasta temporaria /tmp para a /uploads
        if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
            echo "O arquivo " . basename($_FILES['fileToUpload']['name']) . " foi enviado com sucesso! <br><br>";
            echo "<p class='fs-4'>Clique no botão abaixo para atualizar o preço. </p><br><br>";
            echo "<button type='button'><a href='../preco/atualiza_preco.php'>Atualizar preço</a></button>";
        }else{
            echo "Desculpe, houve um erro! Verifique se o nome do arquivo é products.csv <br>";
        }

        ?>
        
    </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
</body>
</html>

   


