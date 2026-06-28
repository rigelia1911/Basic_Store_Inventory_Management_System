<?php
function isValidEmail(string $value): bool
{
    return $value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
}

function isValidDateString(string $value): bool
{
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
    return $date instanceof DateTimeImmutable && $date->format('Y-m-d') === $value;
}

function isPositiveInteger($value): bool
{
    return filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false;
}

function isNonNegativeInteger($value): bool
{
    return filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) !== false;
}

function isPositiveNumber($value): bool
{
    return filter_var($value, FILTER_VALIDATE_FLOAT) !== false && (float) $value > 0;
}
