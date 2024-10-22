<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
class BlogPostController extends Controller
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
        $q = BlogPost::orderBy('id', 'DESC');

        if ($request->has('paginate')) {
            $records = $request->paginate;
        }

        if ($request->has('filter')) {
            $q->where('status', $request->filter);
        }

        if ($request->has('search')) {
            $q->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%')
                      ->orWhere('content', 'LIKE', '%' . $search . '%');
            });
        }

        $blogPosts = $q->paginate($records)->withQueryString(); 
        $title = "Blog Posts";
        $page_name = "Blog Posts List";
        return view('admin.blogposts.index', compact('blogPosts', 'title', 'page_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Create Blog Post";
        $page_name = "Create Blog Post";
        return view('admin.blogposts.create', compact('title', 'page_name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   
     public function store(Request $request)
     {
         $validatedData = $request->validate([
             'title' => 'required|string|max:255',
             'content' => 'required|string',
             'author' => 'required|string|max:255',
             'is_published' => 'required|boolean',
             'slug' => 'required|string|unique:blogposts,slug', // Add slug validation
             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
         ]);
     
         try {
             // Ensure the slug is unique or handle duplication logic here
             $slug = Str::slug($validatedData['slug']);
             $validatedData['slug'] = $slug;
     
             $blogPost = BlogPost::updateOrCreate(['slug' => $slug], $validatedData);
     
             // Handle image upload if exists
             if ($request->hasFile('image')) {
                 $this->deleteOldImage($blogPost);
     
                 $image = $request->file('image');
                 $timestamp = time();
                 $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                 $extension = $image->getClientOriginalExtension();
     
                 // Sanitize the file name
                 $sanitizedName = preg_replace('/[^A-Za-z0-9\-]/', '_', $originalName);
                 $fileName = $sanitizedName . '_' . $timestamp . '.' . $extension;
     
                 // Store the image in S3
                 $imagePath = $image->storeAs('blogposts', $fileName, 's3');
                 $blogPost->image = $imagePath;
                 $blogPost->save();
             }
     
             $message = $request->id ? 'Blog Post is successfully updated' : 'Blog Post is successfully created';
             return redirect()->route('admin.blogposts.index')->with('msg', $message);
         } catch (\Exception $e) {
             return redirect()->back()->with('error', 'Something went wrong, please try again!');
         }
     }
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blogPost = BlogPost::findOrFail($id);
        $title = "Edit Blog Post";
        $page_name = "Edit Blog Post";

        return view('admin.blogposts.create', compact('blogPost', 'title', 'page_name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blogPost = BlogPost::findOrFail($id);
        $this->deleteOldImage($blogPost); // Delete associated image
        $blogPost->delete();

        return redirect()->route('admin.blogposts.index')->with('msg', 'Blog Post successfully deleted');
    }

    /**
     * Delete old image if exists.
     *
     * @param \App\Models\BlogPost $blogPost
     * @return void
     */
    protected function deleteOldImage(BlogPost $blogPost)
    {
        if ($blogPost->image && Storage::disk('s3')->exists($blogPost->image)) {
            Storage::disk('s3')->delete($blogPost->image);
        }
    }
    public function toggleStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:blogposts,id',
                'is_published' => 'required|boolean',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
    
            $blogPost = BlogPost::find($request->input('id'));
            if ($blogPost) {
                $blogPost->is_published = $request->input('is_published');
                $blogPost->save();
    
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Blog post not found'], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Error toggling blog post status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Internal Server Error'], 500);
        }
    }
    
    
    

}
