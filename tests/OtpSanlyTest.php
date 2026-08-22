<?php

declare(strict_types=1);

namespace OtpSanly\Tests;

use OtpSanly\OtpSanly;
use PHPUnit\Framework\TestCase;

final class OtpSanlyTest extends TestCase
{
    public function testEmptyApiKeyThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new OtpSanly('');
    }

    public function testConstructsWithValidApiKey(): void
    {
        $sanly = new OtpSanly('otpsanly_test_key');
        $this->assertInstanceOf(OtpSanly::class, $sanly);
    }

    public function testSendOtpRequiresPhoneOrEmail(): void
    {
        $sanly = new OtpSanly('otpsanly_test_key');
        $this->expectException(\InvalidArgumentException::class);
        $sanly->sendOtp(['project' => 'Test']);
    }

    public function testVerifyOtpRequiresPhoneOrEmail(): void
    {
        $sanly = new OtpSanly('otpsanly_test_key');
        $this->expectException(\InvalidArgumentException::class);
        $sanly->verifyOtp(['code' => '123456']);
    }

    public function testVerifyOtpRequiresCode(): void
    {
        $sanly = new OtpSanly('otpsanly_test_key');
        $this->expectException(\InvalidArgumentException::class);
        $sanly->verifyOtp(['phone' => '+99361234567']);
    }
}
