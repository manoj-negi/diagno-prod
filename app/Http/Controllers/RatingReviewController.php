<?php
namespace App\Http\Controllers;
use App\Models\RatingReview;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;


class RatingReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $perPage = config('app.admin_page_limit', 10);
 
            $q = RatingReview::orderBy('id', 'desc');
            if(Auth::user()->roles->contains(4)){
                $q->where('hospital_id',Auth::user()->id)->orderBy('id','desc');
            }else{
                $q->orderBy('id','desc');
            }

            if (!empty($request->paginate)) {
                $perPage = $request->paginate;
            }

            if (!empty($request->search)) {
                $q->where(function ($query) use ($request) {
                    $query->where('ratings', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('review', 'LIKE', '%' . $request->search . '%');
                });
            }
            if (!empty($request->hospital)) {
                $q->where('hospital_id', $request->hospital);
            }

            if (!empty($request->patient)) {
                $q->where('patient_id', $request->patient);
            }
            $data['data'] = $q->paginate(isset($request->pagination) && !empty($request->pagination)?$request->pagination:10)->withQueryString();

            $data['title'] = "Review";
            $data['page_name'] = "List";

            $data['patient'] = User::where('is_patient', 1)->get();
            $data['hospital'] = User::where('is_hospital', 1)->get();

            return view('admin.rating.index', $data);
        } catch (\Exception $e) {
            
            return redirect()->back()->with('msg', 'Something went wrong. Please try again!');
        }
    }
    
    
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Review";
        $data['page_name'] = "Create";
        $data['hospital'] = User::where('is_hospital',1)->pluck('name','id');
        $data['patient'] = User::where('is_patient',1)->pluck('name','id');
        return view('admin.rating.create',$data);
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
            'hospital_id.required' => 'The hospital ID field is required.',
            'patient_id.required' => 'The hospital ID field is required.',
            'ratings.required' => 'The hospital ID field is required.',
            'review.required' => 'The hospital ID field is required.',
              
        ]);
        


  try{

        $data= RatingReview::UpdateOrCreate([
           'id' => $request->id,
       ],[
        
         'hospital_id' => $request->hospital_id,
         'patient_id' => $request->patient_id,
         'ratings' => $request->ratings,
         'review'=> $request->review,
            
        
    ]);
   
    if($data)
    {
        if($request->id)
        {
            return redirect()->route('ratingreviews.index')->with('msg','RatingReviews is successfully updated'); 
        }
        else
        {
            return redirect()->route('ratingreviews.index')->with('msg','RatingReviews is successfully created'); 
        } 
    }else
    {
        return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
    } 

  }  catch (\Exception $e) {

      echo $e->getMessage();
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
          $rating['title'] = "Review";
          $rating['page_name'] = "Edit";
          $rating['hospital'] = User::where('is_hospital',1)->pluck('name','id');
          $rating['patient'] = User::where('is_patient',1)->pluck('name','id');
          $rating['data'] = RatingReview::findOrFail($id);
           if($rating['data'])
        {
          return view('admin.rating.create',$rating);
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
        $data = RatingReview::find($id);
        $data->delete();
         if($data)
            {
                return redirect()->route('ratingreviews.index'); 
            }
            else
            {
                return redirect()->route('ratingreviews.index'); 
            }
       
    }
}
