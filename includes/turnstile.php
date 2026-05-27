<?php
/**
 * Cloudflare Turnstile CAPTCHA Helper
 * Verifies the cf-turnstile-response token server-side.
 */

define('TURNSTILE_SITE_KEY',   '0x4AAAAAADXCo6gGLFKvN2m-');
define('TURNSTILE_SECRET_KEY', '0x4AAAAAADXCoxQ8eilxVwcfHTPZDGXLtgM');
define('TURNSTILE_VERIFY_URL', 'https://challenges.cloudflare.com/turnstile/v0/siteverify');

/**
 * Verify the Turnstile token sent with a form POST.
 *
 * @param  string $token   Value of cf-turnstile-response from $_POST
 * @param  string $remoteIp Optional: user IP for extra validation
 * @return bool   true if the challenge passed, false otherwise
 */
function verify_turnstile(string $token, string $remoteIp = ''): bool {
    if (empty($token)) {
        return false;
    }

    $payload = [
        'secret'   => TURNSTILE_SECRET_KEY,
        'response' => $token,
    ];

    if (!empty($remoteIp)) {
        $payload['remoteip'] = $remoteIp;
    }

    $ch = curl_init(TURNSTILE_VERIFY_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query($payload),
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response = curl_exec($ch);
    $curlError = curl_errno($ch);
    curl_close($ch);

    if ($curlError || $response === false) {
        // Network failure — fail closed (safe default)
        return false;
    }

    $data = json_decode($response, true);
    return isset($data['success']) && $data['success'] === true;
}
?>
