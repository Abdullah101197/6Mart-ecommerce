<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class SubscriptionAudit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:audit
                            {--routes=routes/vendor.php : Path to route file to scan for subscription:<key>}
                            {--middleware=app/Http/Middleware/Subscription.php : Path to middleware to scan for permission map}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit subscription permissions mapping and DB columns';

    public function handle(): int
    {
        $routesPath = base_path((string) $this->option('routes'));
        $middlewarePath = base_path((string) $this->option('middleware'));

        if (!is_file($routesPath)) {
            $this->error("Routes file not found: {$routesPath}");
            return self::FAILURE;
        }
        if (!is_file($middlewarePath)) {
            $this->error("Middleware file not found: {$middlewarePath}");
            return self::FAILURE;
        }

        $routesContent = file_get_contents($routesPath) ?: '';
        $middlewareContent = file_get_contents($middlewarePath) ?: '';

        // 1) Collect subscription keys used in routes: subscription:<key>
        preg_match_all("/subscription:([a-zA-Z0-9_]+)/", $routesContent, $routeMatches);
        $routeKeys = array_values(array_unique($routeMatches[1] ?? []));
        sort($routeKeys);

        // 2) Collect keys and mapped fields from middleware permissionMap array.
        //    This is a lightweight parser: find the permissionMap array and parse 'key' => 'field' pairs.
        $mapPairs = [];
        if (preg_match('/\\$permissionMap\\s*=\\s*\\[(.*?)\\];/s', $middlewareContent, $m)) {
            $block = $m[1];
            preg_match_all("/'([a-zA-Z0-9_]+)'\\s*=>\\s*'([a-zA-Z0-9_]+)'/", $block, $pairs, PREG_SET_ORDER);
            foreach ($pairs as $p) {
                $mapPairs[$p[1]] = $p[2];
            }
        }

        ksort($mapPairs);

        $this->info('Subscription Audit');
        $this->line("Routes scanned: {$routesPath}");
        $this->line("Middleware scanned: {$middlewarePath}");
        $this->newLine();

        if (empty($routeKeys)) {
            $this->warn('No subscription:<key> usage found in the routes file.');
            return self::SUCCESS;
        }

        if (empty($mapPairs)) {
            $this->error('Could not find or parse $permissionMap in middleware.');
            return self::FAILURE;
        }

        // 3) Missing mapping: route key not found in middleware map
        $missingMap = array_values(array_diff($routeKeys, array_keys($mapPairs)));
        // 4) Unused mapping: middleware has keys not used in routes
        $unusedMap = array_values(array_diff(array_keys($mapPairs), $routeKeys));

        // 5) DB column checks for mapped fields
        $missingColumns = [
            'subscription_packages' => [],
            'store_subscriptions' => [],
        ];

        foreach ($routeKeys as $key) {
            $field = $mapPairs[$key] ?? null;
            if (!$field) {
                continue;
            }
            if (!Schema::hasColumn('subscription_packages', $field)) {
                $missingColumns['subscription_packages'][] = "{$key} => {$field}";
            }
            if (!Schema::hasColumn('store_subscriptions', $field)) {
                $missingColumns['store_subscriptions'][] = "{$key} => {$field}";
            }
        }

        $hasFailures = false;

        $this->line('Keys used in routes:');
        $this->line(' - ' . implode(', ', $routeKeys));
        $this->newLine();

        if (!empty($missingMap)) {
            $hasFailures = true;
            $this->error('Missing middleware mappings for route keys:');
            foreach ($missingMap as $k) {
                $this->line(" - {$k}");
            }
            $this->newLine();
        }

        if (!empty($missingColumns['subscription_packages']) || !empty($missingColumns['store_subscriptions'])) {
            $hasFailures = true;
            $this->error('Missing DB columns for mapped fields:');
            foreach ($missingColumns as $table => $items) {
                if (empty($items)) {
                    continue;
                }
                $this->line("Table: {$table}");
                foreach ($items as $line) {
                    $this->line(" - {$line}");
                }
            }
            $this->newLine();
        }

        if (!$hasFailures) {
            $this->info('PASS: All route keys are mapped and columns exist.');
        } else {
            $this->warn('FAIL: Please fix the issues above.');
        }

        if (!empty($unusedMap)) {
            $this->line('Note: Middleware has unused mappings (not referenced in routes file):');
            foreach ($unusedMap as $k) {
                $this->line(" - {$k}");
            }
        }

        return $hasFailures ? self::FAILURE : self::SUCCESS;
    }
}

