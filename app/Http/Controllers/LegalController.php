<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LegalController extends Controller
{
    /**
     * Display Licensing Terms & Usage Guidelines.
     */
    public function licenses(): View
    {
        return view('legal.licenses', [
            'activePolicy' => 'licenses',
            'metaTitle' => 'Licensing Terms & Usage Rights — Noksha',
        ]);
    }

    /**
     * Display Terms of Service & Contributor Agreement.
     */
    public function terms(): View
    {
        return view('legal.terms', [
            'activePolicy' => 'terms',
            'metaTitle' => 'Terms of Service & Platform Agreement — Noksha',
        ]);
    }

    /**
     * Display Privacy Policy & Data Security.
     */
    public function privacy(): View
    {
        return view('legal.privacy', [
            'activePolicy' => 'privacy',
            'metaTitle' => 'Privacy Policy & Data Protection — Noksha',
        ]);
    }

    /**
     * Display Refund Policy, Contest Escrow & Payout Conditions.
     */
    public function refunds(): View
    {
        return view('legal.refunds', [
            'activePolicy' => 'refunds',
            'metaTitle' => 'Refund Policy & Escrow Dispute Resolution — Noksha',
        ]);
    }
}
