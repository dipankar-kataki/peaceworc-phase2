<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ManageSiteLayout;
use Illuminate\Http\Request;
use App\Mail\GetInTouchMail;
// use App\Models\Blog;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\IpUtils;

class SiteController extends Controller
{
    
    public function index(Request $request)
    {
        try{
            $site_layout = ManageSiteLayout::get();
            return view('site.index')->with(['site_layout' => $site_layout]);
        }catch(Exception $e){
            echo 'Oops! Something Went Wrong.' . $e;
        }
    }

    public function contact(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required',
                'subject' => 'required',
                'message' => 'required',
            ],
            [
                'name.required' => 'Please enter your name',
                'email.required' => 'Please enter your email',
                'subject.required' => 'Please enter subject',
                'message.required' => 'Please enter your message',
            ]
        );

        $details = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        $recaptcha_response = $request->input('g-recaptcha-response');

        if (is_null($recaptcha_response)) {
            $icon = "error";
            $title = "Error";
            $text = "Please Complete the Recaptcha to proceed. ";
        }

        $url = "https://www.google.com/recaptcha/api/siteverify";

        $body = [
            'secret' => config('services.recaptcha.secret'),
            'response' => $recaptcha_response,
            'remoteip' => IpUtils::anonymize($request->ip()) //anonymize the ip to be GDPR compliant. Otherwise just pass the default ip address
        ];

        $response = Http::asForm()->post($url, $body);

        $result = json_decode($response);

        try {

            if ($response->successful() && $result->success == true) {
                // $request->authenticate();

                Mail::to('dipankar.kataki@ekodusinc.com')->send(new GetInTouchMail($details));
                $icon = "success";
                $title = "Success";
                $text = "Email is sent";
            } else {
                $icon = "error";
                $title = "Error";
                $text = "Please Complete the Recaptcha Again to proceed. ";
            }


            
        } catch (Exception $e) {
            $icon = "error";
            $title = "Error";
            $text = $e;
        }

        return response()->json([
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ]);
    }

    // public function blogs(Request $request, $id = null, $viewAs = null)
    // {
    //     if (!$id) {
    //         $blogs = Blog::where('is_activate', 1)->orderBy('created_at', 'DESC')->get();
    //         return view('site.blog.index')->with(['blogs' => $blogs]);
    //     }elseif($viewAs == 'admin'){
    //         $blogs = Blog::orderBy('created_at', 'DESC')->get();
    //         return view('site.blog.index')->with(['blogs' => $blogs]);
    //     }else {
    //         $id = Crypt::decrypt($id);
    //         $blog_details = Blog::where('id', $id)->where('is_activate', 1)->first();
    //         return view('site.blog.blogDetails')->with(['blog_details' => $blog_details]);
    //     }
    // }

    public function terms(){
        return view('site.documents.terms');
    }

    public function privacy(){
        return view('site.documents.privacy');
    }
}
