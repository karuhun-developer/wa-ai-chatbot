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

function jidToPhone(string $jid): string
{
    // Remove domain part if present
    if (str_contains($jid, '@')) {
        $jid = explode('@', $jid)[0];
    }

    return normalizePhoneNumber($jid);
}

if (! function_exists('clamp')) {
    function clamp($value, $min, $max)
    {
        if ($value < $min) {
            return $min;
        }
        if ($value > $max) {
            return $max;
        }

        return $value;
    }
}
