<?php

$build = [
    'version' => '3.7',
    'services' => [],
];

$config = json_decode_file(__DIR__ . '/builder.cfg.json');
$nodeSample = yaml_parse_file(__DIR__ . '/docker-compose.yml');

echo "<pre>";
print_r($nodeSample);

for ($i = 0; $i < $config->maternity_hospital_nodes; $i++) {
    foreach ($nodeSample['services'] as $serviceName => $service) {
        if (!is_need_serial_number_in($serviceName)) {
            continue;
        }

        $build['services']["{$serviceName}_{$i}"] = serviceKeyIdentifierRecursive($service, $i);
    }
}

yaml_emit_file(__DIR__ . '/build.yml', $build);

echo file_get_contents(__DIR__ . '/build.yml');

// FUNCTIONS

function serviceKeyIdentifierRecursive(array $service, int $id): array
{
    $identifiedService = [];

    foreach ($service as $attribute => $value) {
        $key = is_need_serial_number_in($attribute) ? "{$attribute}_{$id}" : $attribute;
        $identifiedService[$key] =
            is_array($value)
                ? serviceKeyIdentifierRecursive($value, $id)
                : serviceValueIdentifier($value, $id);
    }

    return $identifiedService;
}

function serviceValueIdentifier(string $value, int $id): string
{
    if (is_need_serial_number_in($value)) {
        preg_match('/([a-z]|_[a-z])*/m', $value, $matches);
        print_r($matches);
        return str_replace($matches[0], "{$matches[0]}_{$id}", $value);
    }

    return $value;
}

function is_need_serial_number_in(string $str): bool
{
    return str_starts_with($str, 'php_')
        || str_starts_with($str, 'mysql_');
}

function json_decode_file(string $filePathName)
{
    return json_decode(file_get_contents($filePathName));
}

//function build(array)
