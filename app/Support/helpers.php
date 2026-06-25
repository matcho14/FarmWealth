<?php

if (! function_exists('format_number')) {
    function format_number($value, int $decimals = 3, string $decimalSeparator = '.', string $thousandsSeparator = ','): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        if (! is_numeric($value)) {
            return (string) $value;
        }

        $formatted = number_format((float) $value, $decimals, $decimalSeparator, $thousandsSeparator);

        if ($decimals > 0 && str_contains($formatted, $decimalSeparator)) {
            $formatted = rtrim(rtrim($formatted, '0'), $decimalSeparator);
        }

        return $formatted === '-0' ? '0' : $formatted;
    }
}
