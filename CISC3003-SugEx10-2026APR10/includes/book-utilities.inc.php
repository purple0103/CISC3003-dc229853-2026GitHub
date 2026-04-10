<?php

function normalizeTextEncoding($text)
{
    if ($text === '' || preg_match('//u', $text)) {
        return $text;
    }

    $encodings = ['Windows-1250', 'Windows-1252', 'ISO-8859-2'];

    foreach ($encodings as $encoding) {
        $converted = @iconv($encoding, 'UTF-8//IGNORE', $text);
        if ($converted !== false && $converted !== '') {
            return $converted;
        }
    }

    return $text;
}

function readCustomers($filename)
{
    if (!is_readable($filename)) {
        return [];
    }

    $customers = [];
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = normalizeTextEncoding($line);
        $parts = explode(';', trim($line));
        if (count($parts) < 12) {
            continue;
        }

        $sales = array_map(
            static function ($value) {
                return (int) trim($value);
            },
            explode(',', $parts[11])
        );

        $customers[] = [
            'id' => (int) trim($parts[0]),
            'first_name' => trim($parts[1]),
            'last_name' => trim($parts[2]),
            'full_name' => trim($parts[1] . ' ' . $parts[2]),
            'email' => trim($parts[3]),
            'university' => trim($parts[4]),
            'address' => trim($parts[5]),
            'city' => trim($parts[6]),
            'state' => trim($parts[7]),
            'country' => trim($parts[8]),
            'postal' => trim($parts[9]),
            'phone' => trim($parts[10]),
            'sales_raw' => implode(',', $sales),
            'sales' => $sales,
        ];
    }

    return $customers;
}

function readOrders($customer, $filename)
{
    if (!is_readable($filename)) {
        return [];
    }

    $customerId = is_array($customer) ? ($customer['id'] ?? null) : $customer;
    if ($customerId === null || $customerId === '') {
        return [];
    }

    $orders = [];
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = normalizeTextEncoding($line);
        $parts = array_map('trim', explode(',', trim($line)));
        if (count($parts) < 5) {
            continue;
        }

        $orderId = (int) array_shift($parts);
        $lineCustomerId = (int) array_shift($parts);
        $isbn = trim((string) array_shift($parts));
        $category = trim((string) array_pop($parts));
        $title = trim(implode(', ', $parts));

        if ((int) $customerId !== $lineCustomerId) {
            continue;
        }

        $coverRelativePath = 'images/tinysquare/' . $isbn . '.jpg';
        $coverAbsolutePath = dirname(__DIR__) . '/' . $coverRelativePath;

        $orders[] = [
            'order_id' => $orderId,
            'customer_id' => $lineCustomerId,
            'isbn' => $isbn,
            'title' => $title,
            'category' => $category,
            'cover' => is_file($coverAbsolutePath) ? $coverRelativePath : 'images/tinysquare/missing.jpg',
        ];
    }

    return $orders;
}
?>
