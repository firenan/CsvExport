<?php

namespace App\Utils;

class CsvExport
{
    private $headers;
    private $cells;
    private $columns;

    public function __construct(array $headers, array $cells)
    {
        $this->cells = $cells;
        $this->headers = $headers;
        $this->columns = count($headers);

        return $this;
    }

    public function getCsv(string $delimiter = ',', string $enclosure = '"', bool $sci = false): string
    {
        $data = $this->getData();
        $out = '';
        foreach ($data as $line) {
            if (count($line) !== $this->columns) {
                return false;
            }
            $i = 0;
            foreach ($line as $item) {
                if (!$sci && preg_match("/^[0-9]{7,}/", (string)$item)) {
                    $out = $out . '=' . $enclosure . $item . $enclosure;
                } else {
                    $out = $out . $enclosure . $item . $enclosure;
                }

                if ($i === $this->columns - 1) {
                    $out = $out . "\r\n";
                } else {
                    $out = $out . $delimiter;
                }
                $i++;
            }
        }

        return $out;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getCells(): array
    {
        return $this->cells;
    }

    private function getData(): array
    {
        $line = [$this->headers];

        return array_merge($line, $this->cells);
    }
}
