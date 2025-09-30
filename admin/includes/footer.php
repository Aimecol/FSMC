        </div> <!-- End page-content -->
        </div> <!-- End content-wrapper -->
    </main> <!-- End main-content -->

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading...</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/admin.js"></script>
    <script src="assets/js/session_manager.js"></script>
    <?php if (isset($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script>        
        // Global utility functions
        window.showLoading = function() {
            document.getElementById('loadingOverlay').classList.add('show');
        };
        
        window.hideLoading = function() {
            document.getElementById('loadingOverlay').classList.remove('show');
        };
        
        window.showAlert = function(message, type = 'info') {
            const alertContainer = document.querySelector('.page-content');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible`;
            alert.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
                ${message}
                <button type="button" class="alert-close" onclick="this.closest('.alert').remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            alertContainer.insertBefore(alert, alertContainer.firstChild);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        };
    </script>
</body>
</html>
