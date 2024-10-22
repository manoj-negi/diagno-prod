<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Support;
use App\Models\City;


class CityController extends Controller
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
            $q = City::orderBy('id', 'DESC');

            if (isset($_GET['paginate'])) {
                $records = $request->paginate;
            }
            if (isset($request->filter)) {

                $q->where('status', $request->filter);
            }
            if (!empty($request->search)) {

                $q->where(function ($query) use ($search) {

                    $query->where('city', 'LIKE', '%' . $search . '%');
                      
                });

            }

            $data['data'] = $q->paginate(isset($request->pagination) && !empty($request->pagination)?$request->pagination:10)->withQueryString();
            $data['title'] = " City";
            $data['page_name'] = "List";
            return view('admin.city.index', $data);


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
        $data['title'] = "Create City";
        $data['page_name'] = "Create";
     
        return view('admin.city.create', $data);
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
                'city' => 'required',
            
            ]); 
            
            $data =City::UpdateOrCreate([
    
                'id' => $request->id,
           ],[
    
           
                'city' => $request->city,
                
           ]);
            
                    
           if($data)
                {
                    if($request->id)
                    {
                        return redirect()->route('city.index')->with('msg','city is successfully updated'); 
                    }
                    else
                    {
                        return redirect()->route('city.index')->with('msg','city successfully created'); 
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
        $result['title'] = "City";
        $result['page_name'] = "Edit";
        $result['data'] = City::findOrFail($id);
        if($result['data'])
        {
            return view('admin.city.create',$result);
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
        $data = City::find($id);
        $data->delete();
        if($data)
        {
            return redirect()->route('city.index')->with('msg','City is successfully Deleted'); 
        }
        else
        {
            return redirect()->route('city.index')->with('msg','City unsuccessfully Deleted'); 
        }

    }

}

