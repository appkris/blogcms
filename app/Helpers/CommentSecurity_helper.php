<?php

/**
 * Comment Security Helper
 * Menangani: XSS, spam detection, kata terlarang, sensor link, rate limiting
 */

if (!function_exists('comment_clean')) {
    /**
     * Bersihkan input komentar dari XSS dan karakter berbahaya.
     * Admin boleh pakai link, user biasa tidak.
     */
    function comment_clean(string $text, bool $isAdmin = false): string
    {
        // Strip semua tag HTML dari user biasa
        if (!$isAdmin) {
            $text = strip_tags($text);
        } else {
            // Admin: izinkan tag aman saja, link dibuka di tab baru
            $allowed = '<p><br><strong><em><ul><ol><li><a>';
            $text    = strip_tags($text, $allowed);
            // Paksa semua <a> buka di tab baru dan tambah rel noopener
            $text = preg_replace_callback(
                '/<a\s([^>]*)>/i',
                function ($m) {
                    $attrs = $m[1];
                    if (!str_contains($attrs, 'target=')) {
                        $attrs .= ' target="_blank"';
                    }
                    if (!str_contains($attrs, 'rel=')) {
                        $attrs .= ' rel="noopener noreferrer"';
                    }
                    return '<a ' . $attrs . '>';
                },
                $text
            );
        }

        // Encode entitas HTML tersisa
        $text = htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);

        // Decode kembali tag yang diizinkan untuk admin
        if ($isAdmin) {
            $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return trim($text);
    }
}

if (!function_exists('comment_sensor_words')) {
    /**
     * Sensor kata-kata terlarang dengan tanda bintang.
     */
    function comment_sensor_words(string $text): string
    {
        $badWords = [
            'bajingan', 'bangsat', 'brengsek', 'anjing', 'kontol',
            'memek', 'ngentot', 'jancok', 'asu', 'goblok',
            'idiot', 'bodoh', 'tolol', 'babi', 'kampret',
            'fuck', 'shit', 'damn', 'asshole', 'bitch',
        ];

        foreach ($badWords as $word) {
            $replacement = substr($word, 0, 1) . str_repeat('*', strlen($word) - 1);
            $text = preg_replace('/\b' . preg_quote($word, '/') . '\b/iu', $replacement, $text);
        }

        return $text;
    }
}

if (!function_exists('comment_strip_links')) {
    /**
     * Hapus semua URL dari teks komentar user biasa.
     */
    function comment_strip_links(string $text): string
    {
        // Hapus URL http/https
        $text = preg_replace('/https?:\/\/[^\s<>"\']+/i', '[link dihapus]', $text);
        // Hapus www. tanpa protokol
        $text = preg_replace('/\bwww\.[^\s<>"\']+/i', '[link dihapus]', $text);
        return $text;
    }
}

if (!function_exists('comment_is_spam')) {
    /**
     * Deteksi spam sederhana.
     * Return true jika terdeteksi spam.
     */
    function comment_is_spam(string $text, string $name, string $email): bool
    {
        // 1. Terlalu banyak URL
        $urlCount = preg_match_all('/https?:\/\//i', $text);
        if ($urlCount > 2) return true;

        // 2. Teks terlalu pendek (kurang dari 5 karakter)
        if (mb_strlen(trim($text)) < 5) return true;

        // 3. Semua huruf kapital (lebih dari 70% uppercase)
        $letters = preg_replace('/[^a-zA-Z]/', '', $text);
        if (strlen($letters) > 10) {
            $upperRatio = strlen(preg_replace('/[^A-Z]/', '', $letters)) / strlen($letters);
            if ($upperRatio > 0.7) return true;
        }

        // 4. Kata spam umum
        $spamKeywords = [
            'casino', 'poker', 'viagra', 'cialis', 'loan', 'credit',
            'free money', 'click here', 'buy now', 'cheap', 'discount',
            'earn money', 'make money', 'work from home', 'weight loss',
        ];
        $textLower = strtolower($text . ' ' . $name);
        foreach ($spamKeywords as $kw) {
            if (str_contains($textLower, $kw)) return true;
        }

        // 5. Email disposable domain
        $disposableDomains = ['mailinator.com', 'guerrillamail.com', 'tempmail.com', 'throwam.com', 'yopmail.com'];
        $emailDomain = strtolower(substr(strrchr($email, '@'), 1));
        if (in_array($emailDomain, $disposableDomains)) return true;

        return false;
    }
}

if (!function_exists('comment_rate_limit')) {
    /**
     * Cek rate limit: max 3 komentar per IP per 10 menit.
     * Return true jika melebihi batas (harus ditolak).
     */
    function comment_rate_limit(string $ip): bool
    {
        $db    = db_connect();
        $since = date('Y-m-d H:i:s', strtotime('-10 minutes'));
        $count = $db->table('comments')
                    ->where('ip_address', $ip)
                    ->where('created_at >=', $since)
                    ->countAllResults();
        return $count >= 3;
    }
}

if (!function_exists('comment_process')) {
    /**
     * Proses lengkap komentar user biasa:
     * clean → strip links → sensor kata → cek spam
     * Return array ['text' => ..., 'is_spam' => bool]
     */
    function comment_process(string $text, string $name, string $email, bool $isAdmin = false): array
    {
        $isSpam = false;

        if (!$isAdmin) {
            $isSpam = comment_is_spam($text, $name, $email);
            $text   = comment_clean($text, false);
            $text   = comment_strip_links($text);
            $text   = comment_sensor_words($text);
        } else {
            $text = comment_clean($text, true);
        }

        return ['text' => $text, 'is_spam' => $isSpam];
    }
}

if (!function_exists('comment_verify_recaptcha')) {
    /**
     * Verifikasi token response Google reCAPTCHA v2 ke API Google.
     * Return true jika valid, false jika gagal.
     */
    function comment_verify_recaptcha(?string $token, ?string $ip = null): bool
    {
        $secretKey = env('RECAPTCHA_SECRET_KEY');
        if (empty($secretKey)) {
            // Bila secret key belum diset di .env, loloskan untuk development
            return true;
        }

        if (empty($token)) {
            return false;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $postData = [
            'secret'   => $secretKey,
            'response' => $token,
        ];
        if (!empty($ip)) {
            $postData['remoteip'] = $ip;
        }

        try {
            $client = \Config\Services::curlrequest([
                'timeout' => 5,
            ]);
            $response = $client->post($url, [
                'form_params' => $postData,
            ]);

            if ($response->getStatusCode() === 200) {
                $body = json_decode($response->getBody(), true);
                return isset($body['success']) && $body['success'] === true;
            }
        } catch (\Throwable $e) {
            log_message('error', 'Google reCAPTCHA verification error: ' . $e->getMessage());
            // Fallback dengan file_get_contents / curl standar bila Service cURL bermasalah
            if (ini_get('allow_url_fopen')) {
                $context = stream_context_create([
                    'http' => [
                        'method'  => 'POST',
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'content' => http_build_query($postData),
                        'timeout' => 5,
                    ],
                ]);
                $result = @file_get_contents($url, false, $context);
                if ($result !== false) {
                    $body = json_decode($result, true);
                    return isset($body['success']) && $body['success'] === true;
                }
            }
        }

        return false;
    }
}
