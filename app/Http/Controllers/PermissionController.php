<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $records = getenv('ADMIN_PAGE_LIMIT');
            $q = Permission::orderBy('id', 'DESC');

            if (isset($_GET['paginate'])) {
                $records = $request->paginate;
            }
           
        $data['permission'] = $q->paginate($records)->withQueryString();
        $data['title'] = "Permission";
        $data['page_name'] = "List";
        return view('admin.permission.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Permission";
        $data['page_name'] = "Create";
        return view('admin.permission.create',$data);
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
            'permission' => 'required',
           
        ]);

        $permission = Permission::UpdateOrCreate([
            'id' => $request->id,

        ],[
             'permission' => $request->permission,
             ]); 
             
             if($permission)
             {
                 if($request->id)
                 {
                     return redirect()->route('permission.index')->with('msg','Permission is successfully updated'); 
                 }
                 else
                 {
                     return redirect()->route('permission.index')->with('msg','Permission is successfully created'); 
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
        $data['title'] = "Permission";
        $data['page_name'] = "Edit";
        $data['edit']=Permission::findOrFail($id);
        return view('admin.permission.create',$data);
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
        $data = Permission::findOrFail($id);
        $data->delete();

        return redirect()->route('permission.index');
    }
}
