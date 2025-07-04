<?php
/**
 * My Account Template
 */
if (!defined('ABSPATH')) {
    exit;
}

// Get current user and endpoint
$current_user = wp_get_current_user();
$current_endpoint = WC()->query->get_current_endpoint();
$menu_items = wc_get_account_menu_items();

// Remove default WooCommerce output if needed
remove_action('woocommerce_account_content', 'woocommerce_account_content');

/**
 * Helper function to get menu icons
 */
function get_account_menu_icon($endpoint) {
    $icons = [
        'dashboard' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M3 13H11V3H3V13ZM3 21H11V15H3V21ZM13 21H21V11H13V21ZM13 3V9H21V3H13Z" fill="#484848"/></svg>',
        'orders' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M19 3H14.82C14.4 1.84 13.3 1 12 1C10.7 1 9.6 1.84 9.18 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM12 3C12.55 3 13 3.45 13 4C13 4.55 12.55 5 12 5C11.45 5 11 4.55 11 4C11 3.45 11.45 3 12 3ZM14 17H7V15H14V17ZM17 13H7V11H17V13ZM17 9H7V7H17V9Z" fill="#484848"/></svg>',
        'downloads' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M19 9H15V3H9V9H5L12 16L19 9ZM5 18V20H19V18H5Z" fill="#484848"/></svg>',
        'edit-address' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22C12 22 19 14.25 19 9C19 5.13 15.87 2 12 2ZM12 11.5C10.62 11.5 9.5 10.38 9.5 9C9.5 7.62 10.62 6.5 12 6.5C13.38 6.5 14.5 7.62 14.5 9C14.5 10.38 13.38 11.5 12 11.5Z" fill="#484848"/></svg>',
        'edit-account' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" fill="#484848"/></svg>',
        'customer-logout' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M17 7L15.59 8.41L18.17 11H8V13H18.17L15.59 15.58L17 17L22 12L17 7ZM4 5H12V3H4C2.9 3 2 3.9 2 5V19C2 20.1 2.9 21 4 21H12V19H4V5Z" fill="#484848"/></svg>'
    ];

    return isset($icons[$endpoint]) ? $icons[$endpoint] : '';
}
?>

<div class="order-desktop">
    <div class="container">
      
      
       <!-- Add the ajax data div here -->
        <div id="ajax-data" 
             data-nonce="<?php echo wp_create_nonce("cancel-order"); ?>"
             data-ajax-url="<?php echo admin_url('admin-ajax.php'); ?>">
        </div>
        
        
        <div class="my-account myaccount-order">
            <!-- Left Sidebar -->
            <div class="leftside-my-account">
                <button class="mobile-menu-toggle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                    </svg>
                    Menu
                </button>

                <div class="userprofileleft">
                    <?php echo get_avatar($current_user->ID, 80); ?>
                    <div class="profileinfo">
                        <h2><?php echo esc_html($current_user->display_name); ?></h2>
                        <div class="info">
                            <p><?php echo esc_html($current_user->user_email); ?></p>
                        </div>
                    </div>
                    <a href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>" class="edit-profile">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M11.7167 7.51667L12.4833 8.28333L4.93333 15.8333H4.16667V15.0667L11.7167 7.51667ZM14.7167 2.5C14.5083 2.5 14.2917 2.58333 14.1333 2.74167L12.6083 4.26667L15.7333 7.39167L17.2583 5.86667C17.5833 5.54167 17.5833 5.01667 17.2583 4.69167L15.3083 2.74167C15.1417 2.575 14.9333 2.5 14.7167 2.5ZM11.7167 5.15833L2.5 14.375V17.5H5.625L14.8417 8.28333L11.7167 5.15833Z" fill="#484848"/>
                        </svg>
                    </a>
                </div>

                <nav class="myaccountnavigationtab">
                    <ul>
                        <?php foreach ($menu_items as $endpoint => $label) : ?>
                            <li class="myaccounttabs <?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                                <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>">
                                    <div class="tabmain">
                                        <div class="svgtext">
                                            <?php echo get_account_menu_icon($endpoint); ?>
                                            <div class="tab-text-myaccount">
                                                <strong><?php echo esc_html($label); ?></strong>
                                            </div>
                                        </div>
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M8.59 16.59L13.17 12L8.59 7.41L10 6L16 12L10 18L8.59 16.59Z" fill="#AFAFAF"/>
                                        </svg>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>

            <!-- Right Content -->
            <div class="rightside-my-account">
                <div class="ordermainbox">
                  
                    <?php if ('orders' === $current_endpoint || empty($current_endpoint)) : ?>
                        <div class="top-strip-order">
                            <h3><?php esc_html_e('Your Orders', 'woocommerce'); ?></h3>
                            <button class="filter-button">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M2.5 15H7.5V13.3333H2.5V15ZM2.5 5V6.66667H17.5V5H2.5ZM2.5 10.8333H12.5V9.16667H2.5V10.8333Z" fill="#3A3A3A"/>
                                </svg>
                                <?php esc_html_e('Filter', 'woocommerce'); ?>
                            </button>
                        </div>
                  
                

                        <div class="my-order-order-mobile">
                            <?php
                            // Get order status filter
                            $status_filter = isset($_GET['order_status']) ? wc_clean($_GET['order_status']) : '';
                            
                            // Prepare order query arguments
                            $order_args = array(
                                'customer_id' => get_current_user_id(),
                                'limit'       => -1,
                                'status'      => $status_filter ? $status_filter : 'any',
                                'orderby'     => 'date',
                                'order'       => 'DESC',
                            );

                            // Get customer orders using WC_Order_Query
                            $customer_orders = wc_get_orders($order_args);

                            if ($customer_orders) : 
                                foreach ($customer_orders as $order) :
                                    // Get order items
                                    $order_items = $order->get_items();
                                    
                                    foreach ($order_items as $item_id => $item) :
                                        $product = $item->get_product();
                                        if (!$product) continue;
                                        
                                        // Get product image
                                        $image = $product->get_image(array(100, 100));

                                        // Calculate days since order
                                        $order_date = $order->get_date_created();
                                        $current_date = new DateTime();
                                        $days_difference = $current_date->diff($order_date)->days;
                            ?>
                                        <div class="order-product-section">
                                            <div class="orderboxtop">
                                                <p class="order-date">
                                                    <?php 
                                                    echo wp_kses_post(
                                                        sprintf(
                                                            /* translators: 1: order date 2: order time */
                                                            _x('Ordered on %1$s @ %2$s', 'Order date and time', 'woocommerce'),
                                                            $order->get_date_created()->date_i18n(get_option('date_format')),
                                                            $order->get_date_created()->date_i18n(get_option('time_format'))
                                                        )
                                                    ); 
                                                    ?>
                                                </p>
                                                <p class="orderidmain">
                                                    <?php 
                                                    echo sprintf(
                                                        /* translators: %s: order number */
                                                        esc_html__('Order #%s', 'woocommerce'),
                                                        $order->get_order_number()
                                                    ); 
                                                    ?>
                                                </p>
                                            </div>
                                            
                                            <div class="order-content">
                                                <div class="pimg">
                                                    <?php echo $image; ?>
                                                </div>
                                                <div class="pdetails">
                                                    <h4><?php echo esc_html($item->get_name()); ?></h4>
                                                    <div class="order-product-details">
                                                        <p>
                                                            <strong><?php esc_html_e('Quantity:', 'woocommerce'); ?></strong> 
                                                            <?php echo esc_html($item->get_quantity()); ?>
                                                        </p>
                                                        <p>
                                                            <strong><?php esc_html_e('Status:', 'woocommerce'); ?></strong>
                                                            <span class="order-status <?php echo esc_attr('status-' . $order->get_status()); ?>">
                                                                <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
                                                            </span>
                                                        </p>
                                                        <p>
                                                            <strong><?php esc_html_e('Total:', 'woocommerce'); ?></strong>
                                                            <?php 
                                                            echo wp_kses_post(
                                                                sprintf(
                                                                    /* translators: %s: order item total */
                                                                    _x('%s', 'Order item total', 'woocommerce'),
                                                                    $order->get_formatted_line_subtotal($item)
                                                                )
                                                            ); 
                                                            ?>
                                                        </p>
                                                        <?php if ($order->get_payment_method_title()) : ?>
                                                            <p>
                                                                <strong><?php esc_html_e('Payment:', 'woocommerce'); ?></strong>
                                                                <?php echo esc_html($order->get_payment_method_title()); ?>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="order-user-list">
                                                        <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="view">
                                                            <?php esc_html_e('View Order', 'woocommerce'); ?>
                                                        </a>
                                                        <?php 
                                                        // Show cancel button if:
                                                        // 1. Order is not already cancelled or completed
                                                        // 2. Less than 5 days have passed since order creation
                                                        $cancellable_statuses = array('pending', 'processing', 'on-hold');
                                                        if (in_array($order->get_status(), $cancellable_statuses) && $days_difference <= 5) : 
                                                        ?>
                                                        
                                                        
<?php 
// Calculate days remaining for cancellation
$days_remaining = 5 - $days_difference;

if ($order->get_cancel_order_url()) : ?>
    <a href="<?php echo esc_url($order->get_cancel_order_url()); ?>" class="cancel" onclick="return confirm('<?php esc_html_e('Are you sure you want to cancel this order?', 'woocommerce'); ?>')">
        <?php esc_html_e('Cancel Order', 'woocommerce'); ?>
    </a>
    <?php if ($days_remaining > 0) : ?>
        <span class="cancel-timer">
            <?php printf(_n('(%d day left to cancel)', '(%d days left to cancel)', $days_remaining, 'woocommerce'), $days_remaining); ?>
        </span>
    <?php endif; ?>
<?php endif; ?>
                                                       
                                                       
                                                            
                                            
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php 
                                    endforeach;
                                endforeach;
                            else : 
                            ?>
                                <div class="woocommerce-message woocommerce-message--info">
                                    <?php 
                                    if ($status_filter) {
                                        esc_html_e('No orders found with the selected status.', 'woocommerce');
                                    } else {
                                        esc_html_e('No order has been made yet.', 'woocommerce');
                                    }
                                    ?>
                                    <a class="woocommerce-Button button" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                                        <?php esc_html_e('Browse products', 'woocommerce'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Cancel Order Confirmation Modal -->
                        
<!-- Cancel Order Confirmation Modal -->
<?php if (is_user_logged_in()) : ?>
    <!-- AJAX data for JavaScript - Keep this one at the top level -->
    <div id="ajax-data" 
         data-nonce="<?php echo wp_create_nonce('cancel-order'); ?>" 
         data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
         style="display: none;">
    </div>

    <div id="cancelOrderModal" class="modal">
        <div class="modal-content" role="dialog" aria-labelledby="cancelOrderTitle">
            <h3 id="cancelOrderTitle"><?php esc_html_e('Cancel Order', 'woocommerce'); ?></h3>
            <p><?php esc_html_e('Are you sure you want to cancel this order?', 'woocommerce'); ?></p>
            <div class="modal-actions">
                <button id="confirmCancel" class="button alt" type="button">
                    <?php esc_html_e('Yes, Cancel Order', 'woocommerce'); ?>
                </button>
                <button id="cancelModal" class="button" type="button">
                    <?php esc_html_e('No, Keep Order', 'woocommerce'); ?>
                </button>
            </div>
            <?php wp_nonce_field('cancel-order', 'woocommerce-cancel-order-nonce'); ?>
        </div>
    </div>
<?php endif; ?>

                        
                        

                    <?php endif; ?>




                    <?php if ('view-order' === $current_endpoint) : 
                        $order_id = get_query_var('view-order');
                        $order = wc_get_order($order_id);

                        if (!$order) {
                            return;
                        }

                        $order_items = $order->get_items();
                        $shipping_address = $order->get_formatted_shipping_address();
                        $billing_address = $order->get_formatted_billing_address();

                        // Get ShipRocket tracking details
                        $tracking_data = get_post_meta($order->get_id(), '_shiprocket_tracking_number', true);
                        $shipment_status = get_post_meta($order->get_id(), '_shiprocket_shipment_status', true);

                        // Define tracking steps and their status
                        $tracking_steps = array(
                            'ordered' => array(
                                'icon' => '<svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                    <path d="M30 13.3334H25.8167C25.4 12.1734 24.3 11.3334 23 11.3334C21.7 11.3334 20.6 12.1734 20.18 13.3334H16C14.9 13.3334 14 14.2334 14 15.3334V29.3334C14 30.4334 14.9 31.3334 16 31.3334H30C31.1 31.3334 32 30.4334 32 29.3334V15.3334C32 14.2334 31.1 13.3334 30 13.3334ZM23 13.3334C23.55 13.3334 24 13.7834 24 14.3334C24 14.8834 23.55 15.3334 23 15.3334C22.45 15.3334 22 14.8834 22 14.3334C22 13.7834 22.45 13.3334 23 13.3334ZM25 27.3334H18V25.3334H25V27.3334ZM28 23.3334H18V21.3334H28V23.3334ZM28 19.3334H18V17.3334H28V19.3334Z" fill="#484848"/>
                                </svg>',
                                'status' => 'Order Placed',
                                'active' => true
                            ),
                            'processing' => array(
                                'icon' => '<svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                    <path d="M31.6667 11.6666H8.33333C7.41667 11.6666 6.66667 12.4166 6.66667 13.3333V26.6666C6.66667 27.5833 7.41667 28.3333 8.33333 28.3333H31.6667C32.5833 28.3333 33.3333 27.5833 33.3333 26.6666V13.3333C33.3333 12.4166 32.5833 11.6666 31.6667 11.6666ZM31.6667 26.6666H8.33333V13.3333H31.6667V26.6666ZM15.8333 23.3333H24.1667C24.625 23.3333 25 22.9583 25 22.5V17.5C25 17.0416 24.625 16.6666 24.1667 16.6666H15.8333C15.375 16.6666 15 17.0416 15 17.5V22.5C15 22.9583 15.375 23.3333 15.8333 23.3333Z" fill="#484848"/>
                                </svg>',
                                'status' => 'Processing',
                                'active' => in_array($shipment_status, ['processing', 'shipped', 'delivered'])
                            ),
                            'shipped' => array(
                                'icon' => '<svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                    <path d="M31.6667 13.3334H25V10.8334C25 9.91675 24.25 9.16675 23.3333 9.16675H8.33333C7.41667 9.16675 6.66667 9.91675 6.66667 10.8334V25.8334C6.66667 26.7501 7.41667 27.5001 8.33333 27.5001H10C10 29.7501 11.8333 31.5834 14.0833 31.5834C16.3333 31.5834 18.1667 29.7501 18.1667 27.5001H23C23 29.7501 24.8333 31.5834 27.0833 31.5834C29.3333 31.5834 31.1667 29.7501 31.1667 27.5001H31.6667C32.5833 27.5001 33.3333 26.7501 33.3333 25.8334V15.0001C33.3333 14.0834 32.5833 13.3334 31.6667 13.3334ZM14.1667 29.1667C13.1667 29.1667 12.3333 28.3334 12.3333 27.3334C12.3333 26.3334 13.1667 25.5001 14.1667 25.5001C15.1667 25.5001 16 26.3334 16 27.3334C16 28.3334 15.1667 29.1667 14.1667 29.1667ZM27.1667 29.1667C26.1667 29.1667 25.3333 28.3334 25.3333 27.3334C25.3333 26.3334 26.1667 25.5001 27.1667 25.5001C28.1667 25.5001 29 26.3334 29 27.3334C29 28.3334 28.1667 29.1667 27.1667 29.1667ZM31.6667 22.5001H30.8333C30.1667 21.3334 28.75 20.5001 27.1667 20.5001C25.5833 20.5001 24.1667 21.3334 23.5 22.5001H17.6667C17 21.3334 15.5833 20.5001 14 20.5001C12.4167 20.5001 11 21.3334 10.3333 22.5001H8.33333V10.8334H23.3333V22.5001H25V15.8334H31.6667V22.5001Z" fill="#484848"/>
                                </svg>',
                                'status' => 'Shipped',
                                'active' => in_array($shipment_status, ['shipped', 'delivered'])
                            ),
                            'delivered' => array(
                                'icon' => '<svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                    <path d="M20 3.33325C10.8 3.33325 3.33333 10.7999 3.33333 19.9999C3.33333 29.1999 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.1999 36.6667 19.9999C36.6667 10.7999 29.2 3.33325 20 3.33325ZM20 33.3333C12.65 33.3333 6.66667 27.3499 6.66667 19.9999C6.66667 12.6499 12.65 6.66659 20 6.66659C27.35 6.66659 33.3333 12.6499 33.3333 19.9999C33.3333 27.3499 27.35 33.3333 20 33.3333ZM28.3333 13.3333L15 26.6666L11.6667 23.3333L9.16667 25.8333L15 31.6666L30.8333 15.8333L28.3333 13.3333Z" fill="#484848"/>
                                </svg>',
                                'status' => 'Delivered',
                                'active' => $shipment_status === 'delivered'
                            )
                        );
                    ?>
                        <div class="orderinfomain">
                                   


                            <div class="orderinfomain-container">
                                <!-- Top Strip -->
                                <div class="top-strip">
                                    <p>Order Details</p>
                                </div>

                                <!-- Order Info -->
                                <div class="orderinfo">
                                    <div class="orderinfo-box">
                                        <?php foreach ($order_items as $item_id => $item) :
                                            $product = $item->get_product();
                                            if (!$product) continue;
                                        ?>
                                            <div class="pimg">
                                                <div class="imagemain">
                                                    <?php echo $product->get_image(); ?>
                                                    <p class="orderstatusimg"><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></p>
                                                </div>
                                                <div class="ordernumber">
                                                    <span>Order: #<?php echo $order->get_order_number(); ?></span>
                                                </div>
                                            </div>
                                            <div class="pdetails">
                                                <p class="pname"><?php echo esc_html($item->get_name()); ?></p>
                                                <span class="productcategoryname">
                                                    <?php echo wc_get_product_category_list($product->get_id()); ?>
                                                </span>
                                                <div class="order-product-details">
                                                    <p>Size: <?php echo esc_html($item->get_meta('pa_size')); ?></p>
                                                    <p>Color: <?php echo esc_html($item->get_meta('pa_color')); ?></p>
                                                </div>
                                                <p class="qty">Quantity: <?php echo esc_html($item->get_quantity()); ?></p>
                                                <p class="pprice"><?php echo $order->get_formatted_line_subtotal($item); ?></p>
                                                <div class="orderlocationstatus">
                                                    <p class="statusmain"><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                        <!-- Order Tracking -->
                                        <div class="productlivelocation">
                                            <div class="icon-container">
                                                <?php 
                                                $first = true;
                                                foreach ($tracking_steps as $step => $data) : 
                                                    if (!$first) : 
                                                ?>
                                                    <div class="line" style="background-color: <?php echo $data['active'] ? '#FFDD00' : '#d3d3d3'; ?>"></div>
                                                <?php 
                                                    endif;
                                                    $first = false;
                                                ?>
                                                    <div class="icon-item" style="background-color: <?php echo $data['active'] ? '#FFDD00' : '#fff'; ?>">
                                                        <?php echo $data['icon']; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="itemstatusmain">
                                                <?php foreach ($tracking_steps as $step => $data) : ?>
                                                    <div class="p-status">
                                                        <span style="color: <?php echo $data['active'] ? '#000' : '#464646'; ?>">
                                                            <?php echo $data['status']; ?>
                                                        </span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php if ($tracking_data) : ?>
                                                <div class="trackid">
                                                    <span class="trackingidheading">Tracking ID:</span>
                                                    <span class="ittracking"><?php echo esc_html($tracking_data); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Shipping Address -->
                                        <div class="shippingaddress">
                                            <div class="shippingmain">
                                                <p class="headingshipping">Shipping Address</p>
                                                <p class="shippinmgaddress"><?php echo $shipping_address; ?></p>
                                            </div>
                                        </div>

                                        <!-- Updates -->
                                        <div class="updatesend-to">
                                            <p class="upheading">Updates sent to:</p>
                                            <div>
                                                <span><?php echo $order->get_billing_email(); ?></span>
                                            </div>
                                        </div>

                                        <!-- Price Section -->
                                        <div class="price-section-account">
                                            <div class="total-items">
                                                <p class="items-heading">Price Details</p>
                                            </div>
                                            <div class="price-section">
                                                <div class="mrp-text">
                                                    <span>Cart Total</span>
                                                    <span class="checkoutvalue"><?php echo $order->get_subtotal_to_display(); ?></span>
                                                </div>
                                                <?php if ($order->get_shipping_total() > 0) : ?>
                                                    <div class="mrp-text">
                                                        <span>Shipping</span>
                                                        <span class="checkoutvalue"><?php echo $order->get_shipping_to_display(); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($order->get_total_discount() > 0) : ?>
                                                    <div class="mrp-text">
                                                        <span>Discount</span>
                                                        <span class="checkoutvalue">-<?php echo $order->get_discount_to_display(); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                          
                                            <div class="total-amount">
                                                <div class="inner">
                                                    <span>Total Amount</span>
                                                    <span><?php echo $order->get_formatted_order_total(); ?></span>
                                                    
                                                    
                                                    
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Function to update tracking status
                            function updateTrackingStatus() {
                                const orderId = '<?php echo $order->get_id(); ?>';
                                const trackingNumber = '<?php echo esc_js($tracking_data); ?>';
                                
                                if (!trackingNumber) return;

                                // Make AJAX call to ShipRocket API endpoint
                                fetch('/wp-admin/admin-ajax.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: new URLSearchParams({
                                        action: 'get_shiprocket_tracking',
                                        order_id: orderId,
                                        tracking_number: trackingNumber,
                                        security: '<?php echo wp_create_nonce("tracking-status"); ?>'
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Update tracking UI based on response
                                        updateTrackingUI(data.status);
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                            }

                            // Update tracking status every 5 minutes
                            updateTrackingStatus();
                            setInterval(updateTrackingStatus, 300000);
                        });
                        </script>
                    <?php endif; ?>

                    <?php if ('edit-account' === $current_endpoint) : ?>
                        <div class="edit-account-form">
                            <h2><?php esc_html_e('Account Details', 'woocommerce'); ?></h2>
                            <?php do_action('woocommerce_before_edit_account_form'); ?>
                            
                            <form class="woocommerce-EditAccountForm" action="" method="post">
                                <?php do_action('woocommerce_edit_account_form_start'); ?>
                                
                                <p class="form-row">
                                    <label for="account_first_name">
                                        <?php esc_html_e('First name', 'woocommerce'); ?>
                                    </label>
                                    <input type="text" name="account_first_name" id="account_first_name" 
                                        value="<?php echo esc_attr($current_user->first_name); ?>">
                                </p>
                                
                                <p class="form-row">
                                    <label for="account_last_name">
                                        <?php esc_html_e('Last name', 'woocommerce'); ?>
                                    </label>
                                    <input type="text" name="account_last_name" id="account_last_name" 
                                        value="<?php echo esc_attr($current_user->last_name); ?>">
                                </p>
                                
                                <p class="form-row">
                                    <label for="account_display_name">
                                        <?php esc_html_e('Display name', 'woocommerce'); ?>
                                    </label>
                                    <input type="text" name="account_display_name" id="account_display_name" 
                                        value="<?php echo esc_attr($current_user->display_name); ?>">
                                </p>
                                
                                <p class="form-row">
                                    <label for="account_email">
                                        <?php esc_html_e('Email address', 'woocommerce'); ?>
                                    </label>
                                    <input type="email" name="account_email" id="account_email" 
                                        value="<?php echo esc_attr($current_user->user_email); ?>">
                                </p>
                                
                                <fieldset>
                                    <legend><?php esc_html_e('Password change', 'woocommerce'); ?></legend>
                                    
                                    <p class="form-row">
                                        <label for="password_current">
                                            <?php esc_html_e('Current password (leave blank to leave unchanged)', 'woocommerce'); ?>
                                        </label>
                                        <input type="password" name="password_current" id="password_current" autocomplete="off">
                                    </p>
                                    
                                    <p class="form-row">
                                        <label for="password_1">
                                            <?php esc_html_e('New password (leave blank to leave unchanged)', 'woocommerce'); ?>
                                        </label>
                                        <input type="password" name="password_1" id="password_1" autocomplete="off">
                                    </p>
                                    
                                    <p class="form-row">
                                        <label for="password_2">
                                            <?php esc_html_e('Confirm new password', 'woocommerce'); ?>
                                        </label>
                                        <input type="password" name="password_2" id="password_2" autocomplete="off">
                                    </p>
                                </fieldset>
                                
                                <?php do_action('woocommerce_edit_account_form'); ?>
                                
                                <p class="form-row">
                                    <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
                                    <button type="submit" name="save_account_details" value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>">
                                        <?php esc_html_e('Save changes', 'woocommerce'); ?>
                                    </button>
                                    <input type="hidden" name="action" value="save_account_details">
                                </p>
                                
                                <?php do_action('woocommerce_edit_account_form_end'); ?>
                            </form>
                            
                            <?php do_action('woocommerce_after_edit_account_form'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ('edit-address' === $current_endpoint) : 
                        $address_type = isset($_GET['address']) ? wc_clean(wp_unslash($_GET['address'])) : 'billing';
                    ?>
                        <div class="edit-address-form">
                            <h2>
                                <?php echo 'billing' === $address_type ? esc_html__('Billing Address', 'woocommerce') : esc_html__('Shipping Address', 'woocommerce'); ?>
                            </h2>
                            
                            <?php
                            $load_address = $address_type;
                            $country = get_user_meta(get_current_user_id(), $load_address . '_country', true);
                            
                            if (!$country) {
                                $country = WC()->countries->get_base_country();
                            }
                            
                            do_action('woocommerce_before_edit_address_form');
                            
                            $address = WC()->countries->get_address_fields($country, $load_address . '_');
                            ?>
                            
                            <form method="post">
                                <div class="woocommerce-address-fields">
                                    <?php do_action("woocommerce_before_edit_address_form_{$load_address}"); ?>
                                    
                                    <div class="woocommerce-address-fields__field-wrapper">
                                        <?php
                                        foreach ($address as $key => $field) {
                                            woocommerce_form_field($key, $field, wc_get_post_data_by_key($key, $current_user->$key));
                                        }
                                        ?>
                                    </div>
                                    
                                    <?php do_action("woocommerce_after_edit_address_form_{$load_address}"); ?>
                                    
                                    <p>
                                        <?php wp_nonce_field('woocommerce-edit_address', 'woocommerce-edit-address-nonce'); ?>
                                        <button type="submit" name="save_address" value="<?php esc_attr_e('Save address', 'woocommerce'); ?>">
                                            <?php esc_html_e('Save address', 'woocommerce'); ?>
                                        </button>
                                        <input type="hidden" name="action" value="edit_address">
                                    </p>
                                </div>
                            </form>
                            
                            <?php do_action('woocommerce_after_edit_address_form'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ('downloads' === $current_endpoint) : 
                        $downloads = WC()->customer->get_downloadable_products();
                    ?>
                        <div class="downloads-section">
                            <h2><?php esc_html_e('Downloads', 'woocommerce'); ?></h2>
                            
                            <?php if ($downloads) : ?>
                                <table class="woocommerce-table woocommerce-table--downloads">
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e('Product', 'woocommerce'); ?></th>
                                            <th><?php esc_html_e('Downloads remaining', 'woocommerce'); ?></th>
                                            <th><?php esc_html_e('Expires', 'woocommerce'); ?></th>
                                            <th><?php esc_html_e('Download', 'woocommerce'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($downloads as $download) : ?>
                                            <tr>
                                                <td>
                                                    <?php if ($download['product_url']) : ?>
                                                        <a href="<?php echo esc_url($download['product_url']); ?>">
                                                            <?php echo esc_html($download['product_name']); ?>
                                                        </a>
                                                    <?php else : ?>
                                                        <?php echo esc_html($download['product_name']); ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo esc_html(
                                                        $download['downloads_remaining'] === '' 
                                                        ? __('Unlimited', 'woocommerce') 
                                                        : $download['downloads_remaining']
                                                    );
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo esc_html(
                                                        $download['access_expires'] === '' 
                                                        ? __('Never', 'woocommerce') 
                                                        : date_i18n(get_option('date_format'), strtotime($download['access_expires']))
                                                    );
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo esc_url($download['download_url']); ?>" class="button download-button">
                                                        <?php esc_html_e('Download', 'woocommerce'); ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <div class="woocommerce-message woocommerce-message--info">
                                    <?php esc_html_e('No downloads available yet.', 'woocommerce'); ?>
                                    <a class="woocommerce-Button button" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                                        <?php esc_html_e('Browse products', 'woocommerce'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ('customer-logout' === $current_endpoint) : ?>
                        <div class="logout-confirmation">
                            <h2><?php esc_html_e('Logout', 'woocommerce'); ?></h2>
                            <p><?php esc_html_e('Are you sure you want to log out?', 'woocommerce'); ?></p>
                            
                            <div class="logout-actions">
                                <a href="<?php echo esc_url(wc_logout_url()); ?>" class="button confirm-logout">
                                    <?php esc_html_e('Yes, log out', 'woocommerce'); ?>
                                </a>
                                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button cancel-logout">
                                    <?php esc_html_e('Cancel', 'woocommerce'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                  
                  
          
                                                 <!-- WhatsApp Help Button -->
                                        <div class="button-bottom-fixed">
                                            <a target="_blank" href="https://api.whatsapp.com/send/?phone=919257044989&text=Hi%20Store!&type=phone_number&app_absent=0" style="text-transform:capitalize;padding:15px 0px;font-weight:600;font-size:16px;width:100%;background:#FFDD00;color:#3A3A3A;border-radius:4px;text-align:center;display:flex;align-items:center;justify-content:center;gap:5px">
                                                Help
                                                <img src="https://www.beyoung.in/mobile/images/new-my-account/icons/support.png" alt="Support">
                                            </a>
                                        </div>        
                  
                  
                  
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout Popup -->
<div id="logoutPopup" class="logout-popup" style="display: none;">
    <div class="cartremovemain">
        <p><?php esc_html_e('Are you sure you want to log out?', 'woocommerce'); ?></p>
        <span class="applepop" id="confirmLogout"><?php esc_html_e('Yes, log out', 'woocommerce'); ?></span>
        <span class="canclepop" id="cancelLogout"><?php esc_html_e('Cancel', 'woocommerce'); ?></span>
    </div>
</div>

<!-- Filter Popup -->
<div id="orderFilterPopup" class="popup-overlay" style="display: none;">
    <div class="popupmain">
        <div class="popupmain-inner">
            <h3 class="poptopheading"><?php esc_html_e('Filter Orders', 'woocommerce'); ?></h3>
            
            <div class="productstatuspop">
                <div class="fileterorder">
                    <span><?php esc_html_e('Order Status', 'woocommerce'); ?></span>
                    <div class="allfilterpopup">
                        <label>
                            <input type="radio" name="order_status" value="all" checked>
                            <span><?php esc_html_e('All', 'woocommerce'); ?></span>
                        </label>
                        <?php
                        $order_statuses = wc_get_order_statuses();
                        foreach ($order_statuses as $status => $label) :
                            $status = str_replace('wc-', '', $status);
                        ?>
                            <label>
                                <input type="radio" name="order_status" value="<?php echo esc_attr($status); ?>">
                                <span><?php echo esc_html($label); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="appleyandcancle">
                <span class="canclepop" id="cancelFilter"><?php esc_html_e('Cancel', 'woocommerce'); ?></span>
                <span class="applepop" id="applyFilter"><?php esc_html_e('Apply', 'woocommerce'); ?></span>
            </div>
        </div>
    </div>
</div>
