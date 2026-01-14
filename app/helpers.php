<?php

function numberToCurrency($value)
{
    return number_format($value, 0, ',', '.');
}

function currencyToNumber($value)
{
    return (int) str_replace('.', '', $value);
}

function normalizePhoneNumber(string $phoneNumber, string $prefix = '62'): string
{
    // Remove all whitespace and special characters except + and numbers
    $phoneNumber = preg_replace('/[^\d+]/', '', $phoneNumber);

    // Replace leading 0 with prefix
    if (str_starts_with($phoneNumber, '0')) {
        return $prefix.substr($phoneNumber, 1);
    }

    // Replace leading + and keep the rest as is
    if (str_starts_with($phoneNumber, '+')) {
        return substr($phoneNumber, 1);
    }

    // If already starts with $prefix, return as is
    if (str_starts_with($phoneNumber, $prefix)) {
        return $phoneNumber;
    }

    // For any other case, add prefix
    return $prefix.$phoneNumber;
}
