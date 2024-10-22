<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Hash;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
    
        $search = $request->search;
        $records = getenv('ADMIN_PAGE_LIMIT');
        $q = User::with('roles')->select('*')->where('id','!=',1)->orderBy('id','DESC');

        if (isset($_GET['paginate'])) {
                $records = $request->paginate;
            }
            if (isset($request->filter)) {

                $q->where('status', $request->filter);
            }
             if (isset($request->role_filter)) {
                   if($request->role_filter==1){
                    $q->where('is_doctor',1 );
                   }
                   if($request->role_filter==0){
                    $q->where('is_doctor',0);
                   } 
                
            }
            if ($request->search) {

                $q->where(function ($query) use ($search) {

                    $query->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%');
                });

            }
        
        $data['userdata'] = $q->paginate($records)->withQueryString();
        $result['title'] = "User";
        $result['page_name'] = "List";
        return view('admin.User.index',$data,$result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

   

    public function create()
    { 
        $result['title'] = "User";
        $result['page_name'] = "Create";
        $result['user'] = User::all();
        // $data['state'] = State::get();
        $result['role']= Role::all()->pluck('role','id');

        return view('admin.User.create',$result);
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
            'name' => 'required',
             'email' =>['required','email','regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix','unique:users,email,' . $request->id],
             'number' =>'required|numeric|digits:10',
             'role' => 'required'
        ]);

        $userdata = User::UpdateOrCreate([
            'id' => $request->id,

        ],[
             'name' => $request->name,
             'email' => $request->email,
            'password'=> Hash::make($request->password),
             'address' => $request->address,
             'number' => $request->number,
             'dob' => $request->dob,
             'status' => $request->status
        ]);
       
        $userdata->roles()->sync($request->input('role', []));
     
        if($userdata)
        {
            if($request->id)
            {
                return redirect()->route('users.index')->with('success','User is successfully updated'); 
            }
            else
            {
                return redirect()->route('users.index')->with('success','User is successfully created'); 
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
        $result['title'] = "User";
        $result['page_name'] = "Edit";
        $result['edit']=User::findOrFail($id);
        $result['role']= Role::all()->pluck('role','id');
        return view('admin.User.create',$result);
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
        $data= User::find($id);
        $data->delete();
 
        return redirect()->route('users.index');
    }
}
