# University Viva Preparation Guide (Bilingual: English + বাংলা) — Noksha (নকশা)

This guide contains **30 comprehensive Viva Voce questions and strong technical answers** in both English and Bangla, specifically tailored for university project defense examinations.

---

## 🟢 Section 1: Project-Specific Questions

### Q1. What is Noksha (নকশা)? Explain its core purpose.
- **English Answer:** Noksha is an AI-powered graphics template marketplace built with Laravel 12 and Bootstrap 5. It connects Bangladeshi and international designers with buyers, offering automated tagging, intelligent search, digital asset publishing, identity verification (KYC), design contests, and real-time notifications.
- **বাংলা উত্তর:** নকশা (Noksha) হলো একটি লারাভেল ১২ এবং বুটস্ট্র্যাপ ৫ দিয়ে তৈরি এআই-অনুপ্রাণিত ডিজিটাল গ্রাফিক্স টেমপ্লেট মার্কেটপ্লেস। এটি ডিজাইনারদের টেমপ্লেট, ভেক্টর এবং ইউআই কিট বিক্রি করার সুযোগ দেয় এবং ক্রেতাদের অটোম্যাটিক ট্যাগিং ও স্মার্ট সার্চের মাধ্যমে সহজেই ডিজাইন রিসোর্স খুঁজে পাওয়ার সুবিধা দেয়।

---

### Q2. How does the AI Auto Tagging system work in Noksha? Does it use a paid API?
- **English Answer:** Noksha uses a local keyword-based auto-tagging engine (`TagService.php`) without external paid APIs. When a seller uploads a resource, `TagService` analyzes the title, description, and category, filters stop words, matches words against a configurable keyword dictionary (`#ui`, `#fintech`, `#mobile`), and automatically indexes tags in the database.
- **বাংলা উত্তর:** নকশা কোনো পেইড এপিআই ব্যবহার না করে একটি লোকাল কিওয়ার্ড-বেসড এআই ইঞ্জিন (`TagService.php`) ব্যবহার করে। সেলার যখন একটি টেমপ্লেট আপলোড করে, সিস্টেম টাইটেল, ডেসক্রিপশন এবং ক্যাটাগরি বিশ্লেষণ করে স্টপ ওয়ার্ড ফিল্টার করে এবং একটি প্রি-কনফিগারড কিওয়ার্ড ডিকশনারি থেকে অটোমেটিক ট্যাগ তৈরি করে ডাটাবেসে সেভ করে।

---

### Q3. How does Noksha handle identity verification (KYC) for sellers?
- **English Answer:** Sellers submit government ID documents (NID/Passport) and a live selfie on `/seller/verification`. Applications enter the Super Admin verification queue (`/admin/verifications`). Upon approval, the seller account receives a `Pro Verified Author` badge (`is_verified = true`).
- **বাংলা উত্তর:** সেলাররা তাদের এনআইডি/পাসপোর্ট ডকুমেন্ট এবং সেলফি আপলোড করে আবেদন করে। সুপার অ্যাডমিন প্যানেল থেকে এই তথ্যগুলো যাচাই করার পর এপ্রুভ করলে সেলার প্রোফাইলে "Pro Verified Author" ব্যাজ যুক্ত হয় এবং `is_verified = true` হয়।

---

### Q4. Explain the Design Contests & Creator Leaderboard feature.
- **English Answer:** Super Admins launch design challenges with prize pools on `/admin/contests`. Sellers upload entries to the contest gallery. When the admin selects a winning entry, the contest closes, the seller receives a "Contest Winner" badge, and the Top 10 Creator Leaderboard updates based on wins and trust scores.
- **বাংলা উত্তর:** সুপার অ্যাডমিন প্রাইজ মানিসহ ডিজাইন কন্টেস্ট তৈরি করেন। সেলাররা এন্ট্রি জমা দেয়। অ্যাডমিন বিজয়ী নির্বাচন করলে কন্টেস্ট সমাপ্ত হয়, বিজয়ী সেলার প্রোফাইলে "Contest Winner" ব্যাজ পায় এবং টপ ১০ ক্রিয়েটর লিডারবোর্ড আপডেট হয়।

---

### Q5. How are digital file downloads secured in Noksha?
- **English Answer:** Digital resource files (ZIP/PSD) are stored in non-public storage. Direct file streaming in `OrderController@download` validates that the resource is either free or that the authenticated user has a completed order (`payment_status = completed`) before streaming the file.
- **বাংলা উত্তর:** ডিজিটাল ফাইলগুলো প্রাইভেট স্টোরেজে সংরক্ষিত থাকে। `OrderController@download` মেথডে সরাসরি চেক করা হয় রিসোর্সটি ফ্রি কিনা অথবা ব্যবহারকারীর একটি কমপ্লিট অর্ডার আছে কিনা। শর্ত পূরণ করলেই কেবল ফাইল ডাউনলোড স্ট্রিম হয়।

---

## 🔵 Section 2: Laravel 12 Framework Questions

### Q6. Why did you choose Laravel 12 for this project?
- **English Answer:** Laravel 12 provides a clean MVC architecture, robust Eloquent ORM, built-in Authentication & Session security, Blade templating engine, powerful migration system, and seamless integration with Vite for production asset bundling.
- **বাংলা উত্তর:** লারাভেল ১২-এর ক্লিন এমভিসি আর্কিটেকচার, পাওয়ারফুল ইলোকুয়েন্ট ওআরএম, বিল্ট-ইন অথেন্টিকেশন, সেসন সিকিউরিটি, ব্লেড টেমপ্লেটিং এবং ভাইট (Vite) অ্যাসেট বান্ডলিংয়ের কারণে এটি বেছে নেওয়া হয়েছে।

---

### Q7. What is Middleware in Laravel? Give an example from your project.
- **English Answer:** Middleware acts as an HTTP request filter. In Noksha, the `auth` middleware protects pages like `/buyer/dashboard`, `/checkout`, and `/seller/upload`, while custom role guards verify Super Admin privileges on `/admin/dashboard`.
- **বাংলা উত্তর:** মিডলওয়্যার হলো একটি এইচটিটিপি রিকোয়েস্ট ফিল্টার। নকশায় `auth` মিডলওয়্যার ব্যবহার করে কেবল লগইন করা ইউজারদের ড্যাশবোর্ড বা চেকআউটে ঢুকতে দেওয়া হয় এবং অ্যাডমিন রোল গার্ড দিয়ে `/admin/dashboard` সিকিউর করা হয়েছে।

---

### Q8. What is Eloquent ORM and how does it prevent SQL Injection?
- **English Answer:** Eloquent is Laravel's Object-Relational Mapper that interacts with the database using PHP models. It prevents SQL Injection by utilizing PDO parameter binding for all queries.
- **বাংলা উত্তর:** ইলোকুয়েন্ট হলো লারাভেলের ওআরএম যা পিএইচপি মডেলের মাধ্যমে ডাটাবেসের সাথে কাজ করে। এটি পিডিও (PDO) প্যারামিটার বাইন্ডিং ব্যবহার করায় সব কোয়েরি এসকিউএল ইনজেকশন থেকে নিরাপদ থাকে।

---

### Q9. What is N+1 problem in Laravel Eloquent and how did you resolve it?
- **English Answer:** The N+1 problem occurs when executing 1 query for a parent dataset and N additional queries for child relationships in a loop. We resolved it by eager loading relationships using `with(['owner', 'category'])`.
- **বাংলা উত্তর:** এন+১ প্রবলেম হয় যখন প্যারেন্ট ডাটার ১টি কোয়েরির পর লুপের ভেতর চাইল্ড মডেলের জন্য আরও N সংখ্যক আলাদা কোয়েরি রান হয়। আমরা ইগার লোডিং `with(['owner', 'category'])` ব্যবহার করে সব রিলেশন ১টি কোয়েরিতে লোড করে এটি সমাধান করেছি।

---

### Q10. What is CSRF Protection in Laravel?
- **English Answer:** Cross-Site Request Forgery (CSRF) protection ensures that unauthorized commands are not submitted by malicious sites. Laravel enforces this by requiring a secret `@csrf` token in all POST/PUT/DELETE forms.
- **বাংলা উত্তর:** সিএসআরএফ (CSRF) প্রোটেকশন হলো থার্ড পার্টি ওয়েবসাইট থেকে আসা অবৈধ রিকোয়েস্ট আটকানোর মেকানিজম। লারাভেল সব ফর্মেSecret `@csrf` টোকেন বাধ্যতামূলক করে এটি নিশ্চিত করে।

---

## 🟡 Section 3: Database & Relationships Questions

### Q11. Explain the relationship between Users and Resources in Noksha.
- **English Answer:** One-to-Many relationship. A `User` has many `Resources` (`$user->resources()`), and a `Resource` belongs to a single `User` (`$resource->owner()`).
- **বাংলা উত্তর:** এটি একটি 'ওয়ান-টু-মেনি' (One-to-Many) রিলেশনশিপ। একজন ইউজারের অনেকগুলো রিসোর্স থাকতে পারে এবং প্রতিটি রিসোর্স একজন নির্দিষ্ট ইউজারের অধীনস্থ।

---

### Q12. How are Orders and Resources linked in the database?
- **English Answer:** Linked via a Many-to-Many relationship using `orders`, `order_items`, and `resources` tables. An `Order` has many `OrderItems`, and each `OrderItem` references a `Resource`.
- **বাংলা উত্তর:** এটি 'মেনি-টু-মেনি' রিলেশন। `orders` এবং `resources` টেবিল `order_items` পিভট টেবিলের মাধ্যমে যুক্ত রয়েছে।

---

### Q13. What database engine and migrations did you use?
- **English Answer:** MySQL 8.0 with InnoDB engine (supporting Foreign Keys and ACIDS transactions). Database tables were constructed using 15 versioned Laravel migrations.
- **বাংলা উত্তর:** মাইএসকিউএল ৮.০ এর ইনোডিবি (InnoDB) ইঞ্জিন ব্যবহার করা হয়েছে। ডাটাবেসের ১৫টি ভার্সনড মাইগ্রেশন ফাইল রয়েছে।

---

## 🔴 Section 4: Quick-Fire Technical Questions (Q14-Q30)

| # | Question (English / বাংলা) | Technical Answer Summary |
| :--- | :--- | :--- |
| **14** | How is Google OAuth SSO implemented? | Implemented via Laravel Socialite library (`SocialAuthController`). |
| **15** | How is the Cart system managed? | DB-persisted `carts` table linked to `user_id` for authenticated buyers. |
| **16** | What frontend framework is used? | Bootstrap 5 with custom SCSS & purple glassmorphism styling. |
| **17** | How are notifications dispatched? | `Notification::send($userId, $title, $message, $type, $actionUrl)` static helper. |
| **18** | What is the role of `storage:link`? | Creates a symlink from `public/storage` to `storage/app/public`. |
| **19** | How are user passwords stored? | Encrypted using Bcrypt password hashing algorithm. |
| **20** | What is soft deletes in Laravel? | Adds `deleted_at` column to mark records deleted without SQL `DELETE`. |
| **21** | How is search pagination handled? | Using `$resourcesQuery->paginate(12)->withQueryString()`. |
| **22** | How are top search tags calculated? | `array_count_values(array_map('strtolower', $allTags))` and `arsort()`. |
| **23** | What is Vite in Laravel 12? | Modern frontend asset bundler replacing Laravel Mix for fast HMR. |
| **24** | How are reviews restricted? | Checking `Order::whereHas('items')->exists()` before review save. |
| **25** | How are stats cards animated? | Using CSS keyframe transitions and Bootstrap flex utilities. |
| **26** | How is system broadcast implemented? | Loop iterating all active users and inserting `Notification` records. |
| **27** | How is user account suspension enforced? | Toggle `status = 'suspended'` column in `users` table. |
| **28** | Where are preview images stored? | Public storage disk under `storage/app/public/previews/`. |
| **29** | What is the purpose of `CHANGELOG.md`? | Documents version history and milestone features following SemVer. |
| **30** | What is the current version of Noksha? | Version `1.0.0-rc1` (Release Candidate 1). |
