<?php

namespace Mantraideas\LaravelEnvDoctor\Checks;

class FileExistenceCheck
{
    public static function run(array $files): array
    {
        $results = [];

        foreach ($files as $file) {
            $path = $file['path'] ?? null;

            if (! $path) {
                $results[] = ['status' => 'fail', 'message' => '❌ No path specified.'];

                continue;
            }

            if (! file_exists($path)) {
                $results[] = ['status' => 'fail', 'message' => "❌ File not found: {$path}"];

                continue;
            }

            if (! is_readable($path)) {
                $results[] = ['status' => 'fail', 'message' => "❌ File is not readable: {$path}"];

                continue;
            }

            $checkPermissions = $file['check_permissions'] ?? false;

            if ($checkPermissions && isset($file['required_permission'])) {
                $actualPerms = substr(sprintf('%o', fileperms($path)), -3);
                $requiredPerms = ltrim((string) $file['required_permission'], '0');

                if ($actualPerms !== $requiredPerms) {
                    $results[] = [
                        'status' => 'fail',
                        'message' => "❌ {$path} has incorrect permissions. Current: {$actualPerms}, Required: {$requiredPerms}",
                    ];

                    continue;
                }
            }

            $perms = substr(sprintf('%o', fileperms($path)), -3);
            $results[] = ['status' => 'pass', 'message' => "✅ {$path} exists and is readable (Permissions: {$perms})"];
        }

        return $results;
    }
}
