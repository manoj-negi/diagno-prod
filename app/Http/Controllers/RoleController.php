<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\Role;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
          $records = getenv('ADMIN_PAGE_LIMIT');
            $q = Role::orderBy('id', 'DESC');

            if (isset($_GET['paginate'])) {
                $records = $request->paginate;
            }
           
        $data['role'] = $q->paginate($records)->withQueryString();
        $data['title'] = "Role";
        $data['page_name'] = "List";
        return view('admin.role.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Role";
        $data['page_name'] = "Create";
        $data['permission']=Permission::all()->pluck('permission','id');
  
        return view('admin.role.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation is missing here, you might want to add validation rules inside the validate method
    
        $validate = $request->validate([
            // Add validation rules here
        ]);
    
        // Update or create a Role based on the provided ID
        $role = Role::updateOrCreate(
            ['id' => $request->id],
            ['role' => $request->role]
        );
    
        // Sync the permissions for the role
        $role->permissions()->sync($request->input('permission', []));
    
        if ($role) {
            // If the operation was successful
    
            if ($request->id) {
                // If updating an existing role
                return redirect()->route('role.index')->with('msg', 'Role is successfully updated');
            } else {
                // If creating a new role
                return redirect()->route('role.index')->with('msg', 'Role is successfully created');
            }
        } else {
            // If something went wrong
            return redirect()->back()->with('msg', 'Something went wrong, please try again!');
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
        $data['edit']=Role::findOrFail($id);
        $data['title'] = "Role";
        $data['page_name'] = "Edit";
        $data['permission']=Permission::all()->pluck('permission','id');

        return view('admin.role.create',$data);
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
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Role::find($id);
        $data->delete();

        return redirect()->route('role.index');
    }
}
