<!-- PAGINA QUANDO O USUARIO FOR LOGADO, UTILIZA SESSAO DO LOGIN.PHP  -->
<?php
session_start();
$id_logado = $_SESSION['id_usuario'];
$nome_logado = $_SESSION['nome_usuario'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>PARABÉNS <?php echo $nome_logado?>, VOCÊ ESTÁ LOGADO NO SISTEMA</h1>
</body>
</html>


