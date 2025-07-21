<?php
// Database connection settings
$host = "localhost";
$user = "root";
$password = "";
$dbname = "ecom";

// Create connection
$link = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $description = mysqli_real_escape_string($link, $_POST['description']);
    $price = (float) $_POST['price'];
    $category = mysqli_real_escape_string($link, $_POST['category']);

    // Image upload handling
    $image_path = "";
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_dir = "assets/images/uploads/"; // ✅ Correct folder for display
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $image_name = basename($_FILES["image"]["name"]);
        $image_path = $target_dir . $image_name;

        // Move uploaded file
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            die("Image upload failed.");
        }
    }

    // Store only relative path (or just filename)
    $image_to_store = basename($image_path);

    // Insert into DB
    $query = "INSERT INTO product (name, description, product_price, category, image_path)
              VALUES ('$name', '$description', '$price', '$category', '$image_to_store')";

    if (mysqli_query($link, $query)) {
        echo "✅ Product successfully added.";
    } else {
        echo "❌ Error: " . mysqli_error($link);
    }

    // Close DB connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - E-Commerce Admin</title>
    <?php include "./includes/header.php" ?>
</head>
<body>
    <?php include "./includes/navbar.php" ?>
    <div class="container py-5">
        <h2 class="mb-4">Add New Product</h2>
        <form id="addProductForm" action="add-product.php" method="POST" enctype="multipart/form-data" novalidate>
            <div class="mb-3">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="productName" name="name" required>
                <div class="invalid-feedback">Please enter the product name.</div>
            </div>
            <div class="mb-3">
                <label for="productDescription" class="form-label">Description</label>
                <textarea class="form-control" id="productDescription" name="description" rows="3" required></textarea>
                <div class="invalid-feedback">Please enter a description.</div>
            </div>
            <div class="mb-3">
                <label for="productPrice" class="form-label">Price ($)</label>
                <input type="number" class="form-control" id="productPrice" name="price" min="0" step="0.01" required>
                <div class="invalid-feedback">Please enter a valid price.</div>
            </div>
            <div class="mb-3">
                <label for="productCategory" class="form-label">Category</label>
                <input type="text" class="form-control" id="productCategory" name="category" required>
                <div class="invalid-feedback">Please enter a category.</div>
            </div>
            <div class="mb-3">
                <label for="productImage" class="form-label">Product Image</label>
                <input class="form-control" type="file" id="productImage" name="image" accept="image/*" required>
                <div class="invalid-feedback">Please upload a product image.</div>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>
    <?php include "./includes/footer.php" ?>
</body>
</html>
