<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $search = $request->search;
            $q = Plan::orderBy('id', 'DESC');
    
            if ($request->search) {
                $q->where(function ($query) use ($search) {
                    $query->where('title', 'LIKE', '%' . $search . '%')
                          ->orWhere('commission_percentage', 'LIKE', '%' . $search . '%')
                          ->orWhere('validity', 'LIKE', '%' . $search . '%');
                });
            }
    
            $paginateData = [10, 30, 50, 70, 100];
            $perPage = $request->input('pagination', 10);
    
            $data['data'] = $q->paginate($perPage)->withQueryString();
            $data['title'] = "Plan";
            $data['page_name'] = "List";
            $data['paginateData'] = $paginateData;
    
            return view('admin.plans.index', $data);
    
        } catch (\Exception $e) {
            // You might want to handle the exception more gracefully
            echo $e->getMessage();
        }
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Plan";
        $data['page_name'] = "Create";
        return view('admin.plans.create', $data);
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
            'title' => 'required',
            'validity' => 'required',
            'commission_percentage' => 'required|numeric|between:0,100',
           
        ]);

        $data = Plan::UpdateOrCreate([
            'id' => $request->id,
        ],
        [
            'title' => $request->title,
            'commission_percentage' => $request->commission_percentage,
            'validity' => $request->validity,
        ]);
        if($data)
        {
            if($request->id)
            {
                return redirect()->route('plans.index')->with('msg','Plan is successfully updated'); 
            }
            else
            {
                return redirect()->route('plans.index')->with('msg','Plan is successfully created'); 
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
        $result['title'] = "Plan";
        $result['page_name'] = "Edit";
        $result['data'] = Plan::findOrFail($id);
        if($result['data'])
        {
            return view('admin.plans.create',$result);
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
        $data = Plan::find($id);
        $data->delete();
        if($data)
            {
                return redirect()->route('plans.index')->with('msg','Plan is successfully Deleted'); 
            }
            else
            {
                return redirect()->route('plans.index')->with('error','Plan unsuccessfully Deleted'); 
            }
    }
}
