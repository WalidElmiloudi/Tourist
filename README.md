# Tourist API

## Overview
**Tourist** is a tourism platform designed to promote the diversity of destinations in Morocco.  
The platform allows travelers to **create, explore, and share personalized tourist itineraries** including beaches, mountains, rivers, monuments, and more.

This project provides a **RESTful API** that enables users to manage itineraries, destinations, and personal travel lists while ensuring security, reliability, and proper documentation.

---

# Features

## 1. User Authentication & Management
- User registration via API
- Secure user authentication
- Protected routes for authenticated users
- Sanctum authentication

---

## 2. Itinerary Management

Users can manage their own travel itineraries.

### Create Itinerary
Each itinerary includes:
- **Title**
- **Category** (Beach, Mountain, River, Monument, etc.)
- **Duration**
- **Image**
- **At least two destinations**

### Update Itinerary
- Only the **owner of the itinerary** can modify it.

### Favorite Itinerary
- Users can add itineraries to a personal **"Visit List"** (favorites).

---

## 3. Destination Management

Each itinerary can contain **multiple destinations**.

A destination includes:
- **Destination Name**
- **Accommodation Location**
- **Places to visit**
- **Activities to try**
- **Local dishes to taste**

---

## 4. Browsing & Searching

Users can explore available itineraries with different search options:

- View all itineraries
- Filter itineraries by **category**
- Filter itineraries by **duration**
- Search itineraries by **title keyword**

---

# Query Builder Features

The API includes advanced queries such as:

### Retrieve itineraries with destinations
Get all itineraries including their related destinations.

### Filter itineraries
Filter by:
- Category
- Duration

### Search itineraries
Search itineraries containing a **keyword in the title**.

### Popular itineraries
Retrieve itineraries with the **highest number of favorites**.

### Statistics

#### Total itineraries by category
Return statistics of itineraries grouped by category.

#### Total users registered per month
Return monthly statistics of registered users.

---

# Testing

The API is tested using:

## Unit Tests
Unit tests are implemented to ensure each feature works correctly.

## Postman Testing
Postman collections are used to test different API scenarios including:
- Authentication
- Itinerary management
- Destination management
- Filtering and searching

---

# API Documentation

The API is documented using tools such as:

- **Swagger (OpenAPI)**

This documentation provides:
- Endpoint descriptions
- Request parameters
- Example responses
- Authentication requirements

---

# Tech Stack

- PHP
- Laravel
- MySQL
- Sanctum Authentication
- Swagger / OpenAPI
- Postman
- PHPUnit

---

# Project Structure

Tourist/
│
├── app/
├── routes/
│ └── api.php
│
├── database/
│ ├── migrations/
│ └── seeders/
│
├── tests/
│
├── docs/
│ └── swagger
│
└── README.md
