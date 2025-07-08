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

    // MAIN FIX: Handle rightside-my-account content
    const fixRightsideMyAccount = () => {
        // Only run on desktop
        if (window.innerWidth >= 768) {
            const rightSide = document.querySelector('.rightside-my-account');
            
            if (rightSide) {
                // Check if we're on the main dashboard (not a specific page like orders, addresses, etc.)
                const currentPath = window.location.pathname;
                const isMainDashboard = currentPath.endsWith('/my-account/') || 
                                       currentPath.endsWith('/my-account') ||
                                       (currentPath.includes('/my-account/') && 
                                        !currentPath.includes('/orders/') && 
                                        !currentPath.includes('/downloads/') &&
                                        !currentPath.includes('/addresses/') &&
                                        !currentPath.includes('/edit-account/') &&
                                        !currentPath.includes('/payment-methods/'));
                
                if (isMainDashboard) {
                    // Find any navigation menus in the rightside content
                    const duplicateNavs = rightSide.querySelectorAll('.woocommerce-MyAccount-navigation, nav, .myaccounttabs');
                    
                    // Hide all duplicate navigations
                    duplicateNavs.forEach(nav => {
                        nav.style.display = 'none';
                    });
                    
                    // Find the main content container
                    let contentContainer = rightSide.querySelector('.woocommerce-MyAccount-content') || rightSide;
                    
                    // Clear any existing navigation content and replace with dashboard
                    if (!contentContainer.querySelector('.dashboard-content-created')) {
                        // Mark as created to prevent duplicates
                        const marker = document.createElement('div');
                        marker.className = 'dashboard-content-created';
                        marker.style.display = 'none';
                        contentContainer.appendChild(marker);
                        
                        // Create dashboard content
                        const dashboardContent = document.createElement('div');
                        dashboardContent.className = 'my-account-dashboard';
                        dashboardContent.innerHTML = `
                            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 12px; margin-bottom: 30px; text-align: center;">
                                <h2 style="margin: 0 0 10px 0; font-size: 24px; font-weight: 600;">Welcome back!</h2>
                                <p style="margin: 0; opacity: 0.9; font-size: 16px;">Here's what's happening with your account</p>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                                    <div style="font-size: 28px; font-weight: bold; color: #7c29d8; margin-bottom: 5px;">12</div>
                                    <div style="color: #666; font-size: 14px;">Total Orders</div>
                                </div>
                                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                                    <div style="font-size: 28px; font-weight: bold; color: #7c29d8; margin-bottom: 5px;">2</div>
                                    <div style="color: #666; font-size: 14px;">Pending Orders</div>
                                </div>
                                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                                    <div style="font-size: 28px; font-weight: bold; color: #7c29d8; margin-bottom: 5px;">10</div>
                                    <div style="color: #666; font-size: 14px;">Completed Orders</div>
                                </div>
                            </div>
                            
                            <div style="background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
                                <h3 style="margin: 0 0 15px 0; color: #333; font-size: 18px; font-weight: 600;">Recent Orders</h3>
                                <div style="display: flex; align-items: center; padding: 15px; border: 1px solid #e0e0e0; border-radius: 6px; margin-bottom: 10px; transition: all 0.3s ease;">
                                    <img src="https://images.pexels.com/photos/1020585/pexels-photo-1020585.jpeg?auto=compress&cs=tinysrgb&w=100&h=100&dpr=1" alt="Product" style="width: 60px; height: 60px; border-radius: 6px; margin-right: 15px; object-fit: cover;">
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #333; margin-bottom: 5px;">Gamer Squad Oversize T-Shirt...</div>
                                        <div style="font-size: 14px; color: #666;">Order #4741 • Ordered on 07 Jul 2025</div>
                                    </div>
                                    <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; text-transform: uppercase; background: #fff3cd; color: #856404;">Processing</span>
                                </div>
                                <div style="text-align: center; margin-top: 20px;">
                                    <a href="/my-account/orders/" style="background: #7c29d8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: 500;">View All Orders</a>
                                </div>
                            </div>
                        `;
                        
                        // Clear existing content and add dashboard
                        contentContainer.innerHTML = '';
                        contentContainer.appendChild(marker);
                        contentContainer.appendChild(dashboardContent);
                    }
                }
            }
        }
    };

    // Initialize all functionality
    initMobileMenu();
    initLogoutPopup();
    initFilterPopup();
    initOrderTracking();
    initCopyOrderId();
    initAvatarUpload();
    
    // Fix the rightside-my-account issue
    fixRightsideMyAccount();
    
    // Also run on window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            fixRightsideMyAccount();
        }
    });
    
    // Run again after a short delay to ensure DOM is fully loaded
    setTimeout(fixRightsideMyAccount, 500);
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
        
        /* Ensure mobile menu overlay styles */
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
        }
        
        /* Dashboard hover effects */
        .my-account-dashboard [style*="display: flex"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
        }
    `;
    document.head.appendChild(style);
}
