<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HospitalCategory;
use File;
use Auth;
use Datatables;

class HospitalCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $search = $request->search;
        if ($request->ajax()) {
            $data = HospitalCategory::query();
            if(Auth::user()->roles->contains(1)){
                $data->orderBy('id','desc');
             }
            return Datatables::of($data)
                ->addIndexColumn()
               
                // ->addColumn('description', function($row){
                //     //   return $row;
                //    return isset($row->description) ? $row->description : '-';
                //     })
                    ->addColumn('image', function($row){
                        //   return $row;
                       return ' <img src="'.url('uploads/testimonial',$row->image).'" alt="" class="mt-2 mb-2" width="60" height="60">';
                        })
                        ->addColumn('action', function($row){
                            return  '
                            <a class="btn btn-primary btn-sm" href="'.route('lab-category.edit', $row->id).'">
                     <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                 </a>
             
                 <form action="'.route('lab-category.destroy',$row->id).'" method="POST" style="display: inline-block;">
                 <input type="hidden" name="_token" value="'.csrf_token().'">
                 <input type="hidden" name="_method" value="delete">
                     <a class=" text-white btn btn-danger  show_confirm btn-sm"  value="Delete">
                         <i class="fa fa-trash" aria-hidden="true"></i>
                     </a>';
                       })
                        
                ->rawColumns(['title','description','image','action'])
                ->make(true);
        }
        $data["title"] = "Lab Test Sheet";
        $data["page_name"] = "Create";
        return view("admin.hospitalcategory.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data["title"] = "Lab Test Sheet";
        $data["page_name"] = "Create";
        return view("admin.hospitalcategory.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            "title" => "required",
            "description" => "required",
            'image' => 'image|mimes:jpeg,png,jpg,gif',
        ]);

        try {
            $result = HospitalCategory::UpdateOrCreate(
                [
                    "id" => $request->id,
                ],
                [
                    "title" => $request->title,
                    "description" => $request->description,
                ]
            );
            if (isset($request->image)) {
                $path = public_path("/uploads/testimonial/");
                $uploadImg = $this->uploadDocuments($request->image, $path);
                $result->image = $uploadImg;
                $result->save();
            }
            if ($result) {
                if ($request->id) {
                    return redirect()
                        ->route("lab-category.index")
                        ->with("msg", "successfully updated");
                } else {
                    return redirect()
                        ->route("lab-category.index")
                        ->with("msg", "successfully created");
                }
            } else {
                return redirect()
                    ->back()
                    ->with("error", "Something went Wrong, Please try again!");
            }
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with("error", "Something went Wrong, Please try again!");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result["title"] = "HospitalCategory";
        $result["page_name"] = "Edit";
        $result["data"] = HospitalCategory::findOrFail($id);
        if ($result["data"]) {
            return view("admin.hospitalcategory.create", $result);
        } else {
            return redirect()
                ->back()
                ->with("error", "Data not found");
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = HospitalCategory::findOrFail($id);
        $data->delete();
        if ($data) {
            return redirect()
                ->route("lab-category.index")
                ->with("msg", "successfully Deleted");
        } else {
            return redirect()
                ->route("lab-category.index")
                ->with("error", "successfully Deleted");
        }
    }
}
