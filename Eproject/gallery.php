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
    <title>Gallery - Courier Management System</title>
    <meta name="author" content="Mannat Studio">
    <meta name="description" content="Photo gallery showcasing our courier operations, facilities, and successful deliveries.">
    <meta name="keywords"
        content="courier gallery, logistics photos, delivery operations, warehouse, transportation, fleet, facilities">

    <!-- xxx Favicon xxx -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">

    <!-- Main Style CSSS -->
    <link href="assets/css/theme-plugins.min.css" rel="stylesheet">
    <!-- Main Theme CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- Responsive Theme CSS -->
    <link href="assets/css/responsive.css" rel="stylesheet">

    <!-- Gallery Lightbox CSS -->
    <link href="assets/css/cubeportfolio.min.css" rel="stylesheet">

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

/* Gallery Hero Section */
.gallery-hero-banner {
    background:linear-gradient(rgb(0, 0, 0), rgba(255, 255, 255, 0.1)), url('assets/images/my (3).jfif');
    background-size: cover;
    background-position: center;
    padding: 160px 0 120px;
    color: white;
    position: relative;
}

.gallery-hero-banner .container {
    position: relative;
    z-index: 2;
}

.gallery-hero-banner h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: white;
}

.gallery-hero-banner .hero-description {
    font-size: 1.2rem;
    max-width: 800px;
    margin: 0 auto 30px;
    line-height: 1.8;
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

/* Gallery Styles */
.gallery-item {
    position: relative;
    margin-bottom: 30px;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}

.gallery-item img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.gallery-item:hover img {
    transform: scale(1.1);
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(19, 19, 18, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-overlay-content {
    text-align: center;
    color: white;
    padding: 20px;
}

.gallery-overlay-content h4 {
    font-size: 1.2rem;
    margin-bottom: 10px;
    color: #ffa435;
}

.gallery-overlay-content p {
    font-size: 0.9rem;
    margin-bottom: 0;
}

.gallery-icon {
    font-size: 2rem;
    margin-bottom: 15px;
    color: #ffa435;
}

/* Gallery Filter */
.gallery-filter {
    margin-bottom: 40px;
}

.gallery-filter .btn {
    margin: 5px;
    padding: 8px 20px;
    border: 2px solid #f8972a;
    background: transparent;
    color: #e67d22;
    border-radius: 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.gallery-filter .btn:hover,
.gallery-filter .btn.active {
    background: #e87d26;
    color: white;
    border-color: #f49d19;
}

/* Gallery Category Sections */
.gallery-category-title {
    margin: 60px 0 40px;
    padding-bottom: 15px;
    border-bottom: 2px solid #ffa435;
    position: relative;
}

.gallery-category-title:after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100px;
    height: 2px;
    background: #002a5b;
}
/* Gallery Stats */
.gallery-stats {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 30px;
    margin: 50px 0;
}

.gallery-stats .stat-item {
    text-align: center;
    padding: 20px;
}

.gallery-stats .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #002a5b;
    display: block;
    margin-bottom: 10px;
}

.gallery-stats .stat-label {
    font-size: 1rem;
    color: rgb(102, 102, 102);
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

    <!-- Page loader Start -->
    <div id="pageloader">
        <div class="loader-item">
            <div class="loader">
                <div class="spin"></div>
                <div class="bounce"></div>
            </div>
        </div>
    </div>
    <!-- Page loader End -->


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
                           <a class="navbar-brand icon-logo" href="index.php">
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
                                    <a class="nav-link" href="blog.php">Blog</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="gallery.php">Gallery</a>
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
    <!-- Gallery Hero Section Start -->
    <section class="gallery-hero-banner">
        <div class="container">
            <div class="text-center">
                <h1>Our Gallery</h1>
                <p class="hero-description">
                    Explore our photo gallery showcasing the operations, facilities, and success stories 
                    of Courier Management System. Witness our commitment to excellence in logistics.
                </p>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Gallery</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- Gallery Hero Section End -->

    <!-- Main Body Content Start -->
    <main id="body-content">

        <!-- Gallery Stats Start -->
        <section class="wide-tb-50">
            <div class="container">
                <div class="row gallery-stats">
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <span class="stat-number">500+</span>
                            <span class="stat-label">Successful Deliveries</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <span class="stat-number">50+</span>
                            <span class="stat-label">Happy Clients</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <span class="stat-number">100+</span>
                            <span class="stat-label">Vehicles in Fleet</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Operations Support</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Gallery Stats End -->

        <!-- Gallery Filter Start -->
        <section class="wide-tb-30">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="gallery-filter text-center">
                            <button class="btn active" data-filter="*">All Photos</button>
                            <button class="btn" data-filter=".operations">Operations</button>
                            <button class="btn" data-filter=".facilities">Facilities</button>
                            <button class="btn" data-filter=".team">Our Team</button>
                            <button class="btn" data-filter=".events">Events</button>
                            <button class="btn" data-filter=".vehicles">Vehicles</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Gallery Filter End -->

        <!-- Main Gallery Start -->
        <section class="wide-tb-100">
            <div class="container">
                <div class="row" id="gallery-grid">
                    
                    <!-- Operations Gallery -->
                    <div class="col-md-4 gallery-item operations">
                        <img src="assets/images/hi  (16).jfif" alt="Warehouse Operations">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-factory gallery-icon"></i>
                                <h4>Warehouse Operations</h4>
                                <p>Our automated warehouse with efficient sorting systems</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 gallery-item operations">
                        <img src="assets/images/hi  (18).jfif" alt="Sorting Center">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-conveyor gallery-icon"></i>
                                <h4>Sorting Center</h4>
                                <p>High-speed package sorting and distribution hub</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 gallery-item operations">
                        <img src="assets/images/hi  (2).jfif" alt="Delivery Process">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-delivery-time gallery-icon"></i>
                                <h4>Delivery Process</h4>
                                <p>Efficient last-mile delivery operations</p>
                            </div>
                        </div>
                    </div>

                    <!-- Facilities Gallery -->
                    <div class="col-md-4 gallery-item facilities">
                        <img src="assets/images/hi  (10).jfif" alt="Main Office">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-building gallery-icon"></i>
                                <h4>Headquarters</h4>
                                <p>Our main corporate office and control center</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 gallery-item facilities">
                        <img src="assets/images/hi  (19).jfif" alt="Technology Center">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-computer gallery-icon"></i>
                                <h4>Technology Center</h4>
                                <p>Advanced IT infrastructure and monitoring systems</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 gallery-item facilities">
                        <img src="assets/images/hi  (14).jfif" alt="Training Facility">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-learn gallery-icon"></i>
                                <h4>Training Facility</h4>
                                <p>Employee training and development center</p>
                            </div>
                        </div>
                    </div>

                    <!-- Team Gallery -->
                    <div class="col-md-4 gallery-item team">
                        <img src="assets/images/hi  (21).jfif" alt="Management Team">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-group gallery-icon"></i>
                                <h4>Management Team</h4>
                                <p>Our experienced leadership team</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 gallery-item team">
                        <img src="assets/images/hi  (17).jfif" alt="Operations Team">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-users-alt-2 gallery-icon"></i>
                                <h4>Operations Team</h4>
                                <p>Dedicated operations and logistics staff</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 gallery-item team">
                        <img src="assets/images/hi  (13).jfif" alt="Support Team">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-customer-service gallery-icon"></i>
                                <h4>Support Team</h4>
                                <p>24/7 customer support professionals</p>
                            </div>
                        </div>
                    </div>

                    <!-- Events Gallery -->
                    <div class="col-md-4 gallery-item events">
                        <img src="assets/images/hi  (5).jfif" alt="Award Ceremony">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-award gallery-icon"></i>
                                <h4>Award Ceremony</h4>
                                <p>Receiving excellence in logistics award</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 gallery-item events">
                        <img src="assets/images/hi  (20).jfif" alt="Company Event">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-celebration gallery-icon"></i>
                                <h4>Annual Meet</h4>
                                <p>Company annual celebration event</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 gallery-item events">
                        <img src="assets/images/hi  (9).jfif" alt="Training Session">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-presentation gallery-icon"></i>
                                <h4>Training Session</h4>
                                <p>Employee skill development program</p>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicles Gallery -->
                    <div class="col-md-4 gallery-item vehicles">
                        <img src="assets/images/hi  (2).jfif" alt="Delivery Vans">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-truck-loaded gallery-icon"></i>
                                <h4>Delivery Fleet</h4>
                                <p>Modern delivery vans for urban logistics</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 gallery-item vehicles">
                        <img src="assets/images/hi  (3).jfif" alt="Trucks">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-truck gallery-icon"></i>
                                <h4>Transport Trucks</h4>
                                <p>Heavy-duty trucks for intercity transport</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 gallery-item vehicles">
                        <img src="assets/images/hi  (4).jfif" alt="Special Vehicles">
                        <div class="gallery-overlay">
                            <div class="gallery-overlay-content">
                                <i class="icofont-ambulance gallery-icon"></i>
                                <h4>Special Vehicles</h4>
                                <p>Temperature-controlled and special cargo vehicles</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- Main Gallery End -->

        
        <!-- Gallery Description Start -->
        <section class="wide-tb-100">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 wow fadeInLeft" data-wow-duration="0" data-wow-delay="0s">
                        <h2 class="mb-4 fw-7 txt-blue">
                            Behind The Scenes
                        </h2>
                        <p>Our gallery showcases the dedication, technology, and teamwork that power Courier Management System. Each photo tells a story of our commitment to excellence in logistics and customer service.</p>
                        
                        <p>From our state-of-the-art facilities to our dedicated team members, every aspect of our operation is designed to deliver reliable, efficient, and secure courier services. We believe in transparency and are proud to share our journey with you.</p>
                        
                        <div class="mt-4">
                            <a href="contact.php" class="btn-theme bg-navy-blue">Contact Us for More Info</a>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 wow fadeInRight" data-wow-duration="0" data-wow-delay="0s">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <img src="assets/images/hi  (12).jfif" alt="Technology" class="img-fluid rounded">
                            </div>
                            <div class="col-6 mb-3">
                                <img src="assets/images/hi  (8).jfif" alt="Teamwork" class="img-fluid rounded">
                            </div>
                            <div class="col-6">
                                <img src="assets/images/hi  (7).jfif" alt="Operations" class="img-fluid rounded">
                            </div>
                            <div class="col-6">
                                <img src="assets/images/hi  (6).jfif" alt="Success" class="img-fluid rounded">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Gallery Description End -->

        <!-- Callout Start -->
        <section class="wide-tb-80 bg-scroll bg-img-6 pos-rel callout-style-1">
            <div class="bg-overlay blue opacity-60"></div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-12 mb-0 wow slideInUp" data-wow-duration="0" data-wow-delay="0.1s">
                        <h4 class="h4-xl">Want to Visit Our Facilities?</h4>
                    </div>
                    <div class="col wow slideInUp" data-wow-duration="0" data-wow-delay="0.2s">
                        <div class="center-text">
                            Schedule a tour of our facilities to see our operations firsthand. Experience our commitment to excellence in logistics.
                        </div>
                    </div>
                    <div class="col-sm-auto wow slideInUp" data-wow-duration="0" data-wow-delay="0.3s">
                        <a href="contact.php" class="btn btn-theme bg-white bordered">Schedule Visit <i
                                class="icofont-rounded-right"></i></a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Callout End -->

    </main>

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

    <!-- Request Modal -->
    <div class="modal fade" id="request_popup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered request_popup modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" data-lenis-prevent>
                    <!-- Contact Details Start -->
                    <section class="pos-rel bg-light-gray">
                        <div class="container-fluid p-0">
                            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="icofont-close-line"></i>
                            </a>
                            <div class="d-lg-flex justify-content-end no-gutters mb-spacer-md">
                                <div class="col bg-fixed bg-img-7 request_pag_img">
                                    &nbsp;
                                </div>


                                <div class="col-lg-7 col-12">
                                    <div class="form-content">
                                        <h2 class="h2-xl mb-4 fw-6 txt-orange">Request A Quote</h2>
                                        <form action="#" method="post" novalidate="novalidate" class="rounded-field">

                                            <div class="row g-3 mb-4">
                                                <div class="col-md">
                                                    <select title="Please choose a package" required="" name="package"
                                                        class="form-control wide" aria-required="true"
                                                        aria-invalid="false">
                                                        <option value="">Freight Type</option>
                                                        <option value="Type 1">Type 1</option>
                                                        <option value="Type 2">Type 2</option>
                                                        <option value="Type 3">Type 3</option>
                                                        <option value="Type 4">Type 4</option>
                                                    </select>
                                                </div>
                                                <div class="col-md">
                                                    <select title="Please choose a package" required="" name="package"
                                                        class="form-control wide" aria-required="true"
                                                        aria-invalid="false">
                                                        <option value="">Incoterms</option>
                                                        <option value="Type 1">Type 1</option>
                                                        <option value="Type 2">Type 2</option>
                                                        <option value="Type 3">Type 3</option>
                                                        <option value="Type 4">Type 4</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-3 mb-4">
                                                <div class="col-md">
                                                    <input type="text" name="name" class="form-control"
                                                        placeholder="City of departure">
                                                </div>
                                                <div class="col-md">
                                                    <input type="text" name="email" class="form-control"
                                                        placeholder="Delivery city">
                                                </div>
                                            </div>
                                            <div class="row g-3 mb-4">
                                                <div class="col-md">
                                                    <input type="text" name="name" class="form-control"
                                                        placeholder="Total gross weight (KG)">
                                                </div>
                                                <div class="col-md">
                                                    <input type="text" name="email" class="form-control"
                                                        placeholder="Dimension">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="col-md">
                                                    <div class="center-head"><span class="bg-light-gray txt-orange">Your
                                                            Personal Details</span></div>
                                                </div>
                                            </div>
                                            <div class="row g-3 mb-4">
                                                <div class="col-md">
                                                    <input type="text" name="name" class="form-control mb-3"
                                                        placeholder="Your Name">
                                                    <input type="text" name="name" class="form-control mb-3"
                                                        placeholder="Email">
                                                    <input type="text" name="name" class="form-control"
                                                        placeholder="Phone Number">
                                                </div>
                                                <div class="col-md">
                                                    <textarea rows="7" placeholder="Message"
                                                        class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col pt-3">
                                                    <button type="submit" class="form-btn btn-theme bg-orange">Send
                                                        Message <i class="icofont-rounded-right"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Contact Details End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Request Modal -->

    <!-- Back To Top Start -->
    <a id="mkdf-back-to-top" href="#" class="off"><i class="icofont-rounded-up"></i></a>
    <!-- Back To Top End -->

    <!-- Jquery Library JS -->
    <script data-cfasync="false" src="../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/theme-plugins.min.js"></script>
    <script src="assets/twitter/jquery.tweet.js"></script>

    <!-- Gallery Filter JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gallery filter functionality
            const filterButtons = document.querySelectorAll('.gallery-filter .btn');
            const galleryItems = document.querySelectorAll('.gallery-item');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Get filter value
                    const filterValue = this.getAttribute('data-filter');
                    
                    // Show/hide gallery items based on filter
                    galleryItems.forEach(item => {
                        if (filterValue === '*' || item.classList.contains(filterValue.substring(1))) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
            
            // Play button functionality for videos
            const playButtons = document.querySelectorAll('.play-button');
            playButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    alert('Video player would open here. In a real implementation, this would play the video.');
                });
            });
            
            // Image click for lightbox (simplified)
            const galleryImages = document.querySelectorAll('.gallery-item img');
            galleryImages.forEach(img => {
                img.addEventListener('click', function() {
                    const src = this.getAttribute('src');
                    const alt = this.getAttribute('alt');
                    alert(`Viewing: ${alt}\n\nIn a real implementation, this would open in a lightbox.`);
                });
            });
        });
        
        // Current year for copyright
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>

    <!-- Theme Custom FIle -->
    <script src="assets/js/site-custom.js"></script>
    
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"version":"2024.11.0","token":"64224fc8786846928480d180dfc466bd","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}' crossorigin="anonymous"></script>
</body>

</html>