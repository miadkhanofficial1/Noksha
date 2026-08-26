<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\ContestSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContestSubmissionController extends Controller
{
    /**
     * Submit a design entry to an active contest (Seller action).
     */
    public function store(Request $request, Contest $contest): RedirectResponse
    {
        if ($contest->status !== 'active') {
            return redirect()->back()->with('warning', 'This contest is no longer accepting entries.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'preview_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'design_file' => ['required', 'file', 'mimes:zip,rar,psd,ai,svg,pdf', 'max:51200'],
            'note' => ['nullable', 'string', 'max:1000'],
        ], [
            'title.required' => 'Submission title is required.',
            'preview_image.required' => 'Please upload a preview image of your design entry.',
            'design_file.required' => 'Please upload your source design file.',
        ]);

        $previewPath = $request->file('preview_image')->store('contest_previews', 'public');
        $filePath = $request->file('design_file')->store('contest_files', 'public');

        ContestSubmission::create([
            'contest_id' => $contest->id,
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'preview_image' => $previewPath,
            'design_file' => $filePath,
            'note' => $request->note,
            'is_winner' => false,
        ]);

        return redirect()->back()
            ->with('success', '🎨 Your design entry has been submitted successfully!');
    }
}
