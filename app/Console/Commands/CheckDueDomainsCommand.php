<?php

namespace App\Console\Commands;

use App\Jobs\RunDomainCheckJob;
use App\Models\Domain;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class CheckDueDomainsCommand extends Command
{
    protected $signature = 'domains:check-due';
    protected $description = 'Dispatch checks for domains that are due.';

    public function handle(): int
    {
        $dispatched = 0;

        Domain::query()
            ->withMax('checks', 'checked_at')
            ->orderBy('id')
            ->chunkById(100, function (Collection $domains) use (&$dispatched): void {
                foreach ($domains as $domain) {
                    $lastCheckedAt = $domain->checks_max_checked_at;

                    if (! $this->isDue($lastCheckedAt, (int) $domain->check_interval)) {
                        continue;
                    }

                    RunDomainCheckJob::dispatch((int) $domain->id);
                    $dispatched++;
                }
            });

        $this->info("Dispatched {$dispatched} domain checks.");

        return self::SUCCESS;
    }

    private function isDue(mixed $lastCheckedAt, int $intervalSeconds): bool
    {
        if (! $lastCheckedAt) {
            return true;
        }

        return Carbon::parse($lastCheckedAt)
            ->addSeconds($intervalSeconds)
            ->lte(now());
    }
}
