<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionInventory;

class SubscriptionInventoryController extends Controller
{
    public function index(Request $request)
    {

        try {

            $records = getenv('ADMIN_PAGE_LIMIT');
            $search = $request->search;
            $q = SubscriptionInventory::orderBy('id', 'DESC');

            if (isset($_GET['paginate'])) {
                $records = $request->paginate;
            }
            if (isset($request->filter)) {

                $q->where('status', $request->filter);
            }
            if ($request->search) {

                $q->where(function ($query) use ($search) {

                    $query->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%');
                });
                

            }

            $data['data'] = $q->paginate($records)->withQueryString();
            
            $data['title'] = "SubscriptionInventory";
            $data['page_name'] = "List";
            return view('admin.subscriptioninventories.index', $data);


        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
        }


    }
}
