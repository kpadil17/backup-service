<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupDosa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:dosa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to download dosa database backup to local server';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function __invoke() : void
    {
        ini_set('memory_limit', -1);

        $date = Carbon::now()->format('Y/m/d');

        try {
            $filePath = "PH-Manual-dumps/dumps.zip";
            if (!Storage::disk('s3')->exists($filePath)) {
                Log::error("$filePath not found");
            } else {
                Log::info("DOSA Database Backup Success");
                $object = Storage::disk('s3')->get($filePath);
                Storage::put("backups/dosa/$date/dumps.zip", $object);
            }
        } catch (\Throwable $e) {
            Log::error($e);
        }
    }
}
