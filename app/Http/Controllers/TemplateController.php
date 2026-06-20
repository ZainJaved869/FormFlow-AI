<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::where('is_active', true)->get();
        $categories = $templates->pluck('category')->unique()->filter()->values();
        return view('templates.index', compact('templates', 'categories'));
    }

    public function useTemplate($slug)
    {
        $template = Template::where('slug', $slug)->firstOrFail();
        
        $form = Auth::user()->forms()->create([
            'title' => $template->name,
            'description' => $template->description,
            'is_published' => false,
            'shareable_link' => Str::random(12),
        ]);

        foreach ($template->fields as $index => $field) {
            $form->fields()->create([
                'type' => $field['type'],
                'label' => $field['label'],
                'placeholder' => $field['placeholder'] ?? '',
                'options' => $field['options'] ?? [],
                'required' => $field['required'] ?? false,
                'order' => $index,
            ]);
        }

        return redirect()->route('forms.edit', $form->id)
            ->with('success', 'Form created from template successfully!');
    }

    public function getFields($slug)
    {
        $template = Template::where('slug', $slug)->firstOrFail();
        return response()->json($template->fields);
    }
}