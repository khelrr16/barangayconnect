@once
    <style>
        .app-loading-overlay {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 12px;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(2px);
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .app-loading-overlay.is-visible {
            display: flex;
            opacity: 1;
        }

        .app-loading-spinner {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.35);
            border-top-color: #ffffff;
            animation: app-loading-spin 0.8s linear infinite;
        }

        .app-loading-text {
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        @keyframes app-loading-spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <div id="appLoadingOverlay" class="app-loading-overlay" role="status" aria-live="polite" aria-label="Loading">
        <div class="app-loading-spinner"></div>
        <p class="app-loading-text">Loading, please wait...</p>
    </div>

    <script>
        (function () {
            if (window.__appLoadingInitialized) {
                return;
            }

            window.__appLoadingInitialized = true;

            function getOverlay() {
                return document.getElementById('appLoadingOverlay');
            }

            function showLoading(message) {
                var overlay = getOverlay();
                if (!overlay) {
                    return;
                }

                var text = overlay.querySelector('.app-loading-text');
                if (text && message) {
                    text.textContent = message;
                }

                overlay.classList.add('is-visible');
            }

            function hideLoading() {
                var overlay = getOverlay();
                if (!overlay) {
                    return;
                }

                overlay.classList.remove('is-visible');
            }

            window.AppLoading = {
                show: showLoading,
                hide: hideLoading
            };

            window.addEventListener('load', hideLoading);
            window.addEventListener('pageshow', hideLoading);

            document.addEventListener('submit', function (event) {
                var form = event.target;
                if (!(form instanceof HTMLFormElement)) {
                    return;
                }

                if (form.hasAttribute('data-no-loading')) {
                    return;
                }

                if (form.target && form.target.toLowerCase() === '_blank') {
                    return;
                }

                showLoading(form.getAttribute('data-loading-text') || 'Submitting...');
            }, true);

            document.addEventListener('click', function (event) {
                var link = event.target.closest('a[href]');
                if (!link) {
                    return;
                }

                var href = link.getAttribute('href') || '';
                if (!href || href.startsWith('#') || link.hasAttribute('data-no-loading')) {
                    return;
                }

                if (link.target && link.target.toLowerCase() === '_blank') {
                    return;
                }

                if (event.defaultPrevented || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0) {
                    return;
                }

                var url = new URL(link.href, window.location.href);
                if (url.origin !== window.location.origin) {
                    return;
                }

                showLoading(link.getAttribute('data-loading-text') || 'Loading page...');
            }, true);
        })();
    </script>
@endonce
