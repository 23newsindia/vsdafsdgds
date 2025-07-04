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
        const logoutLinks = document.querySelectorAll('a[data-logout="true"], a[href*="customer-logout"]');
        
        logoutLinks.forEach(logoutLink => {
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();
                
                const popup = document.getElementById('logoutPopup');
                if (popup) {
                    popup.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    
                    const confirmBtn = document.getElementById('confirmLogout');
                    const cancelBtn = document.getElementById('cancelLogout');
                    
                    // Remove existing event listeners
                    const newConfirmBtn = confirmBtn.cloneNode(true);
                    const newCancelBtn = cancelBtn.cloneNode(true);
                    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
                    cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
                    
                    newConfirmBtn.addEventListener('click', function() {
                        window.location.href = logoutLink.href;
                    });
                    
                    newCancelBtn.addEventListener('click', function() {
                        popup.style.display = 'none';
                        document.body.style.overflow = '';
                    });
                    
                    popup.addEventListener('click', function(e) {
                        if (e.target === popup) {
                            popup.style.display = 'none';
                            document.body.style.overflow = '';
                        }
                    });
                }
            });
        });
    };

    // Order Filter Popup
    const initFilterPopup = () => {
        const filterButton = document.querySelector('.filter-button');
        const popup = document.getElementById('orderFilterPopup');
        
        if (!filterButton || !popup) return;
        
        filterButton.addEventListener('click', function() {
            popup.style.display = 'block';
            popup.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
        
        const cancelBtn = document.getElementById('cancelFilter');
        const applyBtn = document.getElementById('applyFilter');
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                popup.style.display = 'none';
                popup.classList.remove('show');
                document.body.style.overflow = '';
            });
        }
        
        if (applyBtn) {
            applyBtn.addEventListener('click', function() {
                const selectedStatus = document.querySelector('input[name="status"]:checked');
                const selectedTime = document.querySelector('input[name="time"]:checked');
                
                popup.style.display = 'none';
                popup.classList.remove('show');
                document.body.style.overflow = '';
                
                // Show loading state
                filterButton.innerHTML = `
                    <span class="loading-spinner"></span>
                    Filter
                `;
                filterButton.disabled = true;
                
                // Build filter URL
                let filterUrl = window.location.pathname;
                const params = new URLSearchParams();
                
                if (selectedStatus && selectedStatus.value !== '0') {
                    params.append('order_status', selectedStatus.value);
                }
                
                if (selectedTime && selectedTime.value !== '1') {
                    params.append('order_time', selectedTime.value);
                }
                
                if (params.toString()) {
                    filterUrl += '?' + params.toString();
                }
                
                window.location.href = filterUrl;
            });
        }
        
        // Close popup when clicking overlay
        popup.addEventListener('click', function(e) {
            if (e.target === popup || e.target.classList.contains('fixed')) {
                popup.style.display = 'none';
                popup.classList.remove('show');
                document.body.style.overflow = '';
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

    // Copy order ID functionality
    const initCopyOrderId = () => {
        const copyButtons = document.querySelectorAll('.ordernumber img');
        
        copyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const orderIdElement = this.previousElementSibling;
                if (orderIdElement) {
                    const orderIdText = orderIdElement.textContent;
                    const orderId = orderIdText.replace('Order ID. #', '');
                    
                    // Copy to clipboard
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(orderId).then(() => {
                            showToast('Order ID copied to clipboard!');
                        });
                    } else {
                        // Fallback for older browsers
                        const textArea = document.createElement('textarea');
                        textArea.value = orderId;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);
                        showToast('Order ID copied to clipboard!');
                    }
                }
            });
        });
    };

    // Show toast notification
    const showToast = (message) => {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            z-index: 10000;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    };

    // Initialize all functionality
    initMobileMenu();
    initLogoutPopup();
    initFilterPopup();
    initOrderTracking();
    initCopyOrderId();
});
