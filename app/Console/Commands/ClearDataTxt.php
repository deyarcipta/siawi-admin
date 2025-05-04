<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearDataTxt extends Command
{
    protected $signature = 'clear:data-txt';
    protected $description = 'Clear the contents of data.txt in storage/logs directory';

    public function handle()
    {
        $path = storage_path('logs/data.txt');

        if (file_exists($path)) {
            file_put_contents($path, ''); // kosongkan isi file
            $this->info('data.txt content cleared successfully.');
        } else {
            $this->warn('data.txt file not found.');
        }

        return 0;
    }
}
