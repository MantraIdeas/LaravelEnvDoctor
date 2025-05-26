<?php

namespace mantraideas\LaravelEnvDoctor\Checks;

class DirectoryPermissionCheck
{
    public static function run(array $directories): array
    {
        $results = [];

        foreach ($directories as $dir) {
            $path = $dir['path'] ?? null;
            $required = $dir['required_permission'] ?? 0775;

            if (!$path || !file_exists($path)) {
                $results[] = [
                    'status' => 'fail',
                    'message' => "❌ Directory not found: {$path}"
                ];
                continue;
            }
            $actualPerms = substr(sprintf('%o', fileperms($path)), -3);
            $requiredPerms = ltrim((string) $required, '0');
            if (!is_writable($path)) {
                $results[] = [
                    'status' => 'fail',
                    'message' => "❌ {$path} is not writable by the current user. Current: {$actualPerms}, Required: {$requiredPerms}"
                ];
            } elseif ($actualPerms !== $requiredPerms) {
                $results[] = [
                    'status' => 'fail',
                    'message' => "❌ {$path} has incorrect permissions. Current: {$actualPerms}, Required: {$requiredPerms}"
                ];
            } else {
                $results[] = [
                    'status' => 'pass',
                    'message' => "✅ {$path} is writable (Permissions: {$actualPerms})"
                ];
            }

        }

        return $results;
    }
}
