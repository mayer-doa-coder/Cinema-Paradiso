<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;
use App\Mail\ContactNotification;

class ContactController extends Controller
{
    /**
     * Store a new contact message
     */
    public function store(Request $request)
    {
        try {
            // Debug: Log the request data
            Log::info('Contact form submission attempt', [
                'csrf_token' => $request->input('_token'),
                'session_token' => $request->session()->token(),
                'headers' => $request->headers->all(),
                'all_data' => $request->all()
            ]);

            // Validate the form data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'required|string|max:2000'
            ]);

            // Save to database
            $contact = Contact::create($validatedData);

            Log::info('Contact saved successfully', ['contact_id' => $contact->id]);

            // Send email notification to admin
            try {
                Mail::to('tawhidul.hasan401@gmail.com')
                    ->send(new ContactNotification($contact));
                    
                Log::info('Email notification sent successfully', ['contact_id' => $contact->id]);
            } catch (\Exception $emailError) {
                Log::error('Failed to send contact notification email', [
                    'error' => $emailError->getMessage(),
                    'contact_id' => $contact->id
                ]);
            }

            // Return JSON success response
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message! We will get back to you soon.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            
            // Return validation errors
            return response()->json([
                'success' => false,
                'message' => 'Please check your input.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error sending your message. Please try again.'
            ], 500);
        }
    }

    /**
     * Handle contact form submission (legacy method for non-AJAX)
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