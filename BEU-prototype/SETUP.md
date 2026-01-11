# BEU Incidents System - Setup Guide

## Quick Start

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```
DB_DATABASE=beu_incidents
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Setup Database
```bash
php artisan migrate
php artisan db:seed
```

### 4. Build & Run
```bash
npm run build
php artisan serve
```

Visit: http://localhost:8000

## Default Logins

**Discipline Chair:** discipline@spup.edu.ph / password  
**Principal:** principal@spup.edu.ph / password  
**Adviser:** santos@spup.edu.ph / password

## System Created

✅ 14 Database migrations  
✅ 11 Eloquent models with relationships  
✅ Role-based authentication middleware  
✅ Dashboard with analytics  
✅ Incident management workflow  
✅ Approval system  
✅ Notification service (SMS/Email)  
✅ Parent & student registry  
✅ Blade templates with SPUP branding

## Next Steps

1. Customize violation categories in database seeder
2. Configure SMS/Email providers in .env
3. Add authentication routes (Laravel Breeze/Jetstream)
4. Implement PDF generation for approved reports
5. Add intervention analytics algorithms

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── IncidentController.php
│   │   ├── StudentController.php
│   │   ├── ParentController.php
│   │   └── ApprovalController.php
│   └── Middleware/
│       └── CheckRole.php
├── Models/
│   ├── User.php
│   ├── Role.php
│   ├── Student.php
│   ├── Parent.php (class ParentModel)
│   ├── Incident.php
│   ├── ViolationCategory.php
│   ├── ViolationClause.php
│   ├── Sanction.php
│   └── ... (more models)
└── Services/
    └── NotificationService.php

database/
├── migrations/ (14 migration files)
└── seeders/
    ├── RoleSeeder.php
    ├── ViolationSeeder.php
    └── DatabaseSeeder.php

resources/views/
├── layouts/
│   └── app.blade.php
├── dashboard/
│   └── index.blade.php
└── incidents/
    └── index.blade.php
```

## Features Implemented

### Authentication & Authorization
- Multi-role system (5 roles)
- Role-based middleware
- User management

### Incident Management
- Incident logging with narrative reports
- File upload support
- Automatic incident numbering
- Offense count tracking
- Status workflow (reported → under_review → pending_approval → approved → closed)

### Violation System
- Categories with severity levels
- Clauses linked to Student Handbook
- Sanctions by offense count
- Automatic filtering based on student history

### Notifications
- Parent notification service
- SMS/Email support (ready for integration)
- Generic messages for privacy
- Notification status tracking

### Analytics Dashboard
- At-risk student detection
- Common incident tracking
- Quarterly pattern analysis
- Intervention suggestions
- Real-time statistics

### Data Privacy
- Role-based access control
- Limited parent visibility
- Confidential incident details
- Audit trail (timestamps)

## Database Schema

**Core Tables:**
- users, roles
- students, parents, student_parent
- attendance_records

**Incident Tables:**
- incidents, incident_students
- violation_categories, violation_clauses, sanctions
- parent_notifications, incident_approvals
- intervention_suggestions

## Commands

Process notifications:
```bash
php artisan notifications:process
```

## Notes

- Change default passwords before production
- Configure SMTP for email notifications
- Integrate SMS provider (Semaphore/Twilio)
- Add authentication scaffolding
- Implement PDF export for violation reports
