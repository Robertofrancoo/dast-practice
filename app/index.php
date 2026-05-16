<?php
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'rootpassword';
$db   = getenv('DB_NAME') ?: 'testdb';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ⚠️ VULNERABILIDAD INTENCIONAL - SQL Injection
// Nunca hagas esto en producción
$name = $_GET['name'] ?? 'admin';
$query = "SELECT * FROM users WHERE name = '$name'";
$result = $conn->query($query);

echo "<h2>ZAP Scanning Report - SQL Injection Demo</h2>";
echo "<p>Query ejecutada: <code>$query</code></p>";

if ($result && $result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Password</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['password'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No results found</p>";
}

// ⚠️ VULNERABILIDAD INTENCIONAL - XSS
$search = $_GET['search'] ?? '';
echo "<p>Buscaste: $search</p>";

// ⚠️ VULNERABILIDAD INTENCIONAL - Exposición de datos
echo "<!-- DB credentials: $user:$pass@$host/$db -->";
?>

<form method="GET">
    <input type="text" name="name" placeholder="Buscar usuario">
    <input type="text" name="search" placeholder="Search">
    <button type="submit">Buscar</button>
</form>
