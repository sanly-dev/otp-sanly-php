<?php

declare(strict_types=1);

namespace OtpSanly;

/**
 * OTP Sanly — Official PHP SDK
 * https://otp.sanly.dev/developers
 */
class OtpSanly
{
    private const DEFAULT_BASE_URL = 'https://otp.sanly.dev';

    private string $apiKey;
    private string $baseUrl;

    public function __construct(string $apiKey, string $baseUrl = self::DEFAULT_BASE_URL)
    {
        if ($apiKey === '') {
            throw new \InvalidArgumentException(
                'OtpSanly: apiKey is required. Get one at https://otp.sanly.dev/dashboard/api-keys'
            );
        }
        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Send an OTP via SMS (Turkmenistan numbers only) or email (worldwide).
     *
     * @param array{
     *   phone?: string,
     *   email?: string,
     *   project?: string,
     *   lang?: string
     * } $params Provide "phone" OR "email" (not both). `lang` is "tk"|"ru"|"en" —
     *   which language to send the OTP in. Defaults to "tk" if omitted.
     *   IMPORTANT: since this SDK calls the API server-to-server, the
     *   Accept-Language HTTP header is unreliable — always pass `lang`
     *   explicitly if you support multiple languages for your end users.
     *
     * @return array<string, mixed> Decoded JSON response.
     * @throws OtpSanlyException On network error or a non-2xx / success:false response.
     */
    public function sendOtp(array $params): array
    {
        if (empty($params['phone']) && empty($params['email'])) {
            throw new \InvalidArgumentException('OtpSanly::sendOtp: provide either "phone" or "email"');
        }
        return $this->request('/api/send-otp', $params);
    }

    /**
     * Verify a code the user entered.
     *
     * @param array{
     *   phone?: string,
     *   email?: string,
     *   code: string,
     *   otpId?: int
     * } $params Provide "phone" OR "email" (same value used in sendOtp), plus "code".
     *
     * @return array<string, mixed> Decoded JSON response.
     * @throws OtpSanlyException On network error or a non-2xx / success:false response.
     */
    public function verifyOtp(array $params): array
    {
        if (empty($params['phone']) && empty($params['email'])) {
            throw new \InvalidArgumentException('OtpSanly::verifyOtp: provide either "phone" or "email"');
        }
        if (empty($params['code'])) {
            throw new \InvalidArgumentException('OtpSanly::verifyOtp: "code" is required');
        }
        return $this->request('/api/verify-otp', $params);
    }

    /**
     * @param array<string, mixed> $body
     * @return array<string, mixed>
     */
    private function request(string $path, array $body): array
    {
        $payload = array_merge(['apiKey' => $this->apiKey], $body);
        $json = json_encode($payload);

        $ch = curl_init($this->baseUrl . $path);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
        ]);
        $responseBody = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($errno !== 0) {
            throw new OtpSanlyException("OtpSanly: network error — {$error}", 0);
        }

        $data = json_decode((string) $responseBody, true);
        if (!is_array($data)) {
            $data = [];
        }

        if ($status < 200 || $status >= 300 || (isset($data['success']) && $data['success'] === false)) {
            $message = $data['error'] ?? "Request failed with status {$status}";
            throw new OtpSanlyException($message, $status, $data);
        }

        return $data;
    }
}
