<?php

$statesFile = __DIR__ . '/database/data/states.json';
$outputFile = __DIR__ . '/database/data/states_arab.json';

$arabCountries = ['SA', 'EG', 'AE', 'JO', 'KW', 'QA', 'OM', 'BH', 'LY', 'YE', 'SY', 'IQ', 'LB', 'SD', 'DZ', 'MA', 'TN', 'PS', 'MR'];

$statesJson = file_get_contents($statesFile);
$states = json_decode($statesJson, true);

$filteredStates = [];

foreach ($states as $state) {
    $countryCode = $state['country_code'] ?? null;
    if ($countryCode && in_array($countryCode, $arabCountries)) {
        $filteredStates[] = $state;
    }
}

file_put_contents($outputFile, json_encode($filteredStates, JSON_PRETTY_PRINT));

echo "Created File Arab Republic successfully" . $outputFile . PHP_EOL;
