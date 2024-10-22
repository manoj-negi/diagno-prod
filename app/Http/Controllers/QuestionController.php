<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\questions;
use App\Models\Speciality;
class QuestionController extends Controller
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
            $q = questions::orderBy('id', 'DESC');

            if (isset($_GET['paginate'])) {
                $records = $request->paginate;
            }
            if (isset($request->filter)) {

                $q->where('status', $request->filter);
            }
            if ($request->search) {

                $q->where(function ($query) use ($search) {

                    $query->where('question', 'LIKE', '%' . $search . '%');
                });

            }

            $data['data'] = $q->paginate($records)->withQueryString();
            $data['title'] = "Question";
            $data['page_name'] = "List";
            return view('admin.questions.index', $data);


        } catch (\Exception $e) {

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
        $data['title'] = "Question";
        $data['page_name'] = "Create";
        $data['speciality'] = Speciality::all();
        return view('admin.questions.create', $data);
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
            'question' => 'required',
            // 'speciality_id' => 'required',
        ]);

        $data = questions::UpdateOrCreate([
            'id' => $request->id
        ], [
                'question' => $request->question,
                'spenish_question' => $request->spenish_question,
                'doctor_id' => 0,
                'status' => $request->status,
            ]);

            if($data)
            {
                if($request->id)
                {
                    return redirect()->route('questions.index')->with('msg','question is successfully updated'); 
                }
                else
                {
                    return redirect()->route('questions.index')->with('msg','question is successfully created'); 
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
        $data['title'] = "Question";
        $data['page_name'] = "Edit";
        $data['speciality'] = Speciality::all();
        $data['result'] = questions::findOrFail($id);
        if($data['result'])
        {
            return view('admin.questions.create',$data);
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
        $data = questions::find($id);
        $data->delete();

        if($data)
            {
                return redirect()->route('questions.index')->with('success','question is successfully Deleted'); 
            }
            else
            {
                return redirect()->route('questions.index')->with('error','question successfully Deleted'); 
            }
    }
}
