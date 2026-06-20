<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    /**
     * Display a listing of submissions with readable data.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $formIds = $user->forms()->pluck('id')->toArray();

        $query = Submission::whereIn('form_id', $formIds)
            ->with(['form.fields', 'user']); // eager load form fields for labels

        // Filter by form
        if ($request->filled('form_id')) {
            $query->where('form_id', $request->form_id);
        }

        // Date range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Search in JSON data or form title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('data', 'LIKE', "%{$search}%")
                  ->orWhereHas('form', function ($f) use ($search) {
                      $f->where('title', 'LIKE', "%{$search}%");
                  });
            });
        }

        $submissions = $query->latest()->paginate(20);
        $forms = $user->forms()->get();

        return view('submissions.index', compact('submissions', 'forms'));
    }

    /**
     * Display a single submission with full data.
     */
    public function show($id)
    {
        $submission = Submission::where('id', $id)
            ->whereIn('form_id', Auth::user()->forms()->pluck('id'))
            ->with(['form.fields', 'user'])
            ->firstOrFail();

        return view('submissions.show', compact('submission'));
    }

    /**
     * Export submissions in CSV (fallback for Excel/PDF).
     */
    public function export(Request $request, $format)
    {
        $user = Auth::user();
        $formIds = $user->forms()->pluck('id')->toArray();

        $query = Submission::whereIn('form_id', $formIds)
            ->with(['form.fields', 'user']);

        // Apply filters
        if ($request->filled('form_id')) {
            $query->where('form_id', $request->form_id);
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $submissions = $query->get();
        $filename = 'submissions_' . now()->format('Y-m-d_H-i');

        switch ($format) {
            case 'csv':
                return $this->exportCsv($submissions, $filename);
            case 'excel':
                // If you install Laravel Excel, use it; otherwise fallback to CSV
                return $this->exportCsv($submissions, $filename);
            case 'pdf':
                // If you install DomPDF, use it; otherwise fallback to CSV
                return $this->exportCsv($submissions, $filename);
            default:
                abort(404, 'Invalid format');
        }
    }

    /**
     * Generate CSV export.
     */
    private function exportCsv($submissions, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
        ];

        $callback = function () use ($submissions) {
            $handle = fopen('php://output', 'w');

            // Build headers from the first submission's data keys (if any)
            $headers = ['ID', 'Form', 'User', 'Submitted At'];
            if ($submissions->isNotEmpty()) {
                $firstData = $submissions->first()->data;
                if (is_array($firstData)) {
                    // Use the field labels if available, else use keys
                    $fields = $submissions->first()->form->fields->keyBy('id');
                    foreach (array_keys($firstData) as $key) {
                        $headers[] = $fields[$key]->label ?? 'Field ' . $key;
                    }
                }
            }
            fputcsv($handle, $headers);

            foreach ($submissions as $sub) {
                $row = [
                    $sub->id,
                    $sub->form->title,
                    $sub->user->name ?? 'Guest',
                    $sub->created_at->toDateTimeString(),
                ];
                if (is_array($sub->data)) {
                    foreach ($sub->data as $value) {
                        $row[] = $value;
                    }
                }
                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}