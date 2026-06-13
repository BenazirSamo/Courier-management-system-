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
    <title>Our Services - Courier Management System</title>
    <meta name="author" content="Mannat Studio">
    <meta name="description" content="Comprehensive courier and logistics services for businesses - Ground, Air, and Sea delivery solutions.">
    <meta name="keywords"
        content="courier, services, logistics, delivery, ground transport, air freight, sea shipping, cargo, tracking, management system">

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

/* Services Hero Section */
.services-hero-banner {
    background:linear-gradient(rgb(0, 0, 0), rgba(255, 255, 255, 0.1)), url('assets/images/my\ \(1\).jfif');
    background-size: cover;
    background-position: center;
    padding: 160px 0 120px;
    color: white;
    position: relative;
}

.services-hero-banner .container {
    position: relative;
    z-index: 2;
}

.services-hero-banner h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: white;
}

.services-hero-banner .hero-description {
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

.service-icon-box {
    transition: all 0.3s ease;
    height: 100%;
}

.service-icon-box:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.service-icon-box .icon {
    font-size: 3rem;
    color: #ffa435;
    margin-bottom: 20px;
}

/* Feature Services */
.feature-service-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.feature-service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.feature-service-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.feature-service-card .card-body {
    padding: 30px;
}
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
                                    <a class="nav-link active" href="services.php">Our Services</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="blog.php">Blog</a>
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
    <!-- Services Hero Section Start -->
    <section class="services-hero-banner">
        <div class="container">
            <div class="text-center">
                <h1>Our Comprehensive Courier Services</h1>
                <p class="hero-description">
                    We provide end-to-end logistics solutions with real-time tracking, automated billing, 
                    and efficient delivery management. Choose from our range of ground, air, and sea transport services.
                </p>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Services</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- Services Hero Section End -->

    <!-- Main Body Content Start -->
    <main id="body-content">

        <!-- Feature Services Start -->
        <section class="wide-tb-100">
            <div class="container">
                <div class="row">
                    <!-- Heading Main -->
                    <div class="col-sm-12 wow fadeInDown" data-wow-duration="0" data-wow-delay="0s">
                        <h1 class="heading-main text-center">
                            <span>Core Services</span>
                            Our Main Delivery Solutions
                        </h1>
                    </div>
                    <!-- Heading Main -->

                    <!-- Ground Delivery -->
                    <div class="col-md-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.1s">
                        <a href="#ground-delivery">
                            <div class="feature-service-card">
                                <img src="assets/images/hi  (1).jfif" alt="Ground Delivery">
                                <div class="card-body text-center">
                                    <div class="icon">
                                        <i class="icofont-vehicle-delivery-van"></i>
                                    </div>
                                    <h3 class="h4-md txt-blue">Ground Delivery</h3>
                                    <p>Reliable and cost-effective ground transportation for local and regional shipments with real-time tracking.</p>
                                    <div class="mt-3">
                                        <a href="#ground-delivery" class="btn-theme bg-navy-blue">Learn More</a>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Ground Delivery -->

                    <!-- Air Delivery -->
                    <div class="col-md-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.3s">
                        <a href="#air-delivery">
                            <div class="feature-service-card">
                                <img src="assets/images/my (6).jfif" alt="Air Delivery">
                                <div class="card-body text-center">
                                    <div class="icon">
                                        <i class="icofont-airplane-alt"></i>
                                    </div>
                                    <h3 class="h4-md txt-blue">Air Delivery</h3>
                                    <p>Fast and secure air freight services for urgent shipments and international deliveries.</p>
                                    <div class="mt-3">
                                        <a href="#air-delivery" class="btn-theme bg-navy-blue">Learn More</a>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Air Delivery -->

                    <!-- Sea Delivery -->
                    <div class="col-md-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.5s">
                        <a href="#sea-delivery">
                            <div class="feature-service-card">
                                <img src="assets/images/icon-box-3.jpg" alt="Sea Delivery">
                                <div class="card-body text-center">
                                    <div class="icon">
                                        <i class="icofont-ship"></i>
                                    </div>
                                    <h3 class="h4-md txt-blue">Sea Delivery</h3>
                                    <p>Economical sea freight solutions for bulk shipments and international cargo transport.</p>
                                    <div class="mt-3">
                                        <a href="#sea-delivery" class="btn-theme bg-navy-blue">Learn More</a>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Sea Delivery -->
                </div>
            </div>
        </section>
        <!-- Feature Services End -->

        <!-- Ground Delivery Details Start -->
        <section id="ground-delivery" class="wide-tb-100 bg-light-gray">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 wow fadeInLeft" data-wow-duration="0" data-wow-delay="0s">
                        <h2 class="mb-4 fw-7 txt-blue">
                            <i class="icofont-vehicle-delivery-van me-2"></i>
                            Ground Delivery Services
                        </h2>
                        <p>Our ground delivery services provide reliable and efficient transportation solutions for local, regional, and national shipments. With our fleet of modern vehicles and experienced drivers, we ensure your packages reach their destination safely and on time.</p>
                        
                        <div class="mt-4">
                            <h4 class="h4-md txt-orange">Key Features:</h4>
                            <ul class="list-unstyled icons-listing theme-orange">
                                <li class="mb-2"><i class="icofont-check"></i> Real-time tracking and monitoring</li>
                                <li class="mb-2"><i class="icofont-check"></i> Same-day and next-day delivery options</li>
                                <li class="mb-2"><i class="icofont-check"></i> Temperature-controlled transport</li>
                                <li class="mb-2"><i class="icofont-check"></i> Fragile and special handling available</li>
                                <li class="mb-2"><i class="icofont-check"></i> COD (Cash on Delivery) services</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 wow fadeInRight" data-wow-duration="0" data-wow-delay="0s">
                        <img src="assets/images/all my  (3).jfif" alt="Ground Delivery" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </section>
        <!-- Ground Delivery Details End -->

        <!-- Air Delivery Details Start -->
        <section id="air-delivery" class="wide-tb-100">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 wow fadeInLeft" data-wow-duration="0" data-wow-delay="0s">
                        <img src="assets/images/all my  (6).jfif" alt="Air Delivery" class="img-fluid rounded">
                    </div>
                    
                    <div class="col-lg-6 wow fadeInRight" data-wow-duration="0" data-wow-delay="0s">
                        <h2 class="mb-4 fw-7 txt-blue">
                            <i class="icofont-airplane-alt me-2"></i>
                            Air Delivery Services
                        </h2>
                        <p>For urgent shipments and time-sensitive deliveries, our air freight services provide the fastest transportation solution. We partner with leading airlines to ensure your cargo reaches any destination worldwide efficiently.</p>
                        
                        <div class="mt-4">
                            <h4 class="h4-md txt-orange">Key Features:</h4>
                            <ul class="list-unstyled icons-listing theme-orange">
                                <li class="mb-2"><i class="icofont-check"></i> Express delivery within 24-48 hours</li>
                                <li class="mb-2"><i class="icofont-check"></i> International shipping to 150+ countries</li>
                                <li class="mb-2"><i class="icofont-check"></i> Custom clearance assistance</li>
                                <li class="mb-2"><i class="icofont-check"></i> Door-to-door delivery</li>
                                <li class="mb-2"><i class="icofont-check"></i> Insurance coverage available</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Air Delivery Details End -->

        <!-- Sea Delivery Details Start -->
        <section id="sea-delivery" class="wide-tb-100 bg-light-gray">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 wow fadeInLeft" data-wow-duration="0" data-wow-delay="0s">
                        <h2 class="mb-4 fw-7 txt-blue">
                            <i class="icofont-ship me-2"></i>
                            Sea Delivery Services
                        </h2>
                        <p>Our sea freight services offer cost-effective solutions for large shipments and international cargo. Whether you need Full Container Load (FCL) or Less than Container Load (LCL) services, we provide reliable ocean transportation with comprehensive tracking.</p>
                        
                        <div class="mt-4">
                            <h4 class="h4-md txt-orange">Key Features:</h4>
                            <ul class="list-unstyled icons-listing theme-orange">
                                <li class="mb-2"><i class="icofont-check"></i> FCL and LCL shipping options</li>
                                <li class="mb-2"><i class="icofont-check"></i> Port-to-port and door-to-door services</li>
                                <li class="mb-2"><i class="icofont-check"></i> Bulk and hazardous material handling</li>
                                <li class="mb-2"><i class="icofont-check"></i> Customs documentation support</li>
                                <li class="mb-2"><i class="icofont-check"></i> Competitive international rates</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 wow fadeInRight" data-wow-duration="0" data-wow-delay="0s">
                        <img src="assets/images/all my  (4).jfif" alt="Sea Delivery" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </section>
        <!-- Sea Delivery Details End -->

        <!-- Additional Services Start -->
        <section class="wide-tb-100">
            <div class="container">
                <div class="row">
                    <!-- Heading Main -->
                    <div class="col-sm-12 wow fadeInDown" data-wow-duration="0" data-wow-delay="0s">
                        <h1 class="heading-main text-center">
                            <span>Value Added</span>
                            Additional Services
                        </h1>
                    </div>
                    <!-- Heading Main -->

                    <!-- Icon Box 2 -->
                    <div class="col-md-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.1s">
                        <div class="service-icon-box text-center p-4">
                            <div class="icon mb-3">
                                <i class="icofont-box"></i>
                            </div>
                            <h4 class="h4-md">Packaging Solutions</h4>
                            <p>Professional packaging services to ensure your items are protected during transit with appropriate materials.</p>
                        </div>
                    </div>
                    <!-- Icon Box 2 -->

                    <!-- Icon Box 2 -->
                    <div class="col-md-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.2s">
                        <div class="service-icon-box text-center p-4">
                            <div class="icon mb-3">
                                <i class="icofont-live-support"></i>
                            </div>
                            <h4 class="h4-md">Warehousing</h4>
                            <p>Secure storage facilities with inventory management for businesses needing temporary or long-term storage.</p>
                        </div>
                    </div>
                    <!-- Icon Box 2 -->

                    <!-- Icon Box 2 -->
                    <div class="col-md-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.3s">
                        <div class="service-icon-box text-center p-4">
                            <div class="icon mb-3">
                                <i class="icofont-history"></i>
                            </div>
                            <h4 class="h4-md">Reverse Logistics</h4>
                            <p>Efficient returns management system for handling product returns and exchanges.</p>
                        </div>
                    </div>
                    <!-- Icon Box 2 -->
                </div>

                <div class="row mt-4">
                    <!-- Icon Box 2 -->
                    <div class="col-md-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.4s">
                        <div class="service-icon-box text-center p-4">
                            <div class="icon mb-3">
                                <i class="icofont-tracking"></i>
                            </div>
                            <h4 class="h4-md">Real-time Tracking</h4>
                            <p>Advanced tracking system allowing customers to monitor their shipments in real-time.</p>
                        </div>
                    </div>
                    <!-- Icon Box 2 -->

                    <!-- Icon Box 2 -->
                    <div class="col-md-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.5s">
                        <div class="service-icon-box text-center p-4">
                            <div class="icon mb-3">
                                <i class="icofont-safety"></i>
                            </div>
                            <h4 class="h4-md">Insurance Services</h4>
                            <p>Comprehensive insurance coverage for valuable shipments to protect against loss or damage.</p>
                        </div>
                    </div>
                    <!-- Icon Box 2 -->

                    <!-- Icon Box 2 -->
                    <div class="col-md-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.6s">
                        <div class="service-icon-box text-center p-4">
                            <div class="icon mb-3">
                                <i class="icofont-delivery-time"></i>
                            </div>
                            <h4 class="h4-md">Scheduled Pickups</h4>
                            <p>Flexible pickup schedules including same-day, scheduled, and on-demand pickup services.</p>
                        </div>
                    </div>
                    <!-- Icon Box 2 -->
                </div>
            </div>
        </section>
        <!-- Additional Services End -->

        <!-- How It Works Start -->
        <section class="wide-tb-100 bg-navy-blue txt-white">
            <div class="container">
                <div class="row">
                    <!-- Heading Main -->
                    <div class="col-sm-12 wow fadeInDown" data-wow-duration="0" data-wow-delay="0s">
                        <h1 class="heading-main text-center txt-white">
                            <span>Simple Process</span>
                            How It Works
                        </h1>
                    </div>
                    <!-- Heading Main -->

                    <!-- Step 1 -->
                    <div class="col-md-4 text-center wow fadeInUp" data-wow-duration="0" data-wow-delay="0.1s">
                        <div class="icon-box-5 mb-4">
                            <div class="step-number">1</div>
                            <i class="icofont-ui-add"></i>
                        </div>
                        <h4 class="h4-md">Schedule Pickup</h4>
                        <p>Book your shipment online or call our customer service to schedule a pickup at your convenience.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="col-md-4 text-center wow fadeInUp" data-wow-duration="0" data-wow-delay="0.3s">
                        <div class="icon-box-5 mb-4">
                            <div class="step-number">2</div>
                            <i class="icofont-vehicle-delivery-van"></i>
                        </div>
                        <h4 class="h4-md">We Collect & Process</h4>
                        <p>Our team collects your package, processes documentation, and prepares it for transportation.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="col-md-4 text-center wow fadeInUp" data-wow-duration="0" data-wow-delay="0.5s">
                        <div class="icon-box-5 mb-4">
                            <div class="step-number">3</div>
                            <i class="icofont-tick-mark"></i>
                        </div>
                        <h4 class="h4-md">Delivery Confirmation</h4>
                        <p>Your package is delivered to the destination and you receive confirmation with proof of delivery.</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- How It Works End -->

        <!-- Callout Start -->
        <section class="wide-tb-80 bg-scroll bg-img-6 pos-rel callout-style-1">
            <div class="bg-overlay blue opacity-60"></div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-12 mb-0 wow slideInUp" data-wow-duration="0" data-wow-delay="0.1s">
                        <h4 class="h4-xl">Need Customized Courier Solutions?</h4>
                    </div>
                    <div class="col wow slideInUp" data-wow-duration="0" data-wow-delay="0.2s">
                        <div class="center-text">
                            We offer tailored logistics solutions for businesses of all sizes. Contact us to discuss your specific requirements.
                        </div>
                    </div>
                    <div class="col-sm-auto wow slideInUp" data-wow-duration="0" data-wow-delay="0.3s">
                        <a href="contact.php" class="btn btn-theme bg-white bordered">Contact Us <i
                                class="icofont-rounded-right"></i></a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Callout End -->

        <!-- FAQ Section Start -->
        <section class="wide-tb-100">
            <div class="container">
                <div class="row">
                    <!-- Heading Main -->
                    <div class="col-sm-12 wow fadeInDown" data-wow-duration="0" data-wow-delay="0s">
                        <h1 class="heading-main text-center">
                            <span>Common Questions</span>
                            Service FAQs
                        </h1>
                    </div>
                    <!-- Heading Main -->

                    <!-- FAQ Item 1 -->
                    <div class="col-md-6 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.1s">
                        <div class="faq-item mb-4">
                            <h4 class="h4-md mb-3">What are your delivery timeframes?</h4>
                            <p>Ground delivery typically takes 1-3 business days locally, 3-5 days regionally. Air delivery is available within 24-48 hours for domestic and 2-3 days for international shipments.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="col-md-6 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.2s">
                        <div class="faq-item mb-4">
                            <h4 class="h4-md mb-3">Do you handle fragile items?</h4>
                            <p>Yes, we have special handling procedures for fragile items including extra padding, careful labeling, and dedicated fragile item handling by trained staff.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="col-md-6 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.3s">
                        <div class="faq-item mb-4">
                            <h4 class="h4-md mb-3">What payment methods do you accept?</h4>
                            <p>We accept all major credit cards, bank transfers, and cash on delivery. Business accounts can be set up for regular shipments with monthly billing.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="col-md-6 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.4s">
                        <div class="faq-item mb-4">
                            <h4 class="h4-md mb-3">Can I track my shipment in real-time?</h4>
                            <p>Yes, we provide real-time tracking through our website and mobile app. You'll receive tracking updates via SMS and email at every stage of delivery.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="col-md-6 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.5s">
                        <div class="faq-item mb-4">
                            <h4 class="h4-md mb-3">Do you offer international shipping?</h4>
                            <p>Yes, we provide international shipping to over 150 countries through our air and sea freight services, with customs clearance assistance included.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div class="col-md-6 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.6s">
                        <div class="faq-item mb-4">
                            <h4 class="h4-md mb-3">What if my package gets damaged?</h4>
                            <p>All shipments are insured. In case of damage, file a claim through our website within 7 days of delivery. Our insurance team will process your claim promptly.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- FAQ Section End -->

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

    <div class="video-box">
        <!-- close-video -->
        <div class="close-video">
            <i class="icofont-close-line"></i>
        </div><!-- /close-video -->
    </div><!-- /video-box -->

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

    <!-- Theme Custom FIle -->
    <script src="assets/js/site-custom.js"></script>
    
    <!-- Current Year Script -->
    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>
    
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"version":"2024.11.0","token":"64224fc8786846928480d180dfc466bd","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}' crossorigin="anonymous"></script>
</body>

</html>