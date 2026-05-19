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
    <title>SLOMA COFFEE - ترتيب القائمة</title>
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
        
        .item-list {
            list-style-type: none;
            padding: 0;
            background: white;
            border-radius: 14px;
            overflow: hidden;
        }
        
        .item-list li {
            margin: 10px 0;
            padding: 15px;
            background-color: #fff;
            border-bottom: 1px solid #E0E0E0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .item-list li:last-child {
            border-bottom: none;
        }
        
        .display-order {
            width: 60px !important;
            text-align: center;
        }
        
        @media (max-width: 480px) {
            .item-list li {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .display-order {
                width: 100% !important;
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
        <h2 class="text-center" style="color: var(--sloma-green); margin-bottom: 20px;">ترتيب الأصناف</h2>

        <div class="mb-3">
            <label for="categoryFilter" class="form-label">اختر الفئة:</label>
            <select id="categoryFilter" class="form-control">
                <option value="0">اختر فئة</option>
                <?php
                include 'db_connect.php';
                $sql = "SELECT id, name FROM category";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    }
                }
                $conn->close();
                ?>
            </select>
        </div>

        <ul id="itemList" class="item-list" style="display: none;"></ul>

        <button id="saveOrder" class="btn btn-primary mt-3" style="display: none;">حفظ الترتيب</button>

        <div style="text-align: center; margin-top: 20px;">
            <a href="dashboard.php" class="btn btn-secondary">العودة للوحة التحكم</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            $("#categoryFilter").change(function() {
                const categoryId = $(this).val();

                if (categoryId === "0") {
                    $("#itemList, #saveOrder").hide();
                    return;
                }

                $.ajax({
                    url: 'fetch_items.php',
                    type: 'GET',
                    data: { category_id: categoryId },
                    success: function(response) {
                        const items = JSON.parse(response);
                        $("#itemList").empty();

                        if (items.length > 0) {
                            items.forEach(item => {
                                $("#itemList").append(
                                    `<li data-id="${item.id}">
                                        <input type="number" class="form-control display-order" value="${item.display_order}" />
                                        <span>${item.name}</span>
                                    </li>`
                                );
                            });
                            $("#itemList, #saveOrder").show();
                        } else {
                            $("#itemList").append("<li style='text-align:center'>لا توجد أصناف.</li>");
                            $("#itemList").show();
                            $("#saveOrder").hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('خطأ في جلب البيانات.');
                    }
                });
            });

            $("#saveOrder").click(function() {
                const order = [];
                $("#itemList li").each(function() {
                    const itemId = $(this).data('id');
                    const displayOrder = $(this).find('.display-order').val();
                    order.push({ id: itemId, display_order: displayOrder });
                });

                $.ajax({
                    url: 'update_order.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ order: order }),
                    success: function(response) {
                        try {
                            const result = JSON.parse(response);
                            if (result.status === 'success') {
                                alert('تم حفظ الترتيب بنجاح!');
                                location.reload();
                            } else {
                                alert('خطأ: ' + (result.message || 'Unknown error'));
                            }
                        } catch (e) {
                            alert('خطأ في الاستجابة.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('خطأ في الحفظ: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>