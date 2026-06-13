<!doctype html>
<html lang="en">
<head>
    <!-- xxx Basics xxx -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- xxx Favicon xxx -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no" />
    <title>Blog - Courier Management System</title>
    <meta name="author" content="Courier MS">
    <meta name="keywords"
        content="courier, blog, logistics, shipping, transport, delivery, cargo, freight">
    <meta name="description"
        content="Read our latest blog posts about courier services, logistics management, shipping trends, and delivery solutions.">

    <!-- xxx Favicon xxx -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
    
    <!-- Main Style CSSS -->
    <link href="assets/css/theme-plugins.min.css" rel="stylesheet">
    <!-- Main Theme CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- Responsive Theme CSS -->
    <link href="assets/css/responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<style>
.logo-box img {
    height: 60px;
    width: auto;
    object-fit: contain;
}

/* Navbar height balance */
.navbar {
    padding: 12px 0;
}

/* Offcanvas logo spacing */
.offcanvas-header .logo-box img {
    height: 38px;
}

/* Blog Styles */
.blog-post {
    transition: all 0.3s ease;
    margin-bottom: 30px;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.blog-post:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.blog-post img {
    width: 100%;
    height: 250px;
    object-fit: cover;
}

.blog-content {
    padding: 25px;
}

.meta-box {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
}

.meta-box a {
    color: #ff9900;
    text-decoration: none;
}

.meta-box a:hover {
    color: #b35c00;
}

.blog-tags {
    margin-top: 15px;
}

.tag {
    display: inline-block;
    background: #f8f9fa;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    margin-right: 5px;
    margin-bottom: 5px;
}

.tag:hover {
    background: #e9ecef;
}

/* Sidebar Styles */
.sidebar-widget {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.widget-title {
    color: #333;
    border-bottom: 2px solid #ff7300;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.recent-post-item {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.recent-post-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.recent-post-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 15px;
}

.recent-post-content h5 {
    margin-bottom: 5px;
}

.recent-post-content small {
    color: #666;
}

.category-list li {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.category-list li:last-child {
    border-bottom: none;
}

.category-list a {
    color: #333;
    text-decoration: none;
}

.category-list a:hover {
    color: #ffa600;
}

.category-count {
    float: right;
    background: #ffa200;
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 12px;
}

/* Blog Hero Section - Matching About Us Style */
.blog-hero-banner {
    background: linear-gradient(rgb(0, 0, 0), rgba(255, 255, 255, 0.1)),url('assets/images/my\ \(9\).jfif');
    background-size: cover;
    background-position: center;
    padding: 160px 0 120px;
    color: white;
    position: relative;
}

.blog-hero-banner::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.blog-hero-banner .container {
    position: relative;
    z-index: 2;
}

.blog-hero-banner h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: white;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.blog-hero-banner .hero-description {
    font-size: 1.2rem;
    max-width: 800px;
    margin: 0 auto 30px;
    line-height: 1.8;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

.breadcrumb-wrapper {
    background: rgba(255, 255, 255, 0.1);
    padding: 15px 20px;
    border-radius: 5px;
    display: inline-block;
    margin-top: 20px;
}

.breadcrumb-wrapper .breadcrumb {
    margin-bottom: 0;
    background: transparent;
    padding: 0;
}

.breadcrumb-wrapper .breadcrumb-item a {
    color: white;
    text-decoration: none;
}

.breadcrumb-wrapper .breadcrumb-item.active {
    color: #ffa435;
}

.breadcrumb-wrapper .breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.7);
    content: ">";
}

/* Featured Post */
.featured-post {
    background: url('assets/images/blog_single.jpg');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 120px 0;
    margin-bottom: 40px;
    border-radius: 10px;
}

.featured-content {
    max-width: 600px;
}

.featured-content .meta-box {
    color: #ff9900;
}

.featured-content .meta-box a {
    color: #ff9900;
}

/* Blog Stats */
.blog-stats {
    background: #f8f9fa;
    padding: 40px 0;
    margin: 40px 0;
    border-radius: 10px;
}

.stat-item {
    text-align: center;
    padding: 20px;
}

.stat-item i {
    font-size: 40px;
    color: #ff9900;
    margin-bottom: 15px;
}

.stat-item h3 {
    font-size: 30px;
    color: #333;
    margin-bottom: 5px;
}

.stat-item p {
    color: #666;
}

/* Author Spotlight */
.author-spotlight {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    margin: 40px 0;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
}

.author-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 30px;
}

.author-info h4 {
    color: #333;
    margin-bottom: 10px;
}

.author-info p {
    color: #666;
}

/* Pagination */
.pagination-wrap {
    margin-top: 50px;
}

.pagination .page-item.active .page-link {
    background: #ff9900;
    border-color: #ff9900;
}

.pagination .page-link {
    color: #333;
    border: 1px solid #ddd;
    margin: 0 5px;
    border-radius: 5px;
}

.pagination .page-link:hover {
    background: #ff9900;
    color: white;
    border-color: #ff9900;
}

/* Call to Action */
.blog-cta {
    background: linear-gradient(135deg, #ff9900, #ff7300);
    color: white;
    padding: 60px 0;
    text-align: center;
    margin-top: 50px;
    border-radius: 10px;
}

.blog-cta h3 {
    margin-bottom: 20px;
}

.cta-btn {
    background: white;
    color: #ff9900;
    padding: 12px 30px;
    border-radius: 30px;
    text-decoration: none;
    display: inline-block;
    margin-top: 20px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.cta-btn:hover {
    background: #f8f9fa;
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    color: #ff9900;
}

/* Newsletter Subscribe Fix */
.footer-subscribe .form-control {
    border: 1px solid #ddd;
    padding: 12px 15px;
    border-radius: 5px 0 0 5px;
}

.footer-subscribe .btn-theme {
    border-radius: 0 5px 5px 0;
    padding: 12px 25px;
}

.sidebar-widget .input-group .form-control {
    border: 1px solid #ddd;
    border-right: none;
}

.sidebar-widget .input-group .btn-theme {
    border-radius: 0 5px 5px 0;
}   
        /* Custom styles for single page */
        .single-page-section {
            padding: 80px 0;
        }
        
        .section-title {
            margin-bottom: 40px;
        }
        
        .feature-box {
            padding: 30px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }
        
        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            font-size: 40px;
            color: #ff7a00;
            margin-bottom: 20px;
        }
        
        .contact-info {
            background: #0b1c2d;
            color: white;
            padding: 40px;
            border-radius: 10px;
        }
        
        .contact-info h3 {
            color: #ff7a00;
            margin-bottom: 20px;
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Active nav link */
        .nav-link.active {
            color: #ff7a00 !important;
        }
        
        /* Sign Up/Login Button Styles */
        .btn-signup {
            background: #ff7a00;
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid #ff7a00;
        }
        
        .btn-signup:hover {
            background: transparent;
            color: #ff7a00;
            transform: translateY(-2px);
        }
        
        .btn-login {
            background: transparent;
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid white;
        }
        
        .btn-login:hover {
            background: white;
            color: #ff7a00;
            transform: translateY(-2px);
        }
        
        /* New Sign Up Section */
        .signup-section {
            background: linear-gradient(135deg, #0b1c2d 0%, #1a365d 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .signup-section h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        
        .signup-section p {
            font-size: 18px;
            margin-bottom: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0.9;
        }
        
        .signup-buttons {
            margin-top: 30px;
        }
        
        .btn-signup-large {
            background: #ff7a00;
            color: white;
            padding: 12px 35px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid #ff7a00;
            font-size: 16px;
            margin: 0 10px;
        }
        
        .btn-signup-large:hover {
            background: transparent;
            color: #ff7a00;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 122, 0, 0.2);
        }
        
        .btn-login-large {
            background: transparent;
            color: #ff7a00;
            padding: 12px 35px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid #ff7a00;
            font-size: 16px;
            margin: 0 10px;
        }
        
        .btn-login-large:hover {
            background: #ff7a00;
            color: white;
            transform: translateY(-3px);
        }


/* Pricing Section CSS */
.cms-feature-box {
    background: #fff;
    padding: 40px 30px;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin: 20px 0;
    transition: all 0.3s ease;
    border: 1px solid #eaeaea;
    height: 100%;
}

.cms-feature-box:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.cms-feature-box h3 {
    color: #0b1c2d;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 15px;
}

.cms-stat-number {
    color: #ff7a00;
    font-size: 48px;
    font-weight: 800;
    line-height: 1;
}

.cms-feature-box p {
    color: #666;
    margin-bottom: 20px;
    font-size: 16px;
}

.cms-badge {
    display: inline-block;
    background: #ff7a00;
    color: white;
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.demo-btn {
    display: inline-flex;
    align-items: center;
    background: #ff7a00;
    color: white;
    padding: 12px 30px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid #ff7a00;
}

.demo-btn:hover {
    background: transparent;
    color: #ff7a00;
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(255, 122, 0, 0.2);
}

.list-unstyled {
    margin-top: 20px;
}

.list-unstyled li {
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 15px;
    color: #555;
}

.list-unstyled li:last-child {
    border-bottom: none;
}

.text-orange {
    color: #ff7a00;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .cms-feature-box {
        margin-bottom: 30px;
        transform: none !important;
    }
    
    .cms-stat-number {
        font-size: 36px;
    }
    
    .navbar-brand strong {
        font-size: 20px;
    }
    
    .navbar-brand span {
        font-size: 12px;
    }
    
    .signup-buttons {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    
    .btn-signup-large, .btn-login-large {
        margin: 5px 0;
        width: 80%;
        text-align: center;
    }
}
.navbar-brand {
    font-weight: 700;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.navbar-brand strong {
    color: #ff7a00;
    font-size: 22px;
    letter-spacing: 1px;
}

.navbar-brand span {
    color: #fcfcfc;
    font-size: 13px;
    letter-spacing: 0.5px;
}

.navbar-brand {
    background: transparent !important; 
    padding: 0 !important;             
    border-radius: 0 !important;      
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* Footer Styles */
.footer-links li a {
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.footer-links li a:hover {
    color: #ff7a00;
    padding-left: 5px;
}

.social-icons a {
    display: inline-block;
    width: 36px;
    height: 36px;
    background: #0b1c2d;
    color: white;
    border-radius: 50%;
    text-align: center;
    line-height: 36px;
    margin-right: 10px;
    transition: all 0.3s ease;
}

.social-icons a:hover {
    background: #ff7a00;
    transform: translateY(-3px);
}

.footer-heading {
    color: #0b1c2d;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ff7a00;
}

/* Navbar Signup Button */
.navbar-nav .nav-item:last-child {
    margin-left: 15px;
}
.icon-logo img {
    height: 65px;
    width: auto;
    object-fit: contain;
}


</style>
<body>

    <header class="header-three">
        <!-- Main Navigation Start -->
        <nav class="navbar navbar-expand-lg header-fullpage nav-light">
            <div class="container d-flex align-items-lg-start">
                <div class="col col-lg-2 col-md-3">
                 <a class="navbar-brand icon-logo" href="index.php">
   <img src="assets/images/new logo.png" alt="Courier Logo">
</a>

                </div>
                <!-- Toggle Button Start -->
                <button class="navbar-toggler x collapsed" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Toggle Button End -->
                <div class="col-lg-auto">
                    <div class="text-end top-bar">
                    </div>
                    <div class="navbar-collapse offcanvas offcanvas-start offcanvas-collapse" id="navbarCollapse">
                        <div class="offcanvas-header">
                           <a class="navbar-brand" href="index.php">
    <img src="assets/images/new logo.png" alt="Courier Logo">
</a>

                            <button class="navbar-toggler x collapsed" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarCollapse"
                            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="icofont-close-line"></i>
                            </button>
                        </div>
                        <div class="offcanvas-body w-100" data-lenis-prevent>                        
                           <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="about-us.php">What We Do</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="services.php">Our Services</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="blog.php">Blog</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="gallery.php">Gallery</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="contact.php">Contact</a>
                                </li>
                                <!-- Sign Up Button in Navbar -->
                                <li class="nav-item ms-3">
                                    <a href="public/login.php" class="btn-signup">Login</a>
                                </li>
                                 <li class="nav-item ms-3">
                                    <a href="public/register.php" class="btn-signup">Sign Up</a>
                                </li>
                            </ul>
                        </div>                        
                    </div>
                </div>
            </div>
        </nav>
        <!-- Main Navigation End -->
    </header>

<!-- Blog Hero Section Start -->
<section class="blog-hero-banner">
    <div class="container">
        <div class="text-center">
            <h1>Blog & Latest News</h1>
            <p class="hero-description">
                Stay updated with the latest trends, insights, and innovations in the logistics and courier industry. 
                Explore expert articles, industry news, and practical tips to optimize your delivery operations.
            </p>
            <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Blog</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- Blog Hero Section End -->

    <!-- Main Body Content Start -->
    <main id="body-content">
        <!-- Blog Stats Section -->
        <section class="blog-stats">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <i class="icofont-paper"></i>
                            <h3>150+</h3>
                            <p>Blog Posts</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <i class="icofont-user-alt-3"></i>
                            <h3>5K+</h3>
                            <p>Monthly Readers</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <i class="icofont-tags"></i>
                            <h3>25+</h3>
                            <p>Categories</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <i class="icofont-comment"></i>
                            <h3>1.2K+</h3>
                            <p>Comments</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Blog Section Start -->
        <section class="wide-tb-100">
            <div class="container">
                <div class="row">
                    
                    <!-- Main Content Area - 8 Columns -->
                    <div class="col-lg-8">
                        
                        <!-- Author Spotlight -->
                        <div class="author-spotlight">
                            <img src="assets/images/team_1.jpg" alt="Author" class="author-img">
                            <div class="author-info">
                                <h4>Meet Our Editor</h4>
                                <p><strong>John Doe</strong> - Logistics Expert with 15+ years experience. Sharing insights about courier industry trends and innovations.</p>
                                <a href="#" class="btn-theme bg-navy-blue">View All Articles</a>
                            </div>
                        </div>

                        <!-- Blog Grid View -->
                        <div class="row">
                            <!-- Blog Post 1 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="blog-post">
                                    <img src="assets/images/by (8).jfif" alt="Freight Payment Services">
                                    <div class="blog-content">
                                        <div class="meta-box"><a href="#">Business</a> <span>/</span> September 28, 2018</div>
                                        <h4 class="h4-md mb-3"><a href="blog-single.php">Freight Payment and Auditing Services</a></h4>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantiumg</p>
                                        <a href="blog-single.php" class="btn-theme bg-navy-blue">Read More <i class="icofont-rounded-right"></i></a>
                                        <div class="blog-tags">
                                            <span class="tag">Freight</span>
                                            <span class="tag">Payment</span>
                                            <span class="tag">Auditing</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Blog Post 2 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="blog-post">
                                    <img src="assets/images/by (7).jfif" alt="API Technology">
                                    <div class="blog-content">
                                        <div class="meta-box"><a href="#">Technology</a> <span>/</span> October 15, 2018</div>
                                        <h4 class="h4-md mb-3"><a href="blog-single.php">How API Technology Connects the Transportation Economy</a></h4>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantiumg</p>
                                        <a href="blog-single.php" class="btn-theme bg-navy-blue">Read More <i class="icofont-rounded-right"></i></a>
                                        <div class="blog-tags">
                                            <span class="tag">API</span>
                                            <span class="tag">Technology</span>
                                            <span class="tag">Transport</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Blog Post 3 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="blog-post">
                                    <img src="assets/images/by (6).jfif" alt="New Warehouse">
                                    <div class="blog-content">
                                        <div class="meta-box"><a href="#">Business</a> <span>/</span> November 5, 2018</div>
                                        <h4 class="h4-md mb-3"><a href="blog-single.php">New Warehouse Now Operational</a></h4>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantiumg</p>
                                        <a href="blog-single.php" class="btn-theme bg-navy-blue">Read More <i class="icofont-rounded-right"></i></a>
                                        <div class="blog-tags">
                                            <span class="tag">Warehouse</span>
                                            <span class="tag">Storage</span>
                                            <span class="tag">Logistics</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Blog Post 4 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="blog-post">
                                    <img src="assets/images/by (5).jfif" alt="Project Logistics">
                                    <div class="blog-content">
                                        <div class="meta-box"><a href="#">Logistics</a> <span>/</span> November 20, 2018</div>
                                        <h4 class="h4-md mb-3"><a href="blog-single.php">Project Logistics: Going the Distance</a></h4>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantiumg</p>
                                        <a href="blog-single.php" class="btn-theme bg-navy-blue">Read More <i class="icofont-rounded-right"></i></a>
                                        <div class="blog-tags">
                                            <span class="tag">Project</span>
                                            <span class="tag">Logistics</span>
                                            <span class="tag">Distance</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Blog Post 5 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="blog-post">
                                    <img src="assets/images/by (4).jfif" alt="European Development">
                                    <div class="blog-content">
                                        <div class="meta-box"><a href="#">Sustainability</a> <span>/</span> December 10, 2018</div>
                                        <h4 class="h4-md mb-3"><a href="blog-single.php">For European Sustainable Development</a></h4>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantiumg</p>
                                        <a href="blog-single.php" class="btn-theme bg-navy-blue">Read More <i class="icofont-rounded-right"></i></a>
                                        <div class="blog-tags">
                                            <span class="tag">Europe</span>
                                            <span class="tag">Sustainability</span>
                                            <span class="tag">Development</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Blog Post 6 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="blog-post">
                                    <img src="assets/images/by (3).jfif" alt="Cargo Changes">
                                    <div class="blog-content">
                                        <div class="meta-box"><a href="#">Industry</a> <span>/</span> January 5, 2019</div>
                                        <h4 class="h4-md mb-3"><a href="blog-single.php">Logistics Should Brace for Changes in Cargo</a></h4>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantiumg</p>
                                        <a href="blog-single.php" class="btn-theme bg-navy-blue">Read More <i class="icofont-rounded-right"></i></a>
                                        <div class="blog-tags">
                                            <span class="tag">Cargo</span>
                                            <span class="tag">Changes</span>
                                            <span class="tag">Industry</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Blog Post 7 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="blog-post">
                                    <img src="assets/images/by (2).jfif" alt="Real-time Warehouses">
                                    <div class="blog-content">
                                        <div class="meta-box"><a href="#">Technology</a> <span>/</span> January 25, 2019</div>
                                        <h4 class="h4-md mb-3"><a href="blog-single.php">Distinctively Promote Real-time Strategic Warehouses</a></h4>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantiumg</p>
                                        <a href="blog-single.php" class="btn-theme bg-navy-blue">Read More <i class="icofont-rounded-right"></i></a>
                                        <div class="blog-tags">
                                            <span class="tag">Real-time</span>
                                            <span class="tag">Warehouses</span>
                                            <span class="tag">Strategy</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Blog Post 8 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="blog-post">
                                    <img src="assets/images/by (1).jfif" alt="Project Logistics">
                                    <div class="blog-content">
                                        <div class="meta-box"><a href="#">Logistics</a> <span>/</span> February 15, 2019</div>
                                        <h4 class="h4-md mb-3"><a href="blog-single.php">Project Logistics: Going the Distance</a></h4>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantiumg</p>
                                        <a href="blog-single.php" class="btn-theme bg-navy-blue">Read More <i class="icofont-rounded-right"></i></a>
                                        <div class="blog-tags">
                                            <span class="tag">Project</span>
                                            <span class="tag">Logistics</span>
                                            <span class="tag">Distance</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-wrap">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1"><i class="icofont-rounded-left"></i></a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#"><i class="icofont-rounded-right"></i></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                    </div>

                    <!-- Sidebar Area - 4 Columns -->
                    <div class="col-lg-4">
                        <!-- Search Widget -->
                        <div class="sidebar-widget">
                            <h4 class="widget-title">Search Blog</h4>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search articles...">
                                <button class="btn btn-theme bg-navy-blue" type="button">
                                    <i class="icofont-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Popular Posts Widget -->
                        <div class="sidebar-widget">
                            <h4 class="widget-title">Popular Posts</h4>
                            <div class="recent-post-item">
                                <img src="assets/images/blog_img_1.jpg" alt="Freight Payment" class="recent-post-img">
                                <div class="recent-post-content">
                                    <h5><a href="blog-single.php">Freight Payment Services</a></h5>
                                    <small>September 28, 2018</small>
                                </div>
                            </div>
                            <div class="recent-post-item">
                                <img src="assets/images/blog_img_2.jpg" alt="API Technology" class="recent-post-img">
                                <div class="recent-post-content">
                                    <h5><a href="blog-single.php">API in Transportation</a></h5>
                                    <small>October 15, 2018</small>
                                </div>
                            </div>
                            <div class="recent-post-item">
                                <img src="assets/images/blog_img_4.jpg" alt="Warehouse" class="recent-post-img">
                                <div class="recent-post-content">
                                    <h5><a href="blog-single.php">New Warehouse Launch</a></h5>
                                    <small>November 5, 2018</small>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Widget -->
                        <div class="sidebar-widget">
                            <h4 class="widget-title">Categories</h4>
                            <ul class="list-unstyled category-list">
                                <li><a href="#">Business <span class="category-count">12</span></a></li>
                                <li><a href="#">Logistics <span class="category-count">8</span></a></li>
                                <li><a href="#">Technology <span class="category-count">6</span></a></li>
                                <li><a href="#">Shipping <span class="category-count">10</span></a></li>
                                <li><a href="#">Warehousing <span class="category-count">5</span></a></li>
                                <li><a href="#">Sustainability <span class="category-count">4</span></a></li>
                                <li><a href="#">Industry News <span class="category-count">7</span></a></li>
                                <li><a href="#">Global Trade <span class="category-count">3</span></a></li>
                            </ul>
                        </div>

                        <!-- Tags Widget -->
                        <div class="sidebar-widget">
                            <h4 class="widget-title">Popular Tags</h4>
                            <div>
                                <span class="tag">Courier</span>
                                <span class="tag">Logistics</span>
                                <span class="tag">Shipping</span>
                                <span class="tag">Freight</span>
                                <span class="tag">Delivery</span>
                                <span class="tag">Transport</span>
                                <span class="tag">Warehouse</span>
                                <span class="tag">Supply Chain</span>
                                <span class="tag">Cargo</span>
                                <span class="tag">Express</span>
                                <span class="tag">Logistics Tech</span>
                                <span class="tag">E-commerce</span>
                            </div>
                        </div>

                        <!-- Newsletter Widget -->
                        <div class="sidebar-widget text-center bg-light-theme rounded py-4">
                            <div class="mb-3"><i class="icofont-envelope icofont-3x txt-orange"></i></div>
                            <h4 class="h4-md fw-5 txt-blue mb-3">Subscribe to Newsletter</h4>
                            <p>Get weekly updates about logistics industry</p>
                            <form class="mt-3">
                                <div class="input-group mb-3">
                                    <input type="email" class="form-control" placeholder="Your email">
                                    <button class="btn btn-theme bg-navy-blue" type="submit">Subscribe</button>
                                </div>
                            </form>
                        </div>

                        <!-- Archive Widget -->
                        <div class="sidebar-widget">
                            <h4 class="widget-title">Archives</h4>
                            <ul class="list-unstyled category-list">
                                <li><a href="#">March 2024 <span class="category-count">8</span></a></li>
                                <li><a href="#">February 2024 <span class="category-count">12</span></a></li>
                                <li><a href="#">January 2024 <span class="category-count">10</span></a></li>
                                <li><a href="#">December 2023 <span class="category-count">7</span></a></li>
                                <li><a href="#">November 2023 <span class="category-count">9</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Blog Section End -->

        <!-- Call to Action Section -->
        <section class="blog-cta">
            <div class="container">
                <h3 class="h3-lg text-white">Want to Contribute to Our Blog?</h3>
                <p class="text-white mb-4">Share your expertise and insights about logistics and courier industry</p>
                <a href="contact.php" class="cta-btn">Become a Contributor <i class="icofont-rounded-right"></i></a>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="wide-tb-50 pb-0 bg-light-theme footer-subscribe">
            <div class="container wow fadeInDown" data-wow-duration="0" data-wow-delay="0s">
                <div class="row">
                    <div class="col-sm-12 d-flex col-md-12 col-lg-6 offset-lg-3">
                        <div class="d- align-items-center d-sm-inline-flex w-100">
                            <div class="head">
                                <span class="d-block">STAY UPDATED</span> WITH OUR NEWSLETTER
                            </div>
                            <form class="flex-nowrap col ms-3">
                                <input type="email" class="form-control" placeholder="Enter your email address">
                                <button type="submit" class="btn-theme bg-navy-blue">SUBSCRIBE NOW</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
    <!-- Main Body Content End -->


  <!-- Main Footer Start -->
<footer id="contact" class="wide-tb-70 bg-light-gray pb-0">
    <div class="container">
        <div class="row">

            <!-- About Section -->
            <div class="col-lg-4 col-md-6 wow fadeInLeft" data-wow-duration="0" data-wow-delay="0s">
                <div class="logo-footer mb-3">
                    <a class="navbar-brand logo-box" href="index.php">
                        <img src="assets/images/new logo.png" alt="Courier Logo">
</a>
                </div>
                <p class="mb-3"><strong>Courier Management System</strong> helps businesses automate their delivery operations with real-time tracking, automated billing, and comprehensive reporting.</p>
                <p>Streamline your logistics, enhance customer satisfaction, and grow your business with our powerful platform.</p>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 wow fadeInLeft" data-wow-duration="0" data-wow-delay="0.2s">
                <h4 class="footer-heading">Quick Links</h4>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2"><a href="index.php"><i class="icofont-thin-right me-1"></i> Home</a></li>
                    <li class="mb-2"><a href="about-us.php"><i class="icofont-thin-right me-1"></i> About Us</a></li>
                    <li class="mb-2"><a href="services.php"><i class="icofont-thin-right me-1"></i> Services</a></li>
                    <li class="mb-2"><a href="gallery.php"><i class="icofont-thin-right me-1"></i> Gallery</a></li>
                    <li class="mb-2"><a href="blog.php"><i class="icofont-thin-right me-1"></i> Blog</a></li>
                </ul>
            </div>

            <!-- System Links -->
            <div class="col-lg-3 col-md-6 wow fadeInLeft" data-wow-duration="0" data-wow-delay="0.3s">
                <h4 class="footer-heading">System Access</h4>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2"><a href="user/track.php"><i class="icofont-thin-right me-1"></i> Track Shipment</a></li>
                    <li class="mb-2"><a href="login.php?role=admin"><i class="icofont-thin-right me-1"></i> Admin Login</a></li>
                    <li class="mb-2"><a href="login.php?role=3"><i class="icofont-thin-right me-1"></i> Agent Login</a></li>
                    <li class="mb-2"><a href="login.php?role=2"><i class="icofont-thin-right me-1"></i> Customer Login</a></li>
                </ul>
                
                <h4 class="footer-heading mt-4">Connect</h4>
                <div class="social-icons">
                    <a href="#"><i class="icofont-facebook"></i></a>
                    <a href="#"><i class="icofont-twitter"></i></a>
                    <a href="#"><i class="icofont-linkedin"></i></a>
                    <a href="#"><i class="icofont-youtube-play"></i></a>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6 wow fadeInLeft" data-wow-duration="0" data-wow-delay="0.4s">
                <h4 class="footer-heading">Contact Us</h4>
                <div class="contact-info">
                    <p class="mb-2"><i class="icofont-location-pin me-2 text-orange"></i> 123 Logistics Street<br>City, State 12345</p>
                    <p class="mb-2"><i class="icofont-ui-call me-2 text-orange"></i> +1 (234) 567-8900</p>
                    <p class="mb-3"><i class="icofont-email me-2 text-orange"></i> info@courierms.com</p>
                    <p class="mb-0"><i class="icofont-clock-time me-2 text-orange"></i> Mon-Fri: 9AM-6PM</p>
                </div>
            </div>

        </div>
    </div>

    <div class="copyright-wrap bg-navy-blue wide-tb-30">
        <div class="container">
            <div class="row text-md-start text-center">
                <div class="col-sm-12 col-md-6 copyright-links">
                    <a href="#">Privacy Policy</a> | <a href="#">Terms</a> | <a href="#contact">Contact</a>
                </div>
                <div class="col-sm-12 col-md-6 text-md-end text-center">
                    &copy; <span id="currentYear"></span> Courier Management System. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Main Footer End -->

    <!-- Search Popup Start -->
    <div class="overlay overlay-hugeinc">
        <form class="form-inline mt-2 mt-md-0">
            <div class="form-inner">
                <div class="form-inner-div d-inline-flex align-items-center no-gutters">
                    <div class="col-auto">
                        <i class="icofont-search"></i>
                    </div>
                    <div class="col">
                        <input class="form-control w-100 p-0" type="text" placeholder="Search" aria-label="Search">
                    </div>
                    <div class="col-auto">
                        <a href="#" class="overlay-close link-oragne"><i class="icofont-close-line"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Search Popup End -->

    <!-- Back To Top Start -->
    <a id="mkdf-back-to-top" href="#" class="off"><i class="icofont-rounded-up"></i></a>
    <!-- Back To Top End -->

    <!-- Jquery Library JS -->
    <script data-cfasync="false" src="../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/theme-plugins.min.js"></script>
    <script src="assets/js/site-custom.js"></script>

    <script>
    // Set current year in footer
    document.getElementById('currentYear').textContent = new Date().getFullYear();
    
    // Initialize tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    </script>

</body>
</html>