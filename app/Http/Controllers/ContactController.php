<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Handle contact form submission
     */
    public function send(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000'
        ]);

        try {
            // Log the contact form submission
            Log::info('Contact form submitted', [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'subject' => $validatedData['subject']
            ]);

            // Here you can add email sending functionality
            // Mail::to('admin@cinemaparadiso.com')->send(new ContactMail($validatedData));

            return redirect()->route('help')->with('success', 'Thank you for your message! We will get back to you soon.');
            
        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'data' => $validatedData
            ]);

            return redirect()->route('help')->with('error', 'Sorry, there was an error sending your message. Please try again.');
        }
    }
}