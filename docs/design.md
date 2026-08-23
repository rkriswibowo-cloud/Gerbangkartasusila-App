# DESIGN DOCUMENT

## Kediri Tourism Mobile App

Version: 1.0

---

# 1. PROJECT OVERVIEW

## Project Name

Kediri Tourism Mobile App

## Description

Aplikasi mobile Android dan iOS yang menyediakan informasi lengkap mengenai destinasi wisata, kuliner, event, budaya, sejarah, hotel, dan layanan pariwisata di Kota Kediri.

Aplikasi memiliki dua bagian utama:

1. Mobile App (Flutter)
2. Admin Panel (Laravel Filament)

Admin dapat mengelola seluruh konten melalui dashboard Filament.

---

# 2. PROJECT GOALS

Membantu wisatawan mendapatkan informasi wisata Kota Kediri secara mudah.

Menyediakan:

* Destinasi wisata
* Event daerah
* Kuliner khas
* Hotel
* Artikel wisata
* Peta lokasi
* Galeri foto
* Ulasan pengunjung

---

# 3. TARGET USERS

## Guest User

Dapat:

* Melihat destinasi
* Melihat event
* Melihat artikel
* Melihat kuliner
* Melihat hotel

## Registered User

Dapat:

* Login
* Menyimpan favorit
* Memberikan ulasan
* Mengubah profil

## Administrator

Dapat:

* CRUD destinasi
* CRUD event
* CRUD artikel
* CRUD galeri
* CRUD hotel
* CRUD kuliner
* Kelola pengguna

---

# 4. TECHNOLOGY STACK

## Backend

Laravel 10

PHP 8.1+

Laravel Sanctum

Filament 3

Spatie Permission

MySQL 8

## Frontend Mobile

Flutter

Dart

Riverpod

Dio

Go Router

Flutter Map

OpenStreetMap

Firebase Messaging

## Storage

Public Storage Laravel

## Deployment

Ubuntu Server

Nginx

PHP-FPM

MySQL

SSL Let's Encrypt

---

# 5. SYSTEM ARCHITECTURE

Flutter Mobile App
|
|
Laravel REST API
|
|
MySQL Database
|
|
Filament Admin Panel

---

# 6. USER ROLES

Role:

* Super Admin
* Admin
* Editor
* User

Permission:

Super Admin:

* Full Access

Admin:

* Manage Content

Editor:

* Manage Articles

User:

* Mobile Access

---

# 7. DATABASE DESIGN

## users

id
name
email
password
avatar
phone
status
created_at
updated_at

---

## destination_categories

id
name
icon
slug

---

## destinations

id
category_id
name
slug
description
address
latitude
longitude
ticket_price
opening_hours
thumbnail
is_featured
status
created_at
updated_at

---

## destination_images

id
destination_id
image

---

## events

id
title
slug
description
banner
location
start_date
end_date
status

---

## culinary_places

id
name
slug
description
address
latitude
longitude
thumbnail

---

## hotels

id
name
slug
description
address
latitude
longitude
thumbnail
phone

---

## articles

id
title
slug
content
thumbnail
author_id
published_at

---

## galleries

id
title
image

---

## reviews

id
user_id
destination_id
rating
comment

---

## favorites

id
user_id
destination_id

---

# 8. FILAMENT MODULES

## Dashboard

Statistics:

* Total Destinations
* Total Events
* Total Articles
* Total Users
* Total Reviews

Charts:

* Monthly Visitors
* Content Growth

---

## Destination Management

CRUD Destination

Fields:

* Name
* Category
* Description
* Address
* Latitude
* Longitude
* Ticket Price
* Opening Hours
* Thumbnail

---

## Event Management

CRUD Event

---

## Culinary Management

CRUD Culinary Place

---

## Hotel Management

CRUD Hotel

---

## Article Management

CRUD Article

Rich Editor

SEO Fields

---

## Gallery Management

CRUD Gallery

---

## User Management

CRUD User

Role Assignment

---

# 9. API DESIGN

Base URL

/api/v1

---

Authentication

POST /login

POST /register

POST /logout

GET /profile

---

Destinations

GET /destinations

GET /destinations/{id}

GET /destinations/featured

GET /destinations/category/{slug}

---

Events

GET /events

GET /events/{id}

---

Articles

GET /articles

GET /articles/{id}

---

Hotels

GET /hotels

GET /hotels/{id}

---

Culinary

GET /culinary

GET /culinary/{id}

---

Reviews

GET /reviews

POST /reviews

---

Favorites

GET /favorites

POST /favorites

DELETE /favorites/{id}

---

# 10. MOBILE APP STRUCTURE

lib/

core/

constants/

network/

storage/

theme/

utils/

features/

auth/

home/

destination/

event/

culinary/

hotel/

article/

gallery/

favorite/

profile/

shared/

widgets/

components/

routes/

main.dart

---

# 11. UI DESIGN GUIDELINES

Style:

Modern Tourism App

Color Palette:

Primary:
#2563EB

Secondary:
#14B8A6

Accent:
#F59E0B

Background:
#F8FAFC

Text:
#0F172A

---

Typography

Font:

Poppins

Weights:

400
500
600
700

---

# 12. MOBILE SCREENS

Splash Screen

Onboarding

Login

Register

Home

Destination List

Destination Detail

Event List

Event Detail

Article List

Article Detail

Hotel List

Hotel Detail

Culinary List

Culinary Detail

Favorite

Profile

Settings

---

# 13. HOME PAGE SECTIONS

Hero Banner

Featured Destinations

Categories

Upcoming Events

Popular Culinary

Latest Articles

Gallery Preview

---

# 14. MAP FEATURES

Display destination markers

Show user location

Open navigation

Distance calculation

OpenStreetMap Provider

---

# 15. NOTIFICATION FEATURES

Firebase Cloud Messaging

Event Reminder

New Destination

Promotion Information

---

# 16. SECURITY

Laravel Sanctum

Password Hashing

Role Permissions

API Rate Limiting

Validation Rules

---

# 17. PERFORMANCE

Pagination

Lazy Loading

Image Optimization

Cache API Responses

Database Indexing

---

# 18. SEO FOR ARTICLES

Meta Title

Meta Description

Open Graph

Slug Generation

---

# 19. DEPLOYMENT

Backend

Ubuntu 22.04

Nginx

PHP 8.1

MySQL 8

SSL

---

# 20. FUTURE FEATURES

AI Travel Planner

Multi Language

English

Indonesia

Digital Ticket

QR Code Tourism

Weather Information

Chatbot Tourism Assistant

AR Tourism Experience

---

# 21. DEVELOPMENT RULES

Use Repository Pattern

Use Service Layer

Use Form Request Validation

Use REST API Standards

Use Riverpod State Management

Use Clean Architecture

Use Responsive Design

Use Reusable Components

Maintain PSR-12 Coding Standard

All API responses must use JSON format.

All CRUD modules must support pagination, search, sorting, and filtering.
