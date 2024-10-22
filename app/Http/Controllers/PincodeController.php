<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use League\Csv\Reader;
use App\Models\Pincode;
use Illuminate\Http\Request;
use App\Models\User;
use App\Imports\PincodesImport;
class PincodeController extends Controller
{
    // Show the list of pincodes
    public function index()
    {
        $pincodes = Pincode::all();
        $title = 'Pincode List'; 
        $page_name = 'Pincode List'; 
        $labs = User::whereHas('roles', function($q){ 
            $q->where('id', '=', 4); 
        })->get();
        return view('admin.pincodes.index', compact('pincodes', 'title', 'page_name', 'labs'));
    }
    

   
    public function create()
    {
        $title = 'Add New Pincode'; 
        $page_name = 'Pincode Creation'; 
    
        return view('admin.pincodes.create', compact('title', 'page_name'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'pincode' => 'required|numeric|digits:6', 
        ]);

        Pincode::create($request->all());

        return redirect()->route('pincodes.index')->with('success', 'Pincode added successfully.');
    }

    public function edit(Pincode $pincode)
    {
        // $pincodes = Pincode::all();
        $title = 'Edit Pincode'; 
        $page_name = 'Edit Pincode'; 
        return view('admin.pincodes.edit', compact('pincode', 'title' , 'page_name'));
    }

    // public function update(Request $request, Pincode $pincode)
    // {
    //     $request->validate([
    //         'pincode' => 'required|numeric',
    //     ]);

    //     $pincode->update($request->all());

    //     return redirect()->route('pincodes.index')->with('success', 'Pincode updated successfully.');
    // }
    public function update(Request $request, $id)
    {
        // Validate the pincode before updating
        $request->validate([
            'pincode' => 'required|digits:6', // Ensures it's exactly 6 digits
        ]);
    
        // Now, proceed with updating the record after validation
        $pincode = Pincode::findOrFail($id);
        $pincode->pincode = $request->input('pincode');
        $pincode->save();
    
        return redirect()->route('pincodes.index')->with('success', 'Pincode updated successfully!');
    }
    
    public function destroy(Pincode $pincode)
    {
        $pincode->delete();

        return redirect()->route('pincodes.index')->with('success', 'Pincode deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);
    
        // Load the uploaded file
        $file = $request->file('csv_file');
        $csv = Reader::createFromPath($file->getRealPath());
        $csv->setHeaderOffset(0); // Set header offset to get records correctly
        $records = $csv->getRecords();
    
        // Loop through each record and store it in the database
        foreach ($records as $record) {
            // Access the 'Pincode' key from the record
            $pincode = trim($record['Pincode']); // Make sure to match the exact header name
    
            // Validate pincode before insertion
            if (!empty($pincode) && preg_match('/^\d{6}$/', $pincode)) { // Ensure it's exactly 6 digits
                // Check if the pincode already exists
                if (!Pincode::where('pincode', $pincode)->exists()) {
                    Pincode::create(['pincode' => $pincode]);
                } else {
                    \Log::warning('Duplicate pincode skipped: ' . $pincode);
                }
            } else {
                \Log::warning('Invalid pincode skipped: ' . $pincode);
            }
        }
    
        return redirect()->route('pincodes.index')->with('success', 'Pincodes imported successfully!');
    }
    
    // public function showImportForm()
    // {
    //     // Fetch labs (users with role_id = 4)
    //     $labs = User::where('role_id', 4)->get();

    //     return view('pincodes.import', compact('labs'));
    // }

    // public function importPincodeCsv(Request $request)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'lab_id' => 'required|exists:users,id', // Ensure a valid lab is selected
    //         'csv_file' => 'required|mimes:csv,txt|max:2048',
    //     ]);

    //     // Handle CSV file upload
    //     if ($request->hasFile('csv_file')) {
    //         $file = $request->file('csv_file');
    //         $filePath = $file->getRealPath();

    //         // Read and process the CSV
    //         $file = fopen($filePath, 'r');
    //         $isHeader = true;

    //         while (($row = fgetcsv($file)) !== false) {
    //             if ($isHeader) {
    //                 $isHeader = false; // Skip the header row
    //                 continue;
    //             }

    //             // Save the pincode data to the database
    //             Pincode::create([
    //                 'pincode' => $row[0],  // Assuming CSV first column is the pincode
    //                 'lab_id' => $request->lab_id, // Lab selected from the dropdown
    //             ]);
    //         }

    //         fclose($file);
    //     }

    //     return redirect()->back()->with('success', 'Pincodes imported successfully.');
    // }
}
