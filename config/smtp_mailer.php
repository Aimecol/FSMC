<?php
/**
 * Simple SMTP Mailer Class for FSMC
 * A lightweight alternative to PHPMailer
 */

class SMTPMailer {
    private $host;
    private $port;
    private $username;
    private $password;
    private $socket;
    private $debug = false;
    
    public function __construct($host, $port, $username, $password) {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }
    
    public function setDebug($debug = true) {
        $this->debug = $debug;
    }
    
    private function log($message) {
        if ($this->debug) {
            echo "[SMTP] " . $message . "\n";
        }
    }
    
    private function sendCommand($command, $expectedCode = 250) {
        fwrite($this->socket, $command . "\r\n");
        $response = fgets($this->socket, 512);
        $this->log("Command: $command");
        $this->log("Response: " . trim($response));
        
        $code = intval(substr($response, 0, 3));
        
        // Handle multiple expected codes (array or single value)
        $expectedCodes = is_array($expectedCode) ? $expectedCode : [$expectedCode];
        
        if (!in_array($code, $expectedCodes)) {
            throw new Exception("SMTP Error: Expected " . implode(' or ', $expectedCodes) . ", got $code - $response");
        }
        
        return $response;
    }
    
    public function connect() {
        $this->socket = fsockopen($this->host, $this->port, $errno, $errstr, 30);
        if (!$this->socket) {
            throw new Exception("Failed to connect to SMTP server: $errstr ($errno)");
        }
        
        // Read initial response
        $response = fgets($this->socket, 512);
        $this->log("Initial response: " . trim($response));
        
        // Send EHLO
        $this->sendCommand("EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
        
        // Start TLS (Gmail may respond with 220 or 250)
        $this->sendCommand("STARTTLS", [220, 250]);
        
        // Try different TLS methods for better compatibility
        $tlsEnabled = false;
        $tlsMethods = [
            STREAM_CRYPTO_METHOD_TLS_CLIENT,
            STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
            STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT,
            STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT
        ];
        
        foreach ($tlsMethods as $method) {
            if (@stream_socket_enable_crypto($this->socket, true, $method)) {
                $tlsEnabled = true;
                $this->log("TLS enabled successfully with method: " . $method);
                break;
            }
        }
        
        if (!$tlsEnabled) {
            // Don't log this as an error since we expect it to fail in XAMPP
            throw new Exception("TLS encryption not available (expected in XAMPP)");
        }
        
        // Send EHLO again after TLS
        $this->sendCommand("EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
        
        // Authenticate
        $this->sendCommand("AUTH LOGIN", 334);
        $this->sendCommand(base64_encode($this->username), 334);
        $this->sendCommand(base64_encode($this->password), 235);
        
        $this->log("Successfully connected and authenticated");
    }
    
    public function sendMail($from, $fromName, $to, $subject, $htmlBody, $textBody = null, $replyTo = null) {
        try {
            $this->connect();
            
            // Set sender
            $this->sendCommand("MAIL FROM: <$from>");
            
            // Set recipient
            $this->sendCommand("RCPT TO: <$to>");
            
            // Start data
            $this->sendCommand("DATA", 354);
            
            // Build headers
            $headers = [];
            $headers[] = "From: $fromName <$from>";
            $headers[] = "To: <$to>";
            $headers[] = "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=";
            $headers[] = "Date: " . date('r');
            $headers[] = "Message-ID: <" . uniqid() . "@" . $_SERVER['SERVER_NAME'] ?? 'localhost' . ">";
            $headers[] = "MIME-Version: 1.0";
            
            if ($replyTo) {
                $headers[] = "Reply-To: <$replyTo>";
            }
            
            // Multipart message
            $boundary = "----=_Part_" . uniqid();
            $headers[] = "Content-Type: multipart/alternative; boundary=\"$boundary\"";
            
            // Send headers
            foreach ($headers as $header) {
                fwrite($this->socket, $header . "\r\n");
            }
            fwrite($this->socket, "\r\n");
            
            // Send text part if provided
            if ($textBody) {
                fwrite($this->socket, "--$boundary\r\n");
                fwrite($this->socket, "Content-Type: text/plain; charset=UTF-8\r\n");
                fwrite($this->socket, "Content-Transfer-Encoding: 8bit\r\n\r\n");
                fwrite($this->socket, $textBody . "\r\n");
            }
            
            // Send HTML part
            fwrite($this->socket, "--$boundary\r\n");
            fwrite($this->socket, "Content-Type: text/html; charset=UTF-8\r\n");
            fwrite($this->socket, "Content-Transfer-Encoding: 8bit\r\n\r\n");
            fwrite($this->socket, $htmlBody . "\r\n");
            
            // End multipart
            fwrite($this->socket, "--$boundary--\r\n");
            
            // End data
            $this->sendCommand(".", 250);
            
            $this->log("Email sent successfully");
            return true;
            
        } catch (Exception $e) {
            $this->log("Error: " . $e->getMessage());
            throw $e;
        } finally {
            $this->disconnect();
        }
    }
    
    public function disconnect() {
        if ($this->socket) {
            fwrite($this->socket, "QUIT\r\n");
            fclose($this->socket);
            $this->socket = null;
            $this->log("Disconnected from SMTP server");
        }
    }
}

/**
 * Enhanced email sending function using SMTP
 */
function sendContactEmailSMTP($contactData) {
    try {
        // Validate required data
        if (empty($contactData['name']) || empty($contactData['email']) || empty($contactData['message'])) {
            throw new Exception('Missing required contact information');
        }

        // Create SMTP mailer instance
        $mailer = new SMTPMailer(EMAIL_HOST, EMAIL_PORT, EMAIL_USER, EMAIL_PASS);
        
        // Enable debug in development
        if (defined('FSMC_DEBUG') && FSMC_DEBUG) {
            $mailer->setDebug(true);
        }
        
        // Generate email content
        $subject = 'New Contact Form Submission - ' . ($contactData['subject'] ?: 'General Inquiry');
        $htmlContent = generateEmailTemplate($contactData);
        $textContent = generatePlainTextEmail($contactData);
        
        // Send main notification email
        $result = $mailer->sendMail(
            EMAIL_USER,
            EMAIL_FROM_NAME,
            EMAIL_TO,
            $subject,
            $htmlContent,
            $textContent,
            $contactData['email']
        );
        
        if ($result) {
            // Send auto-reply
            sendAutoReplySMTP($contactData);
        }
        
        return $result;
        
    } catch (Exception $e) {
        error_log('SMTP Email sending failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Send auto-reply using SMTP
 */
function sendAutoReplySMTP($contactData) {
    try {
        $mailer = new SMTPMailer(EMAIL_HOST, EMAIL_PORT, EMAIL_USER, EMAIL_PASS);
        
        $subject = 'Thank you for contacting FSMC - We received your message';
        $htmlContent = generateAutoReplyTemplate($contactData);
        
        $mailer->sendMail(
            EMAIL_USER,
            EMAIL_FROM_NAME,
            $contactData['email'],
            $subject,
            $htmlContent,
            null,
            EMAIL_USER
        );
        
    } catch (Exception $e) {
        error_log('Auto-reply SMTP failed: ' . $e->getMessage());
    }
}
?>
