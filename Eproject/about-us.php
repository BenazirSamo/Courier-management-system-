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
    <title>About Us - Courier Management System</title>
    <meta name="author" content="Mannat Studio">
    <meta name="description" content="About Courier Management System - Efficient logistics and cargo solutions for businesses.">
    <meta name="keywords"
        content="courier, responsive, html5, business, cargo, chain supply, company, corporate, expedition, freight, logistics, packaging, services, shipping, transport, transportation, trucking, warehousing">

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

/* About Hero Section - Template Style */
.about-hero-banner {
    background:linear-gradient(rgb(0, 0, 0), rgba(255, 255, 255, 0.1)), url('assets/images/icon-box-img-2.jpg');
    background-size: cover;
    background-position: center;
    padding: 160px 0 120px;
    color: white;
    position: relative;
}

.about-hero-banner .container {
    position: relative;
    z-index: 2;
}

.about-hero-banner h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: white;
}

.about-hero-banner .hero-description {
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
                                    <a class="nav-link active" href="about-us.php">What We Do</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="services.php">Our Services</a>
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

    <!-- About Hero Section Start -->
    <section class="about-hero-banner">
        <div class="container">
            <div class="text-center">
                <h1>About Courier Management System</h1>
                <p class="hero-description">
                    We are a leading provider of comprehensive logistics and courier management solutions. 
                    Our platform streamlines delivery operations with real-time tracking, automated billing, 
                    and efficient logistics management for businesses of all sizes.
                </p>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">About Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Body Content Start -->
    <main id="body-content">

        <!-- What Makes Us Special Start -->
        <section class="wide-tb-80">
            <div class="container pos-rel">
                <div class="row align-items-center">

                    <div class="col-md-6 wow fadeInLeft" data-wow-duration="0" data-wow-delay="0s">
                        <h2 class="mb-4 fw-7 txt-blue">
                            About <span class="fw-6 txt-orange">Courier Management System</span>
                        </h2>

                        <p>Energistically utilize team driven niche markets rather than leveraged platforms.
                            Monotonectally restore tactical "outside the box" thinking and technically sound
                            deliverables. </p>

                        <p>Compellingly develop fully researched process improvements through innovative opportunities.
                            Credibly productize highly efficient potentialities for vertical core competencies. Quickly
                            maintain pandemic experiences rather than low-risk high-yield processes.</p>
                    </div>


                    <div class="col-md-6 wow fadeInRight" data-wow-duration="0" data-wow-delay="0s">
                        <img src="assets/images/map-bg-orange.jpg" alt="">
                    </div>

                </div>

            </div>
        </section>
        <!-- What Makes Us Special End -->

        <!-- What Makes Us Special Start -->
        <section class="bg-light-gray wide-tb-100 pb-5 why-choose">
            <div class="container pos-rel">
                <div class="row">
                    <!-- Heading Main -->
                    <div class="col-sm-12 wow fadeInDown" data-wow-duration="0" data-wow-delay="0s">
                        <h1 class="heading-main">
                            <span>Our Goodness</span>
                            What Makes Us Special
                        </h1>
                    </div>
                    <!-- Heading Main -->

                    <!-- Icon Box 2 -->
                    <div class="col-12 col-lg-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0s">
                        <div class="icon-box-2">
                            <div class="media">
                                <div class="service-icon">
                                    <i class="icofont-id"></i>
                                </div>
                                <div class="service-inner-content media-body">
                                    <h4 class="h4-md">Trusted Franchise</h4>
                                    <p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis.
                                        Vivamus ac ultrices diam, vitae accumsan tellus.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Icon Box -->

                    <!-- Icon Box 2 -->
                    <div class="col-12 col-lg-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.2s">
                        <div class="icon-box-2">
                            <div class="media">
                                <div class="service-icon">
                                    <i class="icofont-live-support"></i>
                                </div>
                                <div class="service-inner-content media-body">
                                    <h4 class="h4-md">Customer Support</h4>
                                    <p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis.
                                        Vivamus ac ultrices diam, vitae accumsan tellus.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Icon Box -->

                    <!-- Icon Box 2 -->
                    <div class="col-12 col-lg-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.4s">
                        <div class="icon-box-2">
                            <div class="media">
                                <div class="service-icon">
                                    <i class="icofont-history"></i>
                                </div>
                                <div class="service-inner-content media-body">
                                    <h4 class="h4-md">Reliability & Punctuality</h4>
                                    <p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis.
                                        Vivamus ac ultrices diam, vitae accumsan tellus.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Icon Box -->
                </div>

            </div>
        </section>
        <!-- What Makes Us Special End -->

        <!-- Counter Start -->
        <section class="wide-tb-100 mb-spacer-md">
            <div class="container wide-tb-100 pb-0">
                <div class="row d-flex align-items-center">
                    <!-- Counter Col Start -->
                    <div class="col col-12 col-lg-3 col-sm-6 wow slideInUp" data-wow-duration="0" data-wow-delay="0s">
                        <div class="counter-style-1 light-bg">
                            <p class="mb-1"><i class="icofont-google-map"></i></p>
                            <span class="counter">15</span>
                            <div>
                                Our Locations
                            </div>
                        </div>
                    </div>
                    <!-- Counter Col End -->

                    <!-- Counter Col Start -->
                    <div class="col col-12 col-lg-3 col-sm-6 wow slideInUp" data-wow-duration="0" data-wow-delay="0.3s">
                        <div class="counter-style-1 light-bg">
                            <p class="mb-1"><i class="icofont-globe"></i></p>
                            <span class="counter">110</span>
                            <span>+</span>
                            <div>
                                Clients Worldwide
                            </div>
                        </div>
                    </div>
                    <!-- Counter Col End -->

                    <!-- Spacer For Medium -->
                    <div class="w-100 d-none d-sm-block d-lg-none spacer-30"></div>
                    <!-- Spacer For Medium -->

                    <!-- Counter Col Start -->
                    <div class="col col-12 col-lg-3 col-sm-6 wow slideInUp" data-wow-duration="0" data-wow-delay="0.6s">
                        <div class="counter-style-1 light-bg">
                            <p class="mb-1"><i class="icofont-vehicle-delivery-van"></i></p>
                            <span class="counter">240</span>
                            <span>+</span>
                            <div>
                                Owned Vehicles
                            </div>
                        </div>
                    </div>
                    <!-- Counter Col End -->

                    <!-- Counter Col Start -->
                    <div class="col col-12 col-lg-3 col-sm-6 wow slideInUp" data-wow-duration="0" data-wow-delay="0.9s">
                        <div class="counter-style-1 light-bg">
                            <p class="mb-1"><i class="icofont-umbrella-alt"></i></p>
                            <span class="counter">2340</span>
                            <div>
                                Tonnes Transported
                            </div>
                        </div>
                    </div>
                    <!-- Counter Col End -->
                </div>
            </div>
        </section>
        <!-- Counter End -->

        <!-- Tracking Your Freight Start -->
        <section class="pos-rel bg-light-theme">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-5 col-12 p-0">
                        <img src="assets/images/why-choose-us.jpg" class="w-100" alt="">
                    </div>
                    <div class="col-lg-6 col-12">

                        <div class="p-5 about-whoose">
                            <!-- Heading Main -->
                            <h1 class="heading-main text-start mb-4">
                                <span>Why Choose</span>
                                Courier Management System
                            </h1>
                            <!-- Heading Main -->

                            <!-- Tracking Form -->
                            <ul class="list-unstyled icons-listing theme-orange w-half mb-0">
                                <li class="wow fadeInUp" data-wow-duration="0" data-wow-delay="0s"><i
                                        class="icofont-check"></i>Deliver Environmentally Responsible Client Services
                                </li>
                                <li class="wow fadeInUp" data-wow-duration="0" data-wow-delay="0.1s"><i
                                        class="icofont-check"></i>Be an Active Community Partner</li>
                                <li class="wow fadeInUp" data-wow-duration="0" data-wow-delay="0.2s"><i
                                        class="icofont-check"></i>Drive Continuous Improvement</li>
                                <li class="wow fadeInUp" data-wow-duration="0" data-wow-delay="0.3s"><i
                                        class="icofont-check"></i>Clearance and compliance service</li>
                                <li class="wow fadeInUp" data-wow-duration="0" data-wow-delay="0.4s"><i
                                        class="icofont-check"></i>Clearance and compliance service</li>
                                <li class="wow fadeInUp" data-wow-duration="0" data-wow-delay="0.5s"><i
                                        class="icofont-check"></i>Maintain High Ethical Standards</li>
                                <li class="wow fadeInUp" data-wow-duration="0" data-wow-delay="0.6s"><i
                                        class="icofont-check"></i>Air & Ocean Cargo Insurance</li>
                                <li class="wow fadeInUp" data-wow-duration="0" data-wow-delay="0.7s"><i
                                        class="icofont-check"></i>We ensure complete security</li>
                            </ul>
                            <!-- Tracking Form -->
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- Tracking Your Freight End -->

        <!-- Our Team Start -->
        <section class="wide-tb-100 pb-0 team-section-bottom pos-rel">
            <div class="container">
                <!-- Heading Main -->
                <div class="col-sm-12">
                    <h1 class="heading-main">
                        <span>Face Behind Courier System</span>
                        Our Team
                    </h1>
                </div>
                <!-- Heading Main -->

                <div class="row pb-4">
                    <!-- Team Column One -->
                    <div class="col-sm-12 col-md-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0s">
                        <div class="team-section-two">
                            <img src="assets/images/team/team-1.jpg" alt="" class="rounded">
                            <h4 class="h4-md txt-orange">John Morise</h4>
                            <h5 class="h5-md txt-ligt-gray">Founder</h5>
                        </div>
                    </div>
                    <!-- Team Column One -->

                    <!-- Team Column One -->
                    <div class="col-sm-12 col-md-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.1s">
                        <div class="team-section-two">
                            <img src="assets/images/team/team-2.jpg" alt="" class="rounded">
                            <h4 class="h4-md txt-orange">Kevin Mash</h4>
                            <h5 class="h5-md txt-ligt-gray">Head Operational</h5>
                        </div>
                    </div>
                    <!-- Team Column One -->

                    <!-- Team Column One -->
                    <div class="col-sm-12 col-md-4 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.2s">
                        <div class="team-section-two">
                            <img src="assets/images/team/team-3.jpg" alt="" class="rounded">
                            <h4 class="h4-md txt-orange">Mike Douglos</h4>
                            <h5 class="h5-md txt-ligt-gray">Team Lead Support</h5>
                        </div>
                    </div>
                    <!-- Team Column One -->
                </div>
            </div>
        </section>
        <!-- Our Team End -->

        <!-- Free Quote Start -->
        <section class="bg-white wide-tb-100 mb-spacer-md">
            <div class="container">
                <!-- Heading Main -->
                <div class="col-sm-12">
                    <h1 class="heading-main">
                        <span>Request a </span>
                        Free Quote
                    </h1>
                </div>
                <!-- Heading Main -->

                <div class="row">
                    <!-- Right Text Start -->
                    <div class="col-lg-4 wow fadeInRight" data-wow-duration="0" data-wow-delay="0.2s">
                        <div class="align-self-stretch h-100 align-items-center d-flex bg-with-text">
                            Whether you require distribution or fulfillment, defined freight forwarding, or a complete
                            supply chain solution, we are here for you.
                        </div>
                    </div>
                    <!-- Right Text Start -->

                    <!-- Spacer For Medium -->
                    <div class="w-100 d-none d-sm-block d-lg-none spacer-30"></div>
                    <!-- Spacer For Medium -->

                    <div class="col-lg-8 wow fadeInLeft" data-wow-duration="0" data-wow-delay="0.2s">
                        <!-- Free Quote From -->
                        <form action="#" method="post" novalidate="novalidate" class="rounded-field gray-field">
                            <div class="row g-4 mb-4">
                                <div class="col">
                                    <input type="text" name="name" class="form-control" placeholder="Your Name">
                                </div>
                                <div class="col">
                                    <input type="text" name="email" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="row g-4 mb-4">
                                <div class="col">
                                    <select title="Please choose a package" required="" name="package"
                                        class="form-control wide" aria-required="true" aria-invalid="false">
                                        <option value="">Transport Type</option>
                                        <option value="Type 1">Type 1</option>
                                        <option value="Type 2">Type 2</option>
                                        <option value="Type 3">Type 3</option>
                                        <option value="Type 4">Type 4</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <select title="Please choose a package" required="" name="package"
                                        class="form-control wide" aria-required="true" aria-invalid="false">
                                        <option value="">Type of freight</option>
                                        <option value="Type 1">Type 1</option>
                                        <option value="Type 2">Type 2</option>
                                        <option value="Type 3">Type 3</option>
                                        <option value="Type 4">Type 4</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-4 mb-4">
                                <div class="col">
                                    <textarea rows="7" placeholder="Message" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-row text-center">
                                <button type="submit" class="form-btn mx-auto btn-theme bg-orange">Request Quote <i
                                        class="icofont-rounded-right"></i></button>
                            </div>
                        </form>
                        <!-- Free Quote From -->
                    </div>

                </div>
            </div>
        </section>
        <!-- Free Quote End -->

        <!-- Clients Start -->
        <section class="wide-tb-100 bg-fixed clients-bg pos-rel">
            <div class="bg-overlay blue opacity-80"></div>
            <div class="container">
                <!-- Heading Main -->
                <div class="wow fadeInDown" data-wow-duration="0" data-wow-delay="0s">
                    <h1 class="heading-main">
                        <span>SOME OF OUR</span>
                        Clients
                    </h1>
                </div>
                <!-- Heading Main -->

                <div class="row">                   
                    <div class="col-sm-12 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.2s">
                        <div class="owl-carousel owl-theme" id="home-clients">

                            <!-- Client Logo -->
                            <div class="item">
                                <img src="assets/images/clients/client1.png" alt="">
                            </div>
                            <!-- Client Logo -->

                            <!-- Client Logo -->
                            <div class="item">
                                <img src="assets/images/clients/client2.png" alt="">
                            </div>
                            <!-- Client Logo -->

                            <!-- Client Logo -->
                            <div class="item">
                                <img src="assets/images/clients/client3.png" alt="">
                            </div>
                            <!-- Client Logo -->

                            <!-- Client Logo -->
                            <div class="item">
                                <img src="assets/images/clients/client4.png" alt="">
                            </div>
                            <!-- Client Logo -->

                            <!-- Client Logo -->
                            <div class="item">
                                <img src="assets/images/clients/client5.png" alt="">
                            </div>
                            <!-- Client Logo -->

                            <!-- Client Logo -->
                            <div class="item">
                                <img src="assets/images/clients/client6.png" alt="">
                            </div>
                            <!-- Client Logo -->

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Clients End -->

        <!-- Frequently Asked Questions Start -->
        <section class="wide-tb-100 faqs">
            <div class="container">
                <div class="row">
                    <!-- Heading Main -->
                    <div class="col-sm-12">
                        <h1 class="heading-main">
                            <span>Frequently Asked</span>
                            Questions
                        </h1>
                    </div>
                    <!-- Heading Main -->

                    <!-- Questions -->
                    <div class="col-sm-12 col-md-6 wow fadeInUp" data-wow-duration="0" data-wow-delay="0s">
                        <h4 class="h4-md mb-3">Lobortis sit magna ornare magna egestas?</h4>
                        <p>Etiam sit amet mauris suscipit sit amet in odio. Integer congue leo metus. Vitae arcu mollis
                            blandit ultrice ligula egestas magna suscipit lectus magna suscipit luctus undo blandit
                            vitae purus laoreet</p>
                    </div>
                    <!-- Questions -->

                    <!-- Questions -->
                    <div class="col-sm-12 col-md-6 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.2s">
                        <h4 class="h4-md mb-3">Aliquam dapibus pretium ornare?</h4>
                        <p>Feugiat eros ligula massa lipsum primis in orci luctus et ultrices posuere cubilia curae
                            congue lorem. ante ipsum primis in faucibus bibendum sit amet in odio</p>
                    </div>
                    <!-- Questions -->

                    <!-- Questions -->
                    <div class="col-sm-12 col-md-6 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.4s">
                        <h4 class="h4-md mb-3">Placeat axime facere omnis volute?</h4>
                        <p>Etiam sit amet mauris suscipit sit amet in odio. Integer congue leo metus. Vitae arcu mollis
                            blandit ultrice ligula egestas magna suscipit lectus magna suscipit luctus undo blandit
                            vitae purus laoreet</p>
                    </div>
                    <!-- Questions -->

                    <!-- Questions -->
                    <div class="col-sm-12 col-md-6 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.6s">
                        <h4 class="h4-md mb-3">Dapibus lobortis pretium ornare?</h4>
                        <p>Feugiat eros ligula massa lipsum primis in orci luctus et ultrices posuere cubilia curae
                            congue lorem. ante ipsum primis in faucibus bibendum sit amet in odio</p>
                    </div>
                    <!-- Questions -->

                    <!-- Questions -->
                    <div class="col-sm-12 col-md-6 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.8s">
                        <h4 class="h4-md mb-3">An interdum lobortis pretium ornare?</h4>
                        <p>Etiam sit amet mauris suscipit sit amet in odio. Integer congue leo metus. Vitae arcu mollis
                            blandit ultrice ligula egestas magna suscipit lectus magna suscipit luctus undo blandit
                            vitae purus laoreet</p>
                    </div>
                    <!-- Questions -->

                    <!-- Questions -->
                    <div class="col-sm-12 col-md-6 wow fadeInUp" data-wow-duration="0" data-wow-delay="0.9s">
                        <h4 class="h4-md mb-3">Interdum lobortis pretium ornare?</h4>
                        <p>Feugiat eros ligula massa lipsum primis in orci luctus et ultrices posuere cubilia curae
                            congue lorem. ante ipsum primis in faucibus bibendum sit amet in odio</p>
                    </div>
                    <!-- Questions -->
                </div>
            </div>
        </section>
        <!-- Frequently Asked Questions End -->

        <!-- Callout Start -->
        <section class="wide-tb-80 bg-scroll bg-img-6 pos-rel callout-style-1">
            <div class="bg-overlay blue opacity-60"></div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-12 mb-0 wow slideInUp" data-wow-duration="0" data-wow-delay="0.1s">
                        <h4 class="h4-xl">Interested in our Courier System?</h4>
                    </div>
                    <div class="col wow slideInUp" data-wow-duration="0" data-wow-delay="0.2s">
                        <div class="center-text">
                            We don't just manage suppliers, we micro-manage them. We have a consultative, personalized
                            approach
                        </div>
                    </div>
                    <div class="col-sm-auto wow slideInUp" data-wow-duration="0" data-wow-delay="0.3s">
                        <a href="#" class="btn btn-theme bg-white bordered">Get In Touch <i
                                class="icofont-rounded-right"></i></a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Callout End -->
    </main>

    <!-- Email Subscribe Start -->
    <section class="wide-tb-50 pb-0 bg-light-theme footer-subscribe">
        <div class="container wow fadeInDown" data-wow-duration="0" data-wow-delay="0s">
            <div class="row">
                <div class="col-sm-12 d-flex col-md-12 col-lg-6 offset-lg-3">
                    <div class="d- align-items-center d-sm-inline-flex  w-100">
                        <div class="head">
                            <span class="d-block">SUBSCRIBE For</span> NEWSLETTER
                        </div>
                        <form class="flex-nowrap col ms-3">
                            <input type="text" class="form-control" placeholder="Enter order number">
                                <button type="submit" class="btn-theme bg-navy-blue">Check Now <i class="icofont-envelope"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Email Subscribe End -->


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
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"version":"2024.11.0","token":"64224fc8786846928480d180dfc466bd","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}' crossorigin="anonymous"></script>
</body>
</html>