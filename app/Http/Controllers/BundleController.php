<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Bundle;
use App\Models\Section;
use App\Models\File;
use App\Models\generatedTable;
use RealRashid\SweetAlert\Facades\Alert;
class BundleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('users');
        }
        $enrolled_package = auth()
                          ->user()
                          ->load('enrolledPackage')->enrolledPackage;
        if(is_null($enrolled_package))
        {
            return redirect()->route('public.choosePlan');
        }
         $bundle = Bundle::with('section')->where('user_id',$user->id)->get();
        return view('backend.pages.bundle.index',['bundle'=>$bundle]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return view('backend.pages.dashboard');
        }
        $validated = $request->validate([
        'name' => 'required|max:255',
        ]);
        $data['name'] = $request->name;
        $data['slug'] = preg_replace('/\s+/', '-', $request->name);
        $data['user_id'] = $user->id;
        $bundle = Bundle::create($data);
        $bu['name'] = $request->name;
        $bu['bundle_id'] = $bundle->id;
        $bu['user_id'] = $user->id;
        $bu['isDefault'] = 1;
        $bu['sort_id'] = 1;
        $bu['isHiddenInList'] = 1;
        $bu['isHiddenInGenerateIndexList'] = 1;
        $bu['isMainSection'] = 1;
        Section::create($bu);

        $Index['name'] = "Index";
        $Index['bundle_id'] = $bundle->id;
        $Index['user_id'] = $user->id;
        $Index['isDefault'] = 1;
        $Index['sort_id'] = 2;
        $Index['isHiddenInList'] = 1;
        $Index['isHiddenInGenerateIndexList'] = 0;
        $Index['isMainSection'] = 0;
        Section::create($Index);
        $cover['name'] = "Default Bundle Section";
        $cover['bundle_id'] = $bundle->id;
        $cover['user_id'] = $user->id;
        $cover['isDefault'] = 1;
        $cover['sort_id'] = 3;
        $cover['isHiddenInList'] = 0;
        $cover['isHiddenInGenerateIndexList'] = 1;
        $cover['isMainSection'] = 0;
        $cover['serial_alpha'] = "A";
        Section::create($cover);
        Alert::success('Created', 'Bundle Created Successfully');
        return redirect()->route('bundle.show_single', [$bundle->slug,$bundle->id]);
    }

    public function show($slug,$id)
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return view('backend.pages.dashboard');
        }

        $bundle = Bundle::with('section')->where(['user_id'=>$user->id,"slug"=>$slug,"id"=>$id])->first();
        return view('backend.pages.bundle.show',['bundle'=>$bundle]);
    }

    public function edit($id)
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return view('backend.pages.dashboard');
        }

         $bundle = Bundle::with('section')->where(['user_id'=>$user->id,"id"=>$id])->first();
        return view('backend.pages.bundle.edit',['bundle'=>$bundle]);
    }

    public function update(Request $request,$id)
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return view('backend.pages.dashboard');
        }
        $name = $request->name;
        $slug = preg_replace('/\s+/', '-', $request->name);
        Bundle::where(['user_id'=>$user->id,"id"=>$id])->update(['name'=>$name,'slug'=>$slug]);
        Alert::success('Updated', 'Bundle Name Updated Successfully');
        return redirect()->route('bundle.index');
    }

    public function destroy($id)
    {
        $bundle = Bundle::where('id',$id);
        if($bundle->count() > 0)
        {
            $b = $bundle->first();
            $sec = Section::with('files')->where('bundle_id',$b->id)->get();
            foreach($sec as $s)
            {
                if(file_exists(public_path("pdf/section".$s->id.'.pdf'))){
                    unlink(public_path("pdf/section".$s->id.'.pdf'));
                }
                $files = File::where('section_id',$s->id)->get();
                foreach($files as $file){

                    if(!is_null($file)){
                        if(file_exists(public_path("pdf/".$file->filename))){
                            unlink(public_path("pdf/".$file->filename));
                        }
                        File::where('id',$file->id)->delete();
                    }
                }
                Section::where('id',$s->id)->delete();
            }
            if(generatedTable::where('bundle_id',$b->id)->count() > 0)
            {
                $generated_pdf = generatedTable::where('bundle_id',$b->id)->first();
                if(file_exists(public_path("bundle_pdf/".$b->name."/".$b->name.'.pdf'))){
                    unlink(public_path("bundle_pdf/".$b->name."/".$b->name.'.pdf'));
                    rmdir(public_path("bundle_pdf/".$b->name));
                }
                if(file_exists(public_path("bundle_zip/".$b->name.'.zip'))){
                    unlink(public_path("bundle_zip/".$b->name.'.zip'));
                }
                if(file_exists(public_path("generated_pdf/".$b->name.'.pdf'))){
                    unlink(public_path("generated_pdf/".$b->name.'.pdf'));
                }
               generatedTable::where('bundle_id',$b->id)->delete();
            }
            $bundle->delete();
            Alert::success('Deleted', 'Bundle Deleted Successfully');
            return redirect()->back();
        }else{
            dump("no data found");
        }
    }
    
    public function generated_destroy($id)
    {
        $bundle = generatedTable::with('bundle')->where('id',$id);
        if($bundle->count() > 0)
        {
            $file = $bundle->first();
            if(file_exists(public_path("generated_pdf/".$file->filename))){
                unlink(public_path("generated_pdf/".$file->filename));
            }
            if(file_exists(public_path("bundle_pdf/".$file->bundle->name.'/'.$file->filename))){
                unlink(public_path("bundle_pdf/".$file->bundle->name.'/'.$file->filename));
                rmdir(public_path("bundle_pdf/".$file->bundle->name));
            }
            $filename = explode('.',$file->filename);
            if(file_exists(public_path("bundle_zip/".$filename[0].'.zip'))){
                unlink(public_path("bundle_zip/".$filename[0].'.zip'));
            }
                generatedTable::where('id',$file->id)->delete();
                Alert::success('Generated', 'GENERATED BUNDLE SUCCESSFULLY DELETED');
            return redirect()->back();
        }else{
            dump("no data found");
        }
    }
    public function generated_bundle($id)
    {
        $bundle = Bundle::with('generated')->where('id',$id);
        if($bundle->count() > 0)
        {
            $b = $bundle->first();
            return view('backend.pages.bundle.generated_bundle',['bundle'=>$b]);
        }

    }
}
