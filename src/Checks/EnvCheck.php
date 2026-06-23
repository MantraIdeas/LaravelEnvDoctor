<?php

namespace Mantraideas\LaravelEnvDoctor\Checks;

class EnvCheck
{
    public static function run(array $envKeys): array
    {
        $results = [];

        foreach ($envKeys as $key) {
            $hasKey = array_key_exists($key, $_ENV) || array_key_exists($key, $_SERVER);

            if (! $hasKey) {
                $results[] = [
                    'status' => 'fail',
                    'message' => "❌ {$key} is missing.",
                ];

                continue;
            }

            $value = env($key);

            // consider empty only if null or empty string
            if ($value === null || $value === '') {
                $results[] = [
                    'status' => 'fail',
                    'message' => "❌ {$key} is empty.",
                ];
            } else {
                $results[] = [
                    'status' => 'pass',
                    'message' => "✅ {$key} is set",
                ];
            }
        }

        return $results;
    }
}
