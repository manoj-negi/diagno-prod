<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabTest;
use App\Models\LabTestName;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Mail\LabTestCreatedMail; // Add this at the top with your other imports
use Illuminate\Support\Facades\Mail; 
class LabTestController extends Controller
{
    public function index(Request $request)
{
    $data['title'] = "Lab Test";
    $data['page_name'] = "List";
    
    // Get list of labs with role id 4
    $data['labs'] = User::whereHas('roles', function ($q) {
        $q->where('id', 4);
    })->get();
    
    // Build the query with joins and filters
    $dataTest = LabTestName::query()
        ->join('labs_tests', 'lab_test_name.test_id', '=', 'labs_tests.id')
        ->join('users', 'lab_test_name.lab_id', '=', 'users.id')
        ->select('lab_test_name.*', 'labs_tests.test_name', 'users.name as lab_name');

    // Filter by search
    if (!empty($request->search)) {
        $dataTest->where('labs_tests.test_name', 'LIKE', '%' . $request->search . '%');
    }

    // Filter by lab
    if (!empty($request->lab)) {
        $dataTest->where('lab_test_name.lab_id', $request->lab);
    }

    // Apply filter for labs with role_id = 4
    if (Auth::user()->roles->contains(4)) {
        $dataTest->where('lab_test_name.lab_id', Auth::user()->id);
    }

    // Get paginated data
    $data['data'] = $dataTest->orderBy('lab_test_name.id', 'DESC')
        ->paginate($request->pagination ?? 10)
        ->withQueryString();

    // Pass logged-in user's lab_id to the view
    $data['lab_id'] = Auth::user()->id;

    return view('admin.lab-test.index', $data);
}
    public function indexDiagno(Request $request)
    {
        $data['title'] = "Lab Test";
        $data['page_name'] = "List";
    
        // Start building the query with joins and filters
        $dataTest = LabTestName::query()
            ->join('labs_tests', 'lab_test_name.test_id', '=', 'labs_tests.id')
            ->join('users', 'lab_test_name.lab_id', '=', 'users.id')
            ->select('lab_test_name.*', 'labs_tests.test_name', 'users.name as lab_name')
            ->where('lab_test_name.lab_id', 585); // Filter for lab_id 585
    
        // Apply search filter if a search query is provided
        if (!empty($request->search)) {
            $dataTest->where('labs_tests.test_name', 'LIKE', '%' . $request->search . '%');
        }
    
        // Fetch the filtered and paginated results
        $data['data'] = $dataTest->orderBy('lab_test_name.id', 'DESC')
            ->paginate($request->pagination ?? 10)
            ->withQueryString();
    
        return view('admin.lab-test.index', $data);
    }
    
    public function create()
    {
        $result['title'] = "Lab";
        $result['page_name'] = "Create";
        $result['labs'] = User::whereHas('roles', function ($query) {
            $query->where('id', 4);
        })->get();
        return view('admin.lab-test.create', $result);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'test_name' => 'required|string|max:255',
    //         'amount' => 'required|numeric',
    //         'lab_id' => 'required|exists:users,id',  // Validate that lab_id exists in users table
    //         'description' => 'nullable|string',
    //     ]);
    
    //     // Check if the authenticated user is an admin
    //     if (Auth::user()->roles->contains(1)) {
    //         // Admin logic
    //         $lab_id = $request->lab_id; // Admin assigns the test to the selected lab
    //     } else {
    //         // Lab user logic
    //         $lab_id = Auth::user()->id; // Lab assigns the test to their own lab
    //     }
    
    //     // Check if the test already exists
    //     $existingTest = LabTest::where('test_name', $request->test_name)->first();
    
    //     if ($existingTest) {
    //         $testId = $existingTest->id;
    //     } else {
    //         // Create a new test if it doesn't exist
    //         $test = LabTest::create([
    //             'test_name' => $request->test_name,
    //         ]);
    //         $testId = $test->id;
    //     }
    
    //     // Check if the test already exists for the current lab
    //     $existingLabTest = DB::table('lab_test_name')
    //         ->where('test_id', $testId)
    //         ->where('lab_id', $lab_id)
    //         ->first();
    
    //     if ($existingLabTest) {
    //         return redirect()->back()->with('msg', 'This test already exists for the selected lab.');
    //     }
    
    //     // Insert a new record into lab_test_name
    //     DB::table('lab_test_name')->insert([
    //         'test_id' => $testId,
    //         'lab_id' => $lab_id,  // Use the correct lab_id
    //         'amount' => $request->amount,
    //         'description' => $request->description,
    //     ]);
    
    //     return redirect()->to($request->url ?? route('lab-test.index'))->with('msg', 'Test Successfully Created');
    // }
    



    public function store(Request $request)
    {
        
        // Validate the incoming request
        $request->validate([
            'test_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'test_id' => 'nullable|exists:labs_tests,id', // Validate that test_id exists if provided
            'lab_test_name_id' => 'nullable|exists:lab_test_name,id', // Validate that lab_test_name_id exists if provided
        ]);
    
        // Determine the lab ID based on user role
        if (Auth::user()->roles->contains(1)) {
            $lab_id = $request->lab_id; // Admin assigns the test to the selected lab
        } else {
            $lab_id = Auth::user()->id; // Lab user assigns the test to their own lab
        }
    
        // Check if we are updating an existing test
        if ($request->has('test_id')) {
            $test = LabTest::find($request->test_id);
            if (!$test) {
                return redirect()->back()->with('msg', 'Test not found.');
            }
    
            // Check for duplicates if test name has changed
            if ($test->test_name !== $request->test_name) {
                $existingTest = LabTest::where('test_name', $request->test_name)->first();
                if ($existingTest && $existingTest->id != $test->id) {
                    return redirect()->back()->with('msg', 'A test with this name already exists.');
                }
            }
    
            // Update the existing test
            $test->update([
                'test_name' => $request->test_name,
            ]);
            $testId = $test->id;
        } else {
            // Creating a new test
            $existingTest = LabTest::where('test_name', $request->test_name)->first();
            if ($existingTest) {
                $testId = $existingTest->id;
            } else {
                $test = LabTest::create([
                    'test_name' => $request->test_name,
                ]);
                $testId = $test->id;
            }
        }
    
        // Check if this test already exists for the current lab
        $query = DB::table('lab_test_name')
            ->where('test_id', $testId)
            ->where('lab_id', $lab_id);
    
        // Exclude the current record if updating
        if ($request->has('lab_test_name_id')) {
            $query->where('id', '<>', $request->lab_test_name_id); // Ensure this is the correct column name for the primary key of lab_test_name
        }
    
        $existingLabTest = $query->first();
    
        if ($request->id=='' && $existingLabTest) {
            return redirect()->back()->with('msg', 'This test already exists for the selected lab. Please choose a different test name or lab.');
        }
    
        // Insert or update the record in lab_test_name
        if ($request->id!='') {
            // Update existing record
            DB::table('lab_test_name')
                ->where('id', $request->id) // Update using lab_test_name_id
                ->update([
                    'test_id' => $testId,
                    'amount' => $request->amount,
                    'description' => $request->description,
                    'updated_at' => now(),
                ]);
        } else
         {
            // Insert new record
            DB::table('lab_test_name')->insert([
                'test_id' => $testId,
                'lab_id' => $lab_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    
        $user = Auth::user();
        $labTestData = (object) [
            'test_name' => $request->test_name,
            'amount' => $request->amount,
        ];
    
        // Send the email notification
        Mail::to('sharma4271.rs@gmail.com')->send(new LabTestCreatedMail($labTestData));
        return redirect()->to($request->url ?? route('lab-test.index'))->with('msg', 'Test Successfully ' . ($request->has('lab_test_name_id') ? 'Updated' : 'Created'));
    }
    
    

    public function edit($id)
    {
        // Retrieve the lab test record by id, joining with labs_tests to get test_name
        $result['data'] = DB::table('lab_test_name')
            ->join('labs_tests', 'lab_test_name.test_id', '=', 'labs_tests.id')
            ->select('lab_test_name.*', 'labs_tests.test_name')
            ->where('lab_test_name.id', $id)
            ->first();
    
        // Check if the record was found
        if (!$result['data']) {
            abort(404, 'Lab Test Not Found');
        }
    
        // Prepare other data
        $result['title'] = "Edit Lab Test";
        $result['page_name'] = "Edit";
        $result['labs'] = User::whereHas('roles', function ($q) {
            $q->where('id', 4);
        })->get();
    
        // Dump the result to inspect it
        // dd($result);
    
        // Return the view with the result data
        return view('admin.lab-test.create', $result);
    }
    
    public function update(Request $request, $id)
    {
        return $this->store($request);
    }

    public function destroy($id)
    {
        $data = LabTestName::find($id);
        if ($data) {
            $data->delete();
            return redirect()->back()->with('msg', 'Test Deleted Successfully');
        }

        return redirect()->back()->with('error', 'Test not found');
    }

    public function autocomplete(Request $request)
    {
        $term = $request->input('term');

       
        $tests = LabTest::where('test_name', 'LIKE', "%{$term}%")
                    ->distinct()
                    ->get(['test_name']);
        
        
        $results = $tests->map(function ($test) {
            return [
                'id' => $test->test_name,
                'text' => $test->test_name,
            ];
        });
    
        
        if ($results->isEmpty()) {
            $results->push([
                'id' => 'new',
                'text' => 'Create new test',
            ]);
        }
    
        return response()->json($results);
    }

    // public function getLabTest($labId, Request $request)
    // {
    //     $search = $request->input('search', '');
    
    //     // Fetch lab tests with search functionality
    //     $data = LabTestName::select('lab_test_name.*', 'labs_tests.test_name')
    //         ->join('labs_tests', 'labs_tests.id', '=', 'lab_test_name.test_id')
    //         ->where('lab_test_name.lab_id', $labId)
    //         ->where(function($query) use ($search) {
    //             $query->where('labs_tests.test_name', 'like', "%{$search}%")
    //                   ->orWhere('lab_test_name.amount', 'like', "%{$search}%");
    //         })
    //         ->get();
    
    //     return response()->json([
    //         'data' => $data
    //     ]);
    // }
    
    public function getLabTest($labId, Request $request)
    {
        $search = $request->input('search', '');
    
        // Fetch lab tests with search functionality and include lab name
        $data = LabTestName::select('lab_test_name.*', 'labs_tests.test_name', 'users.name as lab_name') // Add lab name
            ->join('labs_tests', 'labs_tests.id', '=', 'lab_test_name.test_id')
            ->join('users', 'lab_test_name.lab_id', '=', 'users.id') // Join with the users table to get lab name
            ->where('lab_test_name.lab_id', $labId)
            ->where(function($query) use ($search) {
                $query->where('labs_tests.test_name', 'like', "%{$search}%")
                      ->orWhere('lab_test_name.amount', 'like', "%{$search}%");
            })
            ->get();
    
        return response()->json([
            'data' => $data
        ]);
    }
    
    
    
}
