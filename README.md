# Embassy Consular Management System (ECMS)

A lightweight internal web application for the **Embassy of the Republic of Togo in Ghana** designed to manage visa records, citizen information, consular assistance cases, and official documents with fast search and print capabilities.

---

## 📌 Project Overview

This system is designed as a **single-user internal workstation application** used by an administrator at the embassy to:

- Manage visa applications for travel to Togo
- Register and track Togolese citizens in Ghana
- Handle consular assistance cases (emergency support, legal aid, etc.)
- Search visa records instantly
- Print applicant or case information
- Manage citizen registry
- Track consular assistance cases
- Store and retrieve official documents
- Generate printable reports (PDF / print view)

---

## 🧱 Tech Stack

### Backend

- Laravel 12 (PHP 8.3)
- Blade templating engine
- Eloquent ORM

### Database

- PostgreSQL

### Frontend

- Blade (server-rendered UI)
- Bootstrap / AdminLTE (UI styling)

### Storage

- Local filesystem (`storage/app/public`)
- Optional: MinIO (S3-compatible storage)

### Server

- local machine (laptop/PC)

---

## 🏗️ System Philosophy

This system is built as a:

> **Fast internal search-and-print consular workstation**

It is NOT a multi-user platform and does NOT include authentication.

The goal is:

- Speed
- Simplicity
- Offline/local operation
- Instant printing of results

---

## 👤 User Model

### Single Role System

There is only one role:

- **Administrator**
  - Full access to all features
  - Can create, edit, delete records
  - Can search all modules
  - Can print any result

❌ No login system  
❌ No multi-user roles  
❌ No permissions system

---

## 🧩 System Modules

### 1. Visa Management

- Create visa records
- Store passport and applicant data
- Search by:
  - Name
  - Passport number
  - Visa number
- Print visa details instantly

---

### 2. Citizen Registry

- Register Togolese citizens in Ghana
- Store personal and contact information
- Track residence details
- Attach documents

---

### 3. Consular Assistance

- Manage emergency and assistance cases:
  - Lost passport
  - Arrest / detention
  - Medical emergency
  - Repatriation
- Link cases to citizens
- Print case summaries

---

### 4. Document Management

- Upload and store documents:
  - Passports
  - Certificates
  - Supporting files
- View and print documents

---

### 5. Global Search System (Core Feature)

The most important feature of the system:

Search across all modules by:

- Full name
- Passport number
- Visa number
- Phone number
- Case ID

### Output:

- Instant results
- Printable view (A4 format)
- Clean report layout for physical printing

---

### 6. Print System

Every result must support:

- Print-friendly layout (A4 format)
- Clean government-style formatting
- PDF export (optional)
- Browser print support (Ctrl + P)

---

## 📂 File Storage

All files are stored locally:

storage/app/public

Includes:

- Passport scans
- Visa documents
- Certificates
- Supporting documents
# ecms
