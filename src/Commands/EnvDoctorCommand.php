<?php

namespace mantraideas\LaravelEnvDoctor\Commands;

use Illuminate\Console\Command;
use mantraideas\LaravelEnvDoctor\Checks\DirectoryPermissionCheck;
use mantraideas\LaravelEnvDoctor\Checks\EnvCheck;
use Symfony\Component\Console\Command\Command as CommandAlias;

class EnvDoctorCommand extends Command
{
    protected $signature = 'env:doctor
    {--log : Enable logging of the diagnosis results}';
    protected $description = 'Diagnose environment configuration issues for your Laravel application';

    public function handle(): int
    {
        $logsEnabled = $this->option('log');

        $this->showWelcomeMessage();

        $issues = 0;
        $totalChecks = 0;

        // Environment Variables Check
        $this->runEnvChecks($issues, $totalChecks, $logsEnabled);

        // Directory Permissions Check
        $this->runDirectoryChecks($issues, $totalChecks, $logsEnabled);

        // Show summary
        $this->showSummary($issues, $totalChecks, $logsEnabled);

        return $issues > 0 ? CommandAlias::FAILURE : CommandAlias::SUCCESS;
    }

    protected function showWelcomeMessage(): void
    {
        $this->output->writeln([
            '',
            '<fg=blue>┌──────────────────────────────────────────────────────────────┐</>',
            '<fg=blue>│</> <fg=white;options=bold>🩺  Laravel Environment Doctor - System Diagnosis</>            <fg=blue>│</>',
            '<fg=blue>└──────────────────────────────────────────────────────────────┘</>',
            '',
            '<fg=gray>Checking your application environment configuration and permissions...</>',
            ''
        ]);
    }

    protected function runEnvChecks(int &$issues, int &$totalChecks, bool $logEnabled = false): array
    {
        $this->output->writeln('<fg=cyan;options=bold>📦 ENVIRONMENT VARIABLES CHECK</>');
        $this->line('Verifying required .env variables are set');

        $envResults = EnvCheck::run(config('laravel-env-doctor.required_env_keys', []));
        $totalChecks += count($envResults);

        if ($logEnabled) {
            \Log::info("[EnvDoctor] ---- ENVIRONMENT VARIABLES CHECK ----");
        }

        $this->renderCheckResults($envResults, $issues, $logEnabled);
        $this->newLine();

        return $envResults;
    }


    protected function renderCheckResults(array $results, int &$issues, bool $logEnabled = false): void
    {
        $this->table(
            ['Status', 'Message'],
            array_map(function ($result) use (&$issues, $logEnabled) {
                $status = $result['status'];
                $message = $result['message'];

                if ($status === 'fail') {
                    $issues++;
                    if ($logEnabled) {
                        \Log::error("[EnvDoctor] ✖ FAIL: $message");
                    }
                    return [
                        '<fg=red>✖ FAIL</>',
                        "<fg=white>{$message}</>"
                    ];
                } else {
                    if ($logEnabled) {
                        \Log::info("[EnvDoctor] ✓ PASS: $message");
                    }
                    return [
                        '<fg=green>✓ PASS</>',
                        "<fg=gray>{$message}</>"
                    ];
                }
            }, $results)
        );
    }


    protected function runDirectoryChecks(int &$issues, int &$totalChecks, bool $logsEnabled = false): array
    {
        $this->output->writeln('<fg=cyan;options=bold>📁 DIRECTORY PERMISSIONS CHECK</>');
        $this->line('Verifying directory permissions and writability');

        $dirResults = DirectoryPermissionCheck::run(config('laravel-env-doctor.directories_to_check', []));
        $totalChecks += count($dirResults);

        if ($logsEnabled) {
            \Log::info("[EnvDoctor] ---- DIRECTORY PERMISSIONS CHECK ----");
        }

        $this->renderCheckResults($dirResults, $issues, $logsEnabled);
        $this->newLine();

        return $dirResults;
    }


    protected function showSummary(int $issues, int $totalChecks, bool $logsEnabled = false): void
    {
        $successRate = $totalChecks > 0 ? round(($totalChecks - $issues) / $totalChecks * 100) : 100;
        if ($logsEnabled) {
            \Log::info("[EnvDoctor] ----- DIAGNOSIS SUMMARY -----");
            \Log::info("[EnvDoctor] Total checks performed: $totalChecks");
            \Log::info("[EnvDoctor] Issues found: $issues");
            \Log::info("[EnvDoctor] Success rate: {$successRate}%");
        }
        $this->output->writeln([
            '',
            '<fg=blue>┌───────────────────────────────────┐</>',
            '<fg=blue>│</> <fg=white;options=bold>🩺  DIAGNOSIS SUMMARY</>             <fg=blue>│</>',
            '<fg=blue>├───────────────────────────────────┤</>',
            "<fg=blue>│</> <fg=white>Total checks performed:</> " . str_pad($totalChecks, 10, ' ',
                STR_PAD_RIGHT) . "<fg=blue>│</>",
            "<fg=blue>│</> <fg=white>Issues found:</> " . str_pad($issues, 20, ' ', STR_PAD_RIGHT) . "<fg=blue>│</>",
            "<fg=blue>│</> <fg=white>Success rate:</> " . str_pad("{$successRate}%", 20, ' ',
                STR_PAD_RIGHT) . "<fg=blue>│</>",
            '<fg=blue>└───────────────────────────────────┘</>',
            ''
        ]);

        if ($issues === 0) {
            $this->output->writeln('<fg=green;options=bold>🎉 Excellent! Your environment is perfectly configured!</>');
        } else {
            $this->output->writeln('<fg=yellow;options=bold>⚠️  Some issues need your attention. See above for details.</>');
        }
    }
}
