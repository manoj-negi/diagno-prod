<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Offer;
use Auth;

class OffersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['title'] = "Offers";
        $data['page_name'] = "List";

        $query = Offer::query();
        if (!empty($request->search)) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate]);
        }

        $data['data'] = $query->orderBy('offer_id', 'DESC')->paginate($request->pagination ?? 10)->withQueryString();

        return view('admin.offer.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Create Offer";
        $data['page_name'] = "Create";
        return view('admin.offer.create', $data);
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'terms_and_conditions' => 'required|string',
            'status' => 'required|in:active,inactive,expired',
            'offer_code' => 'required|string|max:50',
            'offer_type' => 'required|in:percentage,fixed_amount',
            'maximum_discount' => 'required|numeric|min:0',
            'minimum_purchase_amount' => 'required|numeric|min:0',
            'applicable_to' => 'required|in:all_users,new_users,existing_users'
        ]);

        $offer = Offer::create([
            'title' => $request->title,
            'description' => $request->description,
            'discount_percentage' => $request->discount_percentage,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'terms_and_conditions' => $request->terms_and_conditions,
            'status' => $request->status,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'offer_code' => $request->offer_code,
            'offer_type' => $request->offer_type,
            'maximum_discount' => $request->maximum_discount,
            'minimum_purchase_amount' => $request->minimum_purchase_amount,
            'applicable_to' => $request->applicable_to
        ]);

        return redirect()->route('offer.index')->with('success', 'Offer Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($offer_id)
    {
        $data['offer'] = Offer::findOrFail($offer_id);
        return view('admin.offer.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($offer_id)
    {
        $data['title'] = "Edit Offer";
        $data['page_name'] = "Edit";
        $data['offer'] = Offer::findOrFail($offer_id);
        return view('admin.offer.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $offer_id)
    {
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'terms_and_conditions' => 'required|string',
            'status' => 'required|in:active,inactive,expired',
            'offer_code' => 'required|string|max:50',
            'offer_type' => 'required|in:percentage,fixed_amount',
            'maximum_discount' => 'required|numeric|min:0',
            'minimum_purchase_amount' => 'required|numeric|min:0',
            'applicable_to' => 'required|in:all_users,new_users,existing_users'
        ]);

        $offer = Offer::findOrFail($offer_id);
        $offer->update([
            'title' => $request->title,
            'description' => $request->description,
            'discount_percentage' => $request->discount_percentage,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'terms_and_conditions' => $request->terms_and_conditions,
            'status' => $request->status,
            'updated_by' => Auth::id(),
            'offer_code' => $request->offer_code,
            'offer_type' => $request->offer_type,
            'maximum_discount' => $request->maximum_discount,
            'minimum_purchase_amount' => $request->minimum_purchase_amount,
            'applicable_to' => $request->applicable_to
        ]);

        return redirect()->route('offer.index')->with('success', 'Offer Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($offer_id)
    {
        $offer = Offer::findOrFail($offer_id);
        $offer->delete();
        return redirect()->route('offer.index')->with('success', 'Offer Deleted Successfully!');
    }

    public function getOffers(Request $request)
    {
        $query = Offer::query();
        
        if (!empty($request->search)) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }
    
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
    
            $query->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate]);
        }
    
        $offers = $query->orderBy('offer_id', 'DESC')->paginate($request->pagination ?? 10);
    
        return response()->json($offers);
    }
    

}
