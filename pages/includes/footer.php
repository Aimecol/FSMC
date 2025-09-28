<?php
// Include database configuration if not already included
if (!function_exists('getSetting')) {
    require_once '../config/database.php';
}
?>
<footer id="contact" class="footer">
    <div class="container">
    <div class="footer-content">
        <div class="footer-column">
        <h3>About Us</h3>
        <p style="color: #aaa; margin-bottom: 20px; line-height: 1.6">
            <?php echo getSetting('company_name', 'Fair Surveying & Mapping Ltd'); ?> provides reliable,
            professional, and expert solutions for all your surveying and
            mapping needs.
        </p>
        <div class="social-links">
            <a href="<?php echo getSetting('facebook_url', '#'); ?>" class="social-link"
            ><i class="fab fa-facebook-f"></i
            ></a>
            <a href="<?php echo getSetting('twitter_url', '#'); ?>" class="social-link"><i class="fab fa-twitter"></i></a>
            <a href="<?php echo getSetting('linkedin_url', '#'); ?>" class="social-link"
            ><i class="fab fa-linkedin-in"></i
            ></a>
            <a href="<?php echo getSetting('instagram_url', '#'); ?>" class="social-link"
            ><i class="fab fa-instagram"></i
            ></a>
        </div>
        </div>
        <div class="footer-column">
        <h3>Our Services</h3>
        <ul class="footer-links">
            <li>
            <a href="#"
                ><i class="fas fa-chevron-right"></i> Land Surveying</a
            >
            </li>
            <li>
            <a href="#"
                ><i class="fas fa-chevron-right"></i> Land Subdivision</a
            >
            </li>
            <li>
            <a href="#"
                ><i class="fas fa-chevron-right"></i> Building Permits</a
            >
            </li>
            <li>
            <a href="#"
                ><i class="fas fa-chevron-right"></i> Environmental
                Assessment</a
            >
            </li>
            <li>
            <a href="#"
                ><i class="fas fa-chevron-right"></i> Technical Training</a
            >
            </li>
        </ul>
        </div>
        <div class="footer-column footer-contact">
        <h3>Contact Us</h3>
        <p><i class="fas fa-map-marker-alt"></i> <?php echo getSetting('company_address', 'Kigali, Rwanda'); ?></p>
        <p><i class="fas fa-phone"></i> <?php echo getSetting('company_phone', '+250 788 331 697'); ?></p>
        <p><i class="fas fa-envelope"></i> <?php echo getSetting('company_email', 'fsamcompanyltd@gmail.com'); ?></p>
        <p><i class="fas fa-user-tie"></i> Surveyor Code: <?php echo getSetting('surveyor_code', 'LS00280'); ?></p>
        </div>
    </div>
    </div>
    <div class="footer-bottom">
    <div class="copyright">
        &copy; <?php echo date('Y'); ?> <?php echo getSetting('company_name', 'Fair Surveying & Mapping Ltd'); ?>. All Rights Reserved.
    </div>
    <div class="footer-nav">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
    </div>
    </div>
</footer>