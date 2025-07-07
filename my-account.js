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

    // Avatar upload functionality
    const initAvatarUpload = () => {
        const avatarInput = document.getElementById('avatar-upload');
        const avatarDisplay = document.querySelector('.snipcss0-0-0-1 p, .user-avatar');
        
        if (avatarInput && avatarDisplay) {
            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        showToast('Please select a valid image file');
                        return;
                    }
                    
                    // Validate file size (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        showToast('Image size should be less than 2MB');
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Create image element
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'user-avatar-img';
                        img.alt = 'User Avatar';
                        
                        // Replace the text avatar with image
                        avatarDisplay.innerHTML = '';
                        avatarDisplay.appendChild(img);
                        
                        showToast('Avatar updated successfully!');
                        
                        // Here you would typically upload to server
                        // uploadAvatarToServer(file);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
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
    initAvatarUpload();
    initNavigationEffects();
    
    /**
     * Initialize navigation hover and interaction effects
     */
    function initNavigationEffects() {
        const navItems = document.querySelectorAll('.myaccounttabs');
        
        navItems.forEach((item, index) => {
            // Add staggered animation delay
            item.style.setProperty('--animation-delay', `${index * 0.1}s`);
            
            // Add click ripple effect
            item.addEventListener('click', function(e) {
                createRippleEffect(e, this);
            });
            
            // Add loading state for navigation links
            const link = item.querySelector('a');
            if (link) {
                link.addEventListener('click', function(e) {
                    const isLogout = this.hasAttribute('data-logout') || 
                                   this.href.includes('customer-logout');
                    
                    // Don't add loading state for logout (handled by popup)
                    if (!isLogout) {
                        item.classList.add('loading');
                        
                        // Add loading spinner to icon
                        const iconDown = item.querySelector('.icondown img');
                        if (iconDown) {
                            iconDown.style.animation = 'spin 1s linear infinite';
                        }
                        
                        // Remove loading state after navigation
                        setTimeout(() => {
                            item.classList.remove('loading');
                            if (iconDown) {
                                iconDown.style.animation = '';
                            }
                        }, 2000);
                    }
                });
                
                // Add ARIA labels for accessibility
                const text = item.querySelector('.tab-text-myaccount strong');
                if (text) {
                    link.setAttribute('aria-label', `Navigate to ${text.textContent}`);
                }
                
                // Add keyboard navigation
                link.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            }
        });
        
        // Set active states based on current URL
        const currentUrl = window.location.href;
        navItems.forEach(item => {
            const link = item.querySelector('a');
            if (link && currentUrl.includes(link.getAttribute('href'))) {
                item.classList.add('is-active');
            }
        });
    }
    
    /**
     * Create ripple effect on click
     */
    function createRippleEffect(event, element) {
        const ripple = document.createElement('span');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: radial-gradient(circle, rgba(124, 41, 216, 0.3) 0%, transparent 70%);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
            z-index: 1;
        `;
        
        element.appendChild(ripple);
        
        // Remove ripple after animation
        setTimeout(() => {
            if (ripple.parentNode) {
                ripple.parentNode.removeChild(ripple);
            }
        }, 600);
    }
});

// Add CSS for navigation effects
if (!document.querySelector('#navigation-effects-styles')) {
    const style = document.createElement('style');
    style.id = 'navigation-effects-styles';
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .myaccounttabs.loading {
            pointer-events: none;
            opacity: 0.7;
        }
    `;
    document.head.appendChild(style);
}
