# OTP Sanly — PHP SDK

[Türkmençe](README.tk.md) | [Русский](README.ru.md) | English

Official PHP SDK for [OTP Sanly](https://otp.sanly.dev) — SMS & Email OTP authentication for Turkmenistan.

## Install

```bash
composer require otp-sanly/otp-sanly
```

## Quick start

```php
use OtpSanly\OtpSanly;
use OtpSanly\OtpSanlyException;

$sanly = new OtpSanly(getenv('OTP_API_KEY'));

// Send an OTP
try {
    $sent = $sanly->sendOtp([
        'phone' => '+99361234567', // Turkmenistan numbers only for SMS. Use "email" instead for worldwide delivery.
        'project' => 'My App',
        'lang' => 'ru', // 'tk' | 'ru' | 'en' — which language to send the OTP in
    ]);
    echo $sent['otpId'] . ': ' . $sent['message'];
} catch (OtpSanlyException $e) {
    echo 'Error: ' . $e->getMessage();
}

// Verify the code the user entered
$verified = $sanly->verifyOtp([
    'phone' => '+99361234567',
    'code' => '123456',
]);
if ($verified['success']) {
    // proceed — the code was correct
}
```

## Sandbox mode

Create a **sandbox** API key in your [dashboard](https://otp.sanly.dev/dashboard/api-keys) to test your integration
for free, without sending real SMS/email. The OTP code is returned directly in the response:

```php
$sent = $sanly->sendOtp(['phone' => '+99361234567']);
echo $sent['code']; // only present for sandbox keys
```

## Error handling

Failed requests throw `OtpSanly\OtpSanlyException`, which includes the HTTP status (via `getCode()`) and the raw
response body (via `getBody()`):

```php
try {
    $sanly->sendOtp(['phone' => '+99361234567']);
} catch (OtpSanlyException $e) {
    error_log($e->getCode() . ' ' . $e->getMessage());
    var_dump($e->getBody());
}
```

## Requirements

- PHP >= 7.4
- ext-curl, ext-json (standard in most PHP installs)

## Full API reference

See [https://otp.sanly.dev/developers](https://otp.sanly.dev/developers) for the complete API reference, webhook docs,
and framework-specific examples in other languages.

## License

MIT
