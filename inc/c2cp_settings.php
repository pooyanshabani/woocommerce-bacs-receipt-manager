<?php
defined('ABSPATH') || exit;

// Add new sections to the page
function action_woocommerce_sections_my_custom_tab() {
    global $current_section;

    $tab_id = 'checkout';

    // Must contain more than one section to display the links
    // Make first element's key empty ('')
    $sections = array(
        ''              => __( 'Payment method', 'c2cp-td-woocommerce' ),
        'c2cp-settings'  => __( 'Card by card settings', 'c2cp-td-woocommerce' ),
    );

    echo '<ul class="subsubsub">';

    $array_keys = array_keys( $sections );

    foreach ( $sections as $id => $label ) {
        echo '<li><a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . $tab_id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
    }

    echo '</ul><br class="clear" />';
}
add_action( 'woocommerce_sections_checkout', 'action_woocommerce_sections_my_custom_tab', 10 );


// Settings function
function get_c2cp_settings() {
    global $current_section;
	

    $c2cp_main_settings = array();

    if ( $current_section == 'c2cp-settings' ) {
		wp_enqueue_media();
        // My section 1
        $c2cp_main_settings = array(

            // Title
            array(
                'title'     =>  __( 'Main Settings', 'c2cp-td-woocommerce' ),
                'type'      => 'title',
                'id'        => 'c2cp_settings'
            ),
			// SelectPage
            array(
                'title'     => __( 'Box display page', 'c2cp-td-woocommerce' ),
                'desc'      => __( 'Which page do you want the box to be displayed in?', 'c2cp-td-woocommerce' ),
                'id'        => 'c2cp_settings_page',
                'class'     => 'wc-enhanced-select',
                'css'       => 'min-width:300px;',
                'default'   => 'checkout',
                'type'      => 'select',
                'options'   => array(
                    'checkout' => __( 'Checkout', 'woocommerce' ),
					'thankyou' => __( 'Thank you Page', 'c2cp-td-woocommerce' ),
                ),
                'desc_tip' => true,
            ),

			// SelectBanksLogosColumnDesktopThank
            array(
                'title'     => __( 'Banks Logos Column/s', 'c2cp-td-woocommerce' ),
                'desc'      => __( 'Number of Banks logos column/s Thankyou Page in desktop view', 'c2cp-td-woocommerce' ),
                'id'        => 'c2cp_settings_deskcol_thp',
                'class'     => 'wc-enhanced-select',
                'css'       => 'min-width:300px;',
                'default'   => 4,
                'type'      => 'select',
                'options'   => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                    6 => 6,
                    7 => 7,
                    8 => 8,
                    9 => 9,
                    10 => 10,
                ),
                'desc_tip' => true,
            ),

			// SelectBanksLogosColumnDesktop
            array(
                'title'     => __( 'Banks Logos Column/s', 'c2cp-td-woocommerce' ),
                'desc'      => __( 'Number of Banks logos column/s in desktop view', 'c2cp-td-woocommerce' ),
                'id'        => 'c2cp_settings_deskcol',
                'class'     => 'wc-enhanced-select',
                'css'       => 'min-width:70px;',
                'default'   => 1,
                'type'      => 'select',
                'options'   => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                    6 => 6,
                ),
                'desc_tip' => true,
            ),

			// SelectBanksLogosColumnMobile
            array(
                'title'     => __( 'Banks Logos Column/s', 'c2cp-td-woocommerce' ),
                'desc'      => __( 'Number of Banks logos column/s in mobile view', 'c2cp-td-woocommerce' ),
                'id'        => 'c2cp_settings_mobilecol',
                'class'     => 'wc-enhanced-select',
                'css'       => 'min-width:70px;',
                'default'   => 1,
                'type'      => 'select',
                'options'   => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                ),
                'desc_tip' => true,
            ),

			// OrderStatuses
            array(
                'title'     => __( 'Order Status', 'woocommerce' ),
                'desc'      => __( 'What is the status of orders made by direct bank transfer?', 'c2cp-td-woocommerce' ),
                'id'        => 'c2cp_settings_statuses',
                'class'     => 'wc-enhanced-select',
                'css'       => 'min-width:300px;',
                'default'   => 'wc-processing',
                'type'      => 'select',
                'options'   => array(
					'wc-pending'    => __( 'Pending', 'woocommerce' ),
					'wc-processing' => __( 'Processing', 'woocommerce' ),
					'wc-on-hold'    => __( 'On-hold', 'woocommerce' ),
					'wc-completed'  => __( 'Completed', 'woocommerce' ),
					'wc-cancelled'  => __( 'Canceled', 'woocommerce' ),
					'wc-refunded'   => __( 'Refunded', 'woocommerce' ),
					'wc-failed'     => __( 'Failed', 'woocommerce' ),
                ),
                'desc_tip' => true,
            ),

			// CardNumberCheckbox
			array(
				'title'     => __( 'Hide card number', 'c2cp-td-woocommerce' ),
				'desc'      => __( 'Considering that entering the first 6 digits of the card number is necessary to display the bank icon, if you dont want the customer to see the card number, you can hide it this way.', 'c2cp-td-woocommerce' ),
				'default'   => 'no', 
				'id'        => 'c2cp_settings_cardnumberch',
				'type'      => 'checkbox', // 
			),
			'desc_tip' => true,

			// DiscountCheckbox
			array(
				'title'     => __( 'Special discount for this payment method', 'c2cp-td-woocommerce' ),	
				'desc'      => __( 'You can offer the user a discount if they choose this payment method.', 'c2cp-td-woocommerce' ),
				'default'   => 'no', 
				'id'        => 'c2cp_settings_discountcheckbox',
				'type'      => 'checkbox', // 
			),

			

			// Discount type
            array(
                'title'     => __( 'Discount type', 'c2cp-td-woocommerce' ),
                'id'        => 'c2cp_settings_discount_type',
                'class'     => 'wc-enhanced-select',
                'css'       => 'min-width:300px;',
                'default'   => 'Percentage discount',
                'type'      => 'select',
                'options'   => array(
                    'percent'      => __( 'Percentage discount', 'c2cp-td-woocommerce' ),
					'fixed_cart'     => __( 'Fixed cart discount', 'c2cp-td-woocommerce' ),
                ),       
            ),

			// Discount amount
            array(
                'title'     => __( 'Discount amount', 'c2cp-td-woocommerce' ),
                'type'      => 'number',
				'desc'   => __( 'Enter the desired discount amount.', 'c2cp-td-woocommerce' ),
				'desc_tip'  => true,
				'default'   => 5,
                'id'        => 'c2cp_settings_discount_amount',
                'css'       => 'max-width:200px;'
            ),

			// PriceCheckbox
			array(
				'title'     => __( 'Disable other gateways', 'c2cp-td-woocommerce' ),	
				'desc'      => __( 'Disable other gateways if total price is upper than set limit', 'c2cp-td-woocommerce' ),
				'default'   => 'no', 
				'id'        => 'c2cp_settings_pricecheckbox',
				'type'      => 'checkbox', // 
			),

			// TotalPrice
            array(
                'title'     => __( 'Price', 'c2cp-td-woocommerce' ),
                'type'      => 'number',
				'desc'   => __( 'Total amount of the cart', 'c2cp-td-woocommerce' ),
				'desc_tip'  => true,
                'id'        => 'c2cp_settings_totalprice',
                'css'       => 'max-width:200px;'
            ),
			 // SelectTheme
            array(
                'title'     => __( 'Color Theme', 'c2cp-td-woocommerce' ),
                'desc'      => __( 'Box Theme Color', 'c2cp-td-woocommerce' ),
                'id'        => 'c2cp_settings_theme',
                'class'     => 'wc-enhanced-select',
                'css'       => 'min-width:300px;',
                'default'   => 'Red',
                'type'      => 'select',
                'options'   => array(
                    'red'      => __( 'Red', 'c2cp-td-woocommerce' ),
					'blue'     => __( 'Blue', 'c2cp-td-woocommerce' ),
					'green'    => __( 'Green', 'c2cp-td-woocommerce' ),
					'orange'   => __( 'Orange', 'c2cp-td-woocommerce' ),
					'purple'   => __( 'Purple', 'c2cp-td-woocommerce' ),
					'gray'     => __( 'Gray', 'c2cp-td-woocommerce' ),
                ),
                'desc_tip' => true,
            ),
			// ImageUrl
			array(
				'title'     => __( 'Card Image', 'c2cp-td-woocommerce' ),
				'desc'      => __( 'Upload your Card Image', 'c2cp-td-woocommerce' ),
				'id'        => 'c2cp_settings_url',
				'default'   => C2CP_RTD_IMAGES_URL . 'c2cp_card.png',
				'type'      => 'url',
				'desc_tip'  => true,
				'css'       => 'background: url("' . C2CP_RTD_IMAGES_URL . 'c2cp_upload.svg") no-repeat 7px',
				'class'     => 'c2cp-settings-url'
			),

            // BoxTitle
            array(
                'title'     => __( 'Box title', 'c2cp-td-woocommerce' ),
                'type'      => 'text',
				'default'   => __( 'Register payment details', 'c2cp-td-woocommerce' ),
                'id'        => 'c2cp_settings_btitle',
                'css'       => 'min-width:300px;'
            ),

			// ReceiptFieldTitle
            array(
                'title'     => __( 'Field title', 'c2cp-td-woocommerce' ),
                'type'      => 'text',
				'default'   => __( 'Receipt no/payment serial no', 'c2cp-td-woocommerce' ),
                'id'        => 'c2cp_settings_rftitle',
                'css'       => 'min-width:300px;'
            ),
			// ImageCheckbox
			array(
				'title'     => __( 'Image Upload', 'c2cp-td-woocommerce' ),
				'desc'      => __( 'Users can attach an image', 'c2cp-td-woocommerce' ),
				'default'   => 'yes', 
				'id'        => 'c2cp_settings_imgcheckbox',
				'type'      => 'checkbox', // 
			),

			// ImageFieldTitle
            array(
                'title'     => __( 'Image field title', 'c2cp-td-woocommerce' ),
                'type'      => 'text',
				'default'   => __( 'Receipt image', 'c2cp-td-woocommerce' ),
                'id'        => 'c2cp_settings_iftitle',
                'css'       => 'min-width:300px;'
            ),

			// ThankyouNotification
            array(
                'title'     => __( 'Notification', 'c2cp-td-woocommerce' ),
                'type'      => 'textarea',
				'default'   =>__('After checking the financial affairs, your order will be sent.','c2cp-td-woocommerce'),
                'id'        => 'c2cp_settings_tptext',
                'css'       => 'min-width:300px;',
				'desc'      => __( 'Thank you page notification text', 'c2cp-td-woocommerce' ),
				'desc_tip'  => true,
            ),
			// FontSize
            array(
                'title'     => __( 'Font Size', 'c2cp-td-woocommerce' ),
                'type'      => 'number',
				'desc'   => __( 'The font size of the notification of the thank you for purchase page (in px)', 'c2cp-td-woocommerce' ),
				'desc_tip'  => true,
				'default'   => 18,
                'id'        => 'c2cp_settings_fontsize',
                'css'       => 'max-width:200px;'
            ),
			       						
			// Section end
            array(
                'type'      => 'sectionend',
                'id'        => 'c2cp_settings_end'
            ),
			
        );
		
    
    } else {
        // Overview
        $c2cp_main_settings = array(

            // Title
            array(
                'title'     => __( 'Overview', 'woocommerce' ),
                'type'      => 'title',
                'id'        => 'c2cp_settingsoverview'
            ),

            // Section end
            array(
                'type'      => 'sectionend',
                'id'        => 'c2cp_settingsoverview'
            ),
        );
    }
    return $c2cp_main_settings;
	
}

// Add settings
function action_woocommerce_settings_my_custom_tab() {
    // Call settings function
    $settings = get_c2cp_settings();

    WC_Admin_Settings::output_fields( $settings );  
}
add_action( 'woocommerce_sections_checkout', 'action_woocommerce_settings_my_custom_tab', 10 );



// Process/save the settings
function action_woocommerce_settings_save_my_custom_tab() {
    global $current_section;

    $tab_id = 'checkout';

    // Call settings function
    $settings = get_c2cp_settings();

    WC_Admin_Settings::save_fields( $settings );

    if ( $current_section ) {
        do_action( 'woocommerce_update_options_' . $tab_id . '_' . $current_section );
    }
}
add_action( 'woocommerce_settings_save_checkout', 'action_woocommerce_settings_save_my_custom_tab', 10 );
