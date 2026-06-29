<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ContactFormController extends Controller
{
    public function submitContact(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Honeypot Protection
        |--------------------------------------------------------------------------
        */
        if ($request->filled('website')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Spam detected.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Time Protection (minimum 3 seconds)
        |--------------------------------------------------------------------------
        */
        if (
            !$request->filled('form_time') ||
            (time() - (int) $request->form_time < 3)
        ) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Submission too fast. Please try again.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */
        try {

            $validated = $request->validate([
                'full_name'           => 'required|string|max:255',
                'email'               => 'required|email|max:255',
                'discord_username'    => 'required|string|max:255',
                'social_link'         => 'nullable|url|max:255',
                'commission_details'  => 'nullable|string|max:5000',
                'recaptcha_token'     => 'required|string',
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Google reCAPTCHA v3
        |--------------------------------------------------------------------------
        */
        try {

            $googleResponse = Http::asForm()->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret'   => config('services.recaptcha.secret_key'),
                    'response' => $validated['recaptcha_token'],
                    'remoteip' => $request->ip(),
                ]
            );

            $result = $googleResponse->json();

            if (
                empty($result['success']) ||
                ($result['score'] ?? 0) < 0.5 ||
                ($result['action'] ?? '') !== 'contact'
            ) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'reCAPTCHA verification failed.',
                ], 422);

            }

        } catch (\Exception $e) {

            Log::error('reCAPTCHA Error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Unable to verify reCAPTCHA.',
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | Remove reCAPTCHA token before saving
        |--------------------------------------------------------------------------
        */
        unset($validated['recaptcha_token']);

        /*
        |--------------------------------------------------------------------------
        | Save Contact
        |--------------------------------------------------------------------------
        */
        Contact::create($validated);

        /*
        |--------------------------------------------------------------------------
        | Send Email
        |--------------------------------------------------------------------------
        */
        try {

            Mail::to(config('mail.admin_address'))
                ->send(new ContactMail($validated));

        } catch (\Exception $e) {

            Log::error('Contact mail failed: '.$e->getMessage());

        }

        /*
        |--------------------------------------------------------------------------
        | Success Response
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'status' => 'success',
            'message' => 'Your message has been sent successfully!',
        ]);
    }
}