<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Plan;
class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $p1 = Package::create(['name'=>'FREE','price'=>0]);
        $p2 = Package::create(['name'=>'PAYG','price'=>10]);
        $p3 = Package::create(['name'=>'UNLIMITED','price'=>50]);

        Plan::create(['name'=>'bundle_limit','package_id'=>$p1->id]);
        Plan::create(['name'=>'page_limit','package_id'=>$p1->id]);
        Plan::create(['name'=>'storage_validity','package_id'=>$p1->id]);
        Plan::create(['name'=>'default_watermark','package_id'=>$p1->id]);
        Plan::create(['name'=>'own_watermark','package_id'=>$p1->id]);

        Plan::create(['name'=>'bundle_limit','package_id'=>$p2->id]);
        Plan::create(['name'=>'page_limit','package_id'=>$p2->id]);
        Plan::create(['name'=>'storage_validity','package_id'=>$p2->id]);
        Plan::create(['name'=>'default_watermark','package_id'=>$p2->id]);
        Plan::create(['name'=>'own_watermark','package_id'=>$p2->id]);

        Plan::create(['name'=>'bundle_limit','package_id'=>$p3->id]);
        Plan::create(['name'=>'page_limit','package_id'=>$p3->id]);
        Plan::create(['name'=>'storage_validity','package_id'=>$p3->id]);
        Plan::create(['name'=>'default_watermark','package_id'=>$p3->id]);
        Plan::create(['name'=>'own_watermark','package_id'=>$p3->id]);

    }
}
