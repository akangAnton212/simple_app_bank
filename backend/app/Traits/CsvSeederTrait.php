<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

trait CsvSeederTrait
{
    protected function seed(\SplFileObject $file, Builder $model, $map, $delimeter = ';')
    {
        DB::disableQueryLog();
        // tentukan separatornya
        $file->setCsvControl($delimeter);

        $file->setFlags(\SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

        // hitung jumlah line
        $file->seek($file->getSize());
        $line = $file->key();
        $file->seek(0);

        $this->command->getOutput()->progressStart($line);

        $chunk = [];

        while (!$file->eof()) {
            try {
                $data = $file->fgetcsv();

                if (is_null($data)) {
                    continue;
                }

                $data = array_map(function ($val) {
                    return strcasecmp($val, 'NULL') === 0 ? null : $val;
                }, $data);

                if (!$this->useChunk()) {
                    $model->insert($map($data));
                    continue;
                }

                array_push($chunk, $map($data));

                if (sizeof($chunk) == $this->chunk_size) {
                    $model->insert($chunk);
                    $chunk = [];
                }
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }

            $this->command->getOutput()->progressAdvance();
        }

        if ($this->useChunk()) {
            $model->insert($chunk); // Insert last chunk
        }

        $this->command->getOutput()->progressFinish();
    }

    protected function useChunk()
    {
        return property_exists($this, 'chunk_size') && $this->chunk_size > 0;
    }

    public $chunk_size = 100; // jumlah chunk
}
