<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLOMA COFFEE</title>
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
        }
        
        .btn-primary {
            background-color: var(--sloma-green) !important;
            border-color: var(--sloma-green) !important;
        }
        
        .btn-primary:hover {
            background-color: var(--sloma-green-dark) !important;
            border-color: var(--sloma-green-dark) !important;
        }
        
        #menu {
            z-index: 1050;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            background-color: var(--sloma-green-dark) !important;
        }

        #menu.d-none {
            visibility: hidden;
            opacity: 0;
        }

        #menu:not(.d-none) {
            visibility: visible;
            opacity: 1;
        }

        .close-menu-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 1.5rem;
            cursor: pointer;
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
    </style>
</head>
<body>
    <?php include 'db_connect.php'; ?>
    <!-- Header Tab -->
    <div class="w-100 header-hero" style="height: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <img src="img/sloma.jpg" alt="SLOMA COFFEE" style="height: 120px; border-radius: 50%; margin-bottom: 15px;">
        <h1 class="text-white" style="font-size: 2.5rem; font-weight: bold;">SLOMA COFFEE</h1>
    </div>

    <button class="btn btn-primary position-fixed top-0 start-0 m-3" onclick="toggleMenu()">
        <i class="fa fa-bars"></i>
    </button>

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

    <!-- Pizza Menu -->
    <div class="container mt-5 pt-5">
        <div class="mb-5 mt-5">
            <h3 class="text-center">Featured Items - أصناف مميزة</h3>
            <div id="featuredCarousel" class="carousel slide mt-3" data-ride="carousel">
            <div class="carousel-inner">
                    <?php
                    $randomSql = "SELECT i.*, c.name as category_name FROM item i LEFT JOIN category c ON i.category_id = c.id WHERE i.is_featured = 1 ORDER BY i.display_order ASC, i.id ASC";
                    $randomResult = $conn->query($randomSql);

                    $active = true;
        if ($randomResult->num_rows > 0) {
            while ($randomRow = $randomResult->fetch_assoc()) {
                $item_id = $randomRow['id']; // Get item ID for modal triggering
                echo '<div class="carousel-item ' . ($active ? 'active' : '') . '">';
                echo '<div class="card mx-auto" style="max-width: 400px; padding: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" data-toggle="modal" data-target="#itemModal' . $item_id . '">';
                echo '<div class="d-flex align-items-center">';
                echo '<img src="' . $randomRow['image'] . '" class="rounded mr-3" alt="' . $randomRow['name'] . '" style="height: 100px; width: 100px; object-fit: cover;">';
                echo '<div>';
                echo '<h5>' . $randomRow['name'] . '</h5>';
                echo '<p class="mb-0">LYD ' . number_format($randomRow['price'], 2) . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                
                // Modal for displaying detailed information
                echo '<div class="modal fade" id="itemModal' . $item_id . '" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel' . $item_id . '" aria-hidden="true">';
                echo '<div class="modal-dialog modal-lg" role="document">';
                echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo '<h5 class="modal-title" id="itemModalLabel' . $item_id . '">' . $randomRow['name'] . '</h5>';
                echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                echo '<span aria-hidden="true">&times;</span>';
                echo '</button>';
                echo '</div>';
                echo '<div class="modal-body">';
                echo '<img src="' . $randomRow['image'] . '" class="img-fluid mb-3" alt="' . $randomRow['name'] . '">';
                echo '<p><strong>Description - الوصف:</strong> ' . $randomRow['description'] . '</p>';
                echo '<p><strong>Price - السعر:</strong> LYD ' . number_format($randomRow['price'], 2) . '</p>';
                echo '<p><strong>Category - الفئة:</strong> ' . ($randomRow['category_name'] ?? 'Unknown') . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                
                $active = false;
            }
        } else {
            echo '<p>No items found.</p>';
        }
                    ?>
                </div>
                <!-- Carousel Controls -->
                <a class="carousel-control-prev" href="#featuredCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#featuredCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

        <!-- Categories Section -->
        <div class="text-center">
            <h3>Categories - الفئات</h3>
            <div class="d-flex flex-wrap justify-content-center">
                <?php
                $sql = "SELECT * FROM category ORDER BY display_order ASC, id ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="card m-3 text-center" style="width: 300px;">';
                        echo '<img src="' . $row['image'] . '" alt="' . $row['name'] . '" class="card-img-top" style="height: 200px; object-fit: cover;">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $row['name'] . '</h5>';
                        echo '<button class="btn btn-primary" onclick="window.location.href=\'items.php?category_id=' . $row['id'] . '\'">View Items - عرض القائمة</button>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No categories found.</p>';
                }
                ?>
            </div>
        </div>

        <!-- Map Section -->
        <h3 class="mt-5 text-center">Map - الموقع</h3>
        <div class="text-center">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d54111.57287435781!2d20.08646100915153!3d32.042913999631274!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x13831b04adb1c649%3A0x47581217542f1d46!2sSLOMA%20Cafe!5e0!3m2!1sen!2sly!4v1776290377455!5m2!1sen!2sly" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        
            </div>
    </div>

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
            // Trigger the modal for the featured item
            $('#itemModal' + itemId).modal('show');
            toggleMenu(); // Close menu after selection
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
