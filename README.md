# EtuAide Maroc - Student Task Management Platform

An intelligent task management and productivity platform designed for students and educational administrators in Morocco. EtuAide Maroc helps students organize their academic workload, track progress, and manage their time efficiently while enabling administrators to oversee student activities and provide timely support.

## About the Project

EtuAide Maroc is a comprehensive web-based educational platform built with Laravel that bridges the gap between students' needs and administrative oversight. The platform provides a centralized hub for task management, reminders, notifications, and comprehensive activity tracking to enhance academic success and institutional accountability.

## Features for Students

### Task Management
- **Create and Organize Tasks**: Students can create, categorize, and organize their academic and personal tasks
- **Subtask Breakdown**: Divide complex assignments into manageable subtasks with individual tracking
- **Task Categories**: Organize tasks by subject, type, or priority for better structure
- **Progress Tracking**: Monitor task completion status and track overall productivity

### Reminders & Notifications
- **Smart Reminders**: Set customizable reminders for upcoming deadlines and important dates
- **Push Notifications**: Receive timely notifications for task updates and important events
- **Timezone Support**: Automatic timezone detection and conversion for accurate reminder scheduling across different regions

### Recurring Tasks
- **Recurring Schedules**: Set up recurring tasks for regular assignments and study schedules
- **Task Occurrences**: Automatically generate task instances based on defined schedules

### Activity Tracking
- **Activity History**: View a complete log of all task-related activities for accountability and reflection
- **Progress Insights**: Access detailed activity logs to understand productivity patterns

### User Profile
- **Profile Management**: Customize profile information and preferences
- **Account Settings**: Manage notification preferences and display settings

## Features for Administrators

### Oversight & Monitoring
- **Activity Monitoring**: Comprehensive access to all student activity logs for oversight and support
- **Admin Alerts**: Receive alerts for important student milestones, deadlines, and anomalies
- **Alert Management**: Configure and manage custom alerts for proactive intervention

### Category Management
- **Course & Subject Categories**: Create and organize task categories for institutional structure
- **Category Policies**: Define access controls and permissions for task categories
- **Standardized Templates**: Maintain consistent task organization across the institution

### Communication
- **Student Notifications**: Send targeted notifications to students
- **Contact Form Processing**: Manage student inquiries through integrated contact forms
- **Admin Email Notifications**: Automated email notifications for important events

### User Management
- **Student Administration**: View and manage student accounts and profiles
- **Access Control**: Enforce role-based access policies through integrated policy system
- **Notification Management**: Administer user notification preferences

### Reporting & Analytics
- **Activity Insights**: Generate reports based on activity logs for academic analysis
- **Performance Tracking**: Monitor student productivity and engagement metrics
- **Alert Tracking**: Review and respond to system-generated alerts

## Technical Stack

- **Backend**: Laravel Framework (PHP)
- **Frontend**: JavaScript with Vite bundling
- **Database**: SQL-based relational database
- **Email**: Laravel Mail for transactional emails
- **Architecture**: MVC pattern with policy-based authorization

## Project Structure

```
app/
├── Models/              # Database models (User, Task, Subtask, Category, etc.)
├── Policies/            # Authorization policies for models
├── Http/Controllers/    # Request handlers
├── Http/Middleware/     # HTTP middleware
├── Mail/                # Mailable classes for emails
└── Console/Commands/    # Artisan commands

routes/
├── web.php              # Web routes
└── console.php          # Console routes

database/
├── migrations/          # Database schema migrations
├── factories/           # Model factories for testing
└── seeders/             # Database seeders

resources/
├── views/               # Blade templates
├── js/                  # JavaScript files
└── css/                 # Stylesheets

tests/
├── Feature/             # Feature tests
└── Unit/                # Unit tests
```

## Installation & Setup

### Requirements
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL or compatible database

### Getting Started

1. **Clone the repository**
```bash
git clone <repository-url>
cd etuaidemaroc
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Setup database**
```bash
php artisan migrate
php artisan db:seed
```

5. **Build assets**
```bash
npm run build
```

6. **Start the server**
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Development

### Running Tests
```bash
php artisan test
```

### Building for Production
```bash
npm run build
```

## Support & Contributing

For issues, feature requests, or contributions, please visit the project repository or contact the development team.

## License

This project is open-source and available under the MIT License.

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
