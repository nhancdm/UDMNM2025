<?php
class OAuth_Helper {
    public static function encrypt($data) {
        return base64_encode($data); // Cần dùng mã hóa mạnh hơn trong bản thật (OpenSSL)
    }

    public static function decrypt($data) {
        return base64_decode($data);
    }
}