<?php
/**
 * My Account Navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );

// Get current user data
$current_user = wp_get_current_user();
$user_name = $current_user->display_name;
$user_email = $current_user->user_email;
$user_phone = get_user_meta($current_user->ID, 'billing_phone', true);
$user_gender = get_user_meta($current_user->ID, 'billing_gender', true);

// Get first letter for avatar
$first_letter = strtoupper(substr($user_name, 0, 1));

// Check if we're on orders page or order details page
$is_orders_page = is_wc_endpoint_url('orders') || is_wc_endpoint_url('view-order');
$is_order_details = is_wc_endpoint_url('view-order');

// Get order details if on order details page
$order = null;
if ($is_order_details) {
    global $wp;
    $order_id = absint($wp->query_vars['view-order']);
    $order = wc_get_order($order_id);
}
?>

<div class="order-desktop">
    <div class="my-account">
        <div class="myaccount-order">
            <!-- Left Sidebar -->
            <div class="leftside-my-account">
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" aria-expanded="false">
                    <span>My Account</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 10l5 5 5-5z"/>
                    </svg>
                </button>

                <!-- User Profile Section -->
                <div class="userprofileleft">
                    <div class="profile">
                        <div class="edit-profile">
                            <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z'/%3E%3C/svg%3E" alt="Edit Profile">
                            </a>
                        </div>
                        <div class="snipcss0-0-0-1">
                            <p><?php echo esc_html($first_letter); ?></p>
                        </div>
                        <div class="profileinfo">
                            <h2><?php echo esc_html($user_name); ?></h2>
                            <div class="info">
                                <p><?php echo esc_html($user_email); ?></p>
                                <?php if ($user_gender): ?>
                                    <span class="seprator"></span>
                                    <p><?php echo esc_html($user_gender); ?></p>
                                <?php endif; ?>
                                <?php if ($user_phone): ?>
                                    <span class="seprator"></span>
                                    <p><?php echo esc_html($user_phone); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <div class="myaccountnavigationtab">
                    <div class="container">
                        <ul class="myacctab">
                            <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                                <li class="myaccounttabs <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo ($endpoint === 'customer-logout') ? 'data-logout="true"' : ''; ?>>
                                        <div class="tabmain">
                                            <div class="svgtext">
                                                <?php
                                                // Add icons based on endpoint
                                                $icon_svg = '';
                                                switch($endpoint) {
                                                    case 'orders':
                                                        $icon_svg = '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23666\'%3E%3Cpath d=\'M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z\'/%3E%3C/svg%3E" width="25" height="25">';
                                                        break;
                                                    case 'edit-address':
                                                        $icon_svg = '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23666\'%3E%3Cpath d=\'M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22S19 14.25 19 9C19 5.13 15.87 2 12 2ZM12 11.5C10.62 11.5 9.5 10.38 9.5 9S10.62 6.5 12 6.5S14.5 7.62 14.5 9S13.38 11.5 12 11.5Z\'/%3E%3C/svg%3E" width="25" height="25">';
                                                        break;
                                                    case 'edit-account':
                                                        $icon_svg = '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23666\'%3E%3Cpath d=\'M12 12C14.21 12 16 10.21 16 8S14.21 4 12 4S8 5.79 8 8S9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z\'/%3E%3C/svg%3E" width="25" height="25">';
                                                        break;
                                                    case 'customer-logout':
                                                        $icon_svg = '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23666\'%3E%3Cpath d=\'M17 7L15.59 8.41L18.17 11H8V13H18.17L15.59 15.59L17 17L22 12L17 7ZM4 5H12V3H4C2.9 3 2 3.9 2 5V19C2 20.1 2.9 21 4 21H12V19H4V5Z\'/%3E%3C/svg%3E" width="25" height="25">';
                                                        break;
                                                    default:
                                                        $icon_svg = '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23666\'%3E%3Cpath d=\'M12 2C6.48 2 2 6.48 2 12S6.48 22 12 22S22 17.52 22 12S17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z\'/%3E%3C/svg%3E" width="25" height="25">';
                                                }
                                                echo $icon_svg;
                                                ?>
                                                <div class="tab-text-myaccount">
                                                    <strong><?php echo esc_html( $label ); ?></strong>
                                                </div>
                                            </div>
                                            <div class="icondown">
                                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M8.59 16.59L13.17 12L8.59 7.41L10 6L16 12L10 18L8.59 16.59Z'/%3E%3C/svg%3E" width="20" height="20">
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="rightside-my-account">
                <?php if ($is_order_details && $order): ?>
                    <!-- Order Details Page -->
                    <div class="orderinfomain">
                        <div class="usermyaccaddress">
                            <div class="top-heading-main">
                                <a href="<?php echo esc_url( wc_get_endpoint_url( 'orders', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>">
                                    <img width="10" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M20 11H7.83L13.42 5.41L12 4L4 12L12 20L13.41 18.59L7.83 13H20V11Z'/%3E%3C/svg%3E">
                                </a>
                                <p class="heading-top">Order Details</p>
                            </div>
                        </div>
                        
                        <div class="productBox">
                            <div class="orderinfo-container">
                                <?php 
                                $items = $order->get_items();
                                $first_item = true;
                                foreach ($items as $item_id => $item):
                                    $product = $item->get_product();
                                    if (!$product) continue;
                                    
                                    $product_name = $item->get_name();
                                    $quantity = $item->get_quantity();
                                    $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'medium');
                                    $item_total = $order->get_formatted_line_subtotal($item);
                                    
                                    // Get product attributes
                                    $variation_data = array();
                                    if ($product->is_type('variation')) {
                                        $attributes = $product->get_variation_attributes();
                                        foreach ($attributes as $attr_name => $attr_value) {
                                            $attr_name = str_replace('attribute_', '', $attr_name);
                                            $variation_data[] = ucfirst($attr_name) . ': ' . $attr_value;
                                        }
                                    }
                                    
                                    // Get category
                                    $categories = wp_get_post_terms($product->get_id(), 'product_cat');
                                    $category_name = !empty($categories) ? $categories[0]->name : 'Product';
                                ?>
                                <div class="orderinfo" style="<?php echo $first_item ? 'padding-bottom:0' : ''; ?>">
                                    <div class="pimg">
                                        <div class="imagemain">
                                            <?php if ($product_image): ?>
                                                <img src="<?php echo esc_url($product_image[0]); ?>" alt="<?php echo esc_attr($product_name); ?>">
                                            <?php else: ?>
                                                <img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php echo esc_attr($product_name); ?>">
                                            <?php endif; ?>
                                            <p class="orderstatusimg" style="background:#009B19"><?php echo $order->get_payment_method_title(); ?></p>
                                        </div>
                                    </div>
                                    <div class="pdetails">
                                        <p class="pname"><?php echo esc_html($product_name); ?></p>
                                        <span class="productcategoryname"><?php echo esc_html($category_name); ?></span>
                                        <?php if (!empty($variation_data)): ?>
                                            <p class="order-product-details">
                                                <?php foreach ($variation_data as $variation): ?>
                                                    <span><?php echo esc_html($variation); ?></span>
                                                <?php endforeach; ?>
                                            </p>
                                        <?php endif; ?>
                                        <p class="qty">Qty: <?php echo $quantity; ?></p>
                                        <p class="pprice"><?php echo $item_total; ?></p>
                                        <?php if ($order->get_meta('_tracking_number')): ?>
                                            <div class="trackid">
                                                <span class="trackingidheading">Tracking id:</span>
                                                <span class="ittracking"><?php echo esc_html($order->get_meta('_tracking_number')); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php 
                                if ($first_item):
                                    $first_item = false;
                                ?>
                                <div class="style-LJt4L" id="style-LJt4L">
                                    <div style="margin-top:0" class="ordernumber">
                                        <span>Order ID. #<?php echo $order->get_id(); ?></span>
                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M16 1H4C2.9 1 2 1.9 2 3V17H4V3H16V1ZM19 5H8C6.9 5 6 5.9 6 7V21C6 22.1 6.9 23 8 23H19C20.1 23 21 22.1 21 21V7C21 5.9 20.1 5 19 5ZM19 21H8V7H19V21Z'/%3E%3C/svg%3E">
                                    </div>
                                    <div class="orderlocationstatus style-qxoP2" id="style-qxoP2">
                                        <p class="statusmain style-NZOEt" id="style-NZOEt"><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Order Progress Tracking -->
                            <div class="orderprocerss">
                                <div class="productlivelocation">
                                    <div class="icon-container">
                                        <div class="icon-item">
                                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z'/%3E%3C/svg%3E" alt="Bag Icon" class="icon">
                                        </div>
                                        <div class="line style-EHx2a" id="style-EHx2a"></div>
                                        <div class="icon-item">
                                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M18 8C17.24 8 16.56 8.16 15.95 8.46L13.41 5.92C13.78 5.55 14 5.03 14 4.5C14 3.12 12.88 2 11.5 2S9 3.12 9 4.5C9 5.03 9.22 5.55 9.59 5.92L7.05 8.46C6.44 8.16 5.76 8 5 8C3.62 8 2.5 9.12 2.5 10.5S3.62 13 5 13C5.76 13 6.44 12.84 7.05 12.54L9.59 15.08C9.22 15.45 9 15.97 9 16.5C9 17.88 10.12 19 11.5 19S14 17.88 14 16.5C14 15.97 13.78 15.45 13.41 15.08L15.95 12.54C16.56 12.84 17.24 13 18 13C19.38 13 20.5 11.88 20.5 10.5S19.38 8 18 8Z'/%3E%3C/svg%3E" alt="Status Icon" class="icon">
                                        </div>
                                        <div class="line style-9b4KT" id="style-9b4KT"></div>
                                        <div class="icon-item">
                                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M9 12L11 14L15 10M21 12C21 16.97 16.97 21 12 21C7.03 21 3 16.97 3 12C3 7.03 7.03 3 12 3C16.97 3 21 7.03 21 12Z'/%3E%3C/svg%3E" alt="Truck Icon" class="icon">
                                        </div>
                                    </div>
                                    <div class="itemstatusmain">
                                        <div class="p-status">
                                            <span class="pstatusname">PLACED</span>
                                            <span class="pstatusname style-27437" id="style-27437"><?php echo $order->get_date_created()->format('d-m-Y'); ?></span>
                                        </div>
                                        <div class="p-status">
                                            <span class="pstatusname">PROCESSING</span>
                                            <span class="pstatusname style-24P7T" id="style-24P7T"><?php echo $order->get_date_modified()->format('d-m-Y'); ?></span>
                                        </div>
                                        <div class="p-status">
                                            <span class="pstatusname"><?php echo strtoupper($order->get_status()); ?></span>
                                            <span class="pstatusname style-Mm4bx" id="style-Mm4bx"><?php echo $order->get_date_modified()->format('d-m-Y'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Shipping Address -->
                        <div class="shippingaddress">
                            <div class="shippingmain">
                                <p class="headingshipping">Shipping address</p>
                                <?php 
                                $shipping_address = $order->get_formatted_shipping_address();
                                if ($shipping_address):
                                    echo '<p class="shippinmgaddress">' . wp_kses_post($shipping_address) . '</p>';
                                else:
                                    $billing_address = $order->get_formatted_billing_address();
                                    echo '<p class="shippinmgaddress">' . wp_kses_post($billing_address) . '</p>';
                                endif;
                                ?>
                            </div>
                        </div>
                        
                        <!-- Updates sent to -->
                        <div class="updatesend-to style-y6adL" id="style-y6adL">
                            <p class="upheading">Updates sent to</p>
                            <?php if ($order->get_billing_phone()): ?>
                                <div style="margin-bottom:8px">
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M6.62 10.79C8.06 13.62 10.38 15.94 13.21 17.38L15.41 15.18C15.69 14.9 16.08 14.82 16.43 14.93C17.55 15.3 18.75 15.5 20 15.5C20.55 15.5 21 15.95 21 16.5V20C21 20.55 20.55 21 20 21C10.61 21 3 13.39 3 4C3 3.45 3.45 3 4 3H7.5C8.05 3 8.5 3.45 8.5 4C8.5 5.25 8.7 6.45 9.07 7.57C9.18 7.92 9.1 8.31 8.82 8.59L6.62 10.79Z'/%3E%3C/svg%3E">
                                    <span><?php echo esc_html($order->get_billing_phone()); ?></span>
                                </div>
                            <?php endif; ?>
                            <div>
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z'/%3E%3C/svg%3E">
                                <span><?php echo esc_html($order->get_billing_email()); ?></span>
                            </div>
                        </div>
                        
                        <!-- Bill Details -->
                        <div class="price-section-account">
                            <div class="total-items">
                                <p class="items-heading">Bill Details</p>
                            </div>
                            <div class="price-section">
                                <div class="mrp-text">
                                    <span class="checkoutvalue">Subtotal</span>
                                    <span class="checkout-shipping-text"><?php echo $order->get_subtotal_to_display(); ?></span>
                                </div>
                                <?php if ($order->get_shipping_total() > 0): ?>
                                    <div class="mrp-text">
                                        <span class="checkoutvalue">Shipping</span>
                                        <span class="checkout-shipping-text"><?php echo wc_price($order->get_shipping_total()); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($order->get_total_tax() > 0): ?>
                                    <div class="mrp-text">
                                        <span class="checkoutvalue">Tax</span>
                                        <span class="checkout-shipping-text"><?php echo wc_price($order->get_total_tax()); ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="total-amount">
                                    <div class="inner">
                                        <span>Total Amount</span>
                                        <span><?php echo $order->get_formatted_order_total(); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Help Button -->
                        <div class="button-bottom-fixed">
                            <a href="#" class="btn-yellow">
                                Help 
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23000'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12S6.48 22 12 22S22 17.52 22 12S17.52 2 12 2ZM13 19H11V17H13V19ZM15.07 11.25L14.17 12.17C13.45 12.9 13 13.5 13 15H11V14.5C11 13.4 11.45 12.4 12.17 11.67L13.41 10.41C13.78 10.05 14 9.55 14 9C14 7.9 13.1 7 12 7S10 7.9 10 9H8C8 6.79 9.79 5 12 5S16 6.79 16 9C16 9.88 15.64 10.68 15.07 11.25Z'/%3E%3C/svg%3E">
                            </a>
                        </div>
                    </div>
                    
                <?php elseif ($is_orders_page && !$is_order_details): ?>
                    <!-- Orders List Page -->
                    <div class="my-account">
                        <div class="usermyaccaddress">
                            <div class="top-heading-main">
                                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M20 11H7.83L13.42 5.41L12 4L4 12L12 20L13.41 18.59L7.83 13H20V11Z'/%3E%3C/svg%3E">
                                </a>
                                <p class="heading-top">My Orders</p>
                                <button class="filter-button">
                                    <img class="filteraccount" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M10 18H14V16H10V18ZM3 6V8H21V6H3ZM6 13H18V11H6V13Z'/%3E%3C/svg%3E" width="25" height="25">
                                </button>
                            </div>
                        </div>
                        
                        <div class="my-order-order-mobile">
                            <div class="orderallmaindiv">
                                <?php
                                // Get customer orders
                                $customer_orders = wc_get_orders(array(
                                    'customer' => get_current_user_id(),
                                    'limit' => -1,
                                    'orderby' => 'date',
                                    'order' => 'DESC',
                                ));

                                if ($customer_orders) :
                                    foreach ($customer_orders as $order) :
                                        $order_id = $order->get_id();
                                        $order_date = $order->get_date_created();
                                        $order_status = $order->get_status();
                                        $order_total = $order->get_total();
                                        $items = $order->get_items();
                                ?>
                                <div class="order-user-list">
                                    <div class="sub-order-user">
                                        <div class="ordermainbox">
                                            <div class="orderboxtop">
                                                <div>
                                                    <span class="orderidmain">Order no. #<?php echo $order_id; ?></span>
                                                    <p class="order-date">Ordered on <?php echo $order_date->format('d M Y'); ?></p>
                                                </div>
                                            </div>
                                            
                                            <?php foreach ($items as $item_id => $item) :
                                                $product = $item->get_product();
                                                if (!$product) continue;
                                                
                                                $product_name = $item->get_name();
                                                $quantity = $item->get_quantity();
                                                $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'medium');
                                                $view_order_url = $order->get_view_order_url();
                                                
                                                // Get product attributes
                                                $variation_data = '';
                                                if ($product->is_type('variation')) {
                                                    $attributes = $product->get_variation_attributes();
                                                    $variation_details = array();
                                                    foreach ($attributes as $attr_name => $attr_value) {
                                                        $attr_name = str_replace('attribute_', '', $attr_name);
                                                        $variation_details[] = ucfirst($attr_name) . ': ' . $attr_value;
                                                    }
                                                    $variation_data = implode(' | ', $variation_details);
                                                }
                                            ?>
                                            <div class="order-product-section">
                                                <div class="order-content">
                                                    <a href="<?php echo esc_url($view_order_url); ?>" class="pimg">
                                                        <?php if ($product_image): ?>
                                                            <img src="<?php echo esc_url($product_image[0]); ?>" alt="<?php echo esc_attr($product_name); ?>">
                                                        <?php else: ?>
                                                            <img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php echo esc_attr($product_name); ?>">
                                                        <?php endif; ?>
                                                    </a>
                                                    <div class="pdetails">
                                                        <a href="<?php echo esc_url($view_order_url); ?>" class="pname style-Kqo1G" id="style-Kqo1G">
                                                            <span class="style-tazxN"><?php echo esc_html(wp_trim_words($product_name, 4, '...')); ?></span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" height="18" width="18">
                                                                <path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path>
                                                            </svg>
                                                        </a>
                                                        <div class="csq">
                                                            <?php if ($variation_data): ?>
                                                                <div class="order-product-details">
                                                                    <?php 
                                                                    $details = explode(' | ', $variation_data);
                                                                    foreach ($details as $detail): ?>
                                                                        <p><?php echo esc_html($detail); ?></p>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <p class="pqty">Qty: <?php echo $quantity; ?></p>
                                                        </div>
                                                        <div class="pprice-status">
                                                            <section class="write-review-main">
                                                                <?php if ($order_status === 'completed'): ?>
                                                                    <span class="write-review-popup">
                                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                            <svg width="18px" height="18px" viewBox="0 -1 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                                <g transform="translate(-2 -3)">
                                                                                    <path fill="#Fff" d="M12,4,9.22,9.27,3,10.11l4.5,4.1L6.44,20,12,17.27,17.56,20,16.5,14.21l4.5-4.1-6.22-.84Z"></path>
                                                                                    <path d="M12,4,9.22,9.27,3,10.11l4.5,4.1L6.44,20,12,17.27,17.56,20,16.5,14.21l4.5-4.1-6.22-.84Z" fill="none" stroke="rgba(0, 0, 0, 0.3)" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"></path>
                                                                                </g>
                                                                            </svg>
                                                                        <?php endfor; ?>
                                                                    </span>
                                                                <?php endif; ?>
                                                            </section>
                                                            <div class="p-status status-<?php echo esc_attr($order_status); ?>">
                                                                <span class="mainstatus"><?php echo esc_html(wc_get_order_status_name($order_status)); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    endforeach;
                                else: ?>
                                    <div class="order-user-list">
                                        <div class="sub-order-user">
                                            <div class="ordermainbox">
                                                <p style="text-align: center; padding: 40px 20px; color: #666;">No orders found.</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Help Button -->
                            <div class="help-button-container">
                                <a href="#" class="btn-yellow">
                                    Help 
                                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23000'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12S6.48 22 12 22S22 17.52 22 12S17.52 2 12 2ZM13 19H11V17H13V19ZM15.07 11.25L14.17 12.17C13.45 12.9 13 13.5 13 15H11V14.5C11 13.4 11.45 12.4 12.17 11.67L13.41 10.41C13.78 10.05 14 9.55 14 9C14 7.9 13.1 7 12 7S10 7.9 10 9H8C8 6.79 9.79 5 12 5S16 6.79 16 9C16 9.88 15.64 10.68 15.07 11.25Z'/%3E%3C/svg%3E">
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter Popup -->
                    <div id="orderFilterPopup" class="filterpopup">
                        <div class="fixed"></div>
                        <div class="popupmain">
                            <div class="poptopheading"><span>Filter Orders</span></div>
                            <div class="productstatuspop">
                                <div class="fileterorder">
                                    <span>Status</span>
                                    <div class="allfilterpopup">
                                        <label><input type="radio" id="oneastatus" name="status" value="0" checked><span class="statusone">All</span></label>
                                        <label><input type="radio" id="twoastatus" name="status" value="1"><span class="statustwo">On the way</span></label>
                                        <label><input type="radio" id="threestatus" name="status" value="4"><span class="statusthree">Returned</span></label>
                                        <label><input type="radio" id="fourstatus" name="status" value="3"><span class="statusfour">Delivered</span></label>
                                        <label><input type="radio" id="fivestatus" name="status" value="2"><span class="statusfive">Cancelled</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="productstatuspop">
                                <div class="fileterorder">
                                    <span>Time</span>
                                    <div class="allfilterpopup">
                                        <label><input type="radio" id="onetime" name="time" value="1" checked><span class="timeone">All</span></label>
                                        <label><input type="radio" id="twotime" name="time" value="3"><span class="timetwo">Last 7 days</span></label>
                                        <label><input type="radio" id="threetime" name="time" value="3"><span class="timethree">This week</span></label>
                                        <label><input type="radio" id="fourtime" name="time" value="4"><span class="timefour">2024</span></label>
                                        <label><input type="radio" id="fivetime" name="time" value="5"><span class="timefive">Last month</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="appleyandcancle">
                                <span id="cancelFilter" class="canclepop">Cancel</span>
                                <span id="applyFilter" class="applepop">Apply</span>
                            </div>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Default Account Dashboard -->
                    <div class="myaccountmainmobile">
                        <div class="myaccountmobile">
                            <div class="profile">
                                <div class="edit-profile">
                                    <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>">
                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z'/%3E%3C/svg%3E" alt="Edit Profile">
                                    </a>
                                </div>
                                <div>
                                    <p class="user-avatar"><?php echo esc_html($first_letter); ?></p>
                                </div>
                                <div class="profileinfo">
                                    <h2><?php echo esc_html($user_name); ?></h2>
                                    <div class="info">
                                        <p><?php echo esc_html($user_email); ?></p>
                                        <?php if ($user_gender): ?>
                                            <span class="seprator"></span>
                                            <p><?php echo esc_html($user_gender); ?></p>
                                        <?php endif; ?>
                                        <?php if ($user_phone): ?>
                                            <span class="seprator"></span>
                                            <p><?php echo esc_html($user_phone); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="myaccountnavigationtab">
                            <div class="container">
                                <ul class="myacctab">
                                    <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                                        <li class="myaccounttabs <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                                            <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo ($endpoint === 'customer-logout') ? 'data-logout="true"' : ''; ?>>
                                                <div class="tabmain">
                                                    <div class="svgtext">
                                                        <?php
                                                        // Add icons based on endpoint
                                                        $icon_svg = '';
                                                        switch($endpoint) {
                                                            case 'orders':
                                                                $icon_svg = '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23666\'%3E%3Cpath d=\'M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z\'/%3E%3C/svg%3E" width="25" height="25">';
                                                                break;
                                                            case 'edit-address':
                                                                $icon_svg = '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23666\'%3E%3Cpath d=\'M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22S19 14.25 19 9C19 5.13 15.87 2 12 2ZM12 11.5C10.62 11.5 9.5 10.38 9.5 9S10.62 6.5 12 6.5S14.5 7.62 14.5 9S13.38 11.5 12 11.5Z\'/%3E%3C/svg%3E" width="25" height="25">';
                                                                break;
                                                            case 'edit-account':
                                                                $icon_svg = '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23666\'%3E%3Cpath d=\'M12 12C14.21 12 16 10.21 16 8S14.21 4 12 4S8 5.79 8 8S9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z\'/%3E%3C/svg%3E" width="25" height="25">';
                                                                break;
                                                            case 'customer-logout':
                                                                $icon_svg = '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23666\'%3E%3Cpath d=\'M17 7L15.59 8.41L18.17 11H8V13H18.17L15.59 15.59L17 17L22 12L17 7ZM4 5H12V3H4C2.9 3 2 3.9 2 5V19C2 20.1 2.9 21 4 21H12V19H4V5Z\'/%3E%3C/svg%3E" width="25" height="25">';
                                                                break;
                                                            default:
                                                                $icon_svg = '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%23666\'%3E%3Cpath d=\'M12 2C6.48 2 2 6.48 2 12S6.48 22 12 22S22 17.52 22 12S17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z\'/%3E%3C/svg%3E" width="25" height="25">';
                                                        }
                                                        echo $icon_svg;
                                                        ?>
                                                        <div class="tab-text-myaccount">
                                                            <strong><?php echo esc_html( $label ); ?></strong>
                                                        </div>
                                                    </div>
                                                    <div class="icondown">
                                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666'%3E%3Cpath d='M8.59 16.59L13.17 12L8.59 7.41L10 6L16 12L10 18L8.59 16.59Z'/%3E%3C/svg%3E" width="20" height="20">
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Logout Confirmation Popup -->
<div id="logoutPopup" class="logout-popup" style="display: none;">
    <div class="popup-overlay">
        <div class="popup-content">
            <h3>Confirm Logout</h3>
            <p>Are you sure you want to logout?</p>
            <div class="popup-buttons">
                <button id="cancelLogout" class="btn-cancel">Cancel</button>
                <button id="confirmLogout" class="btn-confirm">Logout</button>
            </div>
        </div>
    </div>
</div>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
