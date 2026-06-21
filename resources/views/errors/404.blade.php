<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Page Not Found | Inventory Management System</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body.error-page {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            /* background: linear-gradient(135deg, #0f0c29 0%, #302b63 45%, #24243e 100%)'' */
            position: relative;
            overflow: hidden;
        }

        body.error-page::before,
        body.error-page::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .25;
            animation: drift 12s ease-in-out infinite alternate;
            pointer-events: none;
        }

        body.error-page::before {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #667eea, transparent 70%);
            top: -120px;
            left: -120px;
        }

        body.error-page::after {
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, #f093fb, transparent 70%);
            bottom: -100px;
            right: -100px;
            animation-delay: -6s;
        }

        @keyframes drift {
            from {
                transform: translate(0, 0) scale(1);
            }

            to {
                transform: translate(40px, 30px) scale(1.08);
            }
        }

        .error-card {
            width: 100%;
            max-width: 520px;
            text-align: center;
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(24px) saturate(160%);
            -webkit-backdrop-filter: blur(24px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 20px;
            box-shadow: 0 32px 64px rgba(0, 0, 0, .45), 0 0 0 1px rgba(255, 255, 255, .06) inset;
            padding: 3rem 2.5rem 2.5rem;
            position: relative;
            z-index: 10;
            animation: slideUp .5s cubic-bezier(.16, 1, .3, 1) both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(28px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Icon badge */
        .error-icon-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 78px;
            height: 78px;
            border-radius: 22px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 8px 24px rgba(102, 126, 234, .45);
            margin-bottom: 1.5rem;
            position: relative;
        }

        .error-icon-wrap i {
            font-size: 2.1rem;
            color: #fff;
        }

        /* Big 404 */
        .error-code {
            font-size: 5rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -2px;
            background: linear-gradient(135deg, #818cf8 0%, #c084fc 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: .5rem;
        }

        .error-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.3px;
            margin: 0 0 .65rem;
        }

        .error-subtitle {
            font-size: .9rem;
            color: black;
            margin: 0 0 .35rem;
            line-height: 1.6;
            font-weight: bold;
        }

        .error-path {
            display: inline-block;
            margin-top: .6rem;
            margin-bottom: 1.9rem;
            padding: .4rem .9rem;
            font-size: .8rem;
            font-family: 'SFMono-Regular', Consolas, monospace;
            color: #f87171;
            background: rgba(248, 113, 113, .12);
            border: 1px solid rgba(248, 113, 113, .25);
            border-radius: 8px;
            word-break: break-all;
            max-width: 100%;
        }

        /* Buttons */
        .error-actions {
            display: flex;
            gap: .85rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary-custom {
            padding: .75rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-size: .875rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 6px 20px rgba(102, 126, 234, .40);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(102, 126, 234, .55);
            filter: brightness(1.08);
            color: #fff;
        }

        .btn-primary-custom:active {
            transform: translateY(0);
        }

        .btn-ghost-custom {
            padding: .75rem 1.5rem;
            border: 1.5px solid rgba(255, 255, 255, .14);
            border-radius: 10px;
            font-size: .875rem;
            font-weight: 600;
            color: black;
            background: #e2e2e2;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: background .2s, border-color .2s, color .2s, transform .18s;
        }

        .btn-ghost-custom:hover {
            background: rgb(108 107 207);
            border-color: rgba(255, 255, 255, .25);
            color: black;
            transform: translateY(-2px);
        }

        /* Helper links */
        .helper-row {
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, .08);
            font-size: .8rem;
            color: rgba(255, 255, 255, .32);
        }

        .helper-row a {
            color: #818cf8;
            text-decoration: none;
            font-weight: 500;
            transition: color .2s;
        }

        .helper-row a:hover {
            color: #a5b4fc;
        }

        @media (max-width: 480px) {
            .error-card {
                padding: 2.25rem 1.5rem 2rem;
            }

            .error-code {
                font-size: 3.8rem;
            }

            .error-icon-wrap {
                width: 64px;
                height: 64px;
            }

            .error-icon-wrap i {
                font-size: 1.7rem;
            }

            .error-actions {
                flex-direction: column;
            }

            .btn-primary-custom,
            .btn-ghost-custom {
                width: 100%;
                justify-content: center;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            body.error-page::before,
            body.error-page::after,
            .error-card {
                animation: none;
            }
        }
    </style>
</head>

<body class="error-page">

    <div class="error-card">

        <div class="error-icon-wrap">
            <i class="fas fa-map-location-dot"></i>
        </div>

        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-subtitle">
            The page you're looking for doesn't exist, may have been moved,<br class="d-none d-sm-block">
            or the URL was typed incorrectly.
        </p>

        {{-- Show the attempted URL for context (helpful in dev / support) --}}
        @if (request()->path())
            <div class="error-path">
                <i class="fas fa-link" style="margin-right:.4rem; opacity:.7;"></i>{{ url()->current() }}
            </div>
        @endif
        <br>

        <div class="error-actions">
            <a href="{{ url('/dashboard') }}" class="btn-primary-custom">
                <i class="fas fa-house"></i> Back to Dashboard
            </a>
            <a href="javascript:history.back()" class="btn-ghost-custom">
                <i class="fas fa-arrow-left"></i> Go Back
            </a>
        </div>

        {{-- <div class="helper-row">
            Think this is a mistake?
            <a href="mailto:support@yourcompany.com">Contact support</a>
            or return to the
            @if (Route::has('login'))
                <a href="{{ route('login') }}">sign-in page</a>.
            @else
                <a href="{{ url('/') }}">homepage</a>.
            @endif
        </div> --}}

    </div>

</body>

</html>
