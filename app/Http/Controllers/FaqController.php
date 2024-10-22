<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Role;

class FaqController extends Controller
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
        $q = Faq::select('*')->orderBy('id', 'DESC');

        if (isset($_GET['paginate'])) {
            $records = $request->paginate;
        }
        if (isset($request->filter)) {

            $q->where('status', $request->filter);
        }
        if (isset($request->role_filter)) {
            if($request->role_filter==1){
             $q->where('user_id',3 );
            }
            if($request->role_filter==0){
             $q->where('user_id',2);
            } 
         
     }
        if ($request->search) {

            $q->where(function ($query) use ($search) {

                $query->where('question', 'LIKE', '%' . $search . '%')
                    ->orWhere('answer', 'LIKE', '%' . $search . '%');
            });

        }

        $data['data'] = $q->paginate(isset($request->pagination) && !empty($request->pagination)?$request->pagination:10)->withQueryString();
        $data['title'] = "Faqs";
        $data['page_name'] = "List";
        $data['role'] = Role::whereIn('role',['doctor','patient'])->pluck('role','id');
        // return $data;
        return view('admin.faqs.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Faqs";
        $data['page_name'] = "Create";
        $data['role'] = Role::whereIn('role',['doctor','patient'])->pluck('role','id');
        return view('admin.faqs.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $validate = $request->validate([
            'question' => 'required',
            'answer' => 'required',
            // 'user_id' =>'required'
        ]); 
        
        $data =Faq::UpdateOrCreate([

            'id' => $request->id,
       ],[

        //    'user_id'  =>$request->user_id ??'',
            'question' => $request->question,
            'answer' => $request->answer,
            'status' => $request->status
       ]);
        
                
       if($data)
            {
                if($request->id)
                {
                    return redirect()->route('faqs.index')->with('msg','Faqs is successfully updated'); 
                }
                else
                {
                    return redirect()->route('faqs.index')->with('msg','Faqs successfully created'); 
                } 
            }else
            {
                return redirect()->back()->with('msg', 'Something went Wrong, Please try again!');
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
        $data['title'] = "Faqs";
        $data['page_name'] = "Edit";
        $data['role'] = Role::whereIn('role',['doctor','patient'])->pluck('role','id');
        $data['result'] = Faq::findOrFail($id);
        if($data['result'])
        {
            return view('admin.faqs.create',$data);
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
        $data = faq::find($id);
        $data->delete();

        if($data)
            {
                return redirect()->route('faqs.index')->with('msg','Faqs is successfully Deleted'); 
            }
            else
            {
                return redirect()->route('faqs.index')->with('msg','Faqs unsuccessfully Deleted'); 
            }
    }
}
