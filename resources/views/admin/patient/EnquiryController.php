<?php

namespace App\Http\Controllers\Admin;

use App\Enquiry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $data['title'] = 'Enquiry';
        $q = Enquiry::select('enquiries.*');
        if(isset($request->search)) {
            $q->where('name', 'like', '%'.$request->search.'%');
            $q->where('email', 'like', '%'.$request->search.'%');
        }
        $data['enquiries'] = $q->paginate(10)->withQueryString();
        return view('admin.enquiries.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        $brand = Enquiry::updateOrCreate(['id' => $request->id], $data);
        
        return redirect()->route('dashboard.enquiries.index')->with('msg', 'Updated Successfully');
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
        //
        $data['title'] = 'Enquiry';
        $data['enquiry'] = Enquiry::where('id', $id)->first();
        return view('admin.enquiries.create', $data);
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
        //
        Enquiry::where('id', $id)->delete();
        return redirect()->route('dashboard.enquiries.index')->with('msg', 'Enquiry Deleted Successfully');
    }
}
