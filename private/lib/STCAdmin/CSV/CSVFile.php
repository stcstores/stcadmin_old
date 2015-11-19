<?php
namespace STCAdmin\CSV;

class CSVFile {
    public function __construct($path, $filename, $header)
    {
        $this ->path = $path;
        $this->filename = $filename;
        $this->filepath = $path . '/' . $filename;
        $this->header = $header;

    }

    public function createIfNotExists()
    {
        if (!file_exists($this->path)) {
            mkdir($this->path);

        }
        if (!file_exists($this->filepath)) {
            $file = fopen($this->filepath, 'w');
            fputcsv($file, $this->header);
            fclose($file);
        }
    }

    public function openWrite()
    {
        $file = fopen($this->filepath, 'w');
        return $file;
    }

    public function openRead()
    {
        $file = fopen($this->filepath, 'r');
        return $file;
    }

    public function addline($newline)
    {
        $this->createIfNotExists();
        $file = $this->openRead();

        $existingLines = array();
        while (($line = fgetcsv($file)) !== false ) {
            $existingLines[] = $line;
        }
        fclose($file);
        $file = $this->openWrite();
        //fputcsv($file, $this->header);
        foreach ($existingLines as $line) {
            fputcsv($file, $line);
        }
        fputcsv($file, $newline);
        fclose($file);
    }

    public function read()
    {
        $file = $this->openRead();
        $table = array();
        foreach ($this->header as $head) {
            $table[$head] = array();
        }
        while (($line = fgetcsv($file)) !== false ) {
            foreach ($line as $row => $cell) {
                $table[$this->header[$row]][] = $cell;
            }
        }

        return $table;
    }
}
