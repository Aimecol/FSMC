# Fair Surveying & Mapping Company (FSMC) - Admin System

A comprehensive admin system for managing the Fair Surveying & Mapping Company website, built with PHP, MySQL, and modern web technologies.

## Features

### 🔐 **Secure Authentication System**
- Password hashing with `password_hash()` and `password_verify()`
- Session management with timeout and regeneration
- Brute force protection with account lockout
- CSRF token protection
- Role-based access control (Super Admin, Admin, Editor)

### 📊 **Dashboard & Analytics**
- Real-time statistics overview
- Recent activity monitoring
- Quick access to pending items
- Responsive design for all devices

### 🛠️ **Content Management**
- **Services Management**: CRUD operations for company services
- **Products Management**: Equipment, software, and training materials
- **Training Programs**: Course management and enrollment tracking
- **Research Projects**: Publications and findings management
- **Inquiries & Communications**: Contact form submissions and responses

### 📁 **Media & File Management**
- Secure file upload system
- Image optimization and validation
- Organized file storage structure
- Support for multiple file types

### ⚙️ **System Administration**
- User management with role assignments
- Company settings configuration
- Activity logging and audit trails
- Database backup and maintenance tools

## Technology Stack

- **Backend**: PHP 7.4+ with MySQLi
- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Icons**: Font Awesome 6.7.2
- **Styling**: Custom CSS with CSS Grid and Flexbox
- **Security**: CSRF protection, XSS prevention, SQL injection protection

## Requirements

- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: 7.4 or higher
- **MySQL**: 5.7+ or MariaDB 10.3+
- **Extensions**: mysqli, json, session, fileinfo
- **Memory**: 128MB minimum (256MB recommended)
- **Storage**: 500MB minimum for application and uploads

## Installation

### 1. **Download and Extract**
```bash
# Clone or download the project
git clone <repository-url> fsmc-admin
cd fsmc-admin
```

### 2. **Set Permissions**
```bash
# Make uploads directory writable
chmod 755 uploads/
chmod 755 admin/config/

# Set proper ownership (adjust user/group as needed)
chown -R www-data:www-data uploads/
chown -R www-data:www-data admin/config/
```

### 3. **Database Setup**
Create a MySQL database for the application:
```sql
CREATE DATABASE fsmc_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'fsmc_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON fsmc_db.* TO 'fsmc_user'@'localhost';
FLUSH PRIVILEGES;
```

### 4. **Run Setup Wizard**
1. Navigate to `http://your-domain.com/admin/setup.php`
2. Follow the step-by-step installation wizard:
   - **Step 1**: Configure database connection
   - **Step 2**: Create database tables
   - **Step 3**: Create admin user account
   - **Step 4**: Insert initial data
   - **Step 5**: Complete installation

### 5. **Access Admin Panel**
- URL: `http://your-domain.com/admin/`
- Login with the credentials created during setup
- Default admin user: `admin` / `admin123` (change immediately)

## Configuration

### Database Configuration
Edit `admin/config/database.php` to customize database settings:
```php
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'fsmc_user');
define('DB_PASSWORD', 'your_secure_password');
define('DB_NAME', 'fsmc_db');
```

### Security Settings
Update `admin/config/config.php` for security configurations:
```php
// Session timeout (30 minutes)
define('SESSION_TIMEOUT', 30 * 60);

// Maximum login attempts
define('MAX_LOGIN_ATTEMPTS', 5);

// Login lockout time (15 minutes)
define('LOGIN_LOCKOUT_TIME', 15 * 60);

// Password minimum length
define('PASSWORD_MIN_LENGTH', 6);
```

### File Upload Settings
Configure upload limits in `admin/config/config.php`:
```php
// Maximum file upload sizes
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('MAX_IMAGE_SIZE', 5 * 1024 * 1024);  // 5MB

// Allowed file types
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_DOCUMENT_TYPES', ['pdf', 'doc', 'docx', 'xls', 'xlsx']);
```

## Usage

### Managing Services
1. Navigate to **Services** in the admin menu
2. Click **Add New Service** to create a service
3. Fill in service details:
   - Title and description
   - Languages offered
   - Features and pricing
   - SEO settings
4. Set status (Active/Inactive/Draft) and save

### Managing Products
1. Go to **Products** section
2. Add equipment, software, or training materials
3. Configure categories, specifications, and pricing
4. Upload product images and documentation

### Training Management
1. Access **Training** section
2. Create training programs with:
   - Course details and curriculum
   - Schedules and capacity
   - Pricing and requirements
3. Monitor enrollments and manage participants

### Research Projects
1. Navigate to **Research** section
2. Add research publications with:
   - Abstract and methodology
   - Key findings and authors
   - Publication details and documents

## Security Best Practices

### 1. **Change Default Credentials**
- Change the default admin password immediately
- Use strong passwords (12+ characters)
- Enable two-factor authentication if available

### 2. **Regular Updates**
- Keep PHP and MySQL updated
- Monitor security advisories
- Update dependencies regularly

### 3. **File Permissions**
- Set restrictive permissions on config files
- Ensure uploads directory is not executable
- Use `.htaccess` to protect sensitive directories

### 4. **Backup Strategy**
- Regular database backups
- File system backups
- Test restore procedures

### 5. **Monitoring**
- Monitor activity logs regularly
- Set up alerts for suspicious activity
- Review user access permissions

## Troubleshooting

### Common Issues

**1. Database Connection Failed**
- Check database credentials in config
- Verify MySQL service is running
- Ensure database exists and user has permissions

**2. File Upload Errors**
- Check directory permissions (755 for directories, 644 for files)
- Verify PHP upload settings (`upload_max_filesize`, `post_max_size`)
- Ensure sufficient disk space

**3. Session Issues**
- Check PHP session configuration
- Verify session directory permissions
- Clear browser cookies and cache

**4. Permission Denied Errors**
- Set proper file ownership and permissions
- Check Apache/Nginx user permissions
- Verify SELinux settings if applicable

### Debug Mode
Enable debug mode for development:
```php
// In admin/config/config.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

**Note**: Disable debug mode in production!

## Support

For technical support or questions:
- Email: fsamcompanyltd@gmail.com
- Phone: +250 788 331 697

## License

This project is proprietary software developed for Fair Surveying & Mapping Ltd.
All rights reserved.

## Changelog

### Version 1.0.0 (2025-01-22)
- Initial release
- Complete admin system implementation
- User authentication and authorization
- Content management modules
- File upload and media management
- Dashboard and analytics
- Security features and audit logging