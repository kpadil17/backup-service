<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class S3Backup extends Command
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
     * @return int
     */
    public function handle()
    {
        try {
            $date = Carbon::yesterday()->format('Y/m/d');
            $filePath = "$date/dosa_backup.zip";
            if (!Storage::disk('s3')->exists($filePath)) {
                Log::error("$filePath not found");
            } else {
                $object = Storage::disk('s3')->get($filePath);
                Storage::put('assets.zip', $object);
            }
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
        return 0;
    }
}
