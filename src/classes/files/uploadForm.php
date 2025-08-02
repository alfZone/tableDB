<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../bootstrap.php';
//echo __DIR__;
use classes\files\UploadC;
$targetDir = "/home/paa/public_html/uploads2/"
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <title>upload de ficheiro</title>
</head>
<body>
    <h1><?=$targetDir?></h1>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>Nome do Ficheiro</th>
                <th>Tamanho</th>
                <th>Última Modificação</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>...</td>
                <td>.... KB</td>
                <td>...</td>
            </tr>
        </tbody>
    </table>

<input type="file" id="fileInput">
<button id="uploadButton">Fazer Upload</button>




</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
<script>

    <?php
    $a= new UploadC($targetDir);
    $a->JSUploadFileUsingPUT("https://paa.servicos.esmonserrate.org/public/api/upload");
    ?>
    
    document.querySelector("#uploadButton").addEventListener("click", uploadFile);
</script>
    
</html>
