<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Inventory System - Streamline Your Operations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="assets/css/style.css"> -->
    <style>
        /* Landing Page Specific Styles */
        .landing-navbar {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            padding: 1rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .landing-navbar .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff !important;
        }

        .landing-navbar .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            transition: color 0.3s ease;
            margin: 0 0.5rem;
        }

        .landing-navbar .nav-link:hover {
            color: #fff !important;
        }

        .login-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: #fff;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background: #fff;
            color: #2563eb;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #e3f2fd 100%);
            padding: 6rem 0;
            min-height: calc(100vh - 70px);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.25rem;
            color: #6b7280;
            margin-bottom: 2rem;
            max-width: 600px;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .btn-primary-grad {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border: none;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-grad:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            color: #fff;
        }

        .btn-secondary-outline {
            background: transparent;
            border: 2px solid #2563eb;
            padding: 0.75rem 1.875rem;
            font-weight: 600;
            font-size: 1rem;
            color: #2563eb;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary-outline:hover {
            background: #2563eb;
            color: #fff;
            transform: translateY(-2px);
        }

        .hero-image {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-image-box {
            width: 100%;
            max-width: 500px;
            height: 400px;
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 20px 60px rgba(37, 99, 235, 0.2);
        }

        .hero-image-box i {
            font-size: 8rem;
            color: rgba(255, 255, 255, 0.3);
        }

        /* Features Section */
        .features-section {
            padding: 5rem 0;
            background: #fff;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .section-title p {
            font-size: 1.125rem;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-card {
            padding: 2rem;
            background: #f9fafb;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border-color: #2563eb;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #6b7280;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            padding: 4rem 0;
            color: #fff;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* CTA Section */
        .cta-section {
            padding: 5rem 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #e3f2fd 100%);
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
        }

        .cta-section p {
            font-size: 1.125rem;
            color: #6b7280;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Footer Landing */
        .landing-footer {
            background: #1f2937;
            color: #f9fafb;
            padding: 3rem 0 1rem;
        }

        .landing-footer .footer-col h5 {
            font-weight: 600;
            margin-bottom: 1rem;
            color: #fff;
        }

        .landing-footer .footer-col a {
            color: #d1d5db;
            text-decoration: none;
            transition: color 0.3s ease;
            display: block;
            margin-bottom: 0.5rem;
        }

        .landing-footer .footer-col a:hover {
            color: #2563eb;
        }

        .footer-bottom {
            border-top: 1px solid #374151;
            margin-top: 2rem;
            padding-top: 2rem;
            text-align: center;
            color: #9ca3af;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .hero-buttons a {
                width: 100%;
                justify-content: center;
            }

            .section-title h2 {
                font-size: 1.875rem;
            }

            .stat-number {
                font-size: 2rem;
            }

            .cta-section h2 {
                font-size: 1.875rem;
            }
        }
    </style>
</head>
<body style="background: #fff;">

<!-- NAVIGATION -->
<nav class="navbar landing-navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-pills me-2"></i>Pharmacy Inventory
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link login-btn" href="login.php">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center gap-4">
            <!-- Hero Content -->
            <div class="col-lg-6 hero-content">
                <h1>Streamline Your Pharmacy Operations</h1>
                <p>
                    Complete inventory management system designed for modern pharmacies. Track stock, manage sales, 
                    and optimize operations with our intuitive platform.
                </p>
                <div class="hero-buttons">
                    <a href="login.php" class="btn-primary-grad">
                        <i class="fas fa-arrow-right"></i> Get Started
                    </a>
                    <a href="#features" class="btn-secondary-outline">
                        <i class="fas fa-play"></i> Learn More
                    </a>
                </div>
                <div class="d-flex gap-4 flex-wrap">
                    <div>
                        <strong style="color: #1f2937;">10,000+</strong>
                        <p style="color: #6b7280; margin: 0; font-size: 0.9rem;">Active Pharmacies</p>
                    </div>
                    <div>
                        <strong style="color: #1f2937;">99.9%</strong>
                        <p style="color: #6b7280; margin: 0; font-size: 0.9rem;">Uptime Guarantee</p>
                    </div>
                    <div>
                        <strong style="color: #1f2937;">24/7</strong>
                        <p style="color: #6b7280; margin: 0; font-size: 0.9rem;">Support Available</p>
                    </div>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="col-lg-6 hero-image">
                <div class="hero-image-box">
                    <i class="fas fa-capsules"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES SECTION -->
<section class="features-section" id="features">
    <div class="container">
        <div class="section-title">
            <h2>Powerful Features</h2>
            <p>Everything you need to manage your pharmacy efficiently and effectively</p>
        </div>

        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3>Inventory Management</h3>
                    <p>Real-time tracking of all products with automated alerts for low stock levels and expiring medicines.</p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <h3>Point of Sale</h3>
                    <p>Fast and efficient sales processing with integrated payment handling and receipt generation.</p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Analytics & Reports</h3>
                    <p>Detailed analytics and customizable reports to track sales trends and inventory performance.</p>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Secure & Reliable</h3>
                    <p>Enterprise-grade security with data encryption and regular backups for complete peace of mind.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATS SECTION -->
<section class="stats-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 stat-item mb-4 mb-md-0">
                <div class="stat-number">10,000+</div>
                <div class="stat-label">Pharmacies Worldwide</div>
            </div>
            <div class="col-md-3 stat-item mb-4 mb-md-0">
                <div class="stat-number">500K+</div>
                <div class="stat-label">Daily Transactions</div>
            </div>
            <div class="col-md-3 stat-item mb-4 mb-md-0">
                <div class="stat-number">99.9%</div>
                <div class="stat-label">System Uptime</div>
            </div>
            <div class="col-md-3 stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Customer Support</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="cta-section" id="about">
    <div class="container">
        <h2>Ready to Transform Your Pharmacy?</h2>
        <p>Join thousands of pharmacies already using our system to streamline their operations and increase efficiency.</p>
        <a href="login.php" class="btn-primary-grad">
            <i class="fas fa-arrow-right"></i> Start Free Trial
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer class="landing-footer" id="contact">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-3 footer-col mb-4 mb-md-0">
                <h5>Product</h5>
                <a href="#features">Features</a>
                <a href="#">Pricing</a>
                <a href="#">Security</a>
            </div>
            <div class="col-md-3 footer-col mb-4 mb-md-0">
                <h5>Company</h5>
                <a href="#">About Us</a>
                <a href="#">Blog</a>
                <a href="#">Careers</a>
            </div>
            <div class="col-md-3 footer-col mb-4 mb-md-0">
                <h5>Support</h5>
                <a href="#">Help Center</a>
                <a href="#">Contact</a>
                <a href="#">Documentation</a>
            </div>
            <div class="col-md-3 footer-col">
                <h5>Legal</h5>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Policy</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <span id="year"></span> Pharmacy Inventory System. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Set current year
    document.getElementById('year').textContent = new Date().getFullYear();

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                document.querySelector(href).scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>

</body>
</html>