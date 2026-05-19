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
    <title>SLOMA COFFEE - Dashboard - لوحة التحكم</title>
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
        
        .dashboard-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.12);
            margin-bottom: 20px;
        }
        
        .btn {
            border-radius: 8px;
            padding: 12px 20px;
            margin: 5px;
        }
        
        .nav-btn {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            text-align: right;
        }
        
        @media (min-width: 768px) {
            .nav-btn {
                display: inline-block;
                width: auto;
                margin: 5px;
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
        <h2 class="text-center" style="color: var(--sloma-green); margin-bottom: 20px;">Dashboard - لوحة التحكم</h2>
        
        <div class="dashboard-card">
            <h4 class="text-center" style="margin-bottom: 15px;">Content Management - إدارة المحتوى</h4>
            <div>
                <a href="upload.php" class="btn btn-primary nav-btn">Add Category - إضافة فئة جديدة</a>
                <a href="upload_item.php" class="btn btn-primary nav-btn">Add Item - إضافة صنف جديد</a>
                <a href="category_edit.php" class="btn btn-primary nav-btn">Edit Categories - تعديل الفئات</a>
                <a href="item_edit.php" class="btn btn-primary nav-btn">Edit Items - تعديل الأصناف</a>
                <a href="featured.php" class="btn btn-primary nav-btn">Featured - المميزة</a>
                <a href="arrange_order.php" class="btn btn-primary nav-btn">Arrange Order - ترتيب القائمة</a>
            </div>
        </div>
        
        <div class="dashboard-card">
            <h4 class="text-center" style="margin-bottom: 15px;">User Management - إدارة المستخدمين</h4>
            <div>
                <a href="user.php" class="btn btn-primary nav-btn">Add User - إضافة مستخدم جديد</a>
                <a href="logout.php" class="btn btn-danger nav-btn">Logout - تسجيل خروج</a>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" class="btn btn-secondary">Back to Home - العودة للرئيسية</a>
        </div>
    </div>
</body>
</html>