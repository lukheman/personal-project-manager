@props([
    'title' => 'Akmal - Fullstack Web Developer'
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Jasa pembuatan website dan aplikasi profesional. Fullstack Web Developer dengan pengalaman dalam Laravel, React, dan teknologi modern lainnya.">
    <meta name="keywords" content="web developer, jasa website, pembuatan aplikasi, freelancer, Laravel, React, Indonesia">
    <meta name="author" content="Akmal">
    <title>{{ $title }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @livewireStyles
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #0ea5e9;
            --accent: #10b981;
            --dark: #0f172a;
            --dark-lighter: #1e293b;
            --light: #f8fafc;
            --text: #334155;
            --text-light: #64748b;
            --gradient-primary: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            --gradient-secondary: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text);
            overflow-x: hidden;
            background: var(--light);
        }

        /* Navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-custom.scrolled {
            box-shadow: 0 4px 30px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text) !important;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f0f4ff 0%, #fdf4ff 50%, #f0fdff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 80%;
            height: 150%;
            background: var(--gradient-primary);
            opacity: 0.03;
            border-radius: 50%;
            animation: float 20s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            color: var(--dark);
            margin-bottom: 1.5rem;
        }

        .hero-title .highlight {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.7;
        }

        .btn-primary-custom {
            background: var(--gradient-primary);
            border: none;
            padding: 1rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid var(--primary);
            padding: 1rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            color: var(--primary);
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background: var(--primary);
            color: white;
        }

        .hero-image {
            position: relative;
        }

        .hero-image img {
            max-width: 100%;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
        }

        .floating-card {
            position: absolute;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: floatCard 3s infinite ease-in-out;
        }

        .floating-card.card-1 {
            top: 10%;
            left: -10%;
            animation-delay: 0s;
        }

        .floating-card.card-2 {
            bottom: 20%;
            right: -5%;
            animation-delay: 1s;
        }

        @keyframes floatCard {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Section Styles */
        section {
            padding: 100px 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto 3rem;
        }

        /* Services */
        .service-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            transition: all 0.4s ease;
            border: 1px solid rgba(0,0,0,0.05);
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.1);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
            color: white;
        }

        .service-card h4 {
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .service-card p {
            color: var(--text-light);
            line-height: 1.7;
        }

        /* Portfolio */
        .portfolio-section {
            background: var(--dark);
            color: white;
        }

        .portfolio-section .section-title,
        .portfolio-section .section-subtitle {
            color: white;
        }

        .portfolio-card {
            background: var(--dark-lighter);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .portfolio-card:hover {
            transform: scale(1.03);
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        }

        .portfolio-img {
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .portfolio-content {
            padding: 1.5rem;
        }

        .portfolio-content h5 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .portfolio-content p {
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .tech-badge {
            background: rgba(99, 102, 241, 0.2);
            color: var(--primary-light);
            padding: 0.3rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            display: inline-block;
        }

        /* Tech Stack */
        .tech-stack-section {
            background: linear-gradient(135deg, #f0f4ff 0%, #fdf4ff 100%);
        }

        .tech-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .tech-logo:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .tech-logo i {
            font-size: 2.5rem;
        }

        .tech-name {
            font-weight: 600;
            color: var(--dark);
        }

        /* Testimonials */
        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            position: relative;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        }

        .testimonial-card::before {
            content: '"';
            font-size: 5rem;
            color: var(--primary);
            opacity: 0.1;
            position: absolute;
            top: -10px;
            left: 20px;
            font-family: Georgia, serif;
        }

        .testimonial-text {
            font-style: italic;
            color: var(--text);
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .testimonial-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }

        .testimonial-name {
            font-weight: 700;
            color: var(--dark);
        }

        .testimonial-role {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        /* Workflow */
        .workflow-step {
            text-align: center;
            position: relative;
        }

        .workflow-number {
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1.5rem;
            margin: 0 auto 1.5rem;
        }

        .workflow-step h5 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .workflow-step p {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* About */
        .about-section {
            background: var(--dark);
            color: white;
        }

        .about-image {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 8rem;
            color: white;
            box-shadow: 0 30px 60px rgba(99, 102, 241, 0.3);
        }

        .about-stats {
            display: flex;
            gap: 3rem;
            margin-top: 2rem;
        }

        .stat-item h3 {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-item p {
            color: #94a3b8;
        }

        /* Contact */
        .contact-section {
            background: linear-gradient(135deg, #f0f4ff 0%, #fdf4ff 100%);
        }

        .contact-form {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 30px 60px rgba(0,0,0,0.1);
        }

        .form-control-custom {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .contact-info {
            padding: 2rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: var(--gradient-primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            padding: 3rem 0;
        }

        .footer-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .social-links a {
            width: 45px;
            height: 45px;
            background: var(--dark-lighter);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--gradient-primary);
            transform: translateY(-3px);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .about-stats {
                flex-wrap: wrap;
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                padding-top: 100px;
            }

            .hero-title {
                font-size: 2rem;
            }

            .floating-card {
                display: none;
            }

            .about-image {
                width: 200px;
                height: 200px;
                font-size: 5rem;
            }

            .contact-form {
                padding: 2rem;
            }
        }

        /* Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    {{ $slot }}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Fade in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
    @livewireScripts
</body>
</html>
