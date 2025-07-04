document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle - Fixed overlay issue
    const initMobileMenu = () => {
        const leftSide = document.querySelector('.leftside-my-account');
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        
        if (!leftSide || !mobileMenuToggle) return;
        
        // Toggle menu on button click
        mobileMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isOpen = leftSide.classList.contains('mobile-menu-open');
            
            if (isOpen) {
                leftSide.classList.remove('mobile-menu-open');
                document.body.classList.remove('mobile-menu-open');
                this.setAttribute('aria-expanded', 'false');
                // Remove overlay
                const overlay = document.querySelector('.mobile-menu-overlay');
                if (overlay) overlay.remove();
            } else {
                leftSide.classList.add('mobile-menu-open');
                document.body.classList.add('mobile-menu-open');
                this.setAttribute('aria-expanded', 'true');
                
                // Create and add overlay
                const overlay = document.createElement('div');
                overlay.className = 'mobile-menu-overlay';
                document.body.appendChild(overlay);
                
                // Close menu when clicking overlay
                overlay.addEventListener('click', () => {
                    leftSide.classList.remove('mobile-menu-open');
                    document.body.classList.remove('mobile-menu-open');
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                    overlay.remove();
                });
            }
        });
        
        // Close menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && leftSide.classList.contains('mobile-menu-open')) {
                leftSide.classList.remove('mobile-menu-open');
                document.body.classList.remove('mobile-menu-open');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                const overlay = document.querySelector('.mobile-menu-overlay');
                if (overlay) overlay.remove();
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
            if (popup) {
                popup.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                
                const confirmBtn = document.getElementById('confirmLogout');
                const cancelBtn = document.getElementById('cancelLogout');
                
                if (confirmBtn) {
                    confirmBtn.addEventListener('click', function() {
                        window.location.href = logoutLink.href;
                    });
                }
                
                if (cancelBtn) {
                    cancelBtn.addEventListener('click', function() {
                        popup.style.display = 'none';
                        document.body.style.overflow = '';
                    });
                }
                
                popup.addEventListener('click', function(e) {
                    if (e.target === popup) {
                        popup.style.display = 'none';
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    };

    // Order Filter Popup
    const initFilterPopup = () => {
        const filterButton = document.querySelector('.filter-button');
        if (!filterButton) return;
        
        filterButton.addEventListener('click', function() {
            const popup = document.getElementById('orderFilterPopup');
            if (popup) {
                popup.style.display = 'block';
                document.body.style.overflow = 'hidden';
                
                const cancelBtn = document.getElementById('cancelFilter');
                const applyBtn = document.getElementById('applyFilter');
                
                if (cancelBtn) {
                    cancelBtn.addEventListener('click', function() {
                        popup.style.display = 'none';
                        document.body.style.overflow = '';
                    });
                }
                
                if (applyBtn) {
                    applyBtn.addEventListener('click', function() {
                        const selectedStatus = document.querySelector('input[name="order_status"]:checked');
                        popup.style.display = 'none';
                        document.body.style.overflow = '';
                        
                        // Show loading state
                        filterButton.innerHTML = `
                            <span class="loading-spinner"></span>
                            ${filterButton.textContent.trim()}
                        `;
                        filterButton.disabled = true;
                        
                        if (selectedStatus && selectedStatus.value !== 'all') {
                            window.location.href = window.location.pathname + '?order_status=' + encodeURIComponent(selectedStatus.value);
                        } else {
                            window.location.href = window.location.pathname;
                        }
                    });
                }
                
                popup.addEventListener('click', function(e) {
                    if (e.target === popup) {
                        popup.style.display = 'none';
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    };

    // Order Cancellation with 5-day timer - Fixed order ID detection
    const initOrderCancellation = () => {
        const cancelButtons = document.querySelectorAll('.cancel-order, a.cancel, a[href*="cancel_order"]');
        
        cancelButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Try multiple ways to get order ID
                let orderId = this.dataset.orderId || 
                             this.getAttribute('data-order-id') ||
                             this.getAttribute('data-order') ||
                             this.href.match(/order_id=(\d+)/)?.[1] ||
                             this.href.match(/order=([^&]+)/)?.[1];
                
                const nonce = this.dataset.nonce || 
                             this.getAttribute('data-nonce') ||
                             this.href.match(/_wpnonce=([^&]+)/)?.[1];
                
                if (!orderId) {
                    // Try to find order ID from parent elements
                    const orderSection = this.closest('.order-product-section, .ordermainbox');
                    if (orderSection) {
                        const orderIdElement = orderSection.querySelector('.orderidmain, [class*="order"]');
                        if (orderIdElement) {
                            const orderIdText = orderIdElement.textContent;
                            orderId = orderIdText.match(/#?(\d+)/)?.[1];
                        }
                    }
                }

                if (!orderId) {
                    alert('Order ID not found. Please try again or contact support.');
                    return;
                }

                if (confirm('Are you sure you want to cancel this order?')) {
                    // Show loading state
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<span class="loading-spinner"></span> Processing...';
                    this.style.pointerEvents = 'none';

                    // Check if ajaxurl is available (WordPress AJAX)
                    const ajaxUrl = typeof ajaxurl !== 'undefined' ? ajaxurl : '/wp-admin/admin-ajax.php';

                    fetch(ajaxUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'cancel_order',
                            order_id: orderId,
                            security: nonce || ''
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI
                            const parentSection = this.closest('.order-product-section, .ordermainbox');
                            if (parentSection) {
                                // Update status
                                const statusElement = parentSection.querySelector('.order-status, .statusmain, .p-status');
                                if (statusElement) {
                                    statusElement.textContent = 'Cancelled';
                                    statusElement.className = 'order-status status-cancelled';
                                }
                                
                                // Remove cancel elements
                                this.remove();
                                const timer = parentSection.querySelector('.cancel-timer');
                                if (timer) timer.remove();
                            }
                            
                            alert(data.data?.message || 'Order cancelled successfully');
                        } else {
                            throw new Error(data.data?.message || 'Cancellation failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(error.message || 'Error cancelling order');
                        this.innerHTML = originalHTML;
                        this.style.pointerEvents = 'auto';
                    });
                }
            });
        });
    };

    // Initialize cancel timers (5 days from order date)
    const initCancelTimers = () => {
        const orderSections = document.querySelectorAll('.order-product-section, .ordermainbox');
        
        orderSections.forEach(section => {
            const orderDateElement = section.querySelector('.order-date');
            const cancelButton = section.querySelector('.cancel-order, a.cancel, a[href*="cancel_order"]');
            
            if (orderDateElement && cancelButton) {
                const orderDateText = orderDateElement.textContent.trim();
                const orderDate = new Date(orderDateText);
                const currentDate = new Date();
                const daysDiff = Math.floor((currentDate - orderDate) / (1000 * 60 * 60 * 24));
                const remainingDays = 5 - daysDiff;
                
                if (remainingDays > 0) {
                    // Add timer display
                    let timerElement = section.querySelector('.cancel-timer');
                    if (!timerElement) {
                        timerElement = document.createElement('span');
                        timerElement.className = 'cancel-timer';
                        cancelButton.parentNode.appendChild(timerElement);
                    }
                    timerElement.textContent = `(${remainingDays} days left to cancel)`;
                } else {
                    // Remove cancel button if 5 days have passed
                    cancelButton.remove();
                }
            }
        });
    };

    // Order tracking functionality
    const initOrderTracking = () => {
        const trackingContainers = document.querySelectorAll('.productlivelocation');
        
        trackingContainers.forEach(container => {
            const statusElement = container.closest('.orderinfomain')?.querySelector('.statusmain') ||
                                 container.closest('.ordermainbox')?.querySelector('.p-status .mainstatus');
            if (!statusElement) return;
            
            const currentStatus = statusElement.textContent.toLowerCase().trim();
            const icons = container.querySelectorAll('.icon-item');
            const lines = container.querySelectorAll('.line');
            const statusTexts = container.querySelectorAll('.p-status');
            
            // Define status progression
            const statusOrder = ['processing', 'shipped', 'in-transit', 'delivered'];
            const currentIndex = statusOrder.findIndex(status => 
                currentStatus.includes(status) || 
                (status === 'processing' && currentStatus.includes('processing')) ||
                (status === 'shipped' && currentStatus.includes('shipped')) ||
                (status === 'in-transit' && currentStatus.includes('transit')) ||
                (status === 'delivered' && (currentStatus.includes('delivered') || currentStatus.includes('completed')))
            );
            
            // Update visual indicators
            icons.forEach((icon, index) => {
                if (index <= currentIndex) {
                    icon.classList.add('active');
                }
            });
            
            lines.forEach((line, index) => {
                if (index < currentIndex) {
                    line.classList.add('active');
                }
            });
            
            statusTexts.forEach((text, index) => {
                if (index <= currentIndex) {
                    text.classList.add('active');
                }
            });
        });
    };

    // Initialize all functionality
    initMobileMenu();
    initLogoutPopup();
    initFilterPopup();
    initOrderCancellation();
    initCancelTimers();
    initOrderTracking();
});
