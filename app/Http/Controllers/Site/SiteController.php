<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\GetInTouchMail;
// use App\Models\Blog;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class SiteController extends Controller
{
    
    public function index(Request $request)
    {
        // $blogs = Blog::where('is_activate', 1)->orderBy('created_at', 'DESC')->take(3)->get();
        // return view('site.index')->with(['blogs' => $blogs]);
        return view('site.index');
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

        try {
            Mail::to('dipankar.kataki@ekodusinc.com')->send(new GetInTouchMail($details));
            $icon = "success";
            $title = "Success";
            $text = "Email is sent";
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
