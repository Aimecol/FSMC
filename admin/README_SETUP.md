# FSMC Admin System Setup Guide

## Database Setup

The profile page errors have been fixed. To complete the setup:

### 1. Create Database
First, create the database `fsmc_db` in your MySQL server.

### 2. Run Database Setup
Navigate to: `http://localhost/ikimina/FSMC/admin/setup_database.php`

This will:
- Create the `admin_users` table
- Create the `activity_logs` table  
- Create the `settings` table
- Insert a default admin user

### 3. Default Login Credentials
After running the setup:
- **Username:** admin
- **Email:** admin@fsmc.rw
- **Password:** admin123

**⚠️ IMPORTANT:** Change the default password immediately after first login!

## Fixed Issues

✅ **Fixed "Undefined array key 'user_id'" error**
- Updated to use `getCurrentUserId()` from config
- Added proper session validation

✅ **Fixed "Call to a member function prepare() on null" error**
- Replaced PDO calls with MySQLi database functions
- Updated all database queries to use `dbGetRow()`, `dbExecute()`, etc.

✅ **Updated table references**
- Changed from `users` table to `admin_users` table
- Updated activity logging to use `activity_logs` table

✅ **Added error handling**
- Proper validation of user data retrieval
- Better error messages instead of generic system errors

## Files Modified

- `profile.php` - Fixed all database and session issues
- `setup_tables.sql` - Database schema for required tables
- `setup_database.php` - Automated database setup script

## Next Steps

1. Run the database setup
2. Login with default credentials
3. Change the default password
4. Test the profile page functionality

The profile page should now work without showing "A system error occurred" messages.
