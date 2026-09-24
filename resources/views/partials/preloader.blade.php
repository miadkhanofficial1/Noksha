<!-- NOKSHA WEBSITE PRELOADER / SPLASH SCREEN (FIRST VISIT / SESSION ONLY) -->
<script>
    (function () {
        try {
            if (sessionStorage.getItem('hasSeenSplash')) {
                document.documentElement.classList.add('splash-already-seen');
            }
        } catch (e) {}
    })();
</script>

<style>
    html.splash-already-seen #preloader {
        display: none !important;
    }

    #preloader {
        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100vh;
        background-color: #0F172A;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    @keyframes preloaderPulse {
        0%, 100% {
            transform: scale(0.96);
            opacity: 0.88;
            filter: drop-shadow(0 0 10px rgba(108, 76, 241, 0.3));
        }
        50% {
            transform: scale(1.05);
            opacity: 1;
            filter: drop-shadow(0 0 22px rgba(124, 58, 237, 0.65));
        }
    }
</style>

<div id="preloader" aria-hidden="true">
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem;">
        <img src="{{ asset('images/logo.png') }}" 
             alt="Noksha" 
             id="preloader-logo"
             height="60"
             style="height: 60px; width: auto; max-height: 60px; object-fit: contain; animation: preloaderPulse 1.8s ease-in-out infinite; will-change: transform, opacity, filter;">
    </div>
</div>

<script>
    (function () {
        try {
            if (sessionStorage.getItem('hasSeenSplash')) {
                var el = document.getElementById('preloader');
                if (el && el.parentNode) {
                    el.parentNode.removeChild(el);
                }
                return;
            }
        } catch (e) {
            return;
        }

        const startTime = performance.now();

        function dismissPreloader() {
            const minDuration = 1800; // Keep visible for at least 1.8 seconds so animation is fully seen
            const elapsedTime = performance.now() - startTime;
            const remainingTime = Math.max(0, minDuration - elapsedTime);

            setTimeout(() => {
                const preloader = document.getElementById('preloader');
                if (preloader) {
                    preloader.style.pointerEvents = 'none';
                    preloader.style.transition = 'opacity 0.4s ease, visibility 0.4s ease';
                    preloader.style.opacity = '0';
                    preloader.style.visibility = 'hidden';

                    try {
                        sessionStorage.setItem('hasSeenSplash', 'true');
                    } catch (e) {}

                    setTimeout(() => {
                        if (preloader && preloader.parentNode) {
                            preloader.parentNode.removeChild(preloader);
                        }
                    }, 450);
                }
            }, remainingTime);
        }

        if (document.readyState === 'complete') {
            dismissPreloader();
        } else {
            window.addEventListener('load', dismissPreloader);
            // Fallback timeout in case an asset hangs
            setTimeout(dismissPreloader, 4000);
        }
    })();
</script>
