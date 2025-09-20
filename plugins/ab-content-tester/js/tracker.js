document.addEventListener('DOMContentLoaded', function() {
    const variation = document.querySelector('[data-ab-variation]');
    if (!variation) return;

    const varName = variation.dataset.abVariation;
    const postId = variation.dataset.postId;

    // Theo dõi thời gian trên trang
    let start = Date.now();
    window.addEventListener('beforeunload', function() {
        let seconds = (Date.now() - start) / 1000;
        sendMetric('time', seconds);
    });

    // Theo dõi click vào A/B nội dung (nếu có)
    document.querySelectorAll('[data-ab-click]').forEach(el => {
        el.addEventListener('click', () => sendMetric('click', 1));
    });

    function sendMetric(metric, value) {
        navigator.sendBeacon(abt_ajax.ajax_url, new URLSearchParams({
            action: 'abt_track',
            post_id: postId,
            variation: varName,
            metric: metric,
            value: value
        }));
    }
});