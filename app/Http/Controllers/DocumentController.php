<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\File;
use App\Models\Section;
use App\Models\generatedTable;
use App\Models\Setting;
use App\Models\Bundle;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Carbon\Carbon;
use ZipArchive;
use Image;
use NPDF;
use Auth;
use Storage;
use File as Files;
use Session;
use Exception;
use MPDF;
use setasign\Fpdi\Fpdi;
use App\Http\Helpers\PPDF;
use App\Http\Helpers\TPDF;
use RealRashid\SweetAlert\Facades\Alert;
class DocumentController extends Controller
{

    public function delete($id)
    {
        $file = File::where('id',$id);
        if($file->count() > 0)
        {
            $file = $file->first();
            if(file_exists(public_path("pdf/".$file->filename))){
                unlink(public_path('pdf/'.$file->filename));
            }
            $file->delete();
            Alert::success('Deleted', 'File Deleted Successfully');
            return redirect()->back();
        }
    }
    public function show($bundle_id,$section_id,$id)
    {
        $file = File::with(['section','bundle'])->where('id',$id);
        if($file->count() > 0)
        {
            $file = $file->first();
            return view('backend.pages.bundle.files.show',['file'=>$file,'bundle_id'=>$bundle_id,'section_id'=>$section_id,'file_id'=>$id]);
        }
        return redirect()->back();
    }
    public function updateOrder(Request $request){
        if($request->has('ids')){
            $arr = explode(',',$request->input('ids'));

            foreach($arr as $sortOrder => $id){
               File::where('id',$id)->update(['sort_id'=>$sortOrder]);
            }
            return ['success'=>true,'message'=>'Updated'];
        }
    }
    
     public function packages()
    {
        $enrolled_package = auth()
        ->user()
        ->load('enrolledPackage')->enrolledPackage;
          if($enrolled_package->package_id == 1){
                 $admin_setting = Setting::where(['type'=>"admin",'name'=>"watermark_setting"])->first();
                if(!is_null($admin_setting) && $admin_setting->value == 1){
                    $settings = Setting::where(['name'=>"watermark",'user_id'=>"1"])->first();
                    if(!is_null($settings)){
                        if($settings->type == "IMG")
                        {
                            return $config = ['instanceConfigurator' => function($pdf) use ($settings) {
                                $pdf->SetWatermarkImage(public_path('watermark/'.$settings->value));
                                $pdf->showWatermarkImage  = true;
                            }];
                        }else{
                            return $config = ['instanceConfigurator' => function($pdf) use ($settings) {
                                $pdf->SetWatermarkText($settings->value);
                                $pdf->showWatermarkText  = true;
                            }];
                        }

                    }else{
                        return $config = ['instanceConfigurator' => function($pdf) use ($settings) {
                                // $pdf->SetWatermarkText(env('APP_NAME'));
                                $pdf->SetWatermarkText('');
                                $pdf->showWatermarkText  = true;
                            }];
                    }
                }else{
                    return $config = ['instanceConfigurator' => function($pdf) {
                                // $pdf->SetWatermarkText(env('APP_NAME'));
                                $pdf->SetWatermarkText('');
                                $pdf->showWatermarkText  = true;
                            }];
                }
            }elseif($enrolled_package->package_id == 3){
                $settings_watermark = Setting::where(['user_id'=>auth()->user()->id,'name'=>"watermark_setting"]);
                if($settings_watermark->count() > 0)
                {
                    $settings_watermark = $settings_watermark->first();
                }else{
                    $settings_watermark->value = 0;
                }
                if($settings_watermark->value == 1)
                {
                    $settings = Setting::where(['user_id'=>auth()->user()->id,'name'=>"watermark"])->first();
                    if(!is_null($settings)){
                        $text = $settings->value;
                    }else{
                        // $text =env('APP_NAME');
                        $text ='';
                    }
                    if(!is_null($settings)){
                        if($settings->type == "TEXT"){
                            return $config = ['instanceConfigurator' => function($pdf) use ($settings) {
                                $pdf->SetWatermarkText($settings->value);
                                $pdf->showWatermarkText  = true;
                            }];
                        }else{
                            return $config = ['instanceConfigurator' => function($pdf) use ($settings) {
                                $pdf->SetWatermarkImage(public_path('watermark/'.$settings->value));
                                $pdf->showWatermarkImage  = true;
                            }];
                        }
                    }else{
                       return $config = ['instanceConfigurator' => function($pdf) {
                                // $pdf->SetWatermarkText(env('APP_NAME'));
                                $pdf->SetWatermarkText('');
                                $pdf->showWatermarkText  = false;
                            }];

                    }
                }else{
                    return $config = ['instanceConfigurator' => function($pdf) {
                        // $pdf->SetWatermarkText(env('APP_NAME'));
                        $pdf->SetWatermarkText('');
                        $pdf->showWatermarkText  = false;
                    }];
                }
            }else{
                  return $config = ['instanceConfigurator' => function($pdf) {
                        // $pdf->SetWatermarkText(env('APP_NAME'));
                        $pdf->SetWatermarkText('');
                        $pdf->showWatermarkText  = false;
                    }];
            }
    }

    public function rename(Request $request)
    {
        $name = $request->name;
        $id = $request->file_id;
        $file = File::where('id',$id);
        if($file->count() == 0){
            return abort(404);
        }
        File::where('id',$id)->update(['name'=>$name]);
        $file =$file->first();
        Alert::success('Updated', 'File renamed Successfully');
        return redirect()->route('section.show', [$file->section_id]);
    }

    public function update(Request $request)
    {
        if (!file_exists(storage_path('app/public/files'))) {
            mkdir(storage_path('app/public/files'), 0777, true);
        }
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
       $filename =$file->storeAs('public/files', $filename);
        $bundle_id= $request->bundle_id;
        $section_id= $request->section_id;
        $file_id= $request->file_id;
        $filess = File::where('id',$file_id);
        if($filess->count() > 0){
            $filess = $filess->first();
            unlink(public_path('pdf/'.$filess->filename));
            $filename = explode('/',$filename);
            $splitName = explode('.',  $filename[2]);

            if($extension == "docx" || $extension == "doc" || $extension == "dot")
            {
                $domPdfPath = base_path('vendor/dompdf/dompdf');
                \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
                \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

                //Load word file
                $Content = \PhpOffice\PhpWord\IOFactory::load(storage_path("app/public/files/".$filename[2]));

                //Save it into PDF
                 $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content,'HTML');
             if (!file_exists(public_path('../resources/views/pdf'))) {
                mkdir(public_path('../resources/views/pdf'), 0777, true);
                }
                $PDFWriter->save(public_path('../resources/views/pdf/'. $splitName[0].'.blade.php'));

                $view['view'] = view('pdf.'.$splitName[0])->render();
                $view['view'] = str_replace("PHPWord","",$view['view']);

                $pdf = MPDF::loadHtml(view('newDocsPdf', $view),$this->packages());

                $pdf->save(public_path('pdf/'.$splitName[0].'.pdf'));
                $sourcePath=public_path('pdf/'.$splitName[0].'.pdf');
                $sec = Section::where('id',$section_id)->first();
                $totalPage = File::where('section_id',$section_id)->sum('totalPage');
                $totalPage = $totalPage+1;
                $mpdf = new \Mpdf\Mpdf();
                $pagecount = $mpdf->SetSourceFile($sourcePath);
                $max_page_count = $totalPage+$pagecount;
                for($i=1;$i<=$pagecount;$i++){
                        $mpdf->AddPage('');
                        $import_page = $mpdf->ImportPage($i);
                        $mpdf->UseTemplate($import_page,10, 10, 190, 270);


                }
                $mpdf->output($sourcePath,\Mpdf\Output\Destination::FILE);
                unlink(storage_path("app/public/files/".$filename[2]));
                unlink(public_path("../resources/views/pdf/".$splitName[0].'.blade.php'));

            }else if($extension == "jpe" || $extension == "jpeg" || $extension == "gif"  || $extension == "png"  || $extension== "JPG" || $extension == "jpg"  || $extension== "JPEG"  || $extension== "PNG" || $extension == "GIF")
            {
                $data['image'] = [$filename[2]];
                $pdf->save(public_path('pdf/'.$splitName[0].'.pdf'));
                $sourcePath=public_path('pdf/'.$splitName[0].'.pdf');
                $sec = Section::where('id',$section_id)->first();
                $totalPage = File::where('section_id',$section_id)->sum('totalPage');
                $totalPage = $totalPage+1;
                $mpdf = new \Mpdf\Mpdf();
                $pagecount = $mpdf->SetSourceFile($sourcePath);
                for($i=1;$i<=$pagecount;$i++){
                        $mpdf->AddPage('');
                        $import_page = $mpdf->ImportPage($i);
                        $mpdf->UseTemplate($import_page,10, 10, 190, 270);

                }
                $mpdf->output($sourcePath,\Mpdf\Output\Destination::FILE);
                unlink(storage_path("app/public/files/".$filename[2]));
            }else{

                $enrolled_package = auth()
                ->user()
                ->load('enrolledPackage')->enrolledPackage;
                $sourcePath=storage_path("app/public/files/".$filename[2]);
                $mpdf = new \Mpdf\Mpdf();

                // Specify a PDF template
                $pagecount = $mpdf->SetSourceFile($sourcePath);

                for($i=1;$i<=$pagecount;$i++){
                    $mpdf->AddPage('');
                    $import_page = $mpdf->ImportPage($i);
                    $mpdf->UseTemplate($import_page,10, 10, 190, 270);



                    if($enrolled_package->package_id == 1){
                        $admin_setting = Setting::where(['type'=>"admin",'name'=>"watermark_setting"])->first();
                        if(!is_null($admin_setting) && $admin_setting->value == 1){
                            $settings = Setting::where(['name'=>"watermark",'user_id'=>"1"])->first();
                            if(!is_null($settings)){
                                if($settings->type == "IMG")
                                {
                                    $mpdf->SetWatermarkImage(public_path('watermark/'.$settings->value));
                                    $mpdf->showWatermarkImage  = true;
                                }else{
                                    $mpdf->SetWatermarkText($settings->value);
                                    $mpdf->showWatermarkText  = true;
                                }

                            }else{
                                // $mpdf->SetWatermarkText(env('APP_NAME'));
                                $mpdf->SetWatermarkText('');
                                $mpdf->showWatermarkText  = true;
                            }
                        }else{
                            // $mpdf->SetWatermarkText(env('APP_NAME'));
                            $mpdf->SetWatermarkText('');
                            $mpdf->showWatermarkText  = true;
                        }
                    }elseif($enrolled_package->package_id == 3){
                        $settings_watermark = Setting::where(['user_id'=>auth()->user()->id,'name'=>"watermark_setting"]);
                        if($settings_watermark->count() > 0)
                        {
                            $settings_watermark = $settings_watermark->first();
                        }else{
                            $settings_watermark->value = 0;
                        }
                        if($settings_watermark->value == 1)
                        {
                            $settings = Setting::where(['user_id'=>auth()->user()->id,'name'=>"watermark"])->first();
                            if(!is_null($settings)){
                                $text = $settings->value;
                            }else{
                                // $text =env('APP_NAME');
                                $text ='';
                            }
                            if(!is_null($settings)){
                                if($settings->type == "TEXT"){
                                    $mpdf->SetWatermarkText($settings->value);
                                    $mpdf->showWatermarkText  = true;
                                }else{
                                    $mpdf->SetWatermarkImage(public_path('watermark/'.$settings->value));
                                    $mpdf->showWatermarkImage  = true;
                                }
                            }else{
                                // $mpdf->SetWatermarkText(env('APP_NAME'));
                                $mpdf->SetWatermarkText('');
                                $mpdf->showWatermarkText  = false;

                            }
                        }else{
                            // $mpdf->SetWatermarkText(env('APP_NAME'));
                            $mpdf->SetWatermarkText('');
                            $mpdf->showWatermarkText  = true;
                        }
                    }else{
                        // $mpdf->SetWatermarkText(env('APP_NAME'));
                        $mpdf->SetWatermarkText('');
                        $mpdf->showWatermarkText  = false;
                    }

                }


                $mpdf->output($sourcePath,\Mpdf\Output\Destination::FILE);
                $destinationPath=public_path('pdf/'.$filename[2]);
                if(Files::exists($sourcePath)){
                    Files::move($sourcePath,$destinationPath);
                }



                }
                $path = public_path('pdf/'.$splitName[0].'.pdf');
                $totalPage = $this->countPages($path);
                $enrolled_package = auth()
                            ->user()
                          ->load('enrolledPackage')->enrolledPackage;
            if($enrolled_package->package_id == 1)
            {
                $days_after_file_delete = 100;
            }elseif($enrolled_package->package_id == 2)
            {
                $days_after_file_delete = 730;
            }else{
                $days_after_file_delete = 1095;
            }
            $auto_delete_date = Carbon::now()->addDays($days_after_file_delete)->format('Y-m-d');
            $filess = File::where("id",$file_id)->orderBy("sort_id",'desc')->first();
            if(!is_null($filess))
            {
                $sort_id = $filess->sort_id+1;
            }else{
                $sort_id = 1;
            }
            File::where('id',$file_id)->update(['filename'=>$splitName[0].'.pdf','name'=>$splitName[0],'sort_id'=>$sort_id,'auto_deleted_at'=>$auto_delete_date, 'totalPage'=>$totalPage,'mime_types'=>$splitName[1], 'user_id'=>auth()->user()->id,'bundle_id'=>$bundle_id,'section_id'=>$section_id]);
            Alert::success('Updated', 'File Updated Successfully');
            return response()->json(['success'=>$splitName[0].'.pdf']);

        }
    }

    public function uploadDocuments(Request $request)
    {
        // LIMIT OF 5000000  characters
        ini_set("pcre.backtrack_limit", "5000000");

        if (!file_exists(storage_path('app/public/files'))) {
            mkdir(storage_path('app/public/files'), 0777, true);
        }
        if (!file_exists(public_path('pdf'))) {
            mkdir(public_path('pdf'), 0777, true);
        }

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename =$file->storeAs('public/files', $filename);
        $bundle_id= $request->bundle_id;
        $section_id= $request->section_id;
        $filename = explode('/',$filename);
        $splitName = explode('.',  $filename[2]);
        $file_namess = uniqid();
        $enrolled_package = auth()
            ->user()
            ->load('enrolledPackage')->enrolledPackage;
        if($extension == "docx" || $extension == "doc" || $extension == "dot")
        {
            try{


            $domPdfPath = base_path('vendor/dompdf/dompdf');
            \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
            \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

            //Load word file
            $Content = \PhpOffice\PhpWord\IOFactory::load(storage_path("app/public/files/".$filename[2]));


            //Save it into PDF
            $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content,'HTML');
            if (!file_exists(public_path('../resources/views/pdf'))) {
                mkdir(public_path('../resources/views/pdf'), 0777, true);
            }
            $PDFWriter->save(public_path('../resources/views/pdf/'. $splitName[0].'.blade.php'));

            $view['view'] = view('pdf.'.$splitName[0])->render();
            $view['view'] = str_replace("PHPWord","",$view['view']);

            $pdf = MPDF::loadHtml(view('newDocsPdf', $view),$this->packages());

            $pdf->save(public_path('pdf/'.$file_namess.'.pdf'));
            $sourcePath=public_path('pdf/'.$file_namess.'.pdf');
            $sec = Section::where('id',$section_id)->first();
            $totalPage = File::where('section_id',$section_id)->sum('totalPage');
            $totalPage = $totalPage+1;
            $mpdf = new \Mpdf\Mpdf();
            $pagecount = $mpdf->SetSourceFile($sourcePath);
            $max_page_count = $totalPage+$pagecount;


            $files = File::where('bundle_id',$bundle_id)->sum('totalPage');
            if($enrolled_package->package_id == 1)
            {
                if($files == 60)
                {
                    unlink(storage_path("app/public/files/".$filename[2]));
                    Alert::error('Failed', "You have reached your plan limit. please upgrade!")->autoClose(10000);
                    return response()->json(['status'=>"failed", "msg"=>"You have reached your plan limit. please upgrade!"]);
                }
                $total_page_used = $files+$pagecount;
                $max_page_can_upload = 60 - $files;
                $extra_add_page = $max_page_can_upload -$pagecount;
                if($extra_add_page < 0)
                {
                    Alert::error('Failed', "You are crossing your plan limits.page can be added $max_page_can_upload. uploaded files has $pagecount pages. please upgrade your plan!")->autoClose(10000);

                    unlink(storage_path("app/public/files/".$filename[2]));
                    return response()->json(['status'=>"failed", "msg"=>"You are crossing your plan limits. please upgrade!"]);
                }
            }
            for($i=1;$i<=$pagecount;$i++){
                $mpdf->AddPage('');
                $import_page = $mpdf->ImportPage($i);
                    $mpdf->UseTemplate($import_page,10, 10, 190, 270);


            }
            $mpdf->output($sourcePath,\Mpdf\Output\Destination::FILE);
            unlink(storage_path("app/public/files/".$filename[2]));
            unlink(public_path("../resources/views/pdf/".$splitName[0].'.blade.php'));
            }catch(\Exception $e){
                return response()->json(['error'=>"Can't upload this file please change check the format"],400);
            }

        }else if($extension == "jpg" || $extension == "jpeg" || $extension == "gif"  || $extension == "png"  || $extension == "JPG" || $extension == "jpg"  || $extension == "JPEG"  || $extension == "PNG" || $extension == "GIF")
        {
            try{
            $image = [$filename[2]];
            $pdf = MPDF::loadHtml(view('imgPdf', compact('image')),$this->packages());
            $pdf->save(public_path('pdf/'.$file_namess.'.pdf'));

             $sourcePath=public_path('pdf/'.$file_namess.'.pdf');
            $sec = Section::where('id',$section_id)->first();
            $totalPage = File::where('section_id',$section_id)->sum('totalPage');
            $totalPage = $totalPage+1;
            $mpdf = new \Mpdf\Mpdf();
            $pagecount = $mpdf->SetSourceFile($sourcePath);
            $files = File::where('bundle_id',$bundle_id)->sum('totalPage');
            if($enrolled_package->package_id == 1)
            {
                if($files == 60)
                {
                    unlink(storage_path("app/public/files/".$filename[2]));
                    Alert::error('Failed', "You have reached your plan limit. please upgrade!")->autoClose(10000);
                    return response()->json(['status'=>"failed", "msg"=>"You have reached your plan limit. please upgrade!"]);
                }
                $total_page_used = $files+$pagecount;
                $max_page_can_upload = 60 - $files;
                $extra_add_page = $max_page_can_upload -$pagecount;
                if($extra_add_page < 0)
                {
                    Alert::error('Failed', "You are crossing your plan limits.page can be added $max_page_can_upload. uploaded files has $pagecount pages. please upgrade your plan!")->autoClose(10000);

                    unlink(storage_path("app/public/files/".$filename[2]));
                    return response()->json(['status'=>"failed", "msg"=>"You are crossing your plan limits. please upgrade!"]);
                }
            }
            for($i=1;$i<=$pagecount;$i++){
                    $mpdf->AddPage('');
                    $import_page = $mpdf->ImportPage($i);
                    $mpdf->UseTemplate($import_page,10, 10, 190, 270);

             }
            $mpdf->output($sourcePath,\Mpdf\Output\Destination::FILE);
            unlink(storage_path("app/public/files/".$filename[2]));
            }catch(\Exception $e){
                return response()->json(['error'=>"Can't upload this file please change check the format"],400);
            }
        }else{

            try{

            $enrolled_package = auth()
            ->user()
            ->load('enrolledPackage')->enrolledPackage;
            $sourcePath=storage_path("app/public/files/".$filename[2]);
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode(50);
            $sec = Section::where('id',$section_id)->first();

            $totalPage = File::where('section_id',$section_id)->sum('totalPage');
            $totalPage = $totalPage+1;
            // Specify a PDF template
            $pagecount = $mpdf->SetSourceFile($sourcePath);
            $files = File::where('bundle_id',$bundle_id)->sum('totalPage');
            if($enrolled_package->package_id == 1)
            {
                if($files == 60)
                {
                    unlink(storage_path("app/public/files/".$filename[2]));
                    Alert::error('Failed', "You have reached your plan limit. please upgrade!")->autoClose(10000);
                    return response()->json(['status'=>"failed", "msg"=>"You have reached your plan limit. please upgrade!"]);
                }
                $total_page_used = $files+$pagecount;
                $max_page_can_upload = 60 - $files;
                $extra_add_page = $max_page_can_upload -$pagecount;
                if($extra_add_page < 0)
                {
                    Alert::error('Failed', "You are crossing your plan limits.page can be added $max_page_can_upload. uploaded files has $pagecount pages. please upgrade your plan!")->autoClose(10000);

                    unlink(storage_path("app/public/files/".$filename[2]));
                    return response()->json(['status'=>"failed", "msg"=>"You are crossing your plan limits. please upgrade!"]);
                }
            }

            for($i=1;$i<=$pagecount;$i++){
                    $mpdf->AddPage('');
                    $import_page = $mpdf->ImportPage($i);
                    $mpdf->UseTemplate($import_page,10, 10, 190, 270);


                    if($enrolled_package->package_id == 1){
                        $admin_setting = Setting::where(['type'=>"admin",'name'=>"watermark_setting"])->first();
                        if(!is_null($admin_setting) && $admin_setting->value == 1){
                            $settings = Setting::where(['name'=>"watermark",'user_id'=>"1"])->first();
                            if(!is_null($settings)){
                                if($settings->type == "IMG")
                                {
                                    $mpdf->SetWatermarkImage(public_path('watermark/'.$settings->value));
                                    $mpdf->showWatermarkImage  = true;
                                }else{
                                    $mpdf->SetWatermarkText($settings->value);
                                    $mpdf->showWatermarkText  = true;
                                }

                            }else{
                                // $mpdf->SetWatermarkText(env('APP_NAME'));
                                $mpdf->SetWatermarkText('');
                                $mpdf->showWatermarkText  = true;
                            }
                        }else{
                            // $mpdf->SetWatermarkText(env('APP_NAME'));
                            $mpdf->SetWatermarkText('');
                            $mpdf->showWatermarkText  = true;
                        }
                    }elseif($enrolled_package->package_id == 3){
                        $settings_watermark = Setting::where(['user_id'=>auth()->user()->id,'name'=>"watermark_setting"]);
                        if($settings_watermark->count() > 0)
                        {
                            $settings_watermark = $settings_watermark->first();
                        }else{
                            $settings_watermark->value = 0;
                        }
                        if($settings_watermark->value == 1)
                        {
                            $settings = Setting::where(['user_id'=>auth()->user()->id,'name'=>"watermark"])->first();
                            if(!is_null($settings)){
                                $text = $settings->value;
                            }else{
                                // $text =env('APP_NAME');
                                $text ='';
                            }
                            if(!is_null($settings)){
                                if($settings->type == "TEXT"){
                                    $mpdf->SetWatermarkText($settings->value);
                                    $mpdf->showWatermarkText  = true;
                                }else{
                                    $mpdf->SetWatermarkImage(public_path('watermark/'.$settings->value));
                                    $mpdf->showWatermarkImage  = true;
                                }
                            }else{
                                // $mpdf->SetWatermarkText(env('APP_NAME'));
                                $mpdf->SetWatermarkText('');
                                $mpdf->showWatermarkText  = false;

                            }
                        }else{
                            // $mpdf->SetWatermarkText(env('APP_NAME'));
                            $mpdf->SetWatermarkText('');
                            $mpdf->showWatermarkText  = true;
                        }
                    }else{
                        // $mpdf->SetWatermarkText(env('APP_NAME'));
                        $mpdf->SetWatermarkText('');
                        $mpdf->showWatermarkText  = false;
                    }

            }
            $mpdf->output($sourcePath,\Mpdf\Output\Destination::FILE);
            $destinationPath=public_path('pdf/'.$file_namess.'.pdf');
            if(Files::exists($sourcePath)){
                Files::move($sourcePath,$destinationPath);
            }
            }catch(\Exception $e){
                return response()->json(['error'=>"Can't upload this file please change check the format"],400);
            }
        }
            $path = public_path('pdf/'.$file_namess.'.pdf');
            $totalPage = $this->countPages($path);
            $enrolled_package = auth()
                            ->user()
                            ->load('enrolledPackage')->enrolledPackage;
            if($enrolled_package->package_id == 1)
            {
                $days_after_file_delete = 100;
            }elseif($enrolled_package->package_id == 2)
            {
                $days_after_file_delete = 730;
            }else{
                $days_after_file_delete = 1095;
            }
            $auto_delete_date = Carbon::now()->addDays($days_after_file_delete)->format('Y-m-d');
            $filess = File::where("section_id",$section_id)->orderBy("sort_id",'desc')->first();
            if(!is_null($filess))
            {
                $sort_id = $filess->sort_id+1;
            }else{
                $sort_id = 1;
            }
        File::create(['filename'=>$file_namess.'.pdf','sort_id'=>$sort_id,"name"=>$splitName[0],'auto_deleted_at'=>$auto_delete_date,'totalPage'=>$totalPage,'mime_types'=>$splitName[1], 'user_id'=>auth()->user()->id,'bundle_id'=>$bundle_id,'section_id'=>$section_id]);
        Alert::success('Uploaded', 'File Uploaded Successfully');
        return response()->json(['success'=>$splitName[0].'.pdf']);
    }

    public function create($bundle_id,$section_id)
    {
        $user = Auth::user();
        $section = Section::with(['bundle'])->where('user_id',auth()->user()->id)->where(['bundle_id'=>$bundle_id,'id'=>$section_id])->first();

        if ($user->isAdmin()) {
            return view('backend.pages.dashboard');
        }
        return view('backend.pages.bundle.files.create',['section'=>$section,'bundle_id'=>$bundle_id,'section_id'=>$section_id]);
    }

    public function generate($bundle_id)
    {
        $files = File::where(["user_id"=>auth()->user()->id,'bundle_id'=>$bundle_id])->get();
        $sections = Section::with('files')->where('bundle_id',$bundle_id)->orderBy('sort_id','ASC')->get();

        //add page number
        // $this->pdfPageNumbering($files);

        $pdf = PDFMerger::init();
        $msourceArray = [];
        foreach($sections as $sec)
        {

            if (!file_exists(public_path('pdf'))) {
                mkdir(public_path('pdf'), 0777, true);
            }
            if($sec->isDefault == 1){
                if($sec->isMainSection == 1)
                {
                    $cpdf = MPDF::loadHtml(view('MainindexPdf', compact('sec')),$this->packages());
                    $output=$cpdf->output();
                    file_put_contents('pdf/section'.$sec->id.'.pdf', $output);
                    $pdf->addPDF(public_path('pdf/section'.$sec->id.'.pdf'), 'all');
                }else{
                    if($sec->name == "Index")
                    {
                        $allsections = Section::with('files')->where('bundle_id',$bundle_id)->orderBy('sort_id','ASC')->get();
                        $heading = "INDEX";
                        $cpdf = MPDF::loadHtml(view('indexAllPdf', compact('allsections','heading')),$this->packages());
                        $output=$cpdf->output();
                        file_put_contents('pdf/section'.$sec->id.'.pdf', $output);
                        $pdf->addPDF(public_path('pdf/section'.$sec->id.'.pdf'), 'all');
                    }else{
                        $allsections = Section::with('files')->where('id',$sec->id)->orderBy('sort_id','ASC')->get();
                        $heading = $sec->name ."<br> INDEX";
                        $cpdf = MPDF::loadHtml(view('indexAllPdf', compact('allsections','heading')),$this->packages());
                        $output=$cpdf->output();
                        file_put_contents('pdf/section'.$sec->id.'.pdf', $output);
                        $pdf->addPDF(public_path('pdf/section'.$sec->id.'.pdf'), 'all');
                    }
                }
            }else{
                    $allsections = Section::with('files')->where('id',$sec->id)->orderBy('sort_id','ASC')->get();
                    $heading = $sec->name ."<br> INDEX";
                    $cpdf = MPDF::loadHtml(view('indexAllPdf', compact('allsections','heading')),$this->packages());
                    $output=$cpdf->output();
                    file_put_contents('pdf/section'.$sec->id.'.pdf', $output);
                    $pdf->addPDF(public_path('pdf/section'.$sec->id.'.pdf'), 'all');
            }

            foreach($sec->files as $f){

                $sourcePath=public_path("pdf/".$f->filename);
                $mpdf = new \Mpdf\Mpdf();
                $mpdf->SetDisplayMode(50);
                $sect = Section::where('id',$sec->id)->first();
                $totalPage = File::where('section_id',$sect->id)->sum('gSerial');
                $totalPage = $totalPage+1;
                // Specify a PDF template
                $pagecount = $mpdf->SetSourceFile($sourcePath);
                $files = File::where('bundle_id',$sect->bundle_id)->sum('totalPage');
                for($i=1;$i<=$pagecount;$i++){
                    $mpdf->AddPage('','NEXT-ODD',intval($totalPage++),'1','off');
                    $import_page = $mpdf->ImportPage($i);
                    $mpdf->UseTemplate($import_page,10, 10, 190, 270);
                    $mpdf->setFooter( ['odd' => array (
                    'R' => array (
                        'content' => $sect->serial_alpha.'{PAGENO}',
                        'font-size' => 10,
                        'font-style' => 'B',
                        'font-family' => 'serif',
                        'color'=>'red'
                    ),
                    'line' => 2,
                    ),
                    'even' => array ( 'R' => array (
                        'content' => $sec->serial_alpha.'{PAGENO}',
                        'font-size' => 10,
                        'font-style' => 'B',
                        'font-family' => 'serif',
                        'color'=>'#000000'
                    ),
                    'line' => 2,)]);
                    File::where('id',$f->id)->update(['gSerial'=>$pagecount]);
                }
                $msourcePath=public_path("pdf/".uniqid().'.pdf');
                array_push($msourceArray, $msourcePath);
                $mpdf->output($msourcePath,\Mpdf\Output\Destination::FILE);

                $pdf->addPDF($msourcePath, 'all');

            }
        }
        $bundle = Bundle::where("id",$bundle_id)->first();
        $fileName = $bundle->name.'.pdf';
        $pdf->merge();
         if (!file_exists(public_path('generated_pdf'))) {
                mkdir(public_path('generated_pdf'), 0777, true);
            }

        $pdf->save(public_path('generated_pdf/'.$fileName));
        foreach($msourceArray as $ms)
        {
            unlink($ms);
        }
        //add page number
        // $this->pdfPageNumbering($fileName);

        Session::flash('message', 'Bundle Generated Successfully');
        $enrolled_package = auth()
                          ->user()
                          ->load('enrolledPackage')->enrolledPackage;
        if($enrolled_package->package_id == 1)
            {
                $days_after_file_delete = 100;
            }elseif($enrolled_package->package_id == 2)
            {
                $days_after_file_delete = 730;
            }else{
                $days_after_file_delete = 1095;
            }
            $auto_delete_date = Carbon::now()->addDays($days_after_file_delete)->format('Y-m-d');
        $generated_table = generatedTable::where("bundle_id",$bundle_id)->count();
        if($generated_table > 0)
        {
            generatedTable::where('bundle_id',$bundle_id)->update(['auto_deleted_at'=>$auto_delete_date,'filename'=>$fileName,'paid'=>1]);
        }else{

            generatedTable::create(['bundle_id'=>$bundle_id,'auto_deleted_at'=>$auto_delete_date,'filename'=>$fileName,'paid'=>1]);
        }
        File::where('bundle_id',$bundle_id)->update(['gSerial'=>null]);
        Alert::success('Generated', 'BUNDLE GENERATED SUCCESSFULLY');
        return redirect()->back();
    }



    public function countPages($path) {
        $pdftext = file_get_contents($path);
        $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
        return $num;
    }
    //setWaterMark

    public function watermark($id){
        // Source file and watermark config

        $enrolled_package = auth()
        ->user()
        ->load('enrolledPackage')->enrolledPackage;
        $generated_pdf = generatedTable::where("id",$id)->first();
        $file = public_path('generated_pdf/'.$generated_pdf->filename);
        $bundle = Bundle::where("id",$generated_pdf->bundle_id)->first();
        if (!file_exists(public_path('bundle_pdf'))) {
                mkdir(public_path('bundle_pdf'), 0777, true);
            }
        if (!file_exists(public_path('bundle_pdf/'.$bundle->name))) {
                mkdir(public_path('bundle_pdf/'.$bundle->name), 0777, true);
            }
        // if (!file_exists(public_path('bundle_zip'))) {
        //         mkdir(public_path('bundle_zip'), 0777, true);
        //     }
        $sourcePath=$file;
        $destinationPath=public_path('bundle_pdf/'.$bundle->name.'/'.$generated_pdf->filename);
        if(Files::exists($sourcePath)){
            Files::move($sourcePath,$destinationPath);
        }
        // if (!file_exists(public_path('bundle_zip/'.$bundle->name.'.zip'))) {
        //     touch(public_path('bundle_zip/'.$bundle->name.'.zip'), strtotime('-1 days'));
        // }
        // $fileName = 'bundle_zip/'.$bundle->name.'.zip';
        //     $zip = new ZipArchive;
        // if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {

        //     $files = Files::files(public_path('bundle_pdf/'.$bundle->name));
        //     foreach ($files as $key => $value) {
        //         $relativeNameInZipFile = basename($value);
        //         $zip->addFile($value, $relativeNameInZipFile);
        //     }

        //     $zip->close();
        // }

        if($enrolled_package->package_id == 1){
            //add footer text
            $this->pageFooterText($generated_pdf->filename, $bundle->name);
        }

        return response()->download($destinationPath);

    }


    //PDF Footer Text Add
    public function pageFooterText($file, $bundleName) {
        // initiate PDF
        $pdf = new TPDF();
        // set the source file
        $pageCount = $pdf->setSourceFile(public_path('bundle_pdf/'.$bundleName.'/'.$file));

        $pdf->AliasNbPages();
        for ($i=1; $i <= $pageCount; $i++) {
            //import a page then get the id and will be used in the template
            $tplId = $pdf->importPage($i);
            //create a page
            $pdf->AddPage();
            //use the template of the imporated page
            $pdf->useTemplate($tplId);
        }

        return $pdf->Output(public_path('bundle_pdf/'.$bundleName.'/'.$file),'F');
    }


    //PDF Page Numbering
    // public function pdfPageNumbering($files) {

    //     foreach($files as $singleFile){
    //         $file = $singleFile;
    //         if($file->id ==5)
    //             dd($file->filename);
    //         // initiate PDF
    //         $pdf = new PPDF();
    //         // set the source file
    //         $pageCount = $pdf->setSourceFile(public_path('pdf/'.$file->filename));

    //         $pdf->AliasNbPages();
    //         for ($i=1; $i <= $pageCount; $i++) {
    //             //import a page then get the id and will be used in the template
    //             $tplId = $pdf->importPage($i);
    //             //create a page
    //             $pdf->AddPage();
    //             //use the template of the imporated page
    //             $pdf->useTemplate($tplId);
    //         }

    //         return $pdf->Output(public_path('pdf/'.$file->filename),'F');
    //     }
    // }

}

