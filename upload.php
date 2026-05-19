<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$targetDir = "img/";
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLOMA COFFEE - إضافة</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --sloma-green: #2E7D32;
            --sloma-green-dark: #1B5E20;
            --sloma-green-light: #E8F5E9;
        }
        
        body {
            background-color: var(--sloma-green-light) !important;
            font-family: "Segoe UI", "Cairo", "Helvetica", Arial, sans-serif;
        }
        
        .btn-primary {
            background-color: var(--sloma-green) !important;
            border-color: var(--sloma-green) !important;
        }
        
        .btn-primary:hover {
            background-color: var(--sloma-green-dark) !important;
            border-color: var(--sloma-green-dark) !important;
        }
        
        .header-hero {
            background: linear-gradient(
                rgba(46, 125, 50, 0.7),
                rgba(46, 125, 50, 0.7)
            ),
            url('img/web3.jpg') center/cover no-repeat;
        }
        
        .form-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.12);
            margin-bottom: 20px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #E0E0E0;
            margin-bottom: 15px;
        }
        
        .form-control:focus {
            border-color: var(--sloma-green);
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="w-100 header-hero" style="height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <img src="img/sloma.jpg" alt="SLOMA COFFEE" style="height: 60px; border-radius: 50%; margin-bottom: 10px;">
        <h4 class="text-white" style="font-weight: bold;">SLOMA COFFEE</h4>
    </div>

    <div class="container" style="padding: 20px;">
        <h2 class="text-center" style="color: var(--sloma-green); margin-bottom: 20px;">إضافة فئة أو صنف</h2>
        
        <?php
        include 'db_connect.php';
        
        if (isset($_POST['addCategory'])) {
            $categoryName = $_POST['categoryName'];
            $categoryLink = $_POST['categoryLink'];

            $categoryImage = $_FILES['categoryImage']['name'];
            $targetFile = $targetDir . basename($categoryImage);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES['categoryImage']['tmp_name']);
            if ($check === false) {
                echo '<div class="alert alert-danger">File is not an image.</div>';
                $uploadOk = 0;
            }

            if ($_FILES['categoryImage']['size'] > 500000) {
                echo '<div class="alert alert-danger">Sorry, your file is too large.</div>';
                $uploadOk = 0;
            }

            if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                echo '<div class="alert alert-danger">Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>';
                $uploadOk = 0;
            }

            if ($uploadOk === 1) {
                if (move_uploaded_file($_FILES['categoryImage']['tmp_name'], $targetFile)) {
                    $stmt = $conn->prepare("INSERT INTO category (name, link, image) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $categoryName, $categoryLink, $targetFile);
                    if ($stmt->execute()) {
                        echo '<div class="alert alert-success">Category added successfully!</div>';
                    } else {
                        echo '<div class="alert alert-danger">Error adding category: ' . $conn->error . '</div>';
                    }
                    $stmt->close();
                } else {
                    echo '<div class="alert alert-danger">Sorry, there was an error uploading your file.</div>';
                }
            }
        }

        if (isset($_POST['addItem'])) {
            $itemName = $_POST['itemName'];
            $itemDescription = $_POST['itemDescription'];
            $itemPrice = $_POST['itemPrice'];
            $itemCategory = $_POST['itemCategory'];

            $itemImage = $_FILES['itemImage']['name'];
            $targetFile = $targetDir . basename($itemImage);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES['itemImage']['tmp_name']);
            if ($check === false) {
                echo '<div class="alert alert-danger">File is not an image.</div>';
                $uploadOk = 0;
            }

            if ($_FILES['itemImage']['size'] > 500000) {
                echo '<div class="alert alert-danger">Sorry, your file is too large.</div>';
                $uploadOk = 0;
            }

            if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                echo '<div class="alert alert-danger">Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>';
                $uploadOk = 0;
            }

            if ($uploadOk === 1) {
                if (move_uploaded_file($_FILES['itemImage']['tmp_name'], $targetFile)) {
                    $stmt = $conn->prepare("INSERT INTO item (name, description, price, category_id, image) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssdiss", $itemName, $itemDescription, $itemPrice, $itemCategory, $targetFile);
                    if ($stmt->execute()) {
                        echo '<div class="alert alert-success">Item added successfully!</div>';
                    } else {
                        echo '<div class="alert alert-danger">Error adding item: ' . $conn->error . '</div>';
                    }
                    $stmt->close();
                } else {
                    echo '<div class="alert alert-danger">Sorry, there was an error uploading your file.</div>';
                }
            } else {
                $stmt = $conn->prepare("INSERT INTO item (name, description, price, category_id, image) VALUES (?, ?, ?, ?, ?)");
                $imagePlaceholder = '';
                $stmt->bind_param("ssdiss", $itemName, $itemDescription, $itemPrice, $itemCategory, $imagePlaceholder);
                if ($stmt->execute()) {
                    echo '<div class="alert alert-success">Item added without image successfully!</div>';
                } else {
                    echo '<div class="alert alert-danger">Error adding item: ' . $conn->error . '</div>';
                }
                $stmt->close();
            }
        }

        $conn->close();
        ?>

        <!-- Category Upload Form -->
        <div class="form-card">
            <h4 style="color: var(--sloma-green);">إضافة فئة جديدة</h4>
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <label for="categoryName">اسم الفئة</label>
                <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                
                <label for="categoryLink">رابط الفئة</label>
                <input type="text" class="form-control" id="categoryLink" name="categoryLink" required>
                
                <label for="categoryImage">صورة الفئة</label>
                <input type="file" class="form-control" id="categoryImage" name="categoryImage" accept="image/*" required>
                
                <button type="submit" name="addCategory" class="btn btn-primary btn-block">إضافة الفئة</button>
            </form>
        </div>

        <!-- Item Upload Form -->
        <div class="form-card">
            <h4 style="color: var(--sloma-green);">إضافة صنف جديد</h4>
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <label for="itemName">اسم الصنف</label>
                <input type="text" class="form-control" id="itemName" name="itemName" required>
                
                <label for="itemDescription">وصف الصنف</label>
                <textarea class="form-control" id="itemDescription" name="itemDescription" required></textarea>
                
                <label for="itemPrice">السعر (LYD)</label>
                <input type="number" step="0.01" class="form-control" id="itemPrice" name="itemPrice" required>
                
                <label for="itemCategory">اختر الفئة</label>
                <select class="form-control" id="itemCategory" name="itemCategory" required> 
                    <?php
                    include 'db_connect.php';
                    $sql = "SELECT * FROM category";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                        }
                    }
                    $conn->close();
                    ?>
                </select>
                
                <label for="itemImage">صورة الصنف</label>
                <input type="file" class="form-control" id="itemImage" name="itemImage" accept="image/*" required>
                
                <button type="submit" name="addItem" class="btn btn-primary btn-block">إضافة الصنف</button>
            </form>
        </div>

        <div style="text-align: center;">
            <a href="dashboard.php" class="btn btn-secondary">العودة للوحة التحكم</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>