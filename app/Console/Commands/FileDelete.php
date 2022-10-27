<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
class FileDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Files automatically';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = DB::table('files')->where('auto_deleted_at', Carbon::now()->format('Y-m-d'))->get();
        foreach($files as $file)
        {

            unlink(public_path('pdf/'.$file->filename));
            DB::table('files')->where('id',$file->id)->delete();
        }
        $generated_pdf = DB::table('generated_bundle')->whereDate('auto_deleted_at', Carbon::now()->format('Y-m-d'))->get();
        foreach($generated_pdf as $generated)
        {
            $bundle = DB::table('bundles')->where('id',$generated->bundle_id)->first();
            unlink(public_path('bundle_zip/'.$bundle->name.'.zip'));
            unlink(public_path('generated_pdf/'.$generated->filename));
            DB::table('generated_bundle')->where('id',$generated->id)->delete();
        }

    }
}
