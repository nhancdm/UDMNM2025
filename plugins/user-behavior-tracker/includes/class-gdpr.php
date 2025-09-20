<?php

class UBT_GDPR {
    public static function render_consent_box() {
        if (!isset($_COOKIE['ubt_consent'])) {
            echo '<div id="ubt-gdpr-box"><p>Trang này sử dụng cookie để phân tích hành vi. Bạn đồng ý?</p>
            <button id="ubt-accept">Đồng ý</button></div>';
            echo '<style>#ubt-gdpr-box{position:fixed;bottom:0;left:0;right:0;padding:10px;background:#fff;border-top:1px solid #ccc;text-align:center;z-index:9999;}</style>';
            echo "<script>
                document.getElementById('ubt-accept').onclick = function() {
                    document.cookie = 'ubt_consent=1; path=/; max-age=31536000';
                    location.reload();
                };
            </script>";
        }
    }
}