<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Helper\ResponseBuilder;
use Illuminate\Http\Request;
use Auth;


class ApiController extends Controller
{

    public function blogPosts(Request $request)
    {
        try {

            $blogs = [
                [
                    'title' => 'The Impact of Mental Health Awareness in the Workplace',
                    'image_url' => 'https://diagnomitra.com/wp-content/uploads/2024/06/2404261825420411d755-e1718692824640.webp',
                    'url' => 'https://diagnomitra.com/the-impact-of-mental-health-awareness-in-the-workplace/'
                ],
                [
                    'title' => 'Work-Life Balance: Myth or Reality?',
                    'image_url' => 'https://diagnomitra.com/wp-content/uploads/2024/06/2404261825451107d755.webp',
                    'url' => 'https://diagnomitra.com/work-life-balance-myth-or-reality/'
                ],
                [
                    'title' => 'Should Health Insurance Be Mandatory for All',
                    'image_url' => 'https://diagnomitra.com/wp-content/uploads/2024/06/2404261825420411d755-e1718692824640.webp',
                    'url' => 'https://diagnomitra.com/should-health-insurance-be-mandatory-for-all/'
                ]
            ];
            $this->response->blog_data = $blogs;
            return ResponseBuilder::success($this->response, 'Blog list');
        }
        catch (exception $e) {
            return ResponseBuilder::error( __($e->getMessage()), $this->serverError);
        }
    }
}
