<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::all();
        return view('Faq', compact('faqs'));
    }

    public function show($id)
    {
        $faq = Faq::findOrFail($id);
        return view('Faq_details', compact('faq'));
    }
}