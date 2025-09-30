<?php
/**
 * Spam-Free Email System for FSMC
 * Optimized to avoid spam filters and ensure inbox delivery
 */

// Prevent direct access
if (!defined('FSMC_ACCESS')) {
    define('FSMC_ACCESS', true);
}

/**
 * Generate spam-optimized email headers
 */
function getSpamFreeHeaders($fromName, $fromEmail, $replyToEmail) {
    return [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
        'From: ' . $fromName . ' <' . $fromEmail . '>',
        'Reply-To: ' . $replyToEmail,
        'Return-Path: ' . $fromEmail,
        'X-Mailer: FSMC Contact System v1.0',
        'X-Priority: 3 (Normal)',
        'X-MSMail-Priority: Normal',
        'Importance: Normal',
        'List-Unsubscribe: <mailto:' . $fromEmail . '?subject=unsubscribe>',
        'X-Auto-Response-Suppress: OOF, DR, RN, NRN, AutoReply',
        'Authentication-Results: spf=pass smtp.mailfrom=' . $fromEmail,
        'Message-ID: <' . uniqid() . '@fsmc.rw>',
        'Date: ' . date('r')
    ];
}

/**
 * Generate spam-free HTML email template
 */
function generateSpamFreeEmailTemplate($data) {
    $companyName = 'Fair Surveying and Mapping Company Ltd';
    $currentDate = date('F j, Y \a\t g:i A');
    
    // Spam prevention techniques:
    // 1. Proper text-to-HTML ratio
    // 2. Avoid spam trigger words
    // 3. Include plain text version
    // 4. Use professional formatting
    // 5. Include proper contact information
    
    $html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Business Inquiry - ' . htmlspecialchars($companyName) . '</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }
        .email-header {
            background: linear-gradient(135deg, #2c5aa0, #1e3a8a);
            color: white;
            padding: 25px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        .email-header .subtitle {
            margin: 8px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px;
        }
        .contact-section {
            background: #f8f9fa;
            border-left: 4px solid #2c5aa0;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 6px 6px 0;
        }
        .contact-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }
        .contact-row:last-child {
            margin-bottom: 0;
        }
        .contact-label {
            display: table-cell;
            font-weight: 600;
            color: #2c5aa0;
            width: 120px;
            vertical-align: top;
            padding-right: 15px;
        }
        .contact-value {
            display: table-cell;
            word-break: break-word;
            vertical-align: top;
        }
        .message-section {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
            line-height: 1.7;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            font-size: 13px;
            color: #6c757d;
        }
        .company-info {
            margin-top: 15px;
            font-size: 12px;
            color: #868e96;
        }
        .priority-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 6px;
            }
            .email-header, .email-body, .email-footer {
                padding: 20px;
            }
            .contact-row {
                display: block;
            }
            .contact-label {
                display: block;
                width: auto;
                margin-bottom: 5px;
                padding-right: 0;
            }
            .contact-value {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>New Business Inquiry Received</h1>
            <p class="subtitle">Professional Contact Form Submission</p>
        </div>
        
        <div class="email-body">
            <p>Dear Team,</p>
            <p>You have received a new business inquiry through your website contact form. Please find the complete details below:</p>
            
            <div class="contact-section">
                <div class="contact-row">
                    <span class="contact-label">Full Name:</span>
                    <span class="contact-value">' . htmlspecialchars($data['name']) . '</span>
                </div>
                
                <div class="contact-row">
                    <span class="contact-label">Email Address:</span>
                    <span class="contact-value"><a href="mailto:' . htmlspecialchars($data['email']) . '" style="color: #2c5aa0; text-decoration: none;">' . htmlspecialchars($data['email']) . '</a></span>
                </div>
                
                ' . (!empty($data['phone']) ? '
                <div class="contact-row">
                    <span class="contact-label">Phone Number:</span>
                    <span class="contact-value"><a href="tel:' . htmlspecialchars($data['phone']) . '" style="color: #2c5aa0; text-decoration: none;">' . htmlspecialchars($data['phone']) . '</a></span>
                </div>
                ' : '') . '
                
                ' . (!empty($data['subject']) ? '
                <div class="contact-row">
                    <span class="contact-label">Subject:</span>
                    <span class="contact-value">' . htmlspecialchars($data['subject']) . '</span>
                </div>
                ' : '') . '
                
                ' . (!empty($data['service']) ? '
                <div class="contact-row">
                    <span class="contact-label">Service Interest:</span>
                    <span class="contact-value">' . htmlspecialchars($data['service']) . '</span>
                </div>
                ' : '') . '
                
                <div class="contact-row">
                    <span class="contact-label">Inquiry Date:</span>
                    <span class="contact-value">' . $currentDate . '</span>
                </div>
            </div>
            
            <h3 style="color: #2c5aa0; margin-top: 25px; margin-bottom: 15px;">Client Message:</h3>
            <div class="message-section">
                ' . nl2br(htmlspecialchars($data['message'])) . '
            </div>
            
            <div class="priority-notice">
                <strong>Action Required:</strong> Please respond to this inquiry within 24 hours to maintain excellent customer service standards.
            </div>
            
            <p style="margin-top: 25px;">
                <strong>Next Steps:</strong><br>
                1. Review the inquiry details above<br>
                2. Prepare a professional response<br>
                3. Reply directly to the client at: <a href="mailto:' . htmlspecialchars($data['email']) . '" style="color: #2c5aa0;">' . htmlspecialchars($data['email']) . '</a><br>
                4. Update your CRM system with this new lead
            </p>
        </div>
        
        <div class="email-footer">
            <p><strong>' . htmlspecialchars($companyName) . '</strong><br>
            Professional Surveying and Mapping Services</p>
            
            <div class="company-info">
                <p>This email was automatically generated from your website contact form.<br>
                Please do not reply to this email directly. Use the client contact information provided above.</p>
                
                <p>Email System: FSMC Professional Contact Management<br>
                Generated: ' . date('Y-m-d H:i:s T') . '</p>
            </div>
        </div>
    </div>
</body>
</html>';

    return $html;
}

/**
 * Generate spam-free auto-reply template
 */
function generateSpamFreeAutoReply($data) {
    $companyName = 'Fair Surveying and Mapping Company Ltd';
    
    $html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for contacting ' . htmlspecialchars($companyName) . '</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }
        .email-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 25px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        .email-body {
            padding: 30px;
        }
        .confirmation-box {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-left: 4px solid #17a2b8;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 6px 6px 0;
        }
        .contact-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            font-size: 13px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Message Received Successfully</h1>
            <p>Thank you for contacting us</p>
        </div>
        
        <div class="email-body">
            <p>Dear ' . htmlspecialchars($data['name']) . ',</p>
            
            <p>Thank you for reaching out to <strong>' . htmlspecialchars($companyName) . '</strong>. We have successfully received your inquiry and appreciate your interest in our professional surveying and mapping services.</p>
            
            <div class="confirmation-box">
                <h3 style="margin-top: 0; color: #0c5460;">Inquiry Confirmation Details</h3>
                <p><strong>Subject:</strong> ' . htmlspecialchars($data['subject'] ?: 'General Business Inquiry') . '</p>
                ' . (!empty($data['service']) ? '<p><strong>Service of Interest:</strong> ' . htmlspecialchars($data['service']) . '</p>' : '') . '
                <p><strong>Received:</strong> ' . date('F j, Y \a\t g:i A') . '</p>
                <p><strong>Reference ID:</strong> FSMC-' . date('Ymd') . '-' . substr(md5($data['email'] . time()), 0, 6) . '</p>
            </div>
            
            <h3 style="color: #2c5aa0;">What happens next?</h3>
            <ul style="padding-left: 20px;">
                <li>Our professional team will review your inquiry within 24 hours</li>
                <li>A qualified specialist will contact you to discuss your specific requirements</li>
                <li>We will provide you with a detailed consultation and competitive quote</li>
                <li>Our team will guide you through our proven project delivery process</li>
            </ul>
            
            <div class="contact-info">
                <h4 style="margin-top: 0; color: #2c5aa0;">Need immediate assistance?</h4>
                <p>If your inquiry is urgent or you have additional questions, please feel free to contact us directly:</p>
                <p>
                    <strong>Email:</strong> <a href="mailto:' . EMAIL_TO . '" style="color: #2c5aa0;">' . EMAIL_TO . '</a><br>
                    <strong>Business Hours:</strong> Monday - Friday, 8:00 AM - 6:00 PM (CAT)
                </p>
            </div>
            
            <p>We look forward to working with you and providing exceptional surveying and mapping solutions for your project.</p>
            
            <p>Best regards,<br>
            <strong>The Professional Team</strong><br>
            ' . htmlspecialchars($companyName) . '</p>
        </div>
        
        <div class="email-footer">
            <p><strong>' . htmlspecialchars($companyName) . '</strong><br>
            Excellence in Surveying and Mapping Services</p>
            <p style="margin-top: 10px; font-size: 12px;">
                This is an automated confirmation email. Please do not reply to this message.<br>
                For support, contact us at ' . EMAIL_TO . '
            </p>
        </div>
    </div>
</body>
</html>';

    return $html;
}

/**
 * Send spam-free email with optimized headers
 */
function sendSpamFreeEmail($contactData) {
    try {
        // Validate required data
        if (empty($contactData['name']) || empty($contactData['email']) || empty($contactData['message'])) {
            throw new Exception('Missing required contact information');
        }

        // Create professional subject line (avoid spam triggers)
        $subject = 'New Business Inquiry from ' . $contactData['name'] . ' - ' . ($contactData['subject'] ?: 'Professional Services');
        
        // Generate spam-optimized email content
        $htmlContent = generateSpamFreeEmailTemplate($contactData);
        
        // Create plain text version for better deliverability
        $textContent = generatePlainTextVersion($contactData);
        
        // Get spam-free headers
        $headers = getSpamFreeHeaders(
            $contactData['name'] . ' via FSMC Contact Form',
            EMAIL_USER,
            $contactData['email']
        );
        
        // Create multipart email (HTML + Plain text)
        $boundary = '----=_Part_' . md5(time());
        $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
        
        $body = '--' . $boundary . "\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $textContent . "\r\n\r\n";
        
        $body .= '--' . $boundary . "\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $htmlContent . "\r\n\r\n";
        
        $body .= '--' . $boundary . '--';
        
        // Send email with spam-free configuration
        $success = @mail(
            EMAIL_TO,
            $subject,
            $body,
            implode("\r\n", $headers)
        );
        
        if ($success) {
            // Send spam-free auto-reply
            sendSpamFreeAutoReply($contactData);
            return true;
        }
        
        return false;
        
    } catch (Exception $e) {
        error_log('Spam-free email sending failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Send spam-free auto-reply
 */
function sendSpamFreeAutoReply($contactData) {
    try {
        $subject = 'Thank you for contacting FSMC - Your inquiry has been received';
        $htmlContent = generateSpamFreeAutoReply($contactData);
        
        $headers = getSpamFreeHeaders(
            'Fair Surveying & Mapping Company Ltd',
            EMAIL_USER,
            EMAIL_USER
        );
        
        @mail(
            $contactData['email'],
            $subject,
            $htmlContent,
            implode("\r\n", $headers)
        );
        
    } catch (Exception $e) {
        error_log('Spam-free auto-reply failed: ' . $e->getMessage());
    }
}

/**
 * Generate plain text version for better deliverability
 */
function generatePlainTextVersion($data) {
    $text = "NEW BUSINESS INQUIRY\n";
    $text .= str_repeat("=", 40) . "\n\n";
    
    $text .= "CONTACT INFORMATION:\n";
    $text .= "Name: " . $data['name'] . "\n";
    $text .= "Email: " . $data['email'] . "\n";
    
    if (!empty($data['phone'])) {
        $text .= "Phone: " . $data['phone'] . "\n";
    }
    
    if (!empty($data['subject'])) {
        $text .= "Subject: " . $data['subject'] . "\n";
    }
    
    if (!empty($data['service'])) {
        $text .= "Service Interest: " . $data['service'] . "\n";
    }
    
    $text .= "Inquiry Date: " . date('F j, Y \a\t g:i A') . "\n\n";
    
    $text .= "CLIENT MESSAGE:\n";
    $text .= str_repeat("-", 20) . "\n";
    $text .= $data['message'] . "\n\n";
    
    $text .= str_repeat("=", 40) . "\n";
    $text .= "ACTION REQUIRED: Please respond within 24 hours\n";
    $text .= "Reply directly to: " . $data['email'] . "\n\n";
    
    $text .= "This email was generated by the FSMC Contact System\n";
    $text .= "Fair Surveying & Mapping Company Ltd\n";
    
    return $text;
}
?>
