# OTP Sanly — PHP SDK

[Türkmençe](README.tk.md) | [Русский](README.ru.md) | [English](README.md)

**OTP Sanly** (https://otp.sanly.dev) üçin resmi PHP SDK — Türkmenistan üçin SMS & Email OTP tassyklama hyzmaty.

## Gurnamak

```bash
composer require otp-sanly/otp-sanly
```

## Çalt başlamak

```php
use OtpSanly\OtpSanly;
use OtpSanly\OtpSanlyException;

$sanly = new OtpSanly(getenv('OTP_API_KEY'));

// OTP iber
try {
    $sent = $sanly->sendOtp([
        'phone' => '+99361234567', // SMS üçin diňe Türkmenistan belgileri. Dünýäniň islendik ýerine ibermek üçin "email" ulanyň.
        'project' => 'Meniň Programmam',
        'lang' => 'ru', // 'tm' | 'ru' | 'en' — OTP-iň haýsy dilde ugradylmalydygy
    ]);
    echo $sent['otpId'] . ': ' . $sent['message'];
} catch (OtpSanlyException $e) {
    echo 'Ýalňyşlyk: ' . $e->getMessage();
}

// Ulanyjynyň girizen kodyny barla
$verified = $sanly->verifyOtp([
    'phone' => '+99361234567',
    'code' => '123456',
]);
if ($verified['success']) {
    // dowam et — kod dogry
}
```

## Sandbox (synag) rejimi

Dashboard-yňyzda (https://otp.sanly.dev/dashboard/api-keys) **sandbox** API açary döredip, integrasiýaňyzy
mugt synap görüp bilersiňiz — hakyky SMS/email ugradylmaýar. OTP kody göni jogapda gaýtarylýar:

```php
$sent = $sanly->sendOtp(['phone' => '+99361234567']);
echo $sent['code']; // diňe sandbox açarlarda bar bolýar
```

## Ýalňyşlyklary dolandyrmak

Şowsuz haýyşlar `OtpSanly\OtpSanlyException`-y "taşlaýar" (throw) — `getCode()` bilen HTTP status, `getBody()` bilen çig jogap alyp bolýar:

```php
try {
    $sanly->sendOtp(['phone' => '+99361234567']);
} catch (OtpSanlyException $e) {
    error_log($e->getCode() . ' ' . $e->getMessage());
    var_dump($e->getBody());
}
```

## Talaplar

- PHP >= 7.4
- ext-curl, ext-json (köp PHP gurnamalarda öňünden bar)

## Doly API resminamasy

Doly API resminamasyny, webhook dokumentasiýasyny we beýleki dillerdäki mysallary şu ýerden görüň:
https://otp.sanly.dev/developers

## Ygtyýarnama

MIT
