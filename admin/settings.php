<?php
/**
 * Company Settings Management for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

// Set page variables
$pageTitle = 'Company Settings';
$pageIcon = 'fas fa-cog';
$pageDescription = 'Manage company information and website settings';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
    } else {
        $section = $_POST['section'] ?? '';
        
        try {
            if ($section === 'company') {
                // Update company information
                $settings = [
                    'company_name' => sanitize($_POST['company_name'] ?? ''),
                    'company_tagline' => sanitize($_POST['company_tagline'] ?? ''),
                    'company_description' => sanitize($_POST['company_description'] ?? ''),
                    'company_address' => sanitize($_POST['company_address'] ?? ''),
                    'company_city' => sanitize($_POST['company_city'] ?? ''),
                    'company_country' => sanitize($_POST['company_country'] ?? ''),
                    'company_postal_code' => sanitize($_POST['company_postal_code'] ?? ''),
                    'company_phone' => sanitize($_POST['company_phone'] ?? ''),
                    'company_email' => sanitize($_POST['company_email'] ?? ''),
                    'company_website' => sanitize($_POST['company_website'] ?? ''),
                    'company_founded' => sanitize($_POST['company_founded'] ?? ''),
                    'company_employees' => sanitize($_POST['company_employees'] ?? ''),
                    'company_license' => sanitize($_POST['company_license'] ?? '')
                ];
                
                foreach ($settings as $key => $value) {
                    dbExecute(
                        "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)",
                        [$key, $value]
                    );
                }
                
                logActivity('Company Settings Updated', 'settings', 0, null, $settings);
                setSuccessMessage('Company information updated successfully.');
                
            } elseif ($section === 'contact') {
                // Update contact information
                $settings = [
                    'contact_phone_primary' => sanitize($_POST['contact_phone_primary'] ?? ''),
                    'contact_phone_secondary' => sanitize($_POST['contact_phone_secondary'] ?? ''),
                    'contact_email_general' => sanitize($_POST['contact_email_general'] ?? ''),
                    'contact_email_support' => sanitize($_POST['contact_email_support'] ?? ''),
                    'contact_email_sales' => sanitize($_POST['contact_email_sales'] ?? ''),
                    'contact_hours_weekday' => sanitize($_POST['contact_hours_weekday'] ?? ''),
                    'contact_hours_weekend' => sanitize($_POST['contact_hours_weekend'] ?? ''),
                    'contact_emergency' => sanitize($_POST['contact_emergency'] ?? '')
                ];
                
                foreach ($settings as $key => $value) {
                    dbExecute(
                        "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)",
                        [$key, $value]
                    );
                }
                
                logActivity('Contact Settings Updated', 'settings', 0, null, $settings);
                setSuccessMessage('Contact information updated successfully.');
                
            } elseif ($section === 'social') {
                // Update social media links
                $settings = [
                    'social_facebook' => sanitize($_POST['social_facebook'] ?? ''),
                    'social_twitter' => sanitize($_POST['social_twitter'] ?? ''),
                    'social_linkedin' => sanitize($_POST['social_linkedin'] ?? ''),
                    'social_instagram' => sanitize($_POST['social_instagram'] ?? ''),
                    'social_youtube' => sanitize($_POST['social_youtube'] ?? ''),
                    'social_whatsapp' => sanitize($_POST['social_whatsapp'] ?? '')
                ];
                
                foreach ($settings as $key => $value) {
                    dbExecute(
                        "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)",
                        [$key, $value]
                    );
                }
                
                logActivity('Social Media Settings Updated', 'settings', 0, null, $settings);
                setSuccessMessage('Social media links updated successfully.');
                
            } elseif ($section === 'website') {
                // Update website settings
                $settings = [
                    'site_title' => sanitize($_POST['site_title'] ?? ''),
                    'site_description' => sanitize($_POST['site_description'] ?? ''),
                    'site_keywords' => sanitize($_POST['site_keywords'] ?? ''),
                    'site_author' => sanitize($_POST['site_author'] ?? ''),
                    'site_language' => sanitize($_POST['site_language'] ?? ''),
                    'site_timezone' => sanitize($_POST['site_timezone'] ?? ''),
                    'site_maintenance' => sanitize($_POST['site_maintenance'] ?? ''),
                    'analytics_google' => sanitize($_POST['analytics_google'] ?? ''),
                    'analytics_facebook' => sanitize($_POST['analytics_facebook'] ?? '')
                ];
                
                foreach ($settings as $key => $value) {
                    dbExecute(
                        "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)",
                        [$key, $value]
                    );
                }
                
                logActivity('Website Settings Updated', 'settings', 0, null, $settings);
                setSuccessMessage('Website settings updated successfully.');
            }
            
        } catch (Exception $e) {
            error_log("Settings update error: " . $e->getMessage());
            setErrorMessage('An error occurred while updating settings.');
        }
        
        redirect('settings.php');
    }
}

// Get all settings
$settingsResult = dbGetRows("SELECT setting_key, setting_value FROM settings");
$settings = [];
foreach ($settingsResult as $setting) {
    $settings[$setting['setting_key']] = $setting['setting_value'];
}

// Helper function to get setting value
// function getSetting($key, $default = '') {
//     global $settings;
//     return $settings[$key] ?? $default;
// }

include 'includes/header.php';
?>

<div class="row">
    <!-- Settings Navigation -->
    <div class="col-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Settings</h3>
            </div>
            <style>
                .list-group-item {
                    padding: 0.75rem 1.5rem;
                    font-size: 0.9rem;
                    font-weight: 500;
                    text-transform: uppercase;
                    letter-spacing: 0.05rem;
                    text-decoration: none;
                }
                .list-group-item i {
                    font-size: 1rem;
                }
                .list-group-item {
                    gap: 10px;
                    align-items: center;
                    display: flex;
                    border-radius: 0;
                    color: var(--text-dark);
                    background-color: transparent;
                    border-color: transparent;
                }
                .list-group-item.active {
                    background-color: var(--primary-color);
                    color: var(--white);
                }
            </style>
            <div class="list-group list-group-flush">
                <a href="#company" class="list-group-item list-group-item-action active" data-tab="company">
                    <i class="fas fa-building"></i> Company Information
                </a>
                <a href="#contact" class="list-group-item list-group-item-action" data-tab="contact">
                    <i class="fas fa-phone"></i> Contact Details
                </a>
                <a href="#social" class="list-group-item list-group-item-action" data-tab="social">
                    <i class="fas fa-share-alt"></i> Social Media
                </a>
                <a href="#website" class="list-group-item list-group-item-action" data-tab="website">
                    <i class="fas fa-globe"></i> Website Settings
                </a>
            </div>
        </div>
    </div>
    
    <!-- Settings Content -->
    <div class="col-9">
        <!-- Company Information -->
         <style>
            .tab-content {
                display: none;
            }
            .tab-content.active {
                display: block;
            }
            /* animations */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateX(-20px); }
                to { opacity: 1; transform: translateX(0); }
            }
            .tab-content.active {
                animation: fadeIn 0.5s ease;
            }
        </style>
        <div class="tab-content active" id="company">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Company Information</h3>
                </div>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="hidden" name="section" value="company">
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="company_name" class="form-label">Company Name</label>
                                    <input type="text" id="company_name" name="company_name" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('company_name', 'Fair Surveying & Mapping Company')); ?>">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="company_tagline" class="form-label">Tagline</label>
                                    <input type="text" id="company_tagline" name="company_tagline" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('company_tagline', 'Precision in Every Measurement')); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="company_description" class="form-label">Company Description</label>
                            <textarea id="company_description" name="company_description" class="form-control" 
                                      rows="4"><?php echo htmlspecialchars(getSetting('company_description')); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="company_address" class="form-label">Address</label>
                            <input type="text" id="company_address" name="company_address" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('company_address')); ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="company_city" class="form-label">City</label>
                                    <input type="text" id="company_city" name="company_city" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('company_city', 'Kigali')); ?>">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="company_country" class="form-label">Country</label>
                                    <input type="text" id="company_country" name="company_country" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('company_country', 'Rwanda')); ?>">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="company_postal_code" class="form-label">Postal Code</label>
                                    <input type="text" id="company_postal_code" name="company_postal_code" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('company_postal_code')); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="company_phone" class="form-label">Phone</label>
                                    <input type="text" id="company_phone" name="company_phone" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('company_phone', '+250 788 331 697')); ?>">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="company_email" class="form-label">Email</label>
                                    <input type="email" id="company_email" name="company_email" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('company_email', 'fsamcompanyltd@gmail.com')); ?>">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="company_website" class="form-label">Website</label>
                                    <input type="url" id="company_website" name="company_website" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('company_website')); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="company_founded" class="form-label">Founded Year</label>
                                    <input type="number" id="company_founded" name="company_founded" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('company_founded')); ?>" 
                                           min="1900" max="<?php echo date('Y'); ?>">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="company_employees" class="form-label">Number of Employees</label>
                                    <input type="text" id="company_employees" name="company_employees" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('company_employees')); ?>" 
                                           placeholder="e.g., 10-50, 50+">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="company_license" class="form-label">License Number</label>
                                    <input type="text" id="company_license" name="company_license" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('company_license')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Company Information
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Contact Details -->
        <div class="tab-content" id="contact">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Contact Details</h3>
                </div>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="hidden" name="section" value="contact">
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="contact_phone_primary" class="form-label">Primary Phone</label>
                                    <input type="text" id="contact_phone_primary" name="contact_phone_primary" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('contact_phone_primary', '+250 788 331 697')); ?>">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="contact_phone_secondary" class="form-label">Secondary Phone</label>
                                    <input type="text" id="contact_phone_secondary" name="contact_phone_secondary" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('contact_phone_secondary')); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="contact_email_general" class="form-label">General Email</label>
                                    <input type="email" id="contact_email_general" name="contact_email_general" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('contact_email_general', 'fsamcompanyltd@gmail.com')); ?>">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="contact_email_support" class="form-label">Support Email</label>
                                    <input type="email" id="contact_email_support" name="contact_email_support" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('contact_email_support')); ?>">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="contact_email_sales" class="form-label">Sales Email</label>
                                    <input type="email" id="contact_email_sales" name="contact_email_sales" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('contact_email_sales')); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="contact_hours_weekday" class="form-label">Weekday Hours</label>
                                    <input type="text" id="contact_hours_weekday" name="contact_hours_weekday" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('contact_hours_weekday', 'Monday - Friday: 8:00 AM - 6:00 PM')); ?>">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="contact_hours_weekend" class="form-label">Weekend Hours</label>
                                    <input type="text" id="contact_hours_weekend" name="contact_hours_weekend" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('contact_hours_weekend', 'Saturday: 8:00 AM - 2:00 PM')); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_emergency" class="form-label">Emergency Contact</label>
                            <input type="text" id="contact_emergency" name="contact_emergency" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('contact_emergency')); ?>" 
                                   placeholder="24/7 emergency contact information">
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Contact Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Social Media -->
        <div class="tab-content" id="social">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Social Media Links</h3>
                </div>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="hidden" name="section" value="social">
                    
                    <div class="card-body">
                        <div class="form-group">
                            <label for="social_facebook" class="form-label">
                                <i class="fab fa-facebook text-primary"></i> Facebook
                            </label>
                            <input type="url" id="social_facebook" name="social_facebook" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('social_facebook')); ?>" 
                                   placeholder="https://facebook.com/yourpage">
                        </div>
                        
                        <div class="form-group">
                            <label for="social_twitter" class="form-label">
                                <i class="fab fa-twitter text-info"></i> Twitter
                            </label>
                            <input type="url" id="social_twitter" name="social_twitter" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('social_twitter')); ?>" 
                                   placeholder="https://twitter.com/yourhandle">
                        </div>
                        
                        <div class="form-group">
                            <label for="social_linkedin" class="form-label">
                                <i class="fab fa-linkedin text-primary"></i> LinkedIn
                            </label>
                            <input type="url" id="social_linkedin" name="social_linkedin" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('social_linkedin')); ?>" 
                                   placeholder="https://linkedin.com/company/yourcompany">
                        </div>
                        
                        <div class="form-group">
                            <label for="social_instagram" class="form-label">
                                <i class="fab fa-instagram text-danger"></i> Instagram
                            </label>
                            <input type="url" id="social_instagram" name="social_instagram" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('social_instagram')); ?>" 
                                   placeholder="https://instagram.com/youraccount">
                        </div>
                        
                        <div class="form-group">
                            <label for="social_youtube" class="form-label">
                                <i class="fab fa-youtube text-danger"></i> YouTube
                            </label>
                            <input type="url" id="social_youtube" name="social_youtube" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('social_youtube')); ?>" 
                                   placeholder="https://youtube.com/channel/yourchannel">
                        </div>
                        
                        <div class="form-group">
                            <label for="social_whatsapp" class="form-label">
                                <i class="fab fa-whatsapp text-success"></i> WhatsApp
                            </label>
                            <input type="text" id="social_whatsapp" name="social_whatsapp" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('social_whatsapp')); ?>" 
                                   placeholder="+250788331697">
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Social Media Links
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Website Settings -->
        <div class="tab-content" id="website">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Website Settings</h3>
                </div>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="hidden" name="section" value="website">
                    
                    <div class="card-body">
                        <div class="form-group">
                            <label for="site_title" class="form-label">Site Title</label>
                            <input type="text" id="site_title" name="site_title" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('site_title', 'Fair Surveying & Mapping Company')); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="site_description" class="form-label">Site Description</label>
                            <textarea id="site_description" name="site_description" class="form-control" 
                                      rows="3"><?php echo htmlspecialchars(getSetting('site_description')); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="site_keywords" class="form-label">Site Keywords</label>
                            <input type="text" id="site_keywords" name="site_keywords" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('site_keywords')); ?>" 
                                   placeholder="surveying, mapping, GIS, Rwanda">
                        </div>
                        
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="site_author" class="form-label">Site Author</label>
                                    <input type="text" id="site_author" name="site_author" class="form-control" 
                                           value="<?php echo htmlspecialchars(getSetting('site_author', 'Fair Surveying & Mapping Company')); ?>">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="site_language" class="form-label">Site Language</label>
                                    <select id="site_language" name="site_language" class="form-control">
                                        <option value="en" <?php echo getSetting('site_language', 'en') === 'en' ? 'selected' : ''; ?>>English</option>
                                        <option value="rw" <?php echo getSetting('site_language') === 'rw' ? 'selected' : ''; ?>>Kinyarwanda</option>
                                        <option value="fr" <?php echo getSetting('site_language') === 'fr' ? 'selected' : ''; ?>>French</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="site_timezone" class="form-label">Timezone</label>
                                    <select id="site_timezone" name="site_timezone" class="form-control">
                                        <option value="Africa/Kigali" <?php echo getSetting('site_timezone', 'Africa/Kigali') === 'Africa/Kigali' ? 'selected' : ''; ?>>Africa/Kigali</option>
                                        <option value="UTC" <?php echo getSetting('site_timezone') === 'UTC' ? 'selected' : ''; ?>>UTC</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="site_maintenance" class="form-label">Maintenance Mode</label>
                            <select id="site_maintenance" name="site_maintenance" class="form-control">
                                <option value="0" <?php echo getSetting('site_maintenance', '0') === '0' ? 'selected' : ''; ?>>Disabled</option>
                                <option value="1" <?php echo getSetting('site_maintenance') === '1' ? 'selected' : ''; ?>>Enabled</option>
                            </select>
                            <div class="form-text">When enabled, only administrators can access the site.</div>
                        </div>
                        
                        <hr>
                        <h5>Analytics</h5>
                        
                        <div class="form-group">
                            <label for="analytics_google" class="form-label">Google Analytics ID</label>
                            <input type="text" id="analytics_google" name="analytics_google" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('analytics_google')); ?>" 
                                   placeholder="G-XXXXXXXXXX or UA-XXXXXXXX-X">
                        </div>
                        
                        <div class="form-group">
                            <label for="analytics_facebook" class="form-label">Facebook Pixel ID</label>
                            <input type="text" id="analytics_facebook" name="analytics_facebook" class="form-control" 
                                   value="<?php echo htmlspecialchars(getSetting('analytics_facebook')); ?>" 
                                   placeholder="XXXXXXXXXXXXXXX">
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Website Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle tab navigation
    document.querySelectorAll('[data-tab]').forEach(function(tab) {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetTab = this.dataset.tab;
            
            // Remove active class from all tabs and content
            document.querySelectorAll('[data-tab]').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
