# StockSense.AI: Laravel 12 + SB Admin 2

This project is a modern inventory forecasting system combining a Laravel 12 frontend (with SB Admin 2 theme) and a dedicated backend service. It provides an admin dashboard and tools focused on dataset management, forecasting, and AI-powered recommendations for inventory and stock management.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Backend Service](#backend-service)
- [Usage](#usage)
- [Preview](#preview)
- [Credits](#credits)
- [License](#license)

---

## Overview

StockSense.AI is a web application for stock and inventory management, featuring forecasting capabilities and AI-powered analytics. The frontend is built with Laravel and SB Admin 2, while the backend service (see below) handles business logic and machine learning models.

---

## Features

- **SB Admin 2 Theme:** Responsive Bootstrap admin UI.
- **Authentication:** Secure user login & registration (Laravel Fortify).
- **Dashboard:** Visualize predictions, recommendations, and analytics for inventory.
- **Dataset Management:** Upload, view, and manage datasets.
- **Forecasting Tools:** Predict trends and recommendations using integrated AI models (DeepSeek, SARIMAX, XGBoost).
- **AI Agent (QA):** Integrated Q&A agent for insights and summaries.
- **Profile Management:** Edit and update user information.
- **Role-Based Navigation:** Easy access to recommendations, datasets, AI, and more.

---

## Requirements

- PHP >= 8.2.0
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

---

## Installation

1. **Clone the frontend repository:**
   ```bash
   git clone https://github.com/mfauzanprmna/frontend-transtrack.git
   cd frontend-transtrack
   ```
2. **Install dependencies:**
   ```bash
   composer install
   ```
3. **Configure your environment:**
   - Copy `.env.example` to `.env`
   - Set your database credentials in `.env`
   - Run `php artisan key:generate`
4. **Run database migrations:**
   ```bash
   php artisan migrate
   ```
5. **Start the Laravel development server:**
   ```bash
   php artisan serve
   ```
6. Visit [http://localhost:8000](http://localhost:8000)

> **Note:**  
> Recommended to use this preset on a new project, otherwise your project's design might break.

---

## Backend Service

Before running or developing the frontend, you **must** clone and start the backend service:

1. **Clone the backend repository:**
   ```bash
   git clone https://github.com/DumbiFadhil/stocksense-backend-system.git
   cd stocksense-backend-system
   ```
2. **Install dependencies and set up the environment:**
   - Follow the instructions in the backend README (usually: install dependencies, set up environment variables, run migrations, etc.)
3. **Start the backend server:**
   - If using Node.js:
     ```bash
     npm install
     npm run start
     ```
   - If using Laravel:
     ```bash
     composer install
     php artisan migrate
     php artisan serve
     ```

4. **Ensure the backend runs on the correct port and address to match the frontend configuration.**

---

## Usage

- **Login:**  
  Email: `admin@mail.com`  
  Password: `password`
- **Register:**  
  Use the registration page to create a new account.
- **Dashboard:**  
  Visualize predictions and recommendations.
- **Dataset Management:**  
  Upload and view datasets through the dataset management tab.
- **AI Agent:**  
  Access the AI consultation agent for data-driven questions.

---

## Preview

**Login Page:**  
![Login](https://imgur.com/YjGp6Sbl.png)

**Graph Forecast Page**  
![Graph](https://i.imgur.com/2vl49QJ.png)

**Stock Recomendation:**  
![Restock](https://i.imgur.com/5Z78p79.png)

**Forecast Table:**  
![Forecast Table](https://i.imgur.com/2vl49QJ.png)

**AI agent:**  
![AI](https://i.imgur.com/MQkiPue.png)

---

## Credits

StockSense.AI (Laravel SB Admin 2) uses several open-source libraries and packages:

- [Laravel](https://laravel.com) - PHP framework
- [LaravelEasyNav](https://github.com/DevMarketer/LaravelEasyNav) - Navigation management
- [SB Admin 2](https://startbootstrap.com/themes/sb-admin-2) - Bootstrap admin theme

Special thanks to the web community and contributors.

---

## License

This project is licensed under the [MIT License](LICENSE).
