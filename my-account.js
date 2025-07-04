document.addEventListener('DOMContentLoaded', function() {
    const cancelButtons = document.querySelectorAll('a.cancel');
    
    cancelButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to cancel this order?')) {
                e.preventDefault();
            }
        });
    });

    // Mobile Menu Toggle
    const initMobileMenu = () => {
        const leftSide = document.querySelector('.leftside-my-account');
        if (!leftSide) return;
        
        // Create toggle button if doesn't exist
        if (!document.querySelector('.mobile-menu-toggle')) {
            const mobileMenuToggle = document.createElement('button');
            mobileMenuToggle.className = 'mobile-menu-toggle';
            mobileMenuToggle.innerHTML = `
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                </svg>
                Menu
            `;
            leftSide.insertBefore(mobileMenuToggle, leftSide.firstChild);
            
            mobileMenuToggle.addEventListener('click', function() {
                document.body.classList.toggle('mobile-menu-open');
                leftSide.classList.toggle('mobile-menu-open');
            });
        }
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (leftSide.classList.contains('mobile-menu-open') && 
                !leftSide.contains(e.target) && 
                e.target !== document.querySelector('.mobile-menu-toggle')) {
                document.body.classList.remove('mobile-menu-open');
                leftSide.classList.remove('mobile-menu-open');
            }
        });
    };

    // Logout Confirmation
    const initLogoutPopup = () => {
        const logoutLink = document.querySelector('a[href*="customer-logout"]');
        if (!logoutLink) return;
        
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            const popup = document.getElementById('logoutPopup');
            popup.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            document.getElementById('confirmLogout').addEventListener('click', function() {
                window.location.href = logoutLink.href;
            });
            
            document.getElementById('cancelLogout').addEventListener('click', function() {
                popup.style.display = 'none';
                document.body.style.overflow = '';
            });
            
            popup.addEventListener('click', function(e) {
                if (e.target === popup) {
                    popup.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
        });
    };

    // Order Filter Popup
    const initFilterPopup = () => {
        const filterButton = document.querySelector('.filter-button');
        if (!filterButton) return;
        
        filterButton.addEventListener('click', function() {
            const popup = document.getElementById('orderFilterPopup');
            popup.style.display = 'block';
            document.body.style.overflow = 'hidden';
            
            document.getElementById('cancelFilter').addEventListener('click', function() {
                popup.style.display = 'none';
                document.body.style.overflow = '';
            });
            
            document.getElementById('applyFilter').addEventListener('click', function() {
                const selectedStatus = document.querySelector('input[name="order_status"]:checked').value;
                popup.style.display = 'none';
                document.body.style.overflow = '';
                
                // Show loading state
                filterButton.innerHTML = `
                    <span class="loading-spinner"></span>
                    ${filterButton.textContent.trim()}
                `;
                filterButton.disabled = true;
                
                if (selectedStatus !== 'all') {
                    window.location.href = window.location.pathname + '?order_status=' + encodeURIComponent(selectedStatus);
                } else {
                    window.location.href = window.location.pathname;
                }
            });
            
            popup.addEventListener('click', function(e) {
                if (e.target === popup) {
                    popup.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
        });
    };

  
  
  
    // Order Cancellation
    document.querySelectorAll('.cancel-order').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const orderId = this.dataset.orderId;
            const nonce = this.dataset.nonce;

            if (confirm('Are you sure you want to cancel this order?')) {
                // Show loading state
                const originalHTML = this.innerHTML;
                this.innerHTML = '<span class="spinner is-active"></span> Processing...';

                fetch(ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'cancel_order',
                        order_id: orderId,
                        security: nonce
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI
                        const parentSection = this.closest('.order-product-section');
                        if (parentSection) {
                            // Update status
                            parentSection.querySelector('.order-status').textContent = '<?php _e("Cancelled", "woocommerce"); ?>';
                            parentSection.querySelector('.order-status').classList.add('status-cancelled');
                            
                            // Remove cancel elements
                            this.remove();
                            parentSection.querySelector('.cancel-timer')?.remove();
                        }
                        alert(data.data.message);
                    } else {
                        throw new Error(data.data || '<?php _e("Cancellation failed", "woocommerce"); ?>');
                    }
                })
                .catch(error => {
                    alert(error.message);
                    this.innerHTML = originalHTML;
                });
            }
        });
    });
  
  
  
  
  
  
  
    // Order Cancellation Functionality
const initOrderCancellation = () => {
    const cancelButtons = document.querySelectorAll('a.cancel');
    
    cancelButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const orderId = this.dataset.orderId;
            
            if (confirm('Are you sure you want to cancel this order?')) {
                // Show loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="loading-spinner"></span> Processing...';
                this.style.pointerEvents = 'none';
                
                // AJAX request
                fetch(ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'cancel_order',
                        order_id: orderId,
                        security: document.getElementById('ajax-data').dataset.nonce
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI
                        const orderSection = this.closest('.order-product-section');
                        if (orderSection) {
                            // Update status
                            const statusElement = orderSection.querySelector('.order-status');
                            if (statusElement) {
                                statusElement.textContent = 'Cancelled';
                                statusElement.className = 'order-status status-cancelled';
                            }
                            
                            // Remove cancel button and timer
                            this.remove();
                            const timer = orderSection.querySelector('.cancel-timer');
                            if (timer) timer.remove();
                        }
                        
                        alert(data.data.message);
                    } else {
                        throw new Error(data.data || 'Error cancelling order');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message);
                    this.innerHTML = originalText;
                    this.style.pointerEvents = 'auto';
                });
            }
        });
    });
};

    // Initialize all functionality
    initMobileMenu();
    initLogoutPopup();
    initFilterPopup();
    initOrderCancellation();
});
