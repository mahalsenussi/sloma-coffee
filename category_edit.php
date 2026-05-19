<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLOMA COFFEE - تعديل الفئات</title>
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
        
        .form-control {
            border-radius: 8px;
            border: 1px solid #E0E0E0;
        }
        
        @media (max-width: 768px) {
            .table {
                font-size: 0.8rem;
            }
            
            .table th, .table td {
                padding: 6px 4px;
            }
            
            .form-control {
                font-size: 0.85rem;
                padding: 6px;
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
        <h2 class="text-center" style="color: var(--sloma-green); margin-bottom: 20px;">تعديل الفئات</h2>

        <?php
        require_once 'db_connect.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] == 'update') {
                $id = intval($_POST['id']);
                $name = trim($conn->real_escape_string($_POST['name']));
                $link = trim($conn->real_escape_string($_POST['link']));
                $display_order = intval($_POST['display_order']);
                
                if (empty($name)) {
                    echo '<div class="alert alert-danger">Category name cannot be empty!</div>';
                } elseif (empty($link)) {
                    echo '<div class="alert alert-danger">Category link cannot be empty!</div>';
                } else {
                    $image = '';

                    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                        $targetDir = "img/";
                        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
                        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
                        $image = $conn->real_escape_string($targetFile);
                    } else {
                        $image = $conn->real_escape_string($_POST['existing_image']);
                    }

                    $sql = "UPDATE category SET name = '$name', link = '$link', image = '$image', display_order = $display_order WHERE id = $id";

                    if ($conn->query($sql) === TRUE) {
                        echo '<div class="alert alert-success">تم التحديث بنجاح!</div>';
                    } else {
                        echo "<div class='alert alert-danger'>Error updating category: {$conn->error}</div>";
                    }
                }
            }

            if ($_POST['action'] == 'update_all') {
                foreach ($_POST['items'] as $id => $item) {
                    $id = intval($id);
                    $name = trim($conn->real_escape_string($item['name']));
                    $link = trim($conn->real_escape_string($item['link']));
                    $display_order = intval($item['display_order']);
                    $image = $conn->real_escape_string($item['existing_image']);

                    if (empty($name) || empty($link)) {
                        continue;
                    }

                    if (isset($_FILES['items']['tmp_name'][$id]['image']) && $_FILES['items']['tmp_name'][$id]['image'] != '') {
                        $targetDir = "img/";
                        $targetFile = $targetDir . basename($_FILES['items']['name'][$id]['image']);
                        move_uploaded_file($_FILES['items']['tmp_name'][$id]['image'], $targetFile);
                        $image = $conn->real_escape_string($targetFile);
                    }

                    $sql = "UPDATE category SET name = '$name', link = '$link', image = '$image', display_order = $display_order WHERE id = $id";

                    if (!$conn->query($sql)) {
                        echo "<div class='alert alert-danger'>Error updating category with ID {$id}: {$conn->error}</div>";
                    }
                }
                echo '<div class="alert alert-success">تم تحديث كل الفئات بنجاح!</div>';
            }
        }

        $sql = "SELECT id, name, link, image, display_order FROM category ORDER BY display_order ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0): ?>
            <form id="updateForm" method="post" enctype="multipart/form-data">
                <div style="overflow-x: auto;">
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>الاسم</th>
                                <th>الرابط</th>
                                <th>الصورة</th>
                                <th>الترتيب</th>
                                <th>حفظ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><input type="text" class="form-control" name="items[<?= $row['id'] ?>][name]" value="<?= htmlspecialchars($row['name']) ?>" required></td>
                                    <td><input type="text" class="form-control" name="items[<?= $row['id'] ?>][link]" value="<?= htmlspecialchars($row['link']) ?>" required></td>
                                    <td>
                                        <input type="file" class="form-control" name="items[<?= $row['id'] ?>][image]" accept="image/*" style="font-size: 0.8rem;">
                                        <input type="hidden" name="items[<?= $row['id'] ?>][existing_image]" value="<?= htmlspecialchars($row['image']) ?>">
                                        <?php if ($row['image']): ?>
                                            <img src="<?= htmlspecialchars($row['image']) ?>" alt="Category Image" style="max-width: 60px; margin-top: 5px; border-radius: 8px;">
                                        <?php endif; ?>
                                    </td>
                                    <td><input type="number" class="form-control" name="items[<?= $row['id'] ?>][display_order]" value="<?= htmlspecialchars($row['display_order']) ?>" style="width: 60px;"></td>
                                    <td><button type="button" class="btn btn-success btn-sm update-btn" data-id="<?= $row['id'] ?>">حفظ</button></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <input type="hidden" name="action" value="update_all">
                    <button type="submit" class="btn btn-success">حفظ كل التغييرات</button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">لا توجد فئات.</div>
        <?php endif;

        $conn->close();
        ?>

        <div style="text-align: center; margin-top: 20px;">
            <a href="dashboard.php" class="btn btn-secondary">العودة للوحة التحكم</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).on('click', '.update-btn', function () {
            const id = $(this).data('id');
            const row = $(this).closest('tr');
            const name = row.find('input[name$="[name]"]').val();
            const link = row.find('input[name$="[link]"]').val();
            const display_order = row.find('input[name$="[display_order]"]').val();
            const existing_image = row.find('input[name$="[existing_image]"]').val();
            
            if (!name || name.trim() === '') {
                alert('Category name cannot be empty!');
                return;
            }
            if (!link || link.trim() === '') {
                alert('Category link cannot be empty!');
                return;
            }
            
            const formData = new FormData();
            formData.append('id', id);
            formData.append('name', name);
            formData.append('link', link);
            formData.append('display_order', display_order);
            formData.append('existing_image', existing_image);
            formData.append('action', 'update');

            const imageInput = row.find('input[type="file"]')[0];
            if (imageInput.files.length > 0) {
                formData.append('image', imageInput.files[0]);
            }

            $.ajax({
                url: '',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: (response) => {
                    alert('تم التحديث بنجاح!');
                    location.reload();
                },
                error: (xhr, status, error) => {
                    alert('Failed to update the category. Error: ' + error);
                }
            });
        });
    </script>
</body>
</html>