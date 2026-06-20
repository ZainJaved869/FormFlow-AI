<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Services\AIGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FormController extends Controller
{
    /**
     * List forms
     */
    public function index()
    {
        $forms = Auth::user()->forms()->withCount('submissions')->get();
        return view('forms.index', compact('forms'));
    }

    /**
     * Create form page
     */
    public function create()
    {
        return view('forms.create');
    }

    /**
     * Store form
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
            'fields' => 'array',
            'fields.*.type' => 'required|string',
            'fields.*.label' => 'required|string',
            'fields.*.placeholder' => 'nullable|string',
            'fields.*.options' => 'nullable|json',
            'fields.*.required' => 'boolean',
            'fields.*.order' => 'integer',
        ]);

        $form = Auth::user()->forms()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'shareable_link' => Str::random(12),
        ]);

        if (!empty($data['fields'])) {
            foreach ($data['fields'] as $fieldData) {
                $form->fields()->create([
                    'type' => $fieldData['type'],
                    'label' => $fieldData['label'],
                    'placeholder' => $fieldData['placeholder'] ?? '',
                    'options' => json_decode($fieldData['options'] ?? '[]', true),
                    'required' => $fieldData['required'] ?? false,
                    'order' => $fieldData['order'] ?? 0,
                ]);
            }
        }

        return redirect()->route('forms.index')
            ->with('success', 'Form created successfully.');
    }

    /**
     * Edit form
     */
    public function edit($id)
    {
        $form = Form::findOrFail($id);

        if ($form->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('forms.create', compact('form'));
    }

    /**
     * Update form
     */
    public function update(Request $request, $id)
    {
        $form = Form::findOrFail($id);

        if ($form->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
            'fields' => 'array',
            'fields.*.type' => 'required|string',
            'fields.*.label' => 'required|string',
            'fields.*.placeholder' => 'nullable|string',
            'fields.*.options' => 'nullable|json',
            'fields.*.required' => 'boolean',
            'fields.*.order' => 'integer',
        ]);

        $form->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        $form->fields()->delete();

        if (!empty($data['fields'])) {
            foreach ($data['fields'] as $fieldData) {
                $form->fields()->create([
                    'type' => $fieldData['type'],
                    'label' => $fieldData['label'],
                    'placeholder' => $fieldData['placeholder'] ?? '',
                    'options' => json_decode($fieldData['options'] ?? '[]', true),
                    'required' => $fieldData['required'] ?? false,
                    'order' => $fieldData['order'] ?? 0,
                ]);
            }
        }

        return redirect()->route('forms.index')
            ->with('success', 'Form updated successfully.');
    }

    /**
     * Delete form
     */
    public function destroy($id)
    {
        $form = Form::findOrFail($id);

        if ($form->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $form->delete();

        return redirect()->route('forms.index')
            ->with('success', 'Form deleted successfully.');
    }

    /**
     * AI Generate fields
     */
    public function aiGenerate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string'
        ]);

        $fields = AIGenerator::generateFields($request->prompt);

        return response()->json([
            'fields' => $fields
        ]);
    }

    /**
     * QR Code (SVG VERSION - BEST)
     */
    public function qr($id)
    {
        $form = Form::findOrFail($id);

        if ($form->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $url = route('public.form', $form->shareable_link);

        $qr = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($url);

        return response($qr)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="qr-form-' . $form->id . '.svg"');
    }
}