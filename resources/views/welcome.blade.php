<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FormFlow AI – Welcome</title>

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f4ff 0%, #d9e2ff 50%, #c7d2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow: hidden;
            position: relative;
        }

        /* Animated floating shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.2;
            pointer-events: none;
            animation: float 8s ease-in-out infinite alternate;
        }
        .shape-1 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #6c5ce7, transparent);
            top: -80px;
            left: -80px;
            animation-delay: 0s;
        }
        .shape-2 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #a29bfe, transparent);
            bottom: -120px;
            right: -120px;
            animation-delay: 2s;
        }
        .shape-3 {
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, #fd79a8, transparent);
            top: 50%;
            left: 70%;
            animation-delay: 4s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(40px, -40px) scale(1.1); }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 30px 80px rgba(108, 92, 231, 0.15);
            border-radius: 40px;
            padding: 3rem 2.5rem;
            max-width: 520px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 10;
            animation: fadeUp 0.8s ease forwards;
        }

        .logo-icon {
            width: 72px;
            height: 72px;
            border-radius: 24px;
            background: linear-gradient(135deg, #6c5ce7, #4a3db8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.6rem;
            font-weight: 800;
            color: white;
            margin: 0 auto 1.2rem;
            box-shadow: 0 16px 40px rgba(108, 92, 231, 0.3);
        }

        .tagline {
            font-size: 1.1rem;
            color: #4a5568;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-splash {
            display: inline-block;
            padding: 0.8rem 2.8rem;
            border-radius: 60px;
            font-weight: 600;
            transition: all 0.25s ease;
            text-decoration: none;
            margin: 0.4rem;
            min-width: 160px;
        }
        .btn-primary-splash {
            background: linear-gradient(135deg, #6c5ce7, #4a3db8);
            color: white;
            box-shadow: 0 8px 30px rgba(108, 92, 231, 0.3);
        }
        .btn-primary-splash:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 40px rgba(108, 92, 231, 0.45);
            color: white;
        }
        .btn-outline-splash {
            background: transparent;
            border: 2px solid #6c5ce7;
            color: #6c5ce7;
        }
        .btn-outline-splash:hover {
            background: #6c5ce7;
            color: white;
            transform: translateY(-3px);
        }

        .features-row {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2rem;
            font-size: 0.85rem;
            color: #4a5568;
        }
        .features-row i {
            color: #6c5ce7;
            margin-right: 0.3rem;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 576px) {
            .glass-card {
                padding: 2rem 1.5rem;
                border-radius: 28px;
                margin: 1rem;
            }
            .btn-splash {
                min-width: 140px;
                padding: 0.7rem 2rem;
            }
            .features-row {
                flex-direction: column;
                gap: 0.8rem;
            }
        }
    </style>
</head>
<body>

    <!-- Animated Background Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>

    <!-- Main Card -->
    <div class="glass-card">
        <div class="logo-icon">F</div>
        <h1 class="text-3xl fw-bold text-gray-800">FormFlow AI</h1>
        <p class="tagline">
            Build intelligent forms with AI,<br>
            collect responses, and gain insights — all in one place.
        </p>

        <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
            <a href="{{ route('login') }}" class="btn-splash btn-primary-splash">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </a>
            <a href="{{ route('register') }}" class="btn-splash btn-outline-splash">
                <i class="bi bi-person-plus me-2"></i>Sign Up
            </a>
        </div>

        <div class="features-row">
            <span><i class="bi bi-robot"></i> AI‑Powered</span>
            <span><i class="bi bi-ui-radios"></i> Drag & Drop</span>
            <span><i class="bi bi-bar-chart"></i> Analytics</span>
        </div>

        <p class="mt-4 small text-muted">
            🚀 Free to start • No credit card required
        </p>
    </div>

</body>
</html>