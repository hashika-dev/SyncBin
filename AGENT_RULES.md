### UI Asset & Icon Policy
* **Primary System Logo:** Whenever a prompt asks to generate a header, navigation bar, dashboard card, or any component that requires the main application logo or icon, you MUST strictly use the existing file located at `public/favicon.svg`. 
* **Laravel Syntax:** When embedding this logo in Blade (`.blade.php`) files, always use the correct asset helper: `<img src="{{ asset('favicon.svg') }}" alt="System Logo">`. Do not use external placeholder image links.

### Development Standards & Code Consistency
* **Architecture & Conventions:** Follow standard Laravel conventions (RESTful Controller methods, Form Request validation classes, Eloquent relationships, and explicit named routes in `routes/web.php`).
* **Frontend & UI Layouts:** Maintain a cohesive design system using Tailwind CSS and Alpine.js. Ensure Blade views reuse layout components (`x-app-layout`, `x-guest-layout`) and maintain clean responsiveness.
* **Database & Migrations:** Always structure schema changes through clear, reversible database migrations using appropriate column types, indexes, and foreign key constraints.
* **Code Formatting:** Maintain high code readability adhering to PSR-12 coding standards. Avoid redundant or unorganized inline code, leveraging reusable Laravel components and Vite assets.

### Self-Improvement & Evolution Policy
* **Self-Improvement:** Continuously learn and adapt from feedback, past interactions, and execution results to improve code quality, accuracy, and performance over time.
* **Self-Evolution:** Proactively evaluate and upgrade system processes, documentation, and agent rules to evolve alongside evolving project requirements and best practices.