<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Models\settings;

use Validator;

class HomePageController extends Controller
{

    public function homepage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'slug' => 'required|exists:static_pages,slug'
            ]);
            if ($validator->fails())
                return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);

            $page = StaticPage::where('slug', $request->slug)->first();

            $data = [
                'slug' => $page->slug,
                'content' => json_decode($page->content, true)
            ];
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function testimonial()
    {
        try {

            $testimonial = Testimonial::where('status', 1)->get();

            if (!$testimonial->count())
                return \ResponseBuilder::success(trans('messages.NO_DATA'), $this->success, []);

            $data = $testimonial->map(function ($collect) {
                return [
                    'id' => $collect->id,
                    'name' => $collect->name,
                    'designation' => $collect->designation,
                    'review' => $collect->review,
                    'rating' => $collect->rating,
                    'image' => !empty($collect->image) ? url('uploads/testimonial') . '/' . $collect->image : '',
                    'date' => date('d M Y', strtotime($collect->created_at)),
                    'time' => date('H:i', strtotime($collect->created_at))
                ];
            });

            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);

        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }
    }

    public function siteSetting(Request $request)
    {
        try{

            // $validator = Validator::make($request->all(), [
            //     'key' => 'required|exists:settings,key'
            // ]);

            // if ($validator->fails())
            //    return \ResponseBuilder::fail($validator->errors()->first(), $this->badRequest);
            
            $query = settings::query();
            if(isset($request->key)){
                $query->where('key', $request->key);
            }   
            $data = $query->pluck('value','key')->toArray();

            $data['logo']=url('Images').'/'.$data['logo'];
              $data['footer_logo']=url('Images/footer_logo').'/'.$data['footer_logo'];
              
            return \ResponseBuilder::success(trans('messages.SUCCESS'), $this->success, $data);


        } catch (\Exception $e) {
            return \ResponseBuilder::fail($e->getMessage(), $this->serverError);
        }

    }
}

   

    

