<?php

namespace App\Services\Domain;

use App\Models\Domain;
use App\Models\DomainCheck;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class DomainCheckService
{
    public function check(Domain $domain): void
    {
        $start = microtime(true);

        $payload = [
            'checked_at' => now(),
            'status' => false,
            'status_code' => null,
            'response_time' => 0,
            'error' => null,
        ];

        try {

            $response = Http::timeout($domain->timeout)
                ->connectTimeout($domain->timeout)
                ->retry(1, 100)
                ->send($domain->method, $this->buildUrl($domain->domain));

            $payload['status'] = $response->successful();
            $payload['status_code'] = $response->status();

        } catch (Throwable $e) {

            $payload['error'] = Str::limit($e->getMessage(), 5000, '');

        }

        $payload['response_time'] = (int) round((microtime(true) - $start) * 1000);

        $domain->checks()->create($payload);
    }

    private function buildUrl(string $domain): string
    {
        if (parse_url($domain, PHP_URL_SCHEME)) {
            return $domain;
        }

        return 'https://' . $domain;
    }
}
