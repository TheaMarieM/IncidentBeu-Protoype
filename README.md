# BEU Student Behavioral Incidents Management System

A comprehensive Laravel-based system for managing student behavioral incidents, attendance tracking, and case management at St. Paul University Philippines - Basic Education Unit (BEU).

## 📋 How the System Works

The BEU Incident Management System with Data Analytics is designed to streamline the process of recording, tracking, and managing student behavioral incidents while maintaining strict confidentiality protocols:

1. **Incident Reporting** - Advisers and discipline officers log behavioral violations with detailed narratives, evidence uploads, and handbook-based violation classification
2. **Approval Workflow** - Reports are submitted to the principal/assistant principal for review and approval
3. **Sanction Assignment** - Approved cases automatically apply appropriate sanctions based on offense level (1st, 2nd, 3rd offense)
4. **Parent Notification** - System tracks when parents/guardians are notified about incidents
5. **Attendance Management** - Separate module for logging and tracking student absences and tardiness
6. **Case Archiving** - Approved and closed cases are moved to archives for historical record-keeping

**Confidentiality:** Students and parents only see attendance records - behavioral incidents remain confidential and accessible only to authorized staff.

## ✨ Features

### Current Features

✅ **Multi-Role Authentication System**
- Discipline Chairperson (DC)
- Principal & Assistant Principal
- Classroom Advisers
- Students (attendance view only)
- Parents/Guardians (child's attendance view only)

✅ **Incident Management**
- Log behavioral incidents with narrative reports
- Upload scanned documents and evidence
- Automatic offense count tracking per student
- Handbook-based violation categories and sanctions
- Multi-student incident support
- Incident approval workflow
- Archive system for closed cases

✅ **Attendance Management**
- Separate attendance logging system
- Track absences, tardiness, and excused absences
- Time-in recording for tardy students
- Filter and search attendance records
- Automatic reflection across student, parent, and adviser dashboards

✅ **User Management**
- Student registration with adviser assignment
- Parent account linking to students
- Role-based access control (RBAC)
- Policy-based authorization

✅ **Security Features**
- CSRF protection on all forms
- SQL injection prevention with Eloquent ORM
- XSS protection with auto-escaping
- Rate limiting (brute force protection)
- Suspicious activity logging
- Security headers (CSP, X-Frame-Options, etc.)
- bcrypt password hashing

### 🚧 Features in Development

The following features are planned and will be developed soon:
- 📊 **Data Analytics Dashboard** - Visual statistics and trend analysis
- 📄 **PDF Report Generation** - Printable incident and attendance reports
- 📧 **Email Notifications** - Automated parent/guardian notifications
- 📱 **Mobile Responsive Enhancements** - Improved mobile experience
- 🔍 **Advanced Search & Filters** - Enhanced search capabilities

## 🛠️ Technology Stack

### Backend
- **Framework:** Laravel 12 (PHP 8.2+)
- **Database:** MySQL
- **ORM:** Eloquent
- **Authentication:** Laravel Breeze
- **Session:** Database driver

### Frontend
- **CSS Framework:** Tailwind CSS 3.x
- **JavaScript:** Alpine.js (for interactive components)
- **Icons:** Font Awesome 6.x
- **Template Engine:** Blade

### Development Tools
- **Version Control:** Git/GitHub
- **Package Manager:** Composer (PHP), NPM (Node.js)
- **Build Tool:** Vite
- **Development Server:** PHP Built-in Server / Laravel Artisan

### AI-Assisted Development
During development, we utilized AI tools to enhance planning and problem-solving:
- **Google Gemini** - Architecture planning and code optimization
- **Claude (Anthropic)** - Complex logic implementation and refactoring
- **ChatGPT (OpenAI)** - Debugging and documentation

## 🎨 Development Approach

### UI/UX Design Process

1. **Prototyping Phase**
   - Created initial prototypes using **HTML & CSS** before Laravel implementation
   - This approach allowed for rapid design iterations and visual feedback
   - Made it easier to modify layouts and styling without affecting backend logic

2. **Blueprint-First Methodology**
   - Analyzed system requirements and use case diagrams
   - Mapped out user flows and interactions
   - Designed database schema and relationships
   - Validated design with stakeholders before coding

3. **Why We Avoided Filament**
   - Initially considered Laravel Filament for admin panel
   - Found the development process **slow and restrictive**
   - **Difficult to customize** UI/UX to specific requirements
   - Limited flexibility for complex workflows
   - Decided on custom Laravel + Blade for better control

4. **Planning & Analysis**
   - Started with comprehensive **use case diagram analysis**
   - Identified all actors (roles) and their interactions
   - Mapped out system boundaries and dependencies
   - Iterative refinement with stakeholder feedback

## 👤 Premade Demo Accounts

For demonstration and testing purposes, the system includes premade accounts:

### Staff Accounts
| Role | Username | Password | Email |
|------|----------|----------|-------|
| Discipline Chairperson | DC-001 | password | discipline@spup.edu.ph |
| Principal | PR-001 | password | principal@spup.edu.ph |
| Assistant Principal | AP-001 | password | assprincipal@spup.edu.ph |
| Adviser | ADV-001 | password | andres.bonifacio@spup.edu.ph |
| Adviser | ADV-002 | password | maria.clara@spup.edu.ph |

### Student Accounts
| Student ID | Password | Name |
|------------|----------|------|
| 2025-00124 | password | Juan Cruz |
| 2025-00125 | password | Maria Santos |
| 2025-00126 | password | Pedro Reyes |

### Parent Accounts
- Email: carmen.lopez@spup.edu.ph (Password: password)
- Email: jose.santos@spup.edu.ph (Password: password)

**Note:** Change these credentials in production environments.

## 📦 Installation

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM

### Setup Steps

1. **Clone the repository**
```bash
git clone https://github.com/TheaMarieM/IncidentBeu-Protoype.git
cd BEU-prototype
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database setup**
```bash
# Configure .env with your database credentials
php artisan migrate
php artisan db:seed --class=PremadeAccountsSeeder
```

5. **Build assets**
```bash
npm run build
```

6. **Start development server**
```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` and log in with any premade account.

## 📚 Documentation

- **[SETUP.md](SETUP.md)** - Detailed installation guide
- **[SECURITY.md](SECURITY.md)** - Security best practices
- **[SECURITY_MEASURES.md](SECURITY_MEASURES.md)** - Active security implementations
- **[COMMANDS.md](COMMANDS.md)** - Useful Artisan commands

## 🔐 Security

This system implements multiple layers of security:
- Rate limiting on all routes
- CSRF protection
- SQL injection prevention
- XSS protection
- Suspicious activity monitoring
- Encrypted sessions
- Role-based access control

See [SECURITY_MEASURES.md](SECURITY_MEASURES.md) for complete details.

## 🤝 Contributing

This is an academic project for St. Paul University Philippines. For contributions or suggestions, please contact the development team.

## 👥 Development Team

**St. Paul University Philippines - BSCS Students**
- System Analysis & Design
- Database Architecture
- Backend Development
- Frontend Development
- Quality Assurance

## 📄 License

This project is developed for academic purposes at St. Paul University Philippines.

---

**Version:** 1.0.0  
**Last Updated:** January 19, 2026  
**Status:** Active Development
