<?php
$conn = mysqli_connect("localhost:3307", "root", "", "db_cyclepoint");

if (["REQUEST_METHOD"] == "GET") {
    $query = mysqli_query($conn, "SELECT * FROM usuario;");

    while ($response = mysqli_fetch_assoc($query)) {
        echo $response["nome"] . $response["cargo"] . "<br>";
    };

}

?>