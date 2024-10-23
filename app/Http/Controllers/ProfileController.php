<?php

namespace App\Http\Controllers;

use App\Models\LabProfile;
use App\Models\LabsTestsProfile;
use App\Models\User;
use App\Models\LabTest;
use App\Models\LabTestName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\ProfileCreated; // Add this at the top with your other imports
use Illuminate\Support\Facades\Mail; 
class ProfileController extends Controller
{
    public function index(Request $request)
{
    $data['title'] = 'Lab Profile';
    $data['page_name'] = "List";

    // Determine the user's role
    $user = Auth::user();
    $roleId = $user->roles->first()->id; // Assuming the user has one role

    // Fetch labs with role ID 4 for lab users
    $data['labs'] = User::whereHas('roles', function($q){ 
        $q->where('id', '=', 4); 
    })->get();

    // Start building the query for LabProfile
    $dataTest = LabProfile::query()
        ->join('users', 'lab_profile.lab_id', '=', 'users.id')
        ->select('lab_profile.*', 'users.name as lab_name');

    // Apply filtering based on user role
    if ($roleId == 1) {
        // Admin user: show all profiles
        if (!empty($request->search)) {
            $dataTest->where('lab_profile.profile_name', 'LIKE', '%' . $request->search . '%');
        }
    } else {
        // Lab user: show only profiles for the logged-in lab
        $labId = $user->id; // Assuming the lab ID is the same as the user ID
        $dataTest->where('lab_profile.lab_id', $labId);
        
        if (!empty($request->search)) {
            $dataTest->where('lab_profile.profile_name', 'LIKE', '%' . $request->search . '%');
        }
    }

    // Get the paginated results
    $data['data'] = $dataTest->orderBy('lab_profile.id', 'desc')
        ->paginate($request->input('pagination', 20))
        ->withQueryString();

    return view("admin.labprofile.index", $data);
}


    public function indexDiagno(Request $request)
    {
        $data['title'] = 'Lab Profile';
        $data['page_name'] = "List";
        $dataArray = LabProfile::where('lab_id', 733);

        if (!empty($request->search)) {
            $dataArray->where('profile_name', 'LIKE', '%' . $request->search . '%');
        }

        $data['data'] = $dataArray->orderBy('id', 'desc')->paginate($request->pagination ?? 20)->withQueryString();
        return view("admin.labprofile.index", $data);
    }

    public function test(Request $request)
    {
        $labId = $request->lab_id; // Get the lab_id from the request
        $keyWord = $request->q;

        // Start the query for LabTest
        $query = LabTest::where('test_name', 'LIKE', "%$keyWord%");

        // Filter by lab_id if provided
        if ($labId) {
            $query->where('lab_id', $labId);
        }

        $page = $request->input('page', 1);
        $items = $query->paginate(10, ['*'], 'page', $page);

        $response = [
            'items' => $items->items(),
            'total_count' => $items->total()
        ];

        return response()->json($response);
    }

    public function create(Request $request)
    {
        $data['page_name'] = "Create";
        $data['title'] = "Create New Profile";
        
        // Retrieve profile name if diagnomitra_profile is set
        // if ($request->has('diagnomitra_profile')) {
        //     $profile = LabProfile::where('id', $request->diagnomitra_profile)
        //                  ->select('profile_name')
        //                  ->first();
        //     $data['title'] = $profile ? $profile->profile_name : 'No Profile Found';
        // }
    
        // Retrieve selected tests for the given profile ID
        $data['selectedTests'] = [];
        if ($request->has('id')) {
            $data['selectedTests'] = LabsTestsProfile::where('lab_profile_id', $request->id)->pluck('labs_tests_id')->toArray();
        }
    
        // Retrieve labs with role_id 4
        $data['labs'] = User::whereHas('roles', function($q) {
            $q->where('id', 4);
        })->get();
    
        // Retrieve tests based on user role
        $user = Auth::user();
        $roleId = $user->roles->first()->id; // Assuming one role per user
    
        // For admin role (role_id == 1), fetch all tests
        // For lab role (role_id == 4), also fetch all tests, but you can add specific logic if needed
        $data['tests'] = LabTestName::all(); // Fetch all tests, regardless of lab
    
        // Fetch profiles for Diagnomitra lab
        $data['diagnomitraProfiles'] = LabProfile::where('lab_id', 733)->get();
    
        // Fetch subprofiles
        $data['subprofile'] = LabProfile::where('lab_id', 1)->get();
        
        return view('admin.labprofile.create', $data);
    }
    
    
    
    public function store(Request $request)
{
    // Determine lab ID based on user role
    if (Auth::user()->roles->contains(1)) {
        $labID = $request->lab_id; // Admin assigns the test to the selected lab
    } else {
        $labID = Auth::user()->id; // Lab user assigns the test to their own lab
    }

    // Validation rules
    $validationRules = [
        'profile_name' => 'required_without:diagnomitra_profile|string|max:255',
        'diagnomitra_profile' => 'required_without:profile_name',
        'amount' => 'nullable|numeric',
    ];

    $validatedData = $request->validate($validationRules);

    // Retrieve profile_name if it's being selected from an existing profile
    $profileName = null;
    // if ($request->has('diagnomitra_profile')) {
    //     $profile = LabProfile::where('id', $request->diagnomitra_profile)
    //         ->select('profile_name')
    //         ->first();
    //     $profileName = $profile ? $profile->profile_name : $request->input('profile_name');
    // } else {
    //     $profileName = $request->input('profile_name');
    // }
    if ($request->has('diagnomitra_profile')) {
        $profile = LabProfile::where('id', $request->diagnomitra_profile)
            ->select('profile_name')
            ->first();
        $profileName = $profile ? $profile->profile_name : $request->input('profile_name');
    } else {
        $profileName = $request->input('profile_name');
    }
    
    
// dd($profileName);
    // Create or update the LabProfile record
    $data = LabProfile::updateOrCreate([
        'id' => $request->id,
    ], [
        'profile_name' => $profileName,
        'amount' => $request->amount,
        // 'image' => $request->image,
        'description' => $request->description,
        'lab_id' => $labID,
    ]);
    if ($request->hasFile('image')) {
        $path = public_path("/uploads/profile/");
        $uploadImg = $this->uploadDocuments($request->file('image'), $path);
        $data->image = $uploadImg;
        $data->save();
    }
// dd($data);
    // Prepare to sync test records
    $existingTestIds = LabsTestsProfile::where('lab_profile_id', $data->id)
        ->pluck('labs_tests_id')
        ->toArray();

    $labsTestsProfileData = [];
    foreach ($request->test_id as $testId) {
        // Fetch the amount from lab_test_name based on test_id and lab_id
        $labTest = LabTestName::where('test_id', $testId)
            ->where('lab_id', $labID)
            ->first();
        $testAmount = $labTest ? $labTest->amount : 0;

        // Check if the test is existing or new
        if (in_array($testId, $existingTestIds)) {
            // Update existing records
            LabsTestsProfile::updateOrCreate(
                ['labs_tests_id' => $testId, 'lab_profile_id' => $data->id],
                ['amount' => $testAmount]
            );
        } else {
            // Add new records
            $labsTestsProfileData[] = [
                'labs_tests_id' => $testId,
                'lab_profile_id' => $data->id,
                'lab_id' => $labID,
                'amount' => $testAmount,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    }

    if (!$request->id) { // Check if it's a new profile (id not provided)
        Mail::to('sharma4271.rs@gmail.com')->send(new ProfileCreated($data));
    }
    // if (!$request->id) { // Check if it's a new profile (id not provided)
    //     Mail::to(Auth::user()->email)->send(new ProfileCreated($data));
    // }
    // Insert new LabsTestsProfile records
    if (!empty($labsTestsProfileData)) {
        LabsTestsProfile::insert($labsTestsProfileData);
    }
  
    // Handle image upload if provided
    // if ($request->hasFile('image')) {
    //     $path = public_path("/uploads/profile/");
    //     $uploadImg = $this->uploadDocuments($request->image, $path);
    //     $data->image = $uploadImg;
    //     $data->save();
    // }
    $newTestIds = $request->test_id ?? []; // Initialize as an empty array if not present

    foreach ($newTestIds as $testId) {
        // Fetch the amount from lab_test_name based on test_id and lab_id
        $labTest = LabTestName::where('test_id', $testId)
            ->where('lab_id', $labID)
            ->first();
        $testAmount = $labTest ? $labTest->amount : 0;

        // Add or update the LabsTestsProfile record
        LabsTestsProfile::updateOrCreate(
            ['labs_tests_id' => $testId, 'lab_profile_id' => $data->id],
            ['amount' => $testAmount]
        );
    }

    // Remove tests that are no longer associated
    $testsToRemove = array_diff($existingTestIds, $newTestIds);
    if (!empty($testsToRemove)) {
        LabsTestsProfile::where('lab_profile_id', $data->id)
            ->whereIn('labs_tests_id', $testsToRemove)
            ->delete(); // Remove tests that are no longer associated
    }
    // if ($request->hasFile('image')) {
    //     $path = public_path("/uploads/profile/");
    //     $uploadImg = $this->uploadDocuments($request->file('image'), $path);
    //     $data->image = $uploadImg;
    //     $data->save();
    // }
    // if ($request->hasFile('image')) {
    //     $path = $request->file('image')->store('uploads/profile', 'public'); 
    //     $data->image = $path; 
    //     $data->save();
    // }
    // Set success message based on whether the record was created or updated
    $msg = isset($request->id) && !empty($request->id) ? 'Updated Successfully.' : 'Created Successfully';
    return redirect()->to($request->url)->with('data_created', $msg);
}


//     public function store(Request $request)
// {
//     // Determine lab ID based on user role
//     $labID = $request->lab_id ?? 1;
//     if (Auth::user()->roles->contains(4)) {
//         $labID = Auth::user()->id;
//     }

//     // Validation rules
//     $validationRules = [
//         'profile_name' => 'required|string|max:255', // Add validation for profile_name
//         'amount' => 'nullable|numeric', // Amount is calculated, no need to validate
//         // Add any other necessary validation rules
//     ];

//     $validatedData = $request->validate($validationRules);

//     // Retrieve profile_name if it's being selected from an existing profile
//     $profileName = null;
//     if ($request->has('diagnomitra_profile')) {
//         $profile = LabProfile::where('id', $request->diagnomitra_profile)
//             ->select('profile_name')
//             ->first();
//         $profileName = $profile ? $profile->profile_name : $request->input('profile_name'); // Fallback to input profile_name
//     } else {
//         $profileName = $request->input('profile_name'); // Use the form input if not using an existing profile
//     }

//     // Create or update the LabProfile record
//     $data = LabProfile::updateOrCreate([
//         'id' => $request->id,
//     ], [
//         'profile_name' => $profileName,
//         'amount' => $request->amount,
//         'description' => $request->description,
//         'lab_id' => $labID,
//     ]);

//     // Insert related LabsTestsProfile records if test_id is provided
//     if (!empty($request->test_id)) {
//         $labsTestsProfileData = [];
//         foreach ($request->test_id as $testId) {
//             // Fetch the amount from lab_test_name based on test_id and lab_id
//             $labTest = LabTestName::where('test_id', $testId)
//                 ->where('lab_id', $labID)
//                 ->first();

//             // Set the amount for this test from lab_test_name
//             $testAmount = $labTest ? $labTest->amount : 0;

//             // Add to labsTestsProfileData array
//             $labsTestsProfileData[] = [
//                 'labs_tests_id' => $testId,
//                 'lab_profile_id' => $data->id,
//                 'lab_id' => $labID,
//                 'amount' => $testAmount, // Store the amount retrieved from lab_test_name
//             ];
//         }

//         // Insert the data into the LabsTestsProfile table
//         LabsTestsProfile::insert($labsTestsProfileData);
//     }

//     // Handle image upload if provided
//     if ($request->hasFile('image')) {
//         $path = public_path("/uploads/profile/");
//         $uploadImg = $this->uploadDocuments($request->image, $path);
//         $data->image = $uploadImg;
//         $data->save();
//     }

//     // Set success message based on whether the record was created or updated
//     $msg = isset($request->id) && !empty($request->id) ? 'Updated Successfully.' : 'Created Successfully';
//     return redirect()->to($request->url)->with('data_created', $msg);
// }



    public function show($id, Request $request)
    {
        $data['page_name'] = "Show";
        $data['title'] = 'Profile Details';
        $data['data'] = LabProfile::where('id', $id)->first();

        return view('admin.labprofile.show', $data);
    }


    // public function show($id, Request $request)
    // {
    //     $data['page_name'] = "Show";
    //     $data['title'] = 'Profile Details';
    
    //     // Retrieve the profile and associated test details with amount and test names
    //     $data['data'] = LabProfile::with(['labsTestsProfiles' => function ($query) {
    //         $query->join('labs_tests', 'labs_tests_profiles.labs_tests_id', '=', 'labs_tests.id') // Join labs_tests to get test_name
    //               ->join('lab_test_name', function ($join) {
    //                   $join->on('labs_tests_profiles.labs_tests_id', '=', 'lab_test_name.test_id')
    //                        ->on('lab_test_name.lab_id', '=', 'labs_tests_profiles.lab_id'); // Match by lab_id
    //               })
    //               ->select('labs_tests.test_name', 'lab_test_name.amount', 'labs_tests_profiles.*'); // Select test_name and amount
    //     }])
    //     ->where('id', $id)
    //     ->first();
    
    //     return view('admin.labprofile.show', $data);
    // }

    public function edit($id)
    {
        $labProfile = LabProfile::find($id);
    
        $categories = [];
        $categories['title'] = $labProfile->profile_name;
        $categories['page_name'] = "Edit";
        $categories['data'] = $labProfile; // Use the retrieved profile data
        $categories['subprofile'] = LabProfile::where('lab_id', 1)->get();
    
        // Fetch labs with role ID 4 (for Admin view)
        $categories['labs'] = User::whereHas('roles', function($q){ 
            $q->where('id', '=', 4); 
        })->get();
    
        // Fetch selected tests and their names for the lab profile
        $categories['selectedTests'] = LabsTestsProfile::where('lab_profile_id', $labProfile->id)
            ->join('labs_tests', 'labs_tests.id', '=', 'labs_tests_profiles.labs_tests_id')
            ->pluck('labs_tests.test_name', 'labs_tests.id') // Fetch test names with their IDs
            ->toArray();
    
        // Check if the logged-in user has role_id 4 (Lab Role)
        if (Auth::user()->roles->contains(4)) {
            // Fetch Diagnomitra profiles
            $categories['diagnomitraProfiles'] = LabProfile::where('lab_id', 733)->get(); // Assuming lab_id 585 is Diagnomitra lab
        }
    
        return view('admin.labprofile.create', $categories);
    }
    
    public function updateSelection(Request $request)
    {
        // Find the profile by ID
        $profile = LabProfile::find($request->profile_id);
    
        if ($profile) {
            // Update the 'is_selected' field
            $profile->is_selected = $request->is_selected;
            $profile->save();
    
            return response()->json(['success' => true, 'message' => 'Profile selection updated successfully']);
        }
    
        return response()->json(['success' => false, 'message' => 'Profile not found']);
    }
    

    
    public function update(Request $request, $id)
    {
        return $this->store($request);
    }

    public function destoryProfile($id)
    {
        LabProfile::find($id)->delete();
        return redirect()->back()->with('msg', 'Profile Deleted Successfully');
    }
    
//     public function getProfileNames(Request $request)
// {
//     $labId = 585; 
//     $query = $request->get('q');

    
    
//     $profiles = LabProfile::where('lab_id', $labId)
//         ->where('profile_name', 'LIKE', "%$query%")
//         ->get(['id', 'profile_name as text']);

//     return response()->json(['items' => $profiles]);
// }

// ProfileController.php

public function getTest(Request $request)
{
    $labId = $request->lab_id; // Get the lab_id from the request if provided
    $keyWord = $request->q; // Search keyword

    // Start the query for LabTest
    $query = LabTest::where('test_name', 'LIKE', "%$keyWord%");

    // Filter by lab_id if provided
    if ($labId) {
        $query->where('lab_id', $labId);
    }

    $page = $request->input('page', 1);
    $items = $query->paginate(10, ['*'], 'page', $page);

    $response = [
        'items' => $items->items(),
        'total_count' => $items->total()
    ];

    return response()->json($response);
}

public function getTestsByProfile(Request $request)
{
    $profileId = $request->query('profile_id');
    
    $tests = LabsTestsProfile::where('lab_profile_id', $profileId)
        ->join('labs_tests', 'labs_tests.id', '=', 'labs_tests_profiles.labs_tests_id')
        ->get(['labs_tests.id', 'labs_tests.test_name']);

    return response()->json($tests);
}


// public function getLabProfiles($labId)
// {
//     // Fetch lab profiles with the count of associated tests and include lab name
//     $profiles = LabProfile::withCount('labsTestsProfiles')  // Ensure the labsTestsProfiles relationship is defined
//         ->join('users', 'lab_profile.lab_id', '=', 'users.id')  // Join with the users table (or whichever table stores lab names)
//         ->select('lab_profile.*', 'users.name as lab_name')  // Select all profile fields and the lab name
//         ->where('lab_profile.lab_id', $labId)
//         ->get();

//     return response()->json(['profiles' => $profiles]);
// }

public function getLabProfiles($labId)
{
    // Fetch lab profiles with the count of associated tests and include lab name
    $profiles = LabProfile::withCount('labsTestsProfiles')  // Count the associated test profiles
        ->with(['lab:id,name'])  // Load the related lab name
        ->where('lab_id', $labId)
        ->get();

    // Format profiles to include lab name directly in the profile data
    $formattedProfiles = $profiles->map(function($profile) {
        return [
            'id' => $profile->id,
            'lab_id' => $profile->lab_id,
            'profile_name' => $profile->profile_name,
            'amount' => $profile->amount,
            'total_tests' => $profile->labs_tests_profiles_count,
            'lab_name' => $profile->lab->name ?? '--'  // Use lab name from related model
        ];
    });

    return response()->json(['profiles' => $formattedProfiles]);
}

}
