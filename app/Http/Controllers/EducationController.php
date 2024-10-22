<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Education;
use Validate;
use File;

class EducationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {

            $records = getenv('ADMIN_PAGE_LIMIT');
            $search = $request->search;
            $q = Education::orderBy('id', 'DESC');

            if (isset($_GET['paginate'])) {
                $records = $request->paginate;
            }
            if (isset($request->filter)) {

                $q->where('status', $request->filter);
            }
            if ($request->search) {

                $q->where(function ($query) use ($search) {

                    $query->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%');
                });

            }

            $data['data'] = $q->paginate($records)->withQueryString();
            $data['title'] = "Education";
            $data['page_name'] = "List";
            return view('admin.educations.index', $data);


        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Education";
        $data['page_name'] = "Create";
        return view('admin.educations.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:educations,name,' . $request->id,
            
        ]);

        $data = Education::UpdateOrCreate([
            'id' => $request->id,
        ],
        [
            'name' => $request->name,
            'spenish_name' => $request->spenish_name,
            'description' => $request->description,
            'spenish_description' => $request->spenish_description,
            'status' => $request->status
        ]);
        if($data)
        {
            if($request->id)
            {
                return redirect()->route('educations.index')->with('msg','Education is successfully updated'); 
            }
            else
            {
                return redirect()->route('educations.index')->with('msg','Education is successfully created'); 
            } 
        }else
        {
            return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
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
        $result['title'] = "Education";
        $result['page_name'] = "Edit";
        $result['data'] = Education::findOrFail($id);
        if($result['data'])
        {
            return view('admin.educations.create',$result);
        }
        else
        {
            return redirect()->back()->with('error', 'Data not found');   
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
        $data = Education::find($id);
        $data->delete();
        if($data)
            {
                return redirect()->route('educations.index')->with('msg','Education is successfully Deleted'); 
            }
            else
            {
                return redirect()->route('educations.index')->with('error','Education successfully Deleted'); 
            }
    }

}
