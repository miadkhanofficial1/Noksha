<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display the Contact Support page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('contact.index');
    }

    /**
     * Store an incoming contact support message.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ], [
            'name.required' => 'Please provide your name.',
            'email.required' => 'Please provide a valid email address so we can reply.',
            'email.email' => 'Please provide a valid email address format.',
            'subject.required' => 'Please select or provide a subject inquiry.',
            'message.required' => 'Please write your message or issue description.',
        ]);

        ContactMessage::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'unread',
        ]);

        return back()->with('success', 'Thank you for reaching out! Your message has been received and our support team will respond to your email shortly.');
    }
}
