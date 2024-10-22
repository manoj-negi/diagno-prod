<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;
use File;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $records = getenv('ADMIN_PAGE_LIMIT');
        $search = $request->search;
        $q = Testimonial::orderBy('id', 'DESC');

        if (isset($_GET['paginate'])) {
            $records = $request->paginate;
        }
        if (isset($request->filter)) {

            $q->where('status', $request->filter);
        }
        if ($request->search) {

            $q->where(function ($query) use ($search) {

                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('designation', 'LIKE', '%' . $search . '%')
                    ->orWhere('review', 'LIKE', '%' . $search . '%')
                    ->orWhere('rating', 'LIKE', '%' . $search . '%');
            });

        }

        $data['data'] = $q->paginate($records)->withQueryString();
        $data['title'] = "Testimonial";
        $data['page_name'] = "Create";
        return view('admin.testimonials.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Testimonial";
        $data['page_name'] = "Create";
        return view('admin.testimonials.create', $data);
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
            'designation' => 'required',
            'status' => 'required'
        ]);

        try {
            $result = Testimonial::UpdateOrCreate([
                'id' => $request->id,
            ],[
                'name' => $request->name,
                'designation' => $request->designation,
                'review'=> $request->review,
                'rating' => $request->rating,
                'status' => $request->status
            ]);

            if(isset($request->image)){

                $oldimage = $result->image;
                if ($oldimage != 'user.png') {
                    if (File::exists(public_path('/uploads/testimonial/' . $oldimage)))
                    File::delete(public_path('/uploads/testimonial/' . $oldimage));
                }

                $path = public_path('/uploads/testimonial/');
                $uploadImg = $this->uploadDocuments($request->image, $path);
                $result->image=$uploadImg;
                $result->save();
            } 

            if($result) {
                if($request->id)
                {
                    return redirect()->route('testimonials.index')->with('msg','Testimonial is successfully updated'); 
                }
                else
                {
                return redirect()->route('testimonials.index')->with('msg','Testimonial is successfully created'); 
                } 
            } else {
                return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
            }
        } catch (Exception $e)
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
        $result['title'] = "Testimonial";
        $result['page_name'] = "Edit";
        $result['data'] = Testimonial::findOrFail($id);
         if($result['data'])
        {
             return view('admin.testimonials.create',$result);
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
        $data = Testimonial::findOrFail($id);
        $data->delete();
        if($data)
            {
                return redirect()->route('testimonials.index')->with('msg','Testimonial successfully deleted'); 
            }
            else
            {
                return redirect()->route('testimonials.index')->with('error','Something Went Wrong!'); 
            }
   
    }
}
