<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLOMA COFFEE - القائمة</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        
        .card {
            border-radius: 14px;
            border: 1px solid #E0E0E0;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        }
        
        .menu-item {
            cursor: pointer;
            margin-bottom: 20px;
        }

        .menu-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 14px 14px 0 0;
        }

        .menu-item .menu-item-text {
            text-align: center;
        }
        
        .modal-content {
            border-radius: 14px;
            padding: 20px;
        }
        
        .items-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            padding: 16px;
        }
        
        @media (min-width: 768px) {
            .items-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 1200px) {
            .items-grid {
                grid-template-columns: repeat(3, 1fr);
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

    <!-- Enhanced Navigation Menu -->
    <div id="menu" class="d-none position-fixed top-0 start-0 w-100 h-100">
        <div class="menu-overlay bg-dark text-white">
            <div class="menu-header">
                <span class="close-menu-btn text-white" onclick="toggleMenu()">&times;</span>
                <div class="menu-logo text-center">
                    <img src="img/sloma.jpg" alt="SLOMA COFFEE" style="height: 60px; border-radius: 50%; margin-bottom: 10px;">
                    <h4 style="font-weight: bold;">SLOMA COFFEE</h4>
                </div>
            </div>
            
            <div class="menu-content">
                <!-- Dynamic Categories Section -->
                <div class="menu-section">
                    <h5 class="menu-section-title" onclick="toggleSection('categories')">
                        <i class="fas fa-coffee"></i> Categories - الفئات
                        <i class="fas fa-chevron-down section-arrow" id="categories-arrow"></i>
                    </h5>
                    <div class="menu-section-content" id="categories-content">
                        <?php
                        include 'db_connect.php';
                        $sql = "SELECT * FROM category ORDER BY display_order ASC, id ASC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="menu-category-item" onclick="navigateToCategory(' . $row['id'] . ')">';
                                echo '<div class="category-item-content">';
                                echo '<img src="' . $row['image'] . '" alt="' . $row['name'] . '" class="category-thumb">';
                                echo '<div class="category-info">';
                                echo '<span class="category-name">' . htmlspecialchars($row['name']) . '</span>';
                                echo '<span class="category-action">View Items - عرض القائمة</span>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>

               

                <!-- Contact Section -->
                <div class="menu-section">
                    <h5 class="menu-section-title" onclick="toggleSection('contact')">
                        <i class="fas fa-phone"></i> Contact & Info - معلومات التواصل
                        <i class="fas fa-chevron-down section-arrow" id="contact-arrow"></i>
                    </h5>
                    <div class="menu-section-content" id="contact-content">
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>Branches - الفروع:</strong>
                                    <p class="mb-1" style="font-size: 0.9rem;">Branch 1 - الفرع الأول: Venice opposite Family Gallery</p>
                                    <p style="font-size: 0.9rem;">Branch 2 - الفرع الثاني: Al-Qwarsha Street opposite Al-Dahumi Gallery</p>
                                </div>
                            </div>
                           <div class="contact-item">
                                <i class="fab fa-whatsapp"></i>
                                <a href="https://wa.me/218927051081?text=Hi%20SLOMA%20Cafe%20-%20I%20am%20interested%20in%20your%20coffee%20service!" class="text-white">092-7051081</a>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:malful@yahya.com" class="text-white">malful@yahya.com</a>
                            </div>
                            <div class="contact-item">
                                <i class="fab fa-facebook"></i>
                                <a href="https://www.facebook.com/slomacafe/" class="text-white" target="_blank">Facebook</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Button -->
    <button class="btn btn-primary position-fixed top-0 start-0 m-3" onclick="toggleMenu()">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Back Button -->
    <div style="padding: 16px;">
        <button class="btn btn-primary" onclick="window.location.href = 'index.php'">
            ← Back to Home - العودة للرئيسية
        </button>
    </div>

    <!-- Items Grid -->
    <div class="container">
        <h2 class="text-center" style="margin-bottom: 20px; color: var(--sloma-green);">
            <?php
            include 'db_connect.php';
            if (isset($_GET['category_id'])) {
                $category_id = intval($_GET['category_id']);
                $catStmt = $conn->prepare("SELECT name FROM category WHERE id = ?");
                $catStmt->bind_param("i", $category_id);
                $catStmt->execute();
                $catResult = $catStmt->get_result();
                if ($catRow = $catResult->fetch_assoc()) {
                    echo htmlspecialchars($catRow['name']);
                }
                $catStmt->close();
            } else {
                echo "Menu - القائمة";
            }
            ?>
        </h2>

        <div class="items-grid">
            <?php
            if (isset($_GET['category_id'])) {
                $category_id = intval($_GET['category_id']);
                $sql = "SELECT * FROM item WHERE category_id = ? ORDER BY display_order ASC, id ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $category_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="card menu-item" onclick="showItemDetails(' . $row['id'] . ')">';
                        echo '<img src="' . htmlspecialchars($row['image']) . '" class="card-img-top" alt="' . htmlspecialchars($row['name']) . '">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title" style="font-weight: 600;">' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '</h5>';
                        echo '<p class="card-text" style="color: var(--sloma-green); font-weight: bold;">' . number_format($row['price'], 2) . ' LYD</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="text-center">No items in this category - لا توجد أصناف في هذه الفئة</p>';
                }
                $stmt->close();
            }
            $conn->close();
            ?>
        </div>
    </div>

    <div style="height: 40px;"></div>

    <!-- Modal for item details -->
    <div id="item-modal" class="modal fade" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalLabel">Item Details - تفاصيل الصنف</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modal-item-details">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleMenu() {
            const menu = document.getElementById('menu');
            menu.classList.toggle('d-none');
        }

        function toggleSection(sectionId) {
            const content = document.getElementById(sectionId + '-content');
            const arrow = document.getElementById(sectionId + '-arrow');
            
            content.classList.toggle('collapsed');
            arrow.classList.toggle('rotated');
        }

        function navigateToCategory(categoryId) {
            window.location.href = 'items.php?category_id=' + categoryId;
        }

        function showFeaturedItem(itemId) {
            // For items page, we'll redirect to index to show the modal
            window.location.href = 'index.php#itemModal' + itemId;
        }

        function showItemDetails(itemId) {
            $.ajax({
                url: 'fetch_item_details.php',
                type: 'GET',
                data: { id: itemId },
                success: function(data) {
                    const item = JSON.parse(data);
                    let modalContent = `
                        <div class="text-center">
                            <img src="${item.image}" class="img-fluid" alt="${item.name}" style="border-radius: 14px;">
                            <h3 class="mt-3" style="color: var(--sloma-green);">${item.name}</h3>
                            <p style="font-size: 1.5rem; font-weight: bold; color: var(--sloma-green);">${item.price} LYD</p>
                            <p>${item.description || ''}</p>
                        </div>
                    `;
                    $('#modal-item-details').html(modalContent);
                    $('#item-modal').modal('show');
                }
            });
        }

        // Initialize sections - categories expanded, contact collapsed
        document.addEventListener('DOMContentLoaded', function() {
            // Collapse contact section by default
            const contactContent = document.getElementById('contact-content');
            const contactArrow = document.getElementById('contact-arrow');
            
            if (contactContent && contactArrow) {
                contactContent.classList.add('collapsed');
                contactArrow.classList.add('rotated');
            }
        });
    </script>
</body>
</html>