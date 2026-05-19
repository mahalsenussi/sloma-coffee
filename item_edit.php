<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLOMA COFFEE - تعديل الأصناف</title>
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
        
        .table {
            background: white;
            border-radius: 14px;
            overflow: hidden;
        }
        
        .table th {
            background: var(--sloma-green);
            color: white;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #E0E0E0;
        }
        
        .floating-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            border-radius: 30px;
            padding: 15px 30px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.2);
        }
        
        .item-row {
            background: white;
        }
        
        .item-row:nth-child(even) {
            background: #f9f9f9;
        }
        
        @media (max-width: 768px) {
            .table {
                font-size: 0.8rem;
            }
            
            .table th, .table td {
                padding: 8px 4px;
            }
            
            .floating-btn {
                bottom: 10px;
                right: 10px;
                padding: 12px 20px;
            }
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
        <h2 class="text-center" style="color: var(--sloma-green); margin-bottom: 20px;">تعديل الأصناف</h2>

        <?php
        include 'db_connect.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
            $id = intval($_POST['id']);
            $name = $conn->real_escape_string($_POST['name']);
            $description = $conn->real_escape_string($_POST['description']);
            $price = floatval($_POST['price']);
            $category_id = intval($_POST['category_id']);
            $image = '';

            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $targetDir = "img/";
                $targetFile = $targetDir . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
                $image = $conn->real_escape_string($targetFile);
            } else {
                $image = $conn->real_escape_string($_POST['existing_image']);
            }

            $sql = "UPDATE item SET name = '$name', description = '$description', price = $price, category_id = $category_id, image = '$image' WHERE id = $id";

            if ($conn->query($sql) === TRUE) {
                echo '<div class="alert alert-success">تم التحديث بنجاح!</div>';
            } else {
                echo "<div class=\"alert alert-danger\">خطأ في التحديث: {$conn->error}</div>";
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
            $id = intval($_POST['id']);
            $sql = "DELETE FROM item WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                echo '<div class="alert alert-success">تم الحذف بنجاح!</div>';
            } else {
                echo "<div class=\"alert alert-danger\">خطأ في الحذف: {$conn->error}</div>";
            }
        }

        $categoriesSql = "SELECT id, name FROM category";
        $categoriesResult = $conn->query($categoriesSql);

        $selectedCategory = isset($_GET['category']) ? intval($_GET['category']) : 0;

        $sql = "SELECT item.id, item.name, item.description, item.price, item.image, item.category_id, category.name AS category_name
                FROM item
                LEFT JOIN category ON item.category_id = category.id" . 
                ($selectedCategory > 0 ? " WHERE item.category_id = $selectedCategory" : "");

        $result = $conn->query($sql);
        ?>

        <form method="GET" class="mb-3">
            <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                <option value="0">كل الفئات</option>
                <?php 
                $categoriesResult->data_seek(0);
                while ($category = $categoriesResult->fetch_assoc()): ?>
                    <option value="<?= $category['id'] ?>" <?= ($selectedCategory == $category['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($result && $result->num_rows > 0): ?>
            <div style="overflow-x: auto;">
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>الاسم</th>
                            <th>الوصف</th>
                            <th>السعر</th>
                            <th>الفئة</th>
                            <th>صورة</th>
                            <th>حفظ/حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr data-id="<?= $row['id'] ?>" class="item-row">
                                <td><?= $row['id'] ?></td>
                                <td><input type="text" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" /></td>
                                <td><textarea class="form-control" rows="2"><?= htmlspecialchars($row['description']) ?></textarea></td>
                                <td><input type="number" step="0.01" class="form-control" value="<?= htmlspecialchars($row['price']) ?>" /></td>
                                <td>
                                    <select class="form-select">
                                        <?php
                                        $categoriesResult->data_seek(0);
                                        while ($category = $categoriesResult->fetch_assoc()): ?>
                                            <option value="<?= $category['id'] ?>" <?= ($row['category_id'] == $category['id']) ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="file" class="form-control" accept="image/*" style="font-size: 0.8rem;" />
                                    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($row['image']) ?>" />
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm save-btn" style="margin-bottom: 5px;">حفظ</button>
                                    <form action="" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف?');">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">لا توجد أصناف.</div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 20px; margin-bottom: 80px;">
            <a href="dashboard.php" class="btn btn-secondary">العودة للوحة التحكم</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $('.save-btn').on('click', function() {
            const row = $(this).closest('tr');
            const id = row.data('id');
            const name = row.find('input[type="text"]').val();
            const description = row.find('textarea').val();
            const price = row.find('input[type="number"]').val();
            const category_id = row.find('select').val();
            const imageInput = row.find('input[type="file"]')[0];
            const existingImage = row.find('input[name="existing_image"]').val();

            const formData = new FormData();
            formData.append('id', id);
            formData.append('name', name);
            formData.append('description', description);
            formData.append('price', price);
            formData.append('category_id', category_id);
            formData.append('existing_image', existingImage);
            if (imageInput.files.length > 0) {
                formData.append('image', imageInput.files[0]);
            }

            formData.append('action', 'update');

            $.ajax({
                url: '',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert('تم التحديث بنجاح!');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    alert('خطأ في التحديث: ' + error);
                }
            });
        });
    </script>
</body>
</html>