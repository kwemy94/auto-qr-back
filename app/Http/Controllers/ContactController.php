<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * POST /api/contact  { email, subject, message }
     * Le message est d'abord enregistré en base (rien n'est perdu si
     * l'envoi échoue), puis transmis par e-mail à l'adresse CONTACT_EMAIL.
     */
    public function store(ContactRequest $request)
    {
        $contact = ContactMessage::create([
            'user_id' => Auth::id(),
            'email'   => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'locale'  => app()->getLocale(),
        ]);

        $to = config('mail.contact_address');

        if ($to) {
            try {
                Mail::to($to)->send(new ContactMessageMail($contact));
                $contact->update(['mailed_at' => now()]);
            } catch (\Throwable $e) {
                Log::error("Envoi du message de contact #{$contact->id} échoué : " . $e->getMessage());
            }
        } else {
            Log::warning("CONTACT_EMAIL non configuré : message de contact #{$contact->id} enregistré sans e-mail.");
        }

        return response()->json([
            'success' => true,
            'message' => __('mobile.contact_sent'),
        ], 201);
    }
}
