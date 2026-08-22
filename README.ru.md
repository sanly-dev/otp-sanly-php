# OTP Sanly — PHP SDK

[Türkmençe](README.tk.md) | Русский | [English](README.md)

Официальный PHP SDK для [OTP Sanly](https://otp.sanly.dev) — сервис OTP-подтверждения по SMS и Email для Туркменистана.

## Установка

```bash
composer require otp-sanly/otp-sanly
```

## Быстрый старт

```php
use OtpSanly\OtpSanly;
use OtpSanly\OtpSanlyException;

$sanly = new OtpSanly(getenv('OTP_API_KEY'));

// Отправить OTP
try {
    $sent = $sanly->sendOtp([
        'phone' => '+99361234567', // Только туркменские номера для SMS. Для доставки в любую страну используйте "email".
        'project' => 'Мой сервис',
        'lang' => 'ru', // 'tk' | 'ru' | 'en' — на каком языке отправить OTP
    ]);
    echo $sent['otpId'] . ': ' . $sent['message'];
} catch (OtpSanlyException $e) {
    echo 'Ошибка: ' . $e->getMessage();
}

// Проверить код, введённый пользователем
$verified = $sanly->verifyOtp([
    'phone' => '+99361234567',
    'code' => '123456',
]);
if ($verified['success']) {
    // продолжить — код верный
}
```

## Тестовый режим (sandbox)

Создайте **sandbox** API-ключ в личном кабинете (https://otp.sanly.dev/dashboard/api-keys), чтобы бесплатно
протестировать интеграцию — реальные SMS/email не отправляются. Код возвращается прямо в ответе:

```php
$sent = $sanly->sendOtp(['phone' => '+99361234567']);
echo $sent['code']; // доступно только для sandbox-ключей
```

## Обработка ошибок

Неудачные запросы выбрасывают `OtpSanly\OtpSanlyException` — `getCode()` возвращает HTTP-статус, `getBody()` — полное тело ответа:

```php
try {
    $sanly->sendOtp(['phone' => '+99361234567']);
} catch (OtpSanlyException $e) {
    error_log($e->getCode() . ' ' . $e->getMessage());
    var_dump($e->getBody());
}
```

## Требования

- PHP >= 7.4
- ext-curl, ext-json (обычно уже есть в большинстве установок PHP)

## Полная документация API

Полное описание API, документацию по webhook и примеры на других языках см. здесь:
https://otp.sanly.dev/developers

## Лицензия

MIT
