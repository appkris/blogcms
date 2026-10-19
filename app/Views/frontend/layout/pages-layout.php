<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= isset($pageTitle) ? $pageTitle : "" ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <?= $this->renderSection('page_meta') ?>
    <link rel="shortcut icon" href="/images/blogs/<?= esc(get_settings()->blog_favicon) ?>" type="image/x-icon">
    <link rel="alternate" type="application/rss+xml" title="<?= esc(get_settings()->blog_title) ?> RSS Feed" href="<?= site_url('feed') ?>">

    <!-- theme meta -->
    <meta name="theme-name" content="reporter" />

    <!-- # Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Neuton:wght@700&family=Work+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- # CSS Plugins -->
    <link rel="stylesheet" href="/frontend/plugins/bootstrap/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- # Main Style Sheet -->
    <link rel="stylesheet" href="/frontend/css/style.css">
    
    <!-- AdSense Styles -->
    <link rel="stylesheet" href="/css/adsense.css">
    
    <!-- Plain Social Media Icons -->
    <link rel="stylesheet" href="/css/social-icons.css">
    
    <!-- Modern Navigation Styles -->
    <style>
        .modern-navigation {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, background 0.3s ease;
            transform: translateY(0);
        }
        
        .modern-navigation.nav-hidden {
            transform: translateY(-100%);
        }
        
        .modern-navigation.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
        }
        
        body {
            padding-top: 80px;
        }
        
        .social-icons {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: 15px;
        }
        
        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 4px;
            background-color: #f5f5f5;
            color: #333;
            text-decoration: none;
            font-size: 14px;
        }
        
        .social-link:hover {
            background-color: #e0e0e0;
            color: #333;
            text-decoration: none;
        }
        
        @media (max-width: 991px) {
            .social-icons {
                display: none !important;
            }
            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
            }
        }
        
        /* Enhanced search styling */
        .search input {
            border-radius: 25px;
            border: 2px solid #e9ecef;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        
        .search input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        /* Back to Top Button - Plain Style */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            z-index: 1000;
        }
        
        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background-color: #5a6edb;
        }
        
        /* Modern Breadcrumb */
        .modern-breadcrumb {
            background: white;
            border-radius: 10px;
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .breadcrumb-list {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        .breadcrumb-item {
            display: flex;
            align-items: center;
        }
        
        .breadcrumb-link {
            display: flex;
            align-items: center;
            color: #000;
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 14px;
        }
        
        .breadcrumb-link:hover {
            color: #667eea;
            text-decoration: none;
        }
        
        .breadcrumb-link i {
            margin-right: 5px;
            font-size: 12px;
        }
        
        .breadcrumb-separator {
            color: #000;
            margin: 0 10px;
            font-size: 10px;
        }
        
        .breadcrumb-current {
            color: #000;
            font-weight: 500;
            font-size: 14px;
        }
        
        /* Mobile Navigation & Breadcrumb Responsive */
        @media (max-width: 991px) {
            body {
                padding-top: 70px;
            }
            
            .modern-navigation {
                padding: 8px 0;
            }
            
            .navbar-brand img {
                max-width: 140px !important;
            }
            
            .search input {
                font-size: 14px;
                padding: 6px 15px;
                width: 180px;
            }
            
            .navbar-toggler {
                padding: 4px 8px;
                font-size: 18px;
            }
            
            .navbar-collapse {
                background: rgba(255, 255, 255, 0.98);
                border-radius: 10px;
                margin-top: 10px;
                padding: 15px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }
            
            .navbar-nav .nav-link {
                padding: 8px 15px;
                font-size: 14px;
                border-radius: 6px;
                transition: all 0.3s ease;
            }
            
            .navbar-nav .nav-link:hover {
                background: #f8f9fa;
                color: #667eea;
            }
        }
        
        @media (max-width: 768px) {
            body {
                padding-top: 65px;
            }
            
            .modern-breadcrumb {
                padding: 10px 12px;
                margin-bottom: 15px;
                border-radius: 8px;
            }
            
            .breadcrumb-list {
                gap: 5px;
            }
            
            .breadcrumb-link, .breadcrumb-current {
                font-size: 12px;
            }
            
            .breadcrumb-link span {
                max-width: 120px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            
            .breadcrumb-current {
                max-width: 150px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            
            .breadcrumb-separator {
                margin: 0 6px;
                font-size: 9px;
            }
            
            .breadcrumb-link i {
                margin-right: 3px;
                font-size: 10px;
            }
            
            .navbar-brand img {
                max-width: 120px !important;
            }
            
            .search {
                order: 2 !important;
                margin: 0 10px 0 auto;
            }
            
            .search input {
                width: 140px;
                font-size: 13px;
                padding: 5px 12px;
            }
            
            .navbar-actions {
                order: 3 !important;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding-top: 60px;
            }
            
            .modern-breadcrumb {
                padding: 8px 10px;
                margin-bottom: 12px;
            }
            
            .breadcrumb-link, .breadcrumb-current {
                font-size: 11px;
            }
            
            .breadcrumb-link span {
                max-width: 80px;
            }
            
            .breadcrumb-current {
                max-width: 100px;
            }
            
            .breadcrumb-separator {
                margin: 0 4px;
                font-size: 8px;
            }
            
            .navbar-brand img {
                max-width: 100px !important;
            }
            
            .search input {
                width: 120px;
                font-size: 12px;
                padding: 4px 10px;
            }
            
            .navbar-toggler {
                padding: 3px 6px;
                font-size: 16px;
            }
            
            .navbar-collapse {
                padding: 12px;
                margin-top: 8px;
            }
            
            .navbar-nav .nav-link {
                padding: 6px 12px;
                font-size: 13px;
            }
        }
    </style>
    
    <?= $this->renderSection('stylesheets') ?>
    <?php if(function_exists('get_adsense_head_script')) echo get_adsense_head_script(); ?>
</head>

<body>
    <?php include ('inc/header.php') ?>

    <main>
        <section class="section">
            <div class="container">
                <?= $this->renderSection('breadcrumbs') ?>
                <?php if(function_exists('display_header_ad')): ?>
                    <?= display_header_ad() ?>
                <?php endif; ?>
                <?= $this->renderSection('content') ?>
            </div>
        </section>
    </main>

    <!-- footer  -->
    <?php include ('inc/footer.php') ?>
    <!-- footer  -->
    
    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- # JS Plugins -->
    <script src="/frontend/plugins/jquery/jquery.min.js"></script>
    <script src="/frontend/plugins/bootstrap/bootstrap.min.js"></script>

    <!-- Main Script -->
    <script src="/frontend/js/script.js"></script>
    
    <!-- Modern Navigation Script -->
    <script>
        $(document).ready(function() {
            let lastScrollTop = 0;
            
            // Auto-hide navigation on scroll
            $(window).scroll(function() {
                let scrollTop = $(this).scrollTop();
                
                if (scrollTop > 100) {
                    $('.modern-navigation').addClass('scrolled');
                    
                    if (scrollTop > lastScrollTop) {
                        // Scrolling down - hide nav
                        $('.modern-navigation').addClass('nav-hidden');
                    } else {
                        // Scrolling up - show nav
                        $('.modern-navigation').removeClass('nav-hidden');
                    }
                } else {
                    $('.modern-navigation').removeClass('scrolled nav-hidden');
                }
                
                // Back to top button visibility
                if (scrollTop > 300) {
                    $('#backToTop').addClass('show');
                } else {
                    $('#backToTop').removeClass('show');
                }
                
                lastScrollTop = scrollTop;
            });
            
            // Back to top button click
            $('#backToTop').click(function() {
                $('html, body').animate({scrollTop: 0}, 600);
            });
            
            // Smooth scroll for anchor links
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 80
                    }, 1000);
                }
            });
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>

</html>