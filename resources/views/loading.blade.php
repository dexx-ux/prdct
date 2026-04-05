<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Loading | EliteSphere</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* FULL SCREEN WITH LIGHTER RADIAL BACKGROUND — fresh & airy but retains premium feel */
        body {
            height: 100vh;
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 50% 50%, #eef2f7, #d9e2ec);
            overflow: hidden;
            font-family: 'Segoe UI', 'Poppins', 'Inter', system-ui, -apple-system, sans-serif;
            position: relative;
        }

        /* CENTER CONTAINER — perfect centering flex column */
        .loader-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            flex-direction: column;
        }

        /* MAIN CENTERED GROUP (logo + all animated rings) */
        .center-group {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 300px;
            height: 300px;
        }

        /* LOGO — perfectly centered, using the dominant #1a2c3e harmony */
        .logo {
            width: 140px;
            height: auto;
            z-index: 15;
            filter: drop-shadow(0 0 18px rgba(26, 44, 62, 0.25));
            transition: all 0.2s ease;
            object-fit: contain;
        }

        /* === ANIMATED CIRCLES AROUND LOGO: ALL USING SAME #1a2c3e COLOR === */
        
        /* RING 1 — primary fast spinner (solid #1a2c3e) */
        .ring-primary {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top: 3px solid #1a2c3e;
            border-right: 3px solid #1a2c3e;
            animation: spin 1.1s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
            box-shadow: 0 0 10px rgba(26, 44, 62, 0.2);
        }
        
        /* RING 2 — reverse spin, softer opacity but same exact hue */
        .ring-secondary {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 270px;
            height: 270px;
            border-radius: 50%;
            border: 2.5px solid transparent;
            border-bottom: 3px solid #1a2c3e;
            border-left: 3px solid #1a2c3e;
            animation: spinReverse 1.6s linear infinite;
            opacity: 0.8;
        }

        /* RING 3 — outer delicate ring, slower spin, matching #1a2c3e */
        .ring-tertiary {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 290px;
            height: 290px;
            border-radius: 50%;
            border: 1.8px solid rgba(26, 44, 62, 0.6);
            border-top: 2.2px solid #1a2c3e;
            border-right: 1.5px solid rgba(26, 44, 62, 0.4);
            animation: spin 2.4s linear infinite;
            opacity: 0.7;
        }

        /* PULSE GLOW — lighter aura using #1a2c3e with lower opacity for light background */
        .pulse-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(26, 44, 62, 0.12), rgba(26, 44, 62, 0) 75%);
            animation: gentlePulse 2.2s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        /* ORBITING DOTS — small sparkling dots moving around, using #1a2c3e */
        .orbiting-dot {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            background: #1a2c3e;
            border-radius: 50%;
            box-shadow: 0 0 12px rgba(26, 44, 62, 0.5);
            transform-origin: center;
            animation: orbitRotate 3.2s linear infinite;
            z-index: 6;
        }

        /* Second orbiting dot with delay for extra rhythm */
        .orbiting-dot-delayed {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 8px;
            height: 8px;
            background: #1a2c3e;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(26, 44, 62, 0.5);
            transform-origin: center;
            animation: orbitRotateDelayed 3.8s linear infinite;
            z-index: 6;
            opacity: 0.85;
        }

        /* LOADING TEXT — refined gradient that complements #1a2c3e on light background */
        .loading-text {
            margin-top: 70px;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 5px;
            text-transform: uppercase;
            font-family: 'Segoe UI', 'Inter', monospace;
            background: linear-gradient(135deg, #1a2c3e, #2c4a6e);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: textPulse 1.6s infinite alternate;
            text-shadow: 0 0 8px rgba(26, 44, 62, 0.15);
        }

        /* ANIMATION KEYFRAMES */
        @keyframes spin {
            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        @keyframes spinReverse {
            100% {
                transform: translate(-50%, -50%) rotate(-360deg);
            }
        }

        @keyframes gentlePulse {
            0% {
                transform: translate(-50%, -50%) scale(0.92);
                opacity: 0.3;
            }
            50% {
                transform: translate(-50%, -50%) scale(1.07);
                opacity: 0.1;
            }
            100% {
                transform: translate(-50%, -50%) scale(0.92);
                opacity: 0.3;
            }
        }

        @keyframes orbitRotate {
            0% {
                transform: translate(-50%, -50%) rotate(0deg) translateX(148px) rotate(0deg);
            }
            100% {
                transform: translate(-50%, -50%) rotate(360deg) translateX(148px) rotate(-360deg);
            }
        }

        @keyframes orbitRotateDelayed {
            0% {
                transform: translate(-50%, -50%) rotate(45deg) translateX(160px) rotate(-45deg);
            }
            100% {
                transform: translate(-50%, -50%) rotate(405deg) translateX(160px) rotate(-405deg);
            }
        }

        @keyframes textPulse {
            0% {
                opacity: 0.75;
                letter-spacing: 4px;
                background-position: 0% 50%;
            }
            100% {
                opacity: 1;
                letter-spacing: 6px;
                background-position: 100% 50%;
            }
        }

        /* MOBILE RESPONSIVENESS: keep all rings centered and proportional */
        @media (max-width: 550px) {
            .center-group {
                width: 240px;
                height: 240px;
            }
            .logo {
                width: 100px;
            }
            .ring-primary {
                width: 210px;
                height: 210px;
                border-width: 2.5px;
            }
            .ring-secondary {
                width: 228px;
                height: 228px;
                border-width: 2px;
            }
            .ring-tertiary {
                width: 245px;
                height: 245px;
                border-width: 1.5px;
            }
            .pulse-glow {
                width: 270px;
                height: 270px;
            }
            .loading-text {
                margin-top: 55px;
                font-size: 0.85rem;
                letter-spacing: 4px;
            }
            @keyframes orbitRotate {
                0% { transform: translate(-50%, -50%) rotate(0deg) translateX(120px) rotate(0deg); }
                100% { transform: translate(-50%, -50%) rotate(360deg) translateX(120px) rotate(-360deg); }
            }
            @keyframes orbitRotateDelayed {
                0% { transform: translate(-50%, -50%) rotate(45deg) translateX(130px) rotate(-45deg); }
                100% { transform: translate(-50%, -50%) rotate(405deg) translateX(130px) rotate(-405deg); }
            }
            .orbiting-dot {
                width: 7px;
                height: 7px;
            }
            .orbiting-dot-delayed {
                width: 6px;
                height: 6px;
            }
        }

        /* small landscape tweaks */
        @media (max-width: 800px) and (max-height: 500px) {
            .loading-text {
                margin-top: 40px;
            }
            .logo {
                width: 90px;
            }
            .center-group {
                transform: scale(0.85);
            }
        }

        /* fallback image graceful */
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<div class="loader-wrapper">
    <div class="center-group">
        <!-- Pulsing glow aura with the exact #1a2c3e tone — now subtle on light background -->
        <div class="pulse-glow"></div>
        
        <!-- Multiple animated rings: all using #1a2c3e (same color as logo) -->
        <div class="ring-primary"></div>
        <div class="ring-secondary"></div>
        <div class="ring-tertiary"></div>
        
        <!-- Two orbiting dots that travel around the logo for extra motion -->
        <div class="orbiting-dot"></div>
        <div class="orbiting-dot-delayed"></div>
        
        <!-- CENTER LOGO: the focal point, colored logo blends with ring theme -->
        <img src="{{ asset('images/logos.png') }}" class="logo"
             alt="Loading logo"
             onerror="this.src='https://placehold.co/140x140/1a2c3e/white?text=LOGO'; this.style.borderRadius='12px';">
    </div>
    
    <div class="loading-text">
        LOADING...
    </div>
</div>

<script>
    // Optional: smooth redirect after load simulation (disabled by default)
    // setTimeout(() => {
    //     window.location.href = "{{ route('home') }}";
    // }, 2800);
    
    console.log("✨ Light background loading screen — rings use #1a2c3e, perfectly centered");
</script>
</body>
</html>