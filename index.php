<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    
<div class="header">
    <!-- Lägg till en div med klassen "connection-status" -->
    <div class="connection-status">
        <?php
        // Deklarera variabeln $connection innan den används
        $connection = getConnection();

        // check connection
        if ($connection->connect_error != null) {
            echo "Anslutningen misslyckades: " . $connection->connect_error;
        } else {
            echo "Anslutningen lyckades.";
        }
        ?>
    </div>
</div>

<?php

// skapa anslutning till databasen
function getConnection(){
$host = "localhost";
$port = 3306;
$database = "databas1";
$username = "root";
$password = "";

// create connection to database
$connection = new mysqli($host, $username, $password, $database, $port);
return $connection;
}

// Använder JOIN för att kombinera tabellerna customers och orders (kopplade genom customer_id)
$query1="SELECT products.product_id, products.name, COUNT(reviews.review_id) AS review_count
FROM products
LEFT JOIN reviews ON products.product_id = reviews.product_id
GROUP BY products.product_id, products.name
HAVING review_count >= 10;";


// variablerna $productName och $reviewCount binds till respektive kolumner
$stmt = $connection->prepare($query1);
$stmt->execute();
$stmt->bind_result($productID, $productName, $reviewCount);


if ($stmt === false) {
    die("Fel vid SQL-frågan: " . $connection->error);
}

// Loopa genom resultatet och visa produkter i en div
echo "<div class='product-list'>";
while ($stmt->fetch()) {
    echo "<div class='product'>";
    echo "<h3>Produktnamn: $productName</h3>";
    echo "<p>Antal recensioner: $reviewCount</p>";
    echo "</div>";
}
echo "</div>"; // Avsluta produktlistan




// check connection
if($connection->connect_error != null){
    die("Anslutningen misslyckades: " . $connection->connect_error);
}else{
    echo "Anslutningen lyckades.";
}



// stäng anslutningen till databasen
$connection->close();
    
?>

</body>
</html>