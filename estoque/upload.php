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
    <p class="fs-1">Atualização de estoque</p>
        <form action="../controllers/upload.php"  method="POST" enctype="multipart/form-data">
            Selecione o arquivo: ele deve ser chamado products.csv*<br><br>
            <input class="form-control form-control-lg" name="fileToUpload" type="file">
            <!-- <input type="file" name="fileToUpload"> -->
            <br><br>
            <input type="submit" class="btn btn-primary" value="Enviar">
        </form>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
</body>
</html>