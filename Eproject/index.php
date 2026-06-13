<!DOCTYPE html>
<html lang="en">
<head>
    <!-- xxx Basics xxx -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- xxx Favicon xxx -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">
    <title>Courier Management System</title>
    <meta name="author" content="Your Company">
    <meta name="description" content="Complete Courier Management System for efficient logistics and cargo solutions.">
    <meta name="keywords" content="courier, logistics, cargo, shipping, transport, delivery, tracking">

    <!-- Main Style CSSS -->
    <link href="assets/css/theme-plugins.min.css" rel="stylesheet">
    <!-- Main Theme CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- Responsive Theme CSS -->
    <link href="assets/css/responsive.css" rel="stylesheet">

    <!-- REVOLUTION NAVIGATION STYLES -->
    <link rel="stylesheet" type="text/css" href="assets/revolution/css/layers.css">
    <link rel="stylesheet" type="text/css" href="assets/revolution/css/navigation.css">
    <link rel="stylesheet" type="text/css" href="assets/revolution/css/settings.css">
    <link rel="stylesheet" type="text/css" href="assets/revolution/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css">
    <link rel="stylesheet" type="text/css" href="assets/revolution/fonts/font-awesome/css/font-awesome.css">

    <!-- Custom CSS -->
    <style>
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
</head>

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
                                    <a class="nav-link active" href="index.php">Home</a>
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

    <!-- ========== HERO SLIDER (Your Original Slider) ========== -->
    <div class="slider bg-navy-blue" id="home">
        <div id="rev_slider_1078_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container"
            data-alias="classic4export" data-source="gallery"
            style="margin:0px auto;background-color:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
            <!-- START REVOLUTION SLIDER 5.4.1 fullwidth mode -->
            <div id="rev_slider_1078_1" class="rev_slider fullscreenbanner" style="display:none;" data-version="5.4.1">
                <ul>
                    <li data-index="rs-82" data-transition="fade" data-slotamount="default" data-hideafterloop="0"
                        data-hideslideonmobile="off" data-easein="Power4.easeOut" data-easeout="Power4.easeOut"
                        data-masterspeed="1000" data-thumb="../../assets/images/waterfal-100x50.jpg" data-rotate="0"
                        data-saveperformance="off" data-title="Slide" data-param1="" data-param2="" data-param3=""
                        data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9=""
                        data-param10="" data-description="" data-slicey_shadow="0px 0px 50px 0px transparent">
                        <!-- MAIN IMAGE -->
                        <img src="assets/images/banner_slider.jpg" alt="" data-bgposition="center center"
                            data-kenburns="on" data-duration="7000" data-ease="Linear.easeNone" data-scalestart="100"
                            data-scaleend="150" data-rotatestart="0" data-rotateend="0" data-blurstart="0"
                            data-blurend="0" data-offsetstart="0 0" data-offsetend="0 0" class="rev-slidebg"
                            data-no-retina>
                        <!-- LAYERS -->

                        <!-- LAYER NR. 1 -->
                        <div class="tp-caption tp-resizeme NotGeneric-Title" id="slide-82-layer-2"
                            data-blendmode="color-dodge" data-x="['center','center','center','center']"
                            data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']"
                            data-voffset="['-70','-70','-70','-70']" data-fontsize="['70','60','60','55']"
                            data-lineheight="['80','70','70','40']" data-width="none" data-height="none"
                            data-whitespace="nowrap" data-type="text" data-responsive_offset="on"
                            data-frames='[{"delay":200,"speed":1000,"sfx_effect":"blockfromleft","sfxcolor":"#ffffff","frame":"0","from":"z:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]">We
                            Are Courier MS</div>

                        <!-- LAYER NR. 11 -->
                        <div class="tp-caption medium_light_white tp-resizeme" id="slide-82-layer-3"
                            data-blendmode="color-dodge" data-x="['center','center','center','center']"
                            data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']"
                            data-voffset="['-10','-10','-10','-10']" data-width="none" data-height="none"
                            data-whitespace="nowrap" data-type="text" data-responsive_offset="on"
                            data-frames='[{"delay":500,"speed":1000,"sfx_effect":"blockfromleft","sfxcolor":"#ffffff","frame":"0","from":"z:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                            data-fontsize="['28','28','28','28']" data-lineheight="['34','34','34','50']">From Pickup to
                            Destination</div>

                        <!-- LAYER NR. 12 -->
                        <div class="tp-caption tp-resizeme small_light_white " id="slide-82-layer-4"
                            data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                            data-y="['middle','middle','middle','middle']" data-voffset="['60','60','60','60']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                            data-responsive_offset="on"
                            data-frames='[{"delay":600,"speed":1000,"sfx_effect":"blockfromleft","sfxcolor":"#ffffff","frame":"0","from":"z:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                            data-fontsize="['16','16','16','13']" data-lineheight="['30','30','30','20']">We deliver
                            your products on time with pure safety. Sed ut perspiciatis unde<br> omnis iste natus error
                            sit voluptatem accusantium doloremque laudantium,</div>

                        <!-- LAYER NR. 12 -->
                        <div class="tp-caption tp-resizeme btn-theme bg-navy-blue rev-btn" id="slide-82-layer-5"
                            data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                            data-y="['middle','middle','middle','middle']" data-voffset="['140','140','140','140']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                            data-responsive_offset="on"
                            data-frames='[{"delay":750,"speed":1000,"sfx_effect":"blockfromleft","sfxcolor":"#ffffff","frame":"0","from":"z:0;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[30,30,30,30]" data-paddingbottom="[0,0,0,0]"
                            data-paddingleft="[30,30,30,30]" data-fontsize="['14','14','14','14']"
                            data-lineheight="['16','16','16','16']"><a href="#signup" style="color:white;text-decoration:none;">Get Started <i class="icofont-rounded-right"></i></a>
                        </div>
                    </li>
                    <!-- SLIDE  -->
                    <li data-index="rs-3045" data-transition="zoomout" data-slotamount="default" data-hideafterloop="0"
                        data-hideslideonmobile="off" data-easein="Power4.easeInOut" data-easeout="Power4.easeInOut"
                        data-masterspeed="2000" data-thumb="rev-slider/assets/images/datcolor-100x50.html"
                        data-rotate="0" data-fstransition="fade" data-fsmasterspeed="1500" data-fsslotamount="7"
                        data-saveperformance="off" data-title="Intro" data-param1="" data-param2="" data-param3=""
                        data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9=""
                        data-param10="" data-description="">
                        <!-- MAIN IMAGE -->
                        <img src="assets/images/banner_slider_2.jpg" alt="" data-bgposition="center center"
                            data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="10" class="rev-slidebg"
                            data-no-retina>
                        <!-- LAYERS -->

                       
                        <!-- LAYER NR. 1 -->
                        <div class="tp-caption NotGeneric-Title tp-resizeme" id="slide-3-layer-1" data-x="left"
                            data-hoffset="60" data-y="center" data-voffset="-120"
                            data-width="['auto','auto','auto','auto']" data-height="['auto','auto','auto','auto']"
                            data-transform_idle="o:1;" data-fontsize="['70','70','70','45']"
                            data-transform_in="y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;"
                            data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"
                            data-mask_in="x:0px;y:0px;s:inherit;e:inherit;" data-start="700" data-splitin="none"
                            data-splitout="none" data-responsive_offset="on" style="z-index: 1; white-space: nowrap;">
                            <span class="slider-small">Fast & Reliable<br>Courier Management 
                        </div>

                        <!-- LAYER NR. 2 -->
                        <div class="tp-caption NotGeneric-Title tp-resizeme" id="slide-3-layer-2" data-x="left"
                            data-hoffset="60" data-y="center" data-voffset="10"
                            data-width="['auto','auto','auto','auto']" data-height="['auto','auto','auto','auto']"
                            data-transform_idle="o:1;"
                            data-transform_in="x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;"
                            data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"
                            data-mask_in="x:0px;y:0px;s:inherit;e:inherit;" data-start="1400" data-splitin="none"
                            data-splitout="none" data-responsive_offset="on"
                            style="z-index: 2; white-space: nowrap; font-size: 18px; line-height: 30px;">Courier Management System is a web-based application designed to manage<br>
            courier bookings, customer details, shipment tracking, billing, and reports.
                            <br>
                        </div>
                        <!-- LAYER NR. 3 -->
                        <div class="tp-caption BigBold-Button rev-btn " id="slide-3-layer-3" data-x="left"
                            data-hoffset="60" data-y="center" data-voffset="100" data-width="['auto']"
                            data-height="['auto']" data-transform_idle="o:1;"
                            data-transform_hover="o:1;rX:0;rY:0;rZ:0;z:0;s:300;e:Power1.easeInOut;"
                            data-style_hover="c:rgba(255, 255, 255, 1.00);bg:rgba(41, 46, 49, 0);bc:rgba(255, 255, 255, 1.00);cursor:pointer;"
                            data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power3.easeInOut;"
                            data-transform_out="s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;"
                            data-mask_in="x:0px;y:[100%];s:inherit;e:inherit;" data-start="2100" data-splitin="none"
                            data-splitout="none"
                            data-actions='[{"event":"click","action":"jumptoslide","slide":"next","delay":""}]'
                            data-responsive_offset="on" data-responsive="off"
                            style="z-index: 3; white-space: nowrap; font-weight: 800;background-color:rgba(41, 46, 49, 1.00);border-color:rgba(255, 255, 255, 0);outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;">
                            <a href="#signup" style="color:white;text-decoration:none;">SIGN UP NOW</a>
                        </div>
                    </li>
                </ul>
                <div class="tp-bannertimer tp-bottom" style="height: 7px; background-color: rgba(255, 255, 255, 0.25);">
                </div>
            </div>
        </div>
    </div>
    <!-- Fullscreen Slider End -->

   <section class="wide-tb-80 bg-light-gray single-page-section" id="track">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="mb-3">Track Your Package</h2>
                <p class="mb-4">Enter your tracking number below to get real-time updates on your shipment status.</p>
                
                <form action="../Eproject/user/track.php" method="GET" class="d-flex justify-content-center flex-wrap">
                    <input type="text" name="tracking_number" placeholder="Enter Tracking Number" 
                        class="form-control mb-2 mb-sm-0" style="max-width: 350px; border-radius: 30px; padding: 10px 20px; border: 2px solid #ff7a00;">
                    
                    <button type="submit" class="btn btn-orange ms-2" style="background:#ff7a00; color:white; border-radius:30px; padding:10px 30px; font-weight:600; border:2px solid #ff7a00; transition:0.3s;">
                        Track Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

    <!-- ========== ABOUT SECTION ========== -->
    <section class="wide-tb-100 bg-white single-page-section" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                    <h1 class="heading-main">
                        <small>About Company</small>
                        Reliable & Fast Logistics Solutions
                    </h1>
                    <p>
                        We provide complete logistics and cargo solutions from pickup to final destination.
                        Our experienced team ensures safety, speed and transparency in every delivery.
                    </p>
                    <p>
                        With modern infrastructure and professional staff, we serve businesses and individuals
                        with trusted freight services worldwide.
                    </p>
                    <a href="#contact" class="btn-theme bg-navy-blue mt-3">Contact Us</a>
                </div>

                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.4s">
                    <img src="assets/images/12345 (1).jfif" class="img-fluid rounded" alt="About Us">
                </div>
            </div>
        </div>
    </section>

    <!-- ========== SERVICES SECTION ========== -->
    <section class="wide-tb-100 bg-light-gray single-page-section" id="services">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="heading-main">
                    <small>What We Do</small>
                    Smart Courier Solutions
                </h1>
                <p class="mt-3">
                    We manage courier operations digitally — faster, safer and smarter.
                </p>
            </div>

            <div class="row">
                <div class="col-md-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="icofont-tracking"></i>
                        </div>
                        <h3>Live Shipment Tracking</h3>
                        <p>
                            Track parcels in real-time from pickup to final delivery with GPS technology.
                            Get instant updates and notifications.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="icofont-ui-user-group"></i>
                        </div>
                        <h3>User & Staff Management</h3>
                        <p>
                            Separate roles for Admin, Staff and Customers with full control.
                            Manage permissions and access levels easily.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="icofont-chart-growth"></i>
                        </div>
                        <h3>Reports & Analytics</h3>
                        <p>
                            View delivery reports, performance stats and business analytics.
                            Make data-driven decisions with our insights.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-4 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="icofont-delivery-time"></i>
                        </div>
                        <h3>Fast Delivery</h3>
                        <p>
                            Express delivery services with time-bound commitments.
                            We ensure your packages reach on time, every time.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="icofont-shield"></i>
                        </div>
                        <h3>Secure Handling</h3>
                        <p>
                            Your cargo is handled with utmost care and security.
                            We use advanced security measures for protection.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="icofont-headphone-alt-2"></i>
                        </div>
                        <h3>24/7 Support</h3>
                        <p>
                            Round the clock customer support for all your queries.
                            Our team is always ready to assist you.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FEATURES SECTION ========== -->
    <section class="wide-tb-100 bg-white single-page-section" id="features">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                    <img src="assets/images/12345 (2).jfif" class="img-fluid rounded" alt="Why Choose Us">
                </div>

                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.4s">
                    <h1 class="heading-main">
                        <small>Why Us</small>
                        Why Choose Courier MS
                    </h1>
                    <div class="mt-4">
                        <div class="d-flex align-items-start mb-3">
                            <i class="icofont-check-alt text-orange fs-1 me-3"></i>
                            <div>
                                <h4>On-time Delivery Guarantee</h4>
                                <p>We guarantee timely delivery of your shipments with our optimized routes and experienced team.</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <i class="icofont-check-alt text-orange fs-1 me-3"></i>
                            <div>
                                <h4>Secure Cargo Handling</h4>
                                <p>Your goods are handled with utmost care and security throughout the journey with insurance coverage.</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <i class="icofont-check-alt text-orange fs-1 me-3"></i>
                            <div>
                                <h4>Real-Time Tracking</h4>
                                <p>Monitor your shipment in real-time with our mobile app and web portal. Get instant updates.</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <i class="icofont-check-alt text-orange fs-1 me-3"></i>
                            <div>
                                <h4>24/7 Customer Support</h4>
                                <p>Our support team is available round the clock to assist you with any queries or concerns.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

     <!-- ========== PRICING SECTION ========== -->
    <section class="wide-tb-100 bg-light-gray single-page-section" id="pricing">
        <div class="container">
            <div class="text-center mb-5">
                <span class="cms-badge">PRICING PLANS</span>
                <h1 class="heading-main">
                    Affordable Plans for Every Business
                </h1>
                <p class="mt-3">
                    Choose the perfect plan for your courier business needs
                </p>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="cms-feature-box text-center">
                        <h3 class="mb-3">Basic</h3>
                        <div class="cms-stat-number">$99<span style="font-size:16px;">/month</span></div>
                        <p class="mt-3">Perfect for small courier businesses</p>
                        <ul class="list-unstyled mt-4 text-start">
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> Up to 500 shipments/month</li>
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> Basic tracking</li>
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> Email support</li>
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> Basic reports</li>
                        </ul>
                        <a href="public/register.php" class="demo-btn mt-4" style="justify-content: center;">Sign Up Now</a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="cms-feature-box text-center" style="border: 2px solid #ff7a00; transform: scale(1.05);">
                        <span class="cms-badge" style="margin-bottom: 20px;">MOST POPULAR</span>
                        <h3 class="mb-3">Professional</h3>
                        <div class="cms-stat-number">$199<span style="font-size:16px;">/month</span></div>
                        <p class="mt-3">For growing courier businesses</p>
                        <ul class="list-unstyled mt-4 text-start">
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> Up to 2000 shipments/month</li>
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> Advanced tracking</li>
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> Priority support</li>
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> Advanced analytics</li>
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> Mobile apps</li>
                        </ul>
                        <a href="public/register.php" class="demo-btn mt-4" style="justify-content: center;">Sign Up Now</a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="cms-feature-box text-center">
                        <h3 class="mb-3">Enterprise</h3>
                        <div class="cms-stat-number">Custom</div>
                        <p class="mt-3">For large courier companies</p>
                        <ul class="list-unstyled mt-4 text-start">
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> Unlimited shipments</li>
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> White-label solution</li>
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> 24/7 dedicated support</li>
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> Custom features</li>
                            <li class="mb-2"><i class="icofont-check-alt text-orange me-2"></i> API access</li>
                        </ul>
                        <a href="public/register.php" class="demo-btn mt-4" style="justify-content: center;">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
  <!-- Main Footer Start -->
<footer id="contact" class="wide-tb-70 bg-light-gray pb-0">
    <div class="container">
        <div class="row">

            <!-- About Section -->
            <div class="col-lg-4 col-md-6 wow fadeInLeft" data-wow-duration="0" data-wow-delay="0s">
                <div class="logo-footer mb-3">
                      <a class="navbar-brand icon-logo" href="index.php">
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
                    <li class="mb-2"><a href="index.php"><i class="icofont-thin-right me-1"></i> About Us</a></li>
                    <li class="mb-2"><a href="index.php"><i class="icofont-thin-right me-1"></i> Services</a></li>
                    <li class="mb-2"><a href="index.php"><i class="icofont-thin-right me-1"></i> Features</a></li>
                    <li class="mb-2"><a href="index.php"><i class="icofont-thin-right me-1"></i> Pricing</a></li>
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

<script>
    // Set current year in footer
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>  <!-- Main Footer End -->


    <!-- Back To Top Start -->
    <a id="mkdf-back-to-top" href="index.php#home" class="off"><i class="icofont-rounded-up"></i></a>
    <!-- Back To Top End -->

    <!-- Jquery Library JS -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/theme-plugins.min.js"></script>

    <!-- REVOLUTION JS FILES -->
    <script type="text/javascript" src="assets/revolution/js/jquery.themepunch.tools.min.js"></script>
    <script type="text/javascript" src="assets/revolution/js/jquery.themepunch.revolution.min.js"></script>

    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS -->
    <script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.actions.min.js"></script>
    <script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
    <script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
    <script type="text/javascript"
        src="assets/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
    <script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.migration.min.js"></script>
    <script type="text/javascript"
        src="assets/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
    <script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
    <script type="text/javascript"
        src="assets/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
    <script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.video.min.js"></script>

    <!-- Theme Custom File -->
    <script src="assets/js/site-custom.js"></script>

    <!-- Custom JavaScript for Single Page -->
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    // Close mobile menu if open
                    const navbarCollapse = document.getElementById('navbarCollapse');
                    if(navbarCollapse.classList.contains('show')) {
                        const toggleBtn = document.querySelector('[data-bs-target="#navbarCollapse"]');
                        toggleBtn.click();
                    }
                    
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Update active nav link
                    document.querySelectorAll('.nav-link').forEach(link => {
                        link.classList.remove('active');
                    });
                    this.classList.add('active');
                }
            });
        });
        
        // Update active nav link on scroll
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section, .slider');
            const navLinks = document.querySelectorAll('.nav-link');
            
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if(scrollY >= (sectionTop - 100)) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if(link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
        
        // Revolution Slider Initialization
        var tpj = jQuery;

        var revapi1078;
        tpj(document).ready(function () {
            if (tpj("#rev_slider_1078_1").revolution == undefined) {
                revslider_showDoubleJqueryError("#rev_slider_1078_1");
            } else {
                revapi1078 = tpj("#rev_slider_1078_1").show().revolution({
                    sliderType: "standard",
                    jsFileLocation: "revolution/js/",
                    sliderLayout: "fullscreen",
                    dottedOverlay: "none",
                    delay: 9000,
                    navigation: {
                        keyboardNavigation: "off",
                        keyboard_direction: "horizontal",
                        mouseScrollNavigation: "off",
                        mouseScrollReverse: "default",
                        onHoverStop: "off",
                        touch: {
                            touchenabled: "on",
                            swipe_threshold: 75,
                            swipe_min_touches: 1,
                            swipe_direction: "horizontal",
                            drag_block_vertical: false
                        },
                        arrows: {
                            style: "metis",
                            enable: true,
                            hide_onmobile: true,
                            hide_under: 600,
                            hide_onleave: true,
                            hide_delay: 200,
                            hide_delay_mobile: 1200,
                            left: {
                                h_align: "left",
                                v_align: "center",
                                h_offset: 30,
                                v_offset: 0
                            },
                            right: {
                                h_align: "right",
                                v_align: "center",
                                h_offset: 30,
                                v_offset: 0
                            }
                        },
                        bullets: {
                            style: 'hades',
                            tmp: '<span class="tp-bullet-image"></span>',
                            enable: false,
                            hide_onmobile: true,
                            hide_under: 600,
                            hide_onleave: true,
                            hide_delay: 200,
                            hide_delay_mobile: 1200,
                            direction: "horizontal",
                            h_align: "center",
                            v_align: "bottom",
                            h_offset: 0,
                            v_offset: 30,
                            space: 5
                        }
                    },
                    viewPort: {
                        enable: true,
                        outof: "pause",
                        visible_area: "80%",
                        presize: false
                    },
                    responsiveLevels: [1240, 1024, 778, 480],
                    visibilityLevels: [1240, 1024, 778, 480],
                    gridwidth: [1240, 1024, 778, 480],
                    gridheight: [600, 600, 500, 400],
                    lazyType: "none",
                    parallax: {
                        type: "mouse",
                        origo: "slidercenter",
                        speed: 2000,
                        levels: [2, 3, 4, 5, 6, 7, 12, 16, 10, 50, 47, 48, 49, 50, 51, 55],
                        type: "mouse",
                    },
                    shadow: 0,
                    spinner: 'spinner2',
                    stopLoop: "off",
                    stopAfterLoops: -1,
                    stopAtSlide: -1,
                    shuffle: "off",
                    autoHeight: "off",
                    hideThumbsOnMobile: "off",
                    hideSliderAtLimit: 0,
                    hideCaptionAtLimit: 0,
                    hideAllCaptionAtLilmit: 0,
                    debugMode: false,
                    fallbacks: {
                        simplifyAll: "off",
                        nextSlideOnWindowFocus: "off",
                        disableFocusListener: false,
                    }
                });
            }
        });
    </script>

</body>
</html>