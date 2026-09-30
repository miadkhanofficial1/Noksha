<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HelpCenterController extends Controller
{
    /**
     * Display the FAQ & Help Center knowledge base.
     */
    public function index(Request $request): View
    {
        $search = trim($request->input('q', ''));

        $faqCategories = [
            'buyer' => [
                'name' => 'Buyer Guide',
                'description' => 'Purchasing design templates, instant file downloads, licensing, and invoice management.',
                'icon' => 'cart',
                'faqs' => [
                    [
                        'q' => 'How do I purchase graphic assets and design templates on Noksha?',
                        'a' => 'You can browse or search for templates in the Marketplace, preview clean previews and deliverables, and either pay directly via your Central BDT Wallet or via bKash/Nagad checkout. Once purchased, the master source archive is immediately accessible in your Downloads dashboard.',
                    ],
                    [
                        'q' => 'What file formats are included with my purchase?',
                        'a' => 'Deliverables vary by asset type and typically include layered vector master files (AI, PSD, EPS, SVG), export presets (PNG, WebP, JPG), and a documentation file detailing font usage and color palettes.',
                    ],
                    [
                        'q' => 'What is the difference between Personal, Commercial, and Extended Commercial licenses?',
                        'a' => 'A Personal License covers single non-monetized projects. A Commercial License grants worldwide rights to use the asset in commercial client deliverables and digital marketing. An Extended Commercial License permits use in print-on-demand items or software resale end-products. Refer to our Licensing Terms page for full clause breakdowns.',
                    ],
                    [
                        'q' => 'Can I re-download templates I previously purchased?',
                        'a' => 'Yes. Lifetime download access is tied to your account. Visit your Dashboard and navigate to the "My Downloads" or "Order Records" tab at any time to re-download purchased assets without recurring charges.',
                    ],
                ],
            ],
            'contributor' => [
                'name' => 'Contributor KYC & Royalties',
                'description' => 'Creator verification protocols, asset publishing standards, sales commissions, and payout thresholds.',
                'icon' => 'badge-check',
                'faqs' => [
                    [
                        'q' => 'How do I apply to become an approved Noksha Contributor?',
                        'a' => 'Navigate to "Become a Contributor" from your user menu or footer. Complete the KYC application by providing your portfolio URL (Behance, Dribbble, or personal site), National ID / Passport number, and a government ID photo. Our curation team reviews submissions within 24–48 business hours.',
                    ],
                    [
                        'q' => 'What royalty commission do contributors earn on template sales?',
                        'a' => 'Approved contributors earn up to 85% net royalties on every marketplace sale. Earnings are instantly credited to your separate "Withdrawable Royalties" balance in your Creator Studio.',
                    ],
                    [
                        'q' => 'What is the minimum withdrawal payout threshold?',
                        'a' => 'The minimum payout threshold is ৳500 BDT. Once your withdrawable royalties reach ৳500, you can request an instant cashout directly to your personal bKash or Nagad wallet account from the Seller Payouts dashboard.',
                    ],
                    [
                        'q' => 'Why does Noksha enforce strict Contributor KYC verification?',
                        'a' => 'To safeguard buyers against copyright infringement, plagiarism, and low-quality assets. Only verified creators can publish templates and submit contest designs, maintaining premium creative integrity across the ecosystem.',
                    ],
                ],
            ],
            'deposits' => [
                'name' => 'bKash/Nagad Deposits',
                'description' => 'Adding funds to your Central BDT Wallet, TrxID verification, and transaction processing times.',
                'icon' => 'wallet',
                'faqs' => [
                    [
                        'q' => 'How do I top up my Central BDT Wallet?',
                        'a' => 'Go to your Wallet page, click "Deposit Balance", select your preferred payment channel (bKash or Nagad), send the designated BDT amount to our official merchant/agent number, and input the Sender Phone Number and Transaction ID (TrxID).',
                    ],
                    [
                        'q' => 'Where do I find my Transaction ID (TrxID)?',
                        'a' => 'After completing the payment in the bKash or Nagad mobile app, the TrxID is shown on the confirmation screen and also sent to you via SMS. It is an 8–10 character alphanumeric code (e.g. 9J47K2L8X1).',
                    ],
                    [
                        'q' => 'How long does manual TrxID verification take?',
                        'a' => 'Our automated reconciliation and admin auditing pipeline typically verifies and credits your deposit within 5 to 15 minutes during standard operational hours.',
                    ],
                    [
                        'q' => 'Can deposited buyer balance be withdrawn to bKash?',
                        'a' => 'No. Deposited buyer balance is strictly allocated for marketplace asset purchases and AI editing credits. Only earned creator royalties and won contest prizes in the "Withdrawable Royalties" balance can be cashed out.',
                    ],
                ],
            ],
            'contests' => [
                'name' => 'Contests & Escrow',
                'description' => 'Design challenges, escrow prize protection, single entry limits, and source file handover.',
                'icon' => 'trophy',
                'faqs' => [
                    [
                        'q' => 'How does Noksha guarantee contest prize escrow?',
                        'a' => 'When an organizer launches a design contest, 100% of the prize bounty is pre-funded and held in secure escrow by Noksha. The organizer cannot withdraw or withhold the funds after awarding a winner.',
                    ],
                    [
                        'q' => 'What is the Strict Single Entry Rule for contributors?',
                        'a' => 'To prevent spam and encourage focused, high-caliber submissions, each verified contributor is permitted exactly ONE primary design entry per contest. You cannot submit duplicate or spam concepts.',
                    ],
                    [
                        'q' => 'How does the source file handover and escrow release work?',
                        'a' => 'When a winner is declared, the contest transitions to "Handover Stage". The winner uploads a master ZIP archive (vector AI/PSD/SVG + fonts) to our private storage. The organizer downloads and inspects the files, and upon satisfaction, confirms release. 100% of the bounty is then credited to the designer with zero platform deductions.',
                    ],
                    [
                        'q' => 'Can an organizer request revisions during handover?',
                        'a' => 'Yes. If a deliverable is missing font outlines or layer adjustments, the organizer can trigger a formal revision request in the handover workspace with detailed instructions. The winner is alerted to upload a revised archive.',
                    ],
                ],
            ],
        ];

        return view('help.index', compact('faqCategories', 'search'));
    }
}
