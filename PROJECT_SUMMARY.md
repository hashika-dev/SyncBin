# Project Summary: SyncBin (Capstone)

## 📌 Overview
- **Project Name:** SyncBin
- **Framework / Stack:** Laravel (PHP), Tailwind CSS, Alpine.js, Vite
- **Location:** `SyncBin/` inside the root workspace

---

## 🛠 Project Architecture & Setup
- **Backend:** Laravel (RESTful Controllers, Eloquent ORM, Blade Templates, Named Routes in `routes/web.php`)
- **Frontend:** Tailwind CSS & Alpine.js with layout components (`x-app-layout`, `x-guest-layout`)
- **Main System Logo:** Located at `public/favicon.svg` (embedded via `{{ asset('favicon.svg') }}`)

---

## 🚀 Setup Instructions for New Machine (Friend's PC)
1. **Clone Repository:**
   ```bash
   git clone <your-repository-url>
   cd SyncBin
   ```
2. **Install Dependencies:**
   ```bash
   composer install
   npm install
   ```
3. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. **Database & Build:**
   ```bash
   php artisan migrate
   npm run dev
   ```

---

## 🤖 Instructions for AI Agent (Antigravity) on New Machine
- Read `AGENT_RULES.md` for UI logo policies, coding guidelines, and PSR-12 conventions.
- Maintain consistent Laravel component structure using Blade + Tailwind CSS + Alpine.js.
