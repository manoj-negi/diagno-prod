<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaticPage;
use Str;

class StaticPageController extends Controller
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
            $q = StaticPage::orderBy('id', 'DESC');

            if (isset($_GET['paginate'])) {
                $records = $request->paginate;
            }
            if (isset($request->filter)) {

                $q->where('status', $request->filter);
            }

            if ($request->search) {

                $q->where('title', 'LIKE', '%' . $request->search . '%');
            }


            $data['data'] = $q->paginate(isset($records->pagination) && !empty($records->pagination)?$records->pagination:10)->withQueryString();

            $data['title'] = "Static Page";
            $data['page_name'] = "List";
            return view('admin.staticpages.index', $data);
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
        }


    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = "Static Page";
        $data['page_name'] = "Create";
        return view('admin.staticpages.create', $data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      

        if (isset($request->id) && $request->id == 4) {


            $validate = $request->validate([
                'title' => 'required',
                'top_content' => 'required',
                // 'top_content_banner_image' => 'required',
                // 'middle_content_banner_image' => 'required',
                'middle_content' => 'required',
                // 'service_icon_image_left' => 'required',
                // 'service_icon_image_middle' => 'required',
                // 'service_icon_image_right' => 'required',
                'service_content_left' => 'required',
                'service_content_right' => 'required',
                'service_content_middle' => 'required',
                'support_heading' => 'required',
                // 'support_image_first' => 'required',
                // 'support_image_second' => 'required',
                // 'support_image_third' => 'required',
                // 'support_image_forth' => 'required',
                'support_content_first' => 'required',
                'support_content_second' => 'required',
                'support_content_third' => 'required',
                'support_content_forth' => 'required',
                'section_4_title' => 'required',
                'section_4_description' => 'required',
                'section4_slider_heading' => 'required',
                'section4_slider_description' => 'required',

                // 'app_section_image' => 'required',

            ]);

            if (count($request->section4_slider_heading) != count($request->section4_slider_description)) {

                return redirect()->back()->with('slider_error', 'all headings and descriptions is required');
            }

            $pagedata['top_content'] = $request->top_content;
            $pagedata['middle_content'] = $request->middle_content;
            $pagedata['service_content_left'] = $request->service_content_left;
            $pagedata['service_content_right'] = $request->service_content_right;
            $pagedata['service_content_middle'] = $request->service_content_middle;
            $pagedata['support_heading'] = $request->support_heading;
            $pagedata['support_content_first'] = $request->support_content_first;
            $pagedata['support_content_second'] = $request->support_content_second;
            $pagedata['support_content_third'] = $request->support_content_third;
            $pagedata['support_content_forth'] = $request->support_content_forth;
            $pagedata['app_download_google_playstore_url'] = $request->app_download_google_playstore_url;
            $pagedata['app_download_apple_playstore_url'] = $request->app_download_apple_playstore_url;
            $pagedata['section_4_title'] = $request->section_4_title;
            $pagedata['section_4_description'] = $request->section_4_description;

            $prev = null;

            if (isset($request->id)) {

                $static = StaticPage::where('id', $request->id)->first();
                $prev = json_decode($static->content, true);
                $prev = json_decode($static->spenish_content, true);
            }

            $pagedata['top_content_banner_image'] = isset($prev['top_content_banner_image']) ? $prev['top_content_banner_image'] : '';
            $pagedata['middle_content_banner_image'] = isset($prev['middle_content_banner_image']) ? $prev['middle_content_banner_image'] : '';
            $pagedata['service_icon_image_left'] = isset($prev['service_icon_image_left']) ? $prev['service_icon_image_left'] : '';
            $pagedata['service_icon_image_middle'] = isset($prev['service_icon_image_middle']) ? $prev['service_icon_image_middle'] : '';
            $pagedata['service_icon_image_right'] = isset($prev['service_icon_image_right']) ? $prev['service_icon_image_right'] : '';
            $pagedata['support_image_first'] = isset($prev['support_image_first']) ? $prev['support_image_first'] : '';
            $pagedata['support_image_second'] = isset($prev['support_image_second']) ? $prev['support_image_second'] : '';
            $pagedata['support_image_third'] = isset($prev['support_image_third']) ? $prev['support_image_third'] : '';
            $pagedata['support_image_forth'] = isset($prev['support_image_forth']) ? $prev['support_image_forth'] : '';
            $pagedata['app_section_image'] = isset($prev['app_section_image']) ? $prev['app_section_image'] : '';


            $section_4_images = [];

            if (isset($request->section4_slider_image)) {

                foreach ($request->section4_slider_image as $key => $image) {

                    $imagename = substr(time(), -3) . $image->getClientOriginalName();
                    $image->move('frontend_images', $imagename);
                    $section_4_images[$key] = url('frontend_images') . '/' . $imagename;
                }

            }



            foreach ($request->section4_slider_heading as $key => $value) {

                $heading = $value;
                $description = isset($request->section4_slider_description[$key]) ? $request->section4_slider_description[$key] : '';

                if (isset($section_4_images[$key])) {
                    $image = $section_4_images[$key];
                } elseif (isset($prev['section_4_slider'][$key]['image'])) {
                    $image = $prev['section_4_slider'][$key]['image'];
                } else {
                    $image = '';
                }

                $pagedata['section_4_slider'][] = ['heading' => $heading, 'description' => $description,'spenish_description' => $description,  'image' => $image];

            }


            if ($request->hasfile('top_content_banner_image')) {

                $data = $request->file('top_content_banner_image');
                $top_content_banner_image = $data->getClientOriginalName();
                $path = 'frontend_images';
                $data->move($path, $top_content_banner_image);
                $pagedata['top_content_banner_image'] = url('frontend_images') . '/' . $top_content_banner_image;

            }
            if ($request->hasfile('middle_content_banner_image')) {

                $data = $request->file('middle_content_banner_image');
                $middle_content_banner_image = $data->getClientOriginalName();
                $path = 'frontend_images';
                $data->move($path, $middle_content_banner_image);
                $pagedata['middle_content_banner_image'] = url('frontend_images') . '/' . $middle_content_banner_image;

            }
            if ($request->hasfile('service_icon_image_left')) {
                $data = $request->file('service_icon_image_left');
                $service_icon_image_left = $data->getClientOriginalName();
                $path = 'frontend_images';
                $data->move($path, $service_icon_image_left);
                $pagedata['service_icon_image_left'] = url('frontend_images') . '/' . $service_icon_image_left;
            }
            if ($request->hasfile('service_icon_image_middle')) {
                $data = $request->file('service_icon_image_middle');
                $service_icon_image_middle = $data->getClientOriginalName();
                $path = 'frontend_images';
                $data->move($path, $service_icon_image_middle);
                $pagedata['service_icon_image_middle'] = url('frontend_images') . '/' . $service_icon_image_middle;
            }


            if ($request->hasfile('service_icon_image_right')) {
                $data = $request->file('service_icon_image_right');
                $service_icon_image_right = $data->getClientOriginalName();
                $path = 'frontend_images';
                $data->move($path, $service_icon_image_right);
                $pagedata['service_icon_image_right'] = url('frontend_images') . '/' . $service_icon_image_right;

            }
            if ($request->hasfile('support_image_first')) {
                $data = $request->file('support_image_first');
                $support_image_first = $data->getClientOriginalName();
                $path = 'frontend_images';
                $data->move($path, $support_image_first);
                $pagedata['support_image_first'] = url('frontend_images') . '/' . $support_image_first;

            }
            if ($request->hasfile('support_image_second')) {
                $data = $request->file('support_image_second');
                $support_image_second = $data->getClientOriginalName();
                $path = 'frontend_images';
                $data->move($path, $support_image_second);
                $pagedata['support_image_second'] = url('frontend_images') . '/' . $support_image_second;

            }
            if ($request->hasfile('support_image_third')) {
                $data = $request->file('support_image_third');
                $support_image_third = $data->getClientOriginalName();
                $path = 'frontend_images';
                $data->move($path, $support_image_third);
                $pagedata['support_image_third'] = url('frontend_images') . '/' . $support_image_third;

            }
            if ($request->hasfile('support_image_forth')) {
                $data = $request->file('support_image_forth');
                $support_image_forth = $data->getClientOriginalName();
                $path = 'frontend_images';
                $data->move($path, $support_image_forth);
                $pagedata['support_image_forth'] = url('frontend_images') . '/' . $support_image_forth;

            }
            if ($request->hasfile('app_section_image')) {
                $data = $request->file('app_section_image');
                $app_section_image = $data->getClientOriginalName();
                $path = 'frontend_images';
                $data->move($path, $app_section_image);
                $pagedata['app_section_image'] = url('frontend_images') . '/' . $app_section_image;

            }
            $result = json_encode($pagedata);

            if ($result) {
                $data = StaticPage::UpdateOrCreate([
                    'id' => $request->id,
                ], [
                        'content' => $result,
                        'spenish_content' => $result,
                        'title' => $request->title,
                        'spenish_title' => $request->spenish_title,
                        'meta_keyword' => $request->meta_keyword,
                        'spenish_meta_keyword' => $request->spenish_meta_keyword,
                        'meta_title' => $request->meta_title,
                        'spenish_meta_title' => $request->spenish_meta_title,
                        'meta_description' => $request->meta_description,
                        'spenish_meta_description' => $request->spenish_meta_description,
                    ]);
            }
        } else {

            $request->validate([
                'title' => 'required',
                'content' => 'required'
            ]);

            $pagedata['content'] = $request->content;

            $result = json_encode($pagedata);

            if ($result) {
                $data = StaticPage::UpdateOrCreate(['id' => $request->id], [
                    'content' => $result,
                    'spenish_content' => $result,
                    'slug' => str::slug($request->title),
                    'spenish_slug' => str::slug($request->title),
                    'title' => $request->title,
                    'spenish_title' => $request->spenish_title,
                    'status' => $request->status,
                    'meta_keyword' => $request->meta_keyword,
                    'spenish_meta_keyword' => $request->spenish_meta_keyword,
                    'meta_title' => $request->meta_title,
                    'spenish_meta_title' => $request->spenish_meta_title,
                    'meta_description' => $request->meta_description,
                    'spenish_meta_description' => $request->spenish_meta_description,

                ]);
            }


        }

        if ($data) {
            if ($request->id) {
                return redirect()->route('static-pages.index')->with('msg', 'Page is successfully updated');
            } else {
                return redirect()->route('static-pages.index')->with('msg', 'Page is successfully created');
            }
        } else {
            return redirect()->back()->with('msg', 'Something went Wrong, Please try again!');
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
        $result['datas'] = StaticPage::findOrFail($id);
        $data = StaticPage::select('content','spenish_content')->where('id', $id)->first();
        $dataresult = json_decode($data->content ?? '');
        $dataresult = json_decode($data->spenish_content ?? '');
        $result['title'] = "Static Page";
        $result['page_name'] = "Edit";
        if ($result['datas']) {

            return view('admin.staticpages.create', $result, compact('dataresult','dataresult'));
        } else {
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
        $data = StaticPage::find($id);
        $data->delete();

        if ($data) {
            return redirect()->route('static-pages.index')->with('msg', 'Page is successfully Deleted');
        } else {
            return redirect()->route('static-pages.index')->with('msg', 'Page unsuccessfully Deleted');
        }
    }
}