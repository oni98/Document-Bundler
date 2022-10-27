<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Redirect;
use App\Models\Section;
use App\Models\File;
use RealRashid\SweetAlert\Facades\Alert;
class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return view('backend.pages.dashboard');
        }
        $validated = $request->validate([
        'bundle_id' => 'required',
        'name' => 'required|max:255',
        ]);
        $sec = Section::where(['user_id'=>auth()->user()->id,'name'=>$request->name ])->count();
        if($sec > 0)
        {
            $validator = [
                "msg" => "This name is in used please try with new section name"
            ];
             return redirect()->back()->withErrors($validator);
        }
        $data['name'] = $request->name;
        $data['bundle_id'] = $request->bundle_id;
        $data['user_id'] = $user->id;

        $filess = Section::where("bundle_id",$request->bundle_id)->orderBy("sort_id",'desc')->first();
            if(!is_null($filess))
            {
                $data['sort_id'] =  $filess->sort_id+1;
            }else{
                $data['sort_id'] = 1;
            }
            if(!is_null($filess->serial_alpha))
            {
                $data['serial_alpha'] =  chr(ord($filess->serial_alpha) + 1);
            }else{
                $data['serial_alpha'] = "A";
            }
        Section::create($data);
        Alert::success('Created', 'Section Created Successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return view('backend.pages.dashboard');
        }

        $section = Section::with(['files','bundle'])->where(['user_id'=>$user->id,"id"=>$id])->first();
        return view('backend.pages.bundle.files.index',['section'=>$section]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($bundle_id,$id)
    {
         $user = Auth::user();
        if ($user->isAdmin()) {
            return view('backend.pages.dashboard');
        }

        $section = Section::where(['user_id'=>$user->id,"id"=>$id])->first();
        return view('backend.pages.bundle.sections.edit',['section'=>$section]);
    }

    public function updateOrder(Request $request){
        if($request->has('ids')){
            $arr = explode(',',$request->input('ids'));
            $sectionNumber = ['', '', '', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            foreach($arr as $sortOrder => $id){

               Section::where('id',$id)->update(['sort_id'=>$sortOrder+3,'serial_alpha'=>$sectionNumber[$sortOrder+3]]);
            }
            return ['success'=>true,'message'=>'Updated'];
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $section = Section::with('bundle')->where('id',$id);

        if($section->count() > 0)
        {
            $section->update(['name'=>$request->name]);
            $sec =  $section->first();
            Alert::success('Updated', 'Section Updated Successfully');
            return redirect()->route('bundle.show_single',[$sec->bundle->slug,$sec->bundle_id]);

        }else{
            dump("no data found");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $section = Section::where('id',$id);
        if($section->count() > 0)
        {
            $s = $section->first();
            $files = File::where('section_id',$s->id)->get();
            foreach($files as $file){
                if(!is_null($file)){

                    if(file_exists(public_path('pdf/'.$file->filename))){
                        unlink(public_path('pdf/'.$file->filename));
                    }
                    File::where('id',$file->id)->delete();
                }
            }
            $section->delete();
            Alert::success('Delete', 'Section Deleted Successfully');
            return redirect()->back();

        }else{

            dump("no data found");
        }
    }
}
