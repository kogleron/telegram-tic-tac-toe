<?php

declare(strict_types=1);


namespace App\Service;


class Printer
{
    public function print(array $field): string
    {
        $prepared = "<code>\n   A B C";
        foreach ($field as $i => $row) {
            $prepared .= "\n" . ($i + 1) . ' |' . implode('|', $row) . '|';
        }
        $prepared .= "\n</code>";

        return $prepared;
    }
}