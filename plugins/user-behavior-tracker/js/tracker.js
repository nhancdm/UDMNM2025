(function() {
    if (!document.cookie.includes('ubt_consent=1')) return;

    let clicks = 0;
    let scrollDepth = 0;
    const start = Date.now();

    document.addEventListener('click', () => clicks++);
    window.addEventListener('scroll', () => {
        const scrolled = Math.floor((window.scrollY + window.innerHeight) / document.body.scrollHeight * 100);
        scrollDepth = Math.max(scrollDepth, scrolled);
    });

    window.addEventListener('beforeunload', () => {
        const timeSpent = Math.floor((Date.now() - start) / 1000);

        navigator.sendBeacon(UBT_AJAX.ajax_url, new URLSearchParams({
            action: 'ubt_track',
            nonce: UBT_AJAX.nonce,
            url: location.href,
            clicks,
            scroll: scrollDepth,
            time: timeSpent
        }));
    });
})();