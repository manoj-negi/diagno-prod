<?php
namespace App\Http\Controllers;

use App\Models\LabProfile;
use App\Models\User;
use App\Models\LabTest;
use App\Models\Package;
use App\Models\PackageProfile;
use App\Models\LabSelectedPackages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\PackageCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['title'] = 'Lab Package';
        $data['page_name'] = 'List';

        // Get the authenticated user and their role
        $user = Auth::user();
        $roleId = $user->roles->first()->id; // Assuming each user has only one role

        // Fetch all labs with role ID 4 (Lab users)
        $data['labs'] = User::whereHas('roles', function($q) {
            $q->where('id', '=', 4); // Assuming role ID 4 corresponds to Lab users
        })->get();

        // Start building the query for packages
        $dataPackages = Package::query()
            ->join('users', 'labs_packages.lab_id', '=', 'users.id')
            ->select('labs_packages.*', 'users.name as lab_name');

        // Apply filtering based on the user's role
        if ($roleId == 1) {
            // Admin: show all packages
            if (!empty($request->search)) {
                $dataPackages->where('labs_packages.package_name', 'LIKE', '%' . $request->search . '%');
            }
        } else {
            // Lab user: only show packages for the logged-in user's lab
            $labId = $user->id; // Assuming lab ID is the same as user ID
            $dataPackages->where('labs_packages.lab_id', $labId);

            if (!empty($request->search)) {
                $dataPackages->where('labs_packages.package_name', 'LIKE', '%' . $request->search . '%');
            }
        }

        // Get the paginated results
        $data['data'] = $dataPackages->orderBy('labs_packages.id', 'desc')
            ->paginate($request->input('pagination', 20))
            ->withQueryString();

        return view('admin.package.index', $data);
    }

    public function indexDiagno(Request $request)
    {
        $data['title'] = 'Diagnomitra Package';
        $data['page_name'] = 'List';

        // Fetch only packages for Diagnomitra lab (lab_id = 585)
        $dataPackages = Package::where('lab_id', 585);

        if (!empty($request->search)) {
            $dataPackages->where('package_name', 'LIKE', '%' . $request->search . '%');
        }

        // Get the paginated results
        $data['data'] = $dataPackages->orderBy('id', 'desc')
            ->paginate($request->input('pagination', 20))
            ->withQueryString();

        return view('admin.package.index', $data);
    }


    public function packageProfile(Request $request)
    {
        $LabId = $request->lab_id;
        $lab = LabProfile::where("lab_id", $LabId)->get();

        $outputDataemployees = "";
        if ($lab->count() > 0) {
            foreach ($lab as $item) {
                $outputDataemployees .= "<option value='{$item->id}'>{$item->title}</option>";
            }
        }
        return response()->json([
            "status" => true,
            "outputDataemployees" => $outputDataemployees,
        ]);
    }

    public function getPackageProfile(Request $request)
    {
        $keyWord = $request->q;
        $labId = $request->lab_id;
    
        // Initialize query builder for LabProfile model
        $query = LabProfile::where('profile_name', 'like', '%' . $keyWord . '%');
    
        // Filter by lab_id if the user has role 4
        if (Auth::user()->roles->contains(4)) {
            $query->where('lab_id', Auth::user()->id);
        }
    
        // Paginate the results
        $page = $request->input('page', 1);
        $items = $query->paginate(10, ['*'], 'page', $page);
    
        // Prepare response
        $response = [
            'items' => $items->items(),
            'total_count' => $items->total()
        ];
    
        return response()->json($response);
    }
    

    public function create(Request $request)
    {
        $data["page_name"] = "Create";
        $data["title"] = "Add Lab Package";
    
        // Fetch Diagnomitra Lab ID
        $diagnomitraLabId = User::where('name', 'Diagnomitra')
            ->whereHas('roles', function ($query) {
                $query->where('id', 4);
            })
            ->pluck('id')
            ->first();
        
        // Check if the user has the admin role
        if (Auth::user()->roles->contains(1)) {
            // Fetch all labs for admin role
            $data['labs'] = User::whereHas('roles', function($q) {
                $q->where('id', '=', 4);  // Lab role
            })->get();
        } else {
            // For non-admin users, fetch only the current user's lab
            $data['labs'] = User::where('id', Auth::user()->id)->get();
        }
    
        // Retrieve other necessary data
        $data["packageName"] = Package::where('lab_id', $diagnomitraLabId)->get();
        $data['subprofile'] = LabProfile::where('lab_id', Auth::user()->id)->take(10)->get();
    
        // Retrieve all packages for parent selection
        $data['parentPackages'] = Package::where('lab_id', Auth::user()->id)
                                         ->whereNull('parent_id')
                                         ->orderBy('package_name')
                                         ->get();
    
        return view("admin.package.create", $data);
    }
    



   
    public function getLabProfiles(Request $request)
{
    $search = $request->get('q');

    // Query the lab_profile table
    $profiles = LabProfile::where('profile_name', 'LIKE', "%$search%")
                ->paginate(10);

    // Return data in the format Select2 expects
    $results = [];
    foreach ($profiles as $profile) {
        $results[] = [
            'id' => $profile->id,
            'name' => $profile->name,
        ];
    }

    return response()->json([
        'items' => $results,
        'total_count' => $profiles->total(),
    ]);
}

    public function deleted($id, $profile_id)
    {
        PackageProfile::where("package_id", $profile_id)
            ->where("profile_id", $id)
            ->delete();

        return redirect()->back();
    }

    // public function amountUpdate(Request $request)
    // {
    //     $package = $request->packagesID;
    //     $amount = $request->amount;
    //     $checkbox = $request->checkbox;

    //    if($package){
    //      foreach($package as $key => $item){
    //         $data = Package::findOrFail($item);
    //         $data = LabSelectedPackages::updateOrCreate([
    //                 "package_id" => $item,
    //                 "lab_id" => Auth::user()->id,
    //             ], [
    //                 "amount" => $amount[$key],
    //                 "is_selected" => $checkbox[$key] ?? 0,
    //             ]
    //         );
    //      }
    //    }
    //    $message = 'Data Updated Succssfully';
    //    return redirect()->back()->with("msg", $message);
    // }
    public function updateSelection(Request $request)
    {
        $package = Package::find($request->id);
    
        if ($package) {
            $package->is_selected = $request->is_selected;
            $package->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false]);
    }
    
    public function packageAmountUpdate(Request $request)
    {
        $data = LabSelectedPackages::where('lab_id', Auth::user()->id)->where('package_id', $id)->get();
        return view('admin.package.index',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Define validation rules
        $validationRules = [
            'package_name' => 'required|unique:labs_packages,package_name,' . $request->id,
            'lab_id' => 'required|exists:users,id', // Ensure lab_id is required and must exist in the users table
            'parent_id' => 'nullable|exists:labs_packages,id', // Ensure parent_id is optional and must exist in the packages table
            'amount' => 'required|numeric',
            
        ];
    
        // Validate request data
        $validatedData = $request->validate($validationRules);
    
        // Check if it's a new record or an update
        $data = Package::updateOrCreate([
            "id" => $request->id, // If editing an existing package
        ], [
            "package_name" => $request->package_name,
            "lab_id" => $request->lab_id,
            "amount" => $request->amount,
            'TAT' => $request->TAT,
            'no_of_parameters' => $request->no_of_parameters,
            "is_frequently_booking" => $request->is_frequently_booking ?? 0,
            "is_lifestyle" => $request->is_lifestyle ?? 0,
            "parent_id" => $request->parent_id,
        ]);
    
        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $path = public_path("/uploads/package/");
            $uploadImg = $this->uploadDocuments($request->file('image'), $path);
            $data->image = $uploadImg;
            $data->save();
        }
    
        // Manage related records in labs_profile_packages table
        if (isset($request->profile_id)) {
            \App\Models\LabsProfilePackage::where('package_id', $data->id)->delete(); // Clear previous entries
    
            foreach ($request->profile_id as $profileId) {
                \App\Models\LabsProfilePackage::updateOrCreate([
                    'lab_profile_id' => $profileId,
                    'package_id' => $data->id,
                ]);
            }
        }
        $users = User::whereHas('roles', function($q) {
            $q->where('id', 4); // Assuming role ID 4 corresponds to users who should receive the notification
        })->get();
    
        
            Mail::to('sharma4271.rs@gmail.com')->send(new PackageCreatedMail($data));
        
        // Determine success message
        $msg = isset($request->id) && !empty($request->id) ? "Updated Successfully." : "Created Successfully";
    
        return redirect()->route("package.index")->with("data_created", $msg);
    }
    
    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $data["page_name"] = "Show";
        $data["title"] = " Package Details";
        $data["data"] = LabProfile::where("id", $id)->first();
        $data["data"] = Package::where("id", $id)->first();
        return view("admin.package.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
{
    // Initialize the data array
    $data = [
        "page_name" => "Edit",
        "title" => "Edit Lab Package",
        // Retrieve the package data or fail with a 404 if not found
        'data' => Package::findOrFail($id),
        // Retrieve the selected profiles from labs_profile_packages based on the package ID
        'selectedProfile' => DB::table('labs_profile_packages')
            ->where('package_id', $id)
            ->join('lab_profile', 'labs_profile_packages.lab_profile_id', '=', 'lab_profile.id')
            ->select('lab_profile.profile_name', 'lab_profile.id')
            ->get(),
    ];

    // Check user role and retrieve data accordingly
    
        // For admins, retrieve all packages, profiles, and labs
        $data['packageName'] = Package::whereNull('parent_id')->orderBy('package_name')->get();
        $data['profiles'] = LabProfile::all(); // Retrieve all profiles for admin
        $data['labs'] = User::whereHas('roles', function($query) {
            $query->where('role_id', 4); // Labs
        })->get();
 

    // Return the view with the prepared data
    return view("admin.package.create", $data);
}

    


    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        // Define validation rules
        $validationRules = [
            'package_name' => 'required',
            'lab_id' => 'required|exists:users,id',
            'parent_id' => 'nullable|exists:packages,id',
        ];

        // Validate request data
        $validatedData = $request->validate($validationRules);

        // Update package record
        $data = Package::findOrFail($id);
        $data->update([
            'package_name' => $request->package_name,
            'lab_id' => $request->lab_id,
            'amount' => $request->amount,
            'TAT' => $request->TAT,
            'no_of_parameters' => $request->no_of_parameters,
            'is_frequently_booking' => $request->is_frequently_booking ?? 0,
            'is_lifestyle' => $request->is_lifestyle ?? 0,
            'parent_id' => $request->parent_id,
        ]);

        // Handle image upload if provided
        if (isset($request->image)) {
            $path = public_path("/uploads/package/");
            $uploadImg = $this->uploadDocuments($request->image, $path);
            $data->image = $uploadImg;
            $data->save();
        }

        // Update lab profile packages
        if (isset($request->profile_id)) {
            \App\Models\LabsProfilePackage::where('package_id', $id)->delete();
            foreach ($request->profile_id as $profileId) {
                \App\Models\LabsProfilePackage::updateOrCreate([
                    'lab_profile_id' => $profileId,
                    'package_id' => $id,
                ]);
            }
        }

        return redirect()->route('package.index')->with('data_updated', 'Updated Successfully.');
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destoryPackage($id)
    {
        Package::find($id)->delete();
        return redirect()
            ->route("package.index")
            ->with("msg", "Package Deleted Successfully");
    }
    public function listWithParentPackage()
{
    $data["title"] = "Packages with Parent Package";
    $data["page_name"] = "List";

    // Fetch packages with parentPackage relationship
    $results = Package::with('parentPackage')->get();

    return view("admin.package.with_parent_package", ['results' => $results, 'data' => $data]);
}

}
