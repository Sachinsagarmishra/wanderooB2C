# Wanderoo Project Documentation

This document outlines the technical structure and design decisions made for the Wanderoo PHP/MySQL website.

## 📁 Directory Structure
```text
/wanderoo
├── admin/               # Secure Admin Panel
│   ├── includes/        # Admin-specific templates
│   └── dashboard.php    # Admin Home
├── assets/              # Frontend Assets
│   ├── css/style.css    # Main design system (Urbanist & Playfair Display)
│   ├── js/main.js       # Client-side interactions
│   └── img/             # Project images (logo, hero bg)
├── includes/            # Core Components
│   ├── db.php           # PDO MySQL Connection
│   ├── header.php       # Global Glassmorphism Header
│   ├── footer.php       # Global Footer
│   └── functions.php    # Helper functions
├── config.php           # Global Configuration
├── database.sql         # SQL Schema for import
├── index.php            # Homepage
└── implementation.md    # This documentation
```

## 🎨 Design System
- **Fonts**: 
  - `Urbanist`: Primary sans-serif for body and modern headings.
  - `Playfair Display`: Secondary serif for premium, elegant accents.
- **Colors**:
  - Primary Yellow: `#FFDE59`
  - Secondary: High-contrast dark backgrounds with glassmorphism overlays.
- **Visuals**:
  - Rounded hero section (40px border-radius).
  - Glassmorphism navigation bar with blur effect.

## ⚙️ Technical Details
- **Backend**: PHP 8.x with modular architecture.
- **Database**: MySQL using PDO (PHP Data Objects) for security.
- **Responsive**: Fully optimized for Desktop, Tablet, and Mobile.
- **SEO**: Dynamic page titles and meta descriptions integrated into `header.php`.

## 🚀 Setup Instructions
1. Clone the repository to your local server (XAMPP/MAMP/Laragon).
2. Create a database named `wanderoo_db`.
3. Import `database.sql` into the newly created database.
4. Update `config.php` with your local database credentials.
5. Default Admin Credentials:
   - Username: `admin`
   - Password: `admin123`
