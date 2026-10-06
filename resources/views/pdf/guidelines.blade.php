<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noksha - Creator Upload Guidelines & Asset Submission Standards</title>
    <style>
        @page {
            margin: 15mm 20mm;
            size: A4 portrait;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            background-color: #ffffff;
            margin: 0;
            padding: 24px;
            line-height: 1.5;
            font-size: 13px;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .brand {
            font-size: 26px;
            font-weight: 800;
            color: #4f46e5;
            letter-spacing: -0.5px;
        }
        .brand span {
            color: #0f172a;
        }
        .doc-badge {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #eef2ff;
            color: #4338ca;
            padding: 4px 10px;
            border-radius: 9999px;
            display: inline-block;
        }
        .title-block {
            margin-bottom: 20px;
        }
        .title-block h1 {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 6px 0;
        }
        .title-block p {
            color: #64748b;
            font-size: 12px;
            margin: 0;
        }
        .section-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 16px;
            page-break-inside: avoid;
        }
        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .section-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #4f46e5;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 24px;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }
        .section-card p {
            margin: 0 0 8px 0;
            color: #334155;
            font-size: 12px;
        }
        .checklist {
            list-style: none;
            padding-left: 0;
            margin: 8px 0 0 0;
        }
        .checklist li {
            padding: 3px 0 3px 20px;
            position: relative;
            font-size: 12px;
            color: #475569;
        }
        .checklist li::before {
            content: "✓";
            position: absolute;
            left: 0;
            top: 2px;
            color: #10b981;
            font-weight: bold;
        }
        .alert-box {
            background-color: #fff1f2;
            border: 1px solid #fecdd3;
            border-radius: 10px;
            padding: 14px 16px;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .alert-title {
            color: #be123c;
            font-weight: 700;
            font-size: 13px;
            margin: 0 0 4px 0;
        }
        .alert-box p {
            color: #9f1239;
            font-size: 11.5px;
            margin: 0;
            line-height: 1.4;
        }
        .footer {
            margin-top: 28px;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #94a3b8;
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            background-color: #4f46e5;
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
            text-decoration: none;
            display: inline-block;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Print / Save as PDF</button>
    </div>

    <div class="header">
        <div class="brand">Noksha<span>.</span></div>
        <div class="doc-badge">Official Creator Standards</div>
    </div>

    <div class="title-block">
        <h1>{{ $title ?? 'Noksha Creator Upload Guidelines & Submission Standards' }}</h1>
        <p>Document Version: {{ $version ?? '2026.1' }} &bull; Effective Date: {{ $date ?? date('F d, Y') }} &bull; noksha.com/creator</p>
    </div>

    <!-- Guideline 1: Cover Image -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-num">1</div>
            <h2 class="section-title">Cover Preview Image Guidelines</h2>
        </div>
        <p>Your cover image is the primary storefront visual representing your asset across catalog search and category pages.</p>
        <ul class="checklist">
            <li><strong>Aspect Ratio:</strong> Strictly 16:9 widescreen (e.g. 1920x1080) or 4:3 high-definition (e.g. 1600x1200).</li>
            <li><strong>Supported Formats:</strong> High-quality JPG, PNG, or WEBP.</li>
            <li><strong>File Size:</strong> Maximum file size allowed is 5MB.</li>
            <li><strong>Clarity & Aesthetics:</strong> High-resolution, professional contrast, no blurry or pixelated elements.</li>
            <li><strong>No Watermarks:</strong> Do not place personal watermarks, contact info, or website URLs across the preview.</li>
        </ul>
    </div>

    <!-- Guideline 2: Package Archive -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-num">2</div>
            <h2 class="section-title">Source Package Structure (.ZIP Archive)</h2>
        </div>
        <p>All downloadable design files must be neatly archived into a single, clean .ZIP file.</p>
        <ul class="checklist">
            <li><strong>Format:</strong> Must strictly be a standard .ZIP package (Max 100MB).</li>
            <li><strong>Included Source Files:</strong> Provide editable vector/raw formats: Figma (.fig), Photoshop (.psd), Illustrator (.ai), SVG, or EPS.</li>
            <li><strong>Layer Organization:</strong> All design artboards, folders, and layers must be neatly named and organized.</li>
            <li><strong>Font Licenses:</strong> Use free commercial Google Fonts or system fonts, and link font sources in a readme.txt file.</li>
            <li><strong>No Malware or Junk:</strong> Remove temporary OS files (.DS_Store, __MACOSX, Thumbs.db) prior to compression.</li>
        </ul>
    </div>

    <!-- Guideline 3: Categorization & Search Optimization -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-num">3</div>
            <h2 class="section-title">Category Selection & AI Search Tags</h2>
        </div>
        <p>Proper classification ensures buyers and clients quickly locate and purchase your digital products.</p>
        <ul class="checklist">
            <li><strong>Precise Category:</strong> Select the most matching category (UI Kit, Mockups, Templates, Graphics, Illustrations, Icons, Fonts).</li>
            <li><strong>Descriptive Title:</strong> Write a concise, professional title (e.g. "FinTech Dashboard Mobile App UI Kit").</li>
            <li><strong>Keyword Tags:</strong> Add 3 to 10 comma-separated keywords (e.g., <em>dashboard, crypto, dark mode, figma, app</em>).</li>
            <li><strong>Pricing:</strong> Set fair marketplace pricing in BDT (৳) or mark as 0.00 for free community assets.</li>
        </ul>
    </div>

    <!-- Guideline 4: IP & Legal Compliance -->
    <div class="alert-box">
        <h3 class="alert-title">4. Intellectual Property & Copyright Compliance (Zero Tolerance)</h3>
        <p>
            You must be the original creator or hold valid commercial distribution authorization for all assets submitted. The submission of cloned designs, stolen templates, or copyrighted brand marks without license is strictly prohibited. Violating accounts will face immediate, permanent termination and loss of accrued royalties.
        </p>
    </div>

    <div class="footer">
        <div>&copy; {{ date('Y') }} Noksha Digital Marketplace. All rights reserved.</div>
        <div>support@noksha.com &bull; Dhaka, Bangladesh</div>
    </div>

</body>
</html>
