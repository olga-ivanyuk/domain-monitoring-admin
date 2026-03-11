<?php

namespace App\Jobs;

use App\Models\Domain;
use App\Services\Domain\DomainCheckService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Queue\Queueable;

class RunDomainCheckJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public function __construct(
        public int $domainId,
    ) {
    }

    public function handle(DomainCheckService $domainCheckService): void
    {
        $domain = Domain::query()->find($this->domainId);

        if (! $domain) {
            return;
        }

        $domainCheckService->check($domain);
    }

    public function uniqueId(): string
    {
        return (string) $this->domainId;
    }
}
