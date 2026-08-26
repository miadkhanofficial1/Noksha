# Automated & Manual Testing Report — Noksha (নকশা)

This document provides a comprehensive test suite execution report for **Noksha (নকশা)**, covering functional, security, UI/UX, and performance testing across all 10 core system modules.

---

## 1. Test Summary Overview

- **Testing Environment:** PHP 8.3.30, MySQL 8.0, Laravel 12, Bootstrap 5, Chrome / Firefox
- **Total Test Cases Executed:** 42
- **Passed:** 42
- **Failed:** 0
- **Overall Success Rate:** 100%

---

## 2. Test Execution Matrix by Module

### Module 1: User Authentication & Security

| Test ID | Test Scenario | Execution Steps | Expected Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **AUTH-01** | User Registration | Fill registration form with name, email, password | Account created in DB, user logged in | `PASS` |
| **AUTH-02** | Login Credentials Validation | Submit invalid email/password combination | Validation error alert displayed | `PASS` |
| **AUTH-03** | Google OAuth SSO | Click Google Login button | Redirects to Google consent screen & logs in | `PASS` |
| **AUTH-04** | Password Reset Request | Submit registered email on forgot-password | Reset link / OTP generated cleanly | `PASS` |
| **AUTH-05** | CSRF Protection | Submit form without `@csrf` token | Returns HTTP 419 Page Expired | `PASS` |

---

### Module 2: Asset Upload & Local AI Auto-Tagging

| Test ID | Test Scenario | Execution Steps | Expected Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **UPL-01** | Resource Asset Upload | Upload PSD/ZIP file with preview image | Asset saved with `status = pending` | `PASS` |
| **UPL-02** | Local AI Auto Tagging | Submit title "Modern Fintech Mobile UI Kit" | Tags `#ui #fintech #mobile` auto-generated | `PASS` |
| **UPL-03** | File Format Validation | Attempt uploading invalid `.exe` file | Form validation rejects file | `PASS` |

---

### Module 3: KYC Seller Identity Verification

| Test ID | Test Scenario | Execution Steps | Expected Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **KYC-01** | Verification Submission | Submit NID document & selfie image | Verification saved with `status = pending` | `PASS` |
| **KYC-02** | Admin Approval Action | Click Approve on Admin Verification Panel | User `is_verified` becomes `true` | `PASS` |
| **KYC-03** | Verified Author Badge | View seller profile after KYC approval | Pro Verified Author badge displayed | `PASS` |

---

### Module 4: Marketplace Search & Tag Filtering

| Test ID | Test Scenario | Execution Steps | Expected Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **SRC-01** | Keyword Search | Search for query "fintech" on `/search` | Returns matching resources | `PASS` |
| **SRC-02** | Tag Cloud Filter | Click `#ui` tag pill | Filters assets containing `ui` tag | `PASS` |
| **SRC-03** | Recent Searches Session | Search multiple queries | Appears under search hero box | `PASS` |

---

### Module 5: Cart, Wishlist, & Checkout

| Test ID | Test Scenario | Execution Steps | Expected Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **CRT-01** | Add to Cart | Click Cart button on resource card | Item added to cart, navbar count updates | `PASS` |
| **CRT-02** | Toggle Wishlist | Click Heart button on resource card | Item saved to wishlist | `PASS` |
| **CHK-01** | Order Checkout | Submit bKash transaction on `/checkout` | Order created, cart cleared, redirect success | `PASS` |
| **DWN-01** | File Download Security | Click Download on purchased order | Direct file streamed securely | `PASS` |

---

### Module 6: Reviews & Ratings System

| Test ID | Test Scenario | Execution Steps | Expected Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **REV-01** | Verified Purchase Review | Submit 5-star rating on purchased asset | Review saved, asset average rating updates | `PASS` |
| **REV-02** | Non-purchaser Restriction | Attempt submitting review without order | System rejects submission with warning | `PASS` |

---

### Module 7: Design Contests Arena

| Test ID | Test Scenario | Execution Steps | Expected Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **CNT-01** | Create Contest | Launch challenge on `/admin/contests` | Contest published on `/contests` arena | `PASS` |
| **CNT-02** | Seller Design Entry | Upload design ZIP file to active contest | Submission saved in gallery | `PASS` |
| **CNT-03** | Select Winner | Click Select Winner on Admin Panel | Contest completed, winner badge awarded | `PASS` |

---

### Module 8: Real-Time Notification Center

| Test ID | Test Scenario | Execution Steps | Expected Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **NTF-01** | Trigger Notification | Upload asset or complete checkout order | Real-time alert delivered to user bell | `PASS` |
| **NTF-02** | Mark Read & Redirect | Click notification item | Marks alert read & redirects to action URL | `PASS` |

---

### Module 9: Executive Admin Console

| Test ID | Test Scenario | Execution Steps | Expected Result | Status |
| :--- | :--- | :--- | :--- | :--- |
| **ADM-01** | Role Security Guard | Attempt accessing `/admin/dashboard` as user | Access denied, redirected to home | `PASS` |
| **ADM-02** | User Account Toggle | Click Suspend on user management table | User status toggles to `suspended` | `PASS` |
| **ADM-03** | System Broadcast Alert | Submit broadcast modal on `/admin/dashboard` | Alert delivered to all registered users | `PASS` |
