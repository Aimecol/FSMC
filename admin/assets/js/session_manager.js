/**
 * Session Manager for FSMC Admin Panel
 * Handles automatic session extension and timeout warnings
 */

class SessionManager {
    constructor(options = {}) {
        this.options = {
            extendInterval: 5 * 60 * 1000, // 5 minutes
            warningTime: 5 * 60 * 1000,   // 5 minutes before timeout
            checkInterval: 60 * 1000,      // Check every minute
            ...options
        };
        
        this.sessionTimeout = 30 * 60 * 1000; // 30 minutes default
        this.lastActivity = Date.now();
        this.warningShown = false;
        
        this.init();
    }
    
    init() {
        // Start session monitoring
        this.startMonitoring();
        
        // Track user activity
        this.trackActivity();
        
        // Extend session periodically
        this.startAutoExtend();
        
        console.log('Session Manager initialized');
    }
    
    startMonitoring() {
        setInterval(() => {
            this.checkSession();
        }, this.options.checkInterval);
    }
    
    trackActivity() {
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.updateActivity();
            }, { passive: true });
        });
    }
    
    updateActivity() {
        this.lastActivity = Date.now();
        this.warningShown = false;
        
        // Hide warning if it's showing
        const warning = document.getElementById('session-warning');
        if (warning) {
            warning.style.display = 'none';
        }
    }
    
    startAutoExtend() {
        setInterval(() => {
            this.extendSession();
        }, this.options.extendInterval);
    }
    
    async extendSession() {
        try {
            const response = await fetch('ajax/extend_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin'
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.sessionTimeout = data.session.timeout_duration * 1000;
                console.log('Session extended successfully');
            } else {
                console.warn('Session extension failed:', data.message);
                
                if (data.redirect) {
                    this.redirectToLogin(data.redirect);
                }
            }
        } catch (error) {
            console.error('Session extension error:', error);
        }
    }
    
    async checkSession() {
        try {
            const response = await fetch('ajax/check_session.php', {
                method: 'GET',
                credentials: 'same-origin'
            });
            
            const data = await response.json();
            
            if (!data.success || !data.authenticated) {
                this.redirectToLogin(data.redirect);
                return;
            }
            
            // Check if session is about to expire
            const remainingTime = data.session.remaining_time * 1000;
            
            if (remainingTime <= this.options.warningTime && !this.warningShown) {
                this.showSessionWarning(remainingTime);
            }
            
        } catch (error) {
            console.error('Session check error:', error);
        }
    }
    
    showSessionWarning(remainingTime) {
        this.warningShown = true;
        
        const minutes = Math.floor(remainingTime / 60000);
        
        // Create or update warning element
        let warning = document.getElementById('session-warning');
        if (!warning) {
            warning = document.createElement('div');
            warning.id = 'session-warning';
            warning.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #f39c12;
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                z-index: 10000;
                font-family: Arial, sans-serif;
                font-size: 14px;
                max-width: 300px;
            `;
            document.body.appendChild(warning);
        }
        
        warning.innerHTML = `
            <div style="margin-bottom: 10px;">
                <strong>⚠️ Session Expiring</strong><br>
                Your session will expire in ${minutes} minute(s).
            </div>
            <button onclick="sessionManager.extendSession(); this.parentElement.style.display='none';" 
                    style="background: white; color: #f39c12; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                Extend Session
            </button>
        `;
        
        warning.style.display = 'block';
        
        // Auto-hide after 30 seconds
        setTimeout(() => {
            if (warning.style.display !== 'none') {
                warning.style.display = 'none';
            }
        }, 30000);
    }
    
    redirectToLogin(url) {
        if (url) {
            console.log('Redirecting to login:', url);
            window.location.href = url;
        } else {
            window.location.reload();
        }
    }
}

// Initialize session manager when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're in the admin area
    if (window.location.pathname.includes('/admin/')) {
        window.sessionManager = new SessionManager();
    }
});

// Export for manual use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SessionManager;
}
