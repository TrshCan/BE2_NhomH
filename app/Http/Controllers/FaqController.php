<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class FaqController extends Controller
{
    public function index(Request $request)
    {
        $faqs = Faq::orderBy('created_at', 'desc')->paginate(4);
        if ($request->page > $faqs->lastPage()) {
            return redirect()->route('faq.index')
                            ->with('error', 'Trang bạn yêu cầu không tồn tại! chúng tôi có tối đa ' .  $faqs->lastPage() . ' trang' );
        }
        return view('Faq', compact('faqs'));
    }

    public function show($id)
    {
        try {
            $faq = Faq::findOrFail($id);
        } catch (\Exception $ex) {
           
            Session::flash('error', 'Không ID của câu hỏi thường gặp.');
 
            return redirect()->route('faq.index');
        }
      
        return view('Faq_details', compact('faq'));
    }
}