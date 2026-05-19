<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_changes'])) {
        foreach ($_POST['items'] as $item_id => $is_featured) {
            $is_featured = $is_featured ? 1 : 0;
            $sql = "UPDATE item SET is_featured = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $is_featured, $item_id);
            $stmt->execute();
            $stmt->close();
        }
        echo '<div class="alert alert-success text-center">تم حفظ التغييرات بنجاح!</div>';
    }
}

$categories = [];
$categorySql = "SELECT * FROM category";
$categoryResult = $conn->query($categorySql);
if ($categoryResult->num_rows > 0) {
    while ($row = $categoryResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

$filter_category = isset($_GET['category']) ? intval($_GET['category']) : null;
$sql = "SELECT * FROM item";
if ($filter_category) {
    $sql .= " WHERE category_id = $filter_category";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLOMA COFFEE - المميزة</title>
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
    </style>
</head>
<body>
    <!-- Header -->
    <div class="w-100 header-hero" style="height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <img src="img/sloma.jpg" alt="SLOMA COFFEE" style="height: 60px; border-radius: 50%; margin-bottom: 10px;">
        <h4 class="text-white" style="font-weight: bold;">SLOMA COFFEE</h4>
    </div>

    <div class="container" style="padding: 20px;">
        <h2 class="text-center" style="color: var(--sloma-green); margin-bottom: 20px;">العناصر المميزة</h2>

        <form method="GET" action="" class="mb-3">
            <select name="category" id="category" class="form-control" onchange="this.form.submit()">
                <option value="">كل الفئات</option>
                <?php
                foreach ($categories as $category) {
                    $selected = ($filter_category == $category['id']) ? 'selected' : '';
                    echo '<option value="' . $category['id'] . '" ' . $selected . '>' . htmlspecialchars($category['name']) . '</option>';
                }
                ?>
            </select>
        </form>

        <form method="POST" action="">
            <div style="overflow-x: auto;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>السعر</th>
                            <th>مميز</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                echo '<td>' . number_format($row['price'], 2) . ' LYD</td>';
                                echo '<td>';
                                echo '<input type="hidden" name="items[' . $row['id'] . ']" value="0">';
                                echo '<input type="checkbox" name="items[' . $row['id'] . ']" value="1" ' . ($row['is_featured'] ? 'checked' : '') . '>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="3" class="text-center">لا توجد أصناف.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center">
                <button type="submit" name="save_changes" class="btn btn-success">حفظ التغييرات</button>
            </div>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="dashboard.php" class="btn btn-secondary">العودة للوحة التحكم</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>