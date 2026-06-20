<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Submission;
use Illuminate\Http\Request;

class PublicFormController extends Controller
{
    public function show($link)
    {
        $form = Form::with('fields')->where('shareable_link', $link)->firstOrFail();
        abort_unless($form->is_published, 404);
        return view('form-public.show', compact('form'));
    }

    public function submit(Request $request, $link)
    {
        $form = Form::where('shareable_link', $link)->firstOrFail();
        abort_unless($form->is_published, 404);

        $data = $request->except('_token');

        Submission::create([
            'form_id' => $form->id,
            'user_id' => auth()->id(),
            'data' => $data,
        ]);

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }
}