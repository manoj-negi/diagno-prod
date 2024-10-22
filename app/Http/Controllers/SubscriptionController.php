<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Plan;

class SubscriptionController extends Controller
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
        $q = Subscription::with('plans')->select('*')->orderBy('id', 'DESC');

        if (isset($request->filter)) {

            $q->where('status', $request->filter);
        }
        if ($request->search) {

            $q->where(function ($query) use ($search) {

                $query->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('price', 'LIKE', '%' . $search . '%');
            });

        }
        $data['data'] = $q->paginate($records)->withQueryString();
        $result['title'] = "Subscription";
        $result['page_name'] = "List";
        return view('admin.subscriptions.index', $result, $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Subscription";
        $data['page_name'] = "Create";
        $data['plan']=Plan::all()->pluck('name','id');
        return view('admin.subscriptions.create', $data);
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

            'title' => 'required',
            'price' => 'required',
            'days'  => 'required',
            'plan'  => 'required',
        ]);


        $data = Subscription::UpdateOrCreate([
            'id' => $request->id,
        ], [
                'title' => $request->title,
                'price' => $request->price,
                // 'content' => $request->content,
                'status' => $request->status,
                'days' => $request->days
            ]);
            $data->plans()->sync($request->input('plan', []));

             if($data)
            {
                if($request->id)
                {
                    return redirect()->route('subscription.index')->with('msg','subscription is successfully updated'); 
                }
                else
                {
                    return redirect()->route('subscription.index')->with('msg','subscription is successfully created'); 
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
        $result['title'] = "Subscription";
        $result['page_name'] = "Edit";
        $result['data'] = Subscription::findOrFail($id);
        $result['plan'] = Plan::all()->pluck('name','id');
        if($result['data'])
        {
            return view('admin.subscriptions.create',$result);
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
        $data = Subscription::find($id);
        $data->delete();
        if($data)
            {
                return redirect()->route('subscription.index')->with('msg','subscription is successfully Deleted'); 
            }
            else
            {
                return redirect()->route('subscription.index')->with('msg','subscription unsuccessfully Deleted'); 
            }

    }
}