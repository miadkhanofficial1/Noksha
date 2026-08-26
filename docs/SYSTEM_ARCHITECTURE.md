# System Architecture & Flow Diagrams — Noksha (নকশা)

This document details the architectural layout, component layers, and workflow diagrams for **Noksha (নকশা)**.

---

## 1. High-Level System Architecture

Noksha is structured following Laravel's standard **Model-View-Controller (MVC)** architectural pattern:

```mermaid
graph TD
    User[Client Browser / User] -->|HTTP Request| Router[Laravel Router / routes/web.php]
    Router -->|Middleware / Auth| Controller[Controllers Layer]
    Controller -->|Query / Mutate| Model[Eloquent Models]
    Model -->|SQL Queries| Database[(MySQL Database)]
    Controller -->|Auto Tagging| TagService[TagService Local AI Engine]
    Controller -->|Pass Data| View[Blade Views / Layouts]
    View -->|Render HTML/CSS/JS| User
    Controller -->|Stream Digital File| Storage[Laravel Storage / Disk]
```

---

## 2. User Authentication & Registration Flow

```mermaid
sequenceDiagram
    autonumber
    actor User
    participant Router as Laravel Web Router
    participant Auth as Auth Controllers
    participant DB as MySQL Database

    User->>Router: GET /register or GET /login
    Router-->>User: Render Auth Form View
    User->>Router: POST /register (Name, Email, Password)
    Router->>Auth: Validate Credentials & CSRF Token
    Auth->>DB: INSERT INTO users (default role = 'user')
    DB-->>Auth: User Record Created
    Auth-->>User: Create Auth Session & Redirect to /seller/dashboard or /buyer/dashboard
```

---

## 3. Seller Upload & Local AI Auto-Tagging Flow

```mermaid
flowchart TD
    Start([Seller Form Submission]) --> Upload[Upload Title, Image, Source File, Price]
    Upload --> TagServiceCall[Invoke TagService::generate]
    TagServiceCall --> ExtractWords[Extract Words & Remove Stop Words]
    ExtractWords --> DictionaryMatch[Match Keyword Dictionary #ui #fintech #mobile]
    DictionaryMatch --> MergeTags[Merge Manual & Auto-generated Tags]
    MergeTags --> SaveDB[(Save Resource to Database status = pending)]
    SaveDB --> DispatchNotif[Dispatch Notifications to Seller & Admins]
    DispatchNotif --> End([Return Success Alert & Pending Badge])
```

---

## 4. Buyer Checkout & File Streaming Flow

```mermaid
sequenceDiagram
    autonumber
    actor Buyer
    participant Cart as Cart / Checkout Controller
    participant DB as MySQL Database
    participant Storage as Public Storage Disk

    Buyer->>Cart: POST /cart/{resource}
    Cart->>DB: Add item to cart
    Buyer->>Cart: GET /checkout -> POST /checkout
    Cart->>DB: Create Order & OrderItems (payment_status = completed)
    Cart->>DB: Clear User Cart
    Cart->>DB: Dispatch Buyer Notification ("Order Placed & Files Ready")
    Cart-->>Buyer: Redirect to /orders/{order}/success
    Buyer->>Cart: GET /download/{resource}
    Cart->>DB: Verify Ownership (Order exists & payment_status = completed)
    Cart->>Storage: Stream File Download (ZIP/PSD/AI)
    Storage-->>Buyer: File Download Delivered
```

---

## 5. Super Admin Moderation & Winner Selection Flow

```mermaid
flowchart TD
    Admin([Super Admin Dashboard]) --> CheckRole{Check User Role}
    CheckRole -->|role != admin| Deny[Redirect to Home with Warning]
    CheckRole -->|role == admin| Access[Access Admin Dashboard / Moderation]
    
    Access --> ModResource[Approve / Reject Pending Resources]
    Access --> ModKYC[Approve / Reject Seller KYC Docs]
    Access --> CreateContest[Create New Design Contest]
    Access --> PickWinner[Select Winning Entry in Contest]
    
    PickWinner --> UpdateContest[Set Contest status = completed & is_winner = true]
    UpdateContest --> AwardBadge[Award Contest Winner Badge to Seller Profile]
    AwardBadge --> Leaderboard[Update Top 10 Creators Leaderboard]
```
