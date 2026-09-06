### UI Asset & Icon Policy
* **Primary System Logo:** Whenever a prompt asks to generate a header, navigation bar, dashboard card, or any component that requires the main application logo or icon, you MUST strictly use the existing file located at `public/favicon.svg`. 
* **Laravel Syntax:** When embedding this logo in Blade (`.blade.php`) files, always use the correct asset helper:
  ```blade
  <img src="{{ asset('favicon.svg') }}" alt="System Logo">
  ```
  Do not use external placeholder image links.

### Development Standards & Code Consistency
* **Architecture & Conventions:** Follow standard Laravel conventions (RESTful Controller methods, Form Request validation classes, Eloquent relationships, and explicit named routes in `routes/web.php`).
* **Frontend & UI Layouts:** Maintain a cohesive design system using Tailwind CSS and Alpine.js. Ensure Blade views reuse layout components (`x-app-layout`, `x-guest-layout`) and maintain clean responsiveness.
* **Database & Migrations:** Always structure schema changes through clear, reversible database migrations using appropriate column types, indexes, and foreign key constraints.
* **Code Formatting:** Maintain high code readability adhering to PSR-12 coding standards. Avoid redundant or unorganized inline code, leveraging reusable Laravel components and Vite assets.

### Communication & Execution Policy
* **Pre-Execution Disclosure:** Always explain clearly and concisely what actions, code edits, or terminal commands you are about to perform on the system BEFORE asking for permission or executing system modifications.

### Local Development Server Policy
* **Automatic Localhost Startup:** Always verify and ensure the localhost development server is running (`http://127.0.0.1:8000`) whenever a conversation begins. If not already active, immediately launch `composer run dev` (which concurrently starts `php artisan serve`, `php artisan queue:listen`, and `npm run dev`) in the background.

### Self-Improvement & Evolution Policy
* **Self-Improvement:** Continuously learn and adapt from feedback, past interactions, and execution results to improve code quality, accuracy, and performance over time.
* **Self-Evolution:** Proactively evaluate and upgrade system processes, documentation, and agent rules to evolve alongside evolving project requirements and best practices.
