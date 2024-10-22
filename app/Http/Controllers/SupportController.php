<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Support;

class SupportController extends Controller
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
            $q = Support::orderBy('id', 'DESC');

            if (isset($_GET['paginate'])) {
                $records = $request->paginate;
            }
            if (isset($request->filter)) {

                $q->where('status', $request->filter);
            }
            if (!empty($request->search)) {

                $q->where(function ($query) use ($search) {

                    $query->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%')
                        ->orWhere('subject', 'LIKE', '%' . $search . '%');
                });

            }

            $data['data'] = $q->paginate(isset($request->pagination) && !empty($request->pagination)?$request->pagination:10)->withQueryString();
            $data['title'] = " Support";
            $data['page_name'] = "List";
            return view('admin.supports.index', $data);


        } catch (\Exception $e) {

            return redirect()->back()->with('msg', 'Something went Wrong, Please try again!');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Create Support";
        $data['page_name'] = "Create";
        return view('admin.supports.create', $data);
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
            'name' => 'required',    
            'email'=>'required',
            'phone'=>'required|max:10',
            'subject'=>'required',
            'message'=>'required',
        ]);

        try{
              $data = Support::UpdateOrCreate([
               'id' => $request->id,
            ],
            
            [
                'name'   => $request->name,
                'email'   => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
            ]);
            
            if($data)
            {
                if($request->id)
                {
                    return redirect()->route('supports.index')->with('msg','Support is successfully updated'); 
                }
                else
                {
                    return redirect()->route('supports.index')->with('msg','Support is successfully created'); 
                } 
                }else
                {
                  return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
                } 

              } catch (\Exception $e) {
              {
                 return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
              } 

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
        $result['title'] = "Support";
        $result['page_name'] = "Edit";
        $result['data'] = Support::findOrFail($id);
        if($result['data'])
        {
            return view('admin.supports.create',$result);
        }
        else
        {
            return redirect()->back()->with('msg', 'Data not found');   
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
        $data = Support::find($id);
        $data->delete();
        if($data)
        {
            return redirect()->route('supports.index')->with('msg','Support is successfully Deleted'); 
        }
        else
        {
            return redirect()->route('supports.index')->with('msg','Support unsuccessfully Deleted'); 
        }

    }

}
