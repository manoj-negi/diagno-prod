<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
// use App\Models\MailTemplate;
use App\Models\MailTemplate;
use Illuminate\Http\Request;

class MailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['title'] = 'Mail Template';
        $data['page_name'] = 'Mail Template';
        $q = MailTemplate::select('mail_templates.*');
        if(isset($request->search)) {
            $q->where('from_name', 'like', '%'.$request->search.'%')
              ->orWhere('subject', 'like', '%'.$request->search.'%')
              ->orWhere('category', 'like', '%'.$request->search.'%');
        }
        $data['mailTemplates'] = $q->paginate(10)->withQueryString();
        return view('admin.mail-template.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data['title'] = 'Add Mail Template';
        $data['page_name'] = 'Add Mail Template';
        $data['categories'] = $this->MailCategories();
        return view('admin.mail-template.create', $data);
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
            'from_name' => 'required',
            'subject' => 'required',
            // 'from_email' => 'required',
            // 'replay_from_email' => 'required',
            'category' => 'required',
            'message' => 'required',
        ]);

        $data = [
            'from_name' => $request->from_name,
            'subject' => $request->subject??'',
            'from_email' => ($request->from_email)??'',
            'replay_from_email' => ($request->replay_from_email) ??'',
            'category' => $request->category,
            'message' => $request->message,
        ];

        MailTemplate::updateOrCreate(['id' => $request->id], $data);

        if(isset($request->id)) {
            $msg = 'Mail Template Updated.';
        } else {
            $msg = 'Mail Template Added.';
        }
        return redirect()->route('mails-template.index')->with('msg', $msg);
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
        $data['page_name'] = 'Edit Mail Template';
        $data['title'] = 'Edit Mail Template';
        $data['MailTemplate'] = MailTemplate::where('id', $id)->first();
        $data['categories'] = $this->MailCategories();
        return view('admin.mail-template.create', $data);
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
        MailTemplate::where('id' , $id)->delete();
        return redirect()->back()->with('msg', 'Mail Template Deleted Successfully');
    }
}
