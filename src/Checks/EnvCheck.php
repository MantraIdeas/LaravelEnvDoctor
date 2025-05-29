<?php

namespace Mantraideas\LaravelEnvDoctor\Checks;

class EnvCheck
{
    public static function run(array $envKeys): array
    {
        $results = [];

        foreach ($envKeys as $key) {
            $value = env($key);

            if (empty($value)) {
                $results[] = [
                    'status' => 'fail',
                    'message' => "❌ {$key} is missing or empty.",
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
