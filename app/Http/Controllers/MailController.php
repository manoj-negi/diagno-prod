<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MailTemplate;
use validate;

class MailController extends Controller
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
        $q = MailTemplate::select('*')->orderBy('id', 'DESC');

        if (isset($_GET['paginate'])) {
            $records = $request->paginate;
        }

        if ($request->search) {
            $q->where('title', 'like', '%' . $search . '%')->orwhere('mail_key', 'like', '%' . $search . '%')->orwhere('mail_subject', 'like', '%' . $search . '%')
                ->orwhere('id', 'like', '%' . $search . '%');
        }

        if (isset($request->filter)) {
            $q->where('status', $request->filter);
        }

        $data['data'] = $q->paginate($records)->withQueryString();
        $data['title'] = "Mail";
        $data['page_name'] = "List";
        return view('admin.mail.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Mail";
        $data['page_name'] = "Create";
        return view('admin.mail.create', $data);
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
            'mail_subject' => 'required',
            'content' => 'required',
            'status' => 'required',
            'mail_key' => 'required|unique:mail_templates,mail_key,' . $request->id,

        ]);

        $data = MailTemplate::UpdateOrCreate([
            'id' => $request->id,
        ], [
                'title' => $request->title,
                'mail_subject' => $request->mail_subject,
                'content' => $request->content,
                'status' => $request->status,
                'mail_key' => $request->mail_key,

            ]);
            if($data)
            {
            if($request->id)
            {
                return redirect()->route('mail.index')->with('success','Mail is successfully updated'); 
            }
            else
            {
                return redirect()->route('mail.index')->with('success','Mail is successfully created'); 
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
        $data['title'] = "Mail";
        $data['page_name'] = "Edit";
        $data['result'] = MailTemplate::findOrFail($id);
        if($data['result'])
        {
            return view('admin.mail.create',$data);
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
        $data = MailTemplate::find($id);
        $data->delete();
        if($data)
            {
                return redirect()->route('mail.index')->with('success','Mail is successfully Deleted'); 
            }
            else
            {
                return redirect()->route('mail.index')->with('error','Mail unsuccessfully Deleted'); 
            }
    }
}