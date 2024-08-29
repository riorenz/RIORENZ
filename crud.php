<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app_dev";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create new product
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $description = $_POST['Description'];
    $price = $_POST['Price'];
    $quantity = $_POST['Quantity'];

    $stmt = $conn->prepare("INSERT INTO products (name, Description, Price, Quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $name, $description, $price, $quantity);
    $stmt->execute();
    $stmt->close();
}

// Delete a product
if (isset($_GET['delete'])) {
    $ID = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE ID=?");
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $stmt->close();
}

// Update a product
if (isset($_POST['edit'])) {
    $ID = $_POST['ID'];
    $name = $_POST['name'];
    $description = $_POST['Description'];
    $price = $_POST['Price'];
    $quantity = $_POST['Quantity'];

    $stmt = $conn->prepare("UPDATE products SET name=?, Description=?, Price=?, Quantity=? WHERE ID=?");
    $stmt->bind_param("ssiii", $name, $description, $price, $quantity, $ID);
    $stmt->execute();
    $stmt->close();
}

// Retrieve all products
$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple CRUD</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        form { margin-bottom: 20px; }
        input, button { padding: 5px; margin: 5px; }
        .btn-delete { color: red; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Product</h1>
    <form action="crud.php" method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="Description" placeholder="Description">
        <input type="text" name="Price" placeholder="Price">
        <input type="text" name="Quantity" placeholder="Quantity">
        <button type="submit" name="create">Create</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['ID']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['Description']); ?></td>
            <td><?php echo htmlspecialchars($row['Price']); ?></td>
            <td><?php echo htmlspecialchars($row['Quantity']); ?></td>
            <td><?php echo htmlspecialchars($row['Created_at']); ?></td>
            <td><?php echo htmlspecialchars($row['Updated_at']); ?></td>
            <td>
                <a href="crud.php?delete=<?php echo htmlspecialchars($row['ID']); ?>" class="btn-delete">Delete</a>
                <form action="crud.php" method="POST" style="display:inline;">
                    <input type="hidden" name="ID" value="<?php echo htmlspecialchars($row['ID']); ?>">
                    <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                    <input type="text" name="Description" value="<?php echo htmlspecialchars($row['Description']); ?>">
                    <input type="text" name="Price" value="<?php echo htmlspecialchars($row['Price']); ?>">
                    <input type="text" name="Quantity" value="<?php echo htmlspecialchars($row['Quantity']); ?>">
                    <button type="submit" name="edit">Edit</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
