<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Detect card network from card number.
 *
 * @param string $card_number
 * @return array
 */
function c2cp_detect_card( $card_number ) {

    // فقط اعداد
    $card_number = preg_replace( '/\D/', '', $card_number );

    error_log( '=============================' );
    error_log( 'Card: ' . $card_number );

    if ( empty( $card_number ) ) {
        error_log( 'Empty card number.' );
        return false;
    }

    error_log( 'Visa Regex: ' . ( preg_match( '/^4/', $card_number ) ? 'YES' : 'NO' ) );
    error_log( 'Mastercard Regex: ' . ( preg_match( '/^(5[1-5])/', $card_number ) || preg_match( '/^(222[1-9]|22[3-9]|2[3-6]|27[01]|2720)/', $card_number ) ? 'YES' : 'NO' ) );
    error_log( 'Amex Regex: ' . ( preg_match( '/^(34|37)/', $card_number ) ? 'YES' : 'NO' ) );
    error_log( 'Discover Regex: ' . ( preg_match( '/^(6011|65|64[4-9])/', $card_number ) ? 'YES' : 'NO' ) );
    error_log( 'JCB Regex: ' . ( preg_match( '/^35(2[89]|[3-8])/', $card_number ) ? 'YES' : 'NO' ) );
    error_log( 'UnionPay Regex: ' . ( preg_match( '/^62/', $card_number ) ? 'YES' : 'NO' ) );
    error_log( 'Maestro Regex: ' . ( preg_match( '/^(50|5[6-9]|6[0-9])/', $card_number ) ? 'YES' : 'NO' ) );
    error_log( 'Diners Regex: ' . ( preg_match( '/^(30[0-5]|36|38|39)/', $card_number ) ? 'YES' : 'NO' ) );
    error_log( 'Mir Regex: ' . ( preg_match( '/^220[0-4]/', $card_number ) ? 'YES' : 'NO' ) );
    error_log( 'RuPay Regex: ' . ( preg_match( '/^(60|65|81|82|508)/', $card_number ) ? 'YES' : 'NO' ) );

    $result = array(
        'type' => 'unknown',
        'name' => __( 'Unknown Card', 'c2cp-td-woocommerce' ),
        'logo' => '',
    );

    // Visa
    if ( preg_match( '/^4/', $card_number ) ) {

        error_log( 'Matched: VISA' );

        $result = array(
            'type' => 'visa',
            'name' => 'Visa',
            'logo' => 'visa.png',
        );
    }

    // Mastercard
    elseif (
        preg_match( '/^(5[1-5])/', $card_number ) ||
        preg_match( '/^(222[1-9]|22[3-9]|2[3-6]|27[01]|2720)/', $card_number )
    ) {

        error_log( 'Matched: MASTERCARD' );

        $result = array(
            'type' => 'mastercard',
            'name' => 'Mastercard',
            'logo' => 'mastercard.png',
        );
    }

    // American Express
    elseif ( preg_match( '/^(34|37)/', $card_number ) ) {

        error_log( 'Matched: AMEX' );

        $result = array(
            'type' => 'amex',
            'name' => 'American Express',
            'logo' => 'amex.svg',
        );
    }

    // Discover
    elseif ( preg_match( '/^(6011|65|64[4-9])/', $card_number ) ) {

        error_log( 'Matched: DISCOVER' );

        $result = array(
            'type' => 'discover',
            'name' => 'Discover',
            'logo' => 'discover.svg',
        );
    }

    // JCB
    elseif ( preg_match( '/^35(2[89]|[3-8])/', $card_number ) ) {

        error_log( 'Matched: JCB' );

        $result = array(
            'type' => 'jcb',
            'name' => 'JCB',
            'logo' => 'jcb.svg',
        );
    }

    // UnionPay
    elseif ( preg_match( '/^62/', $card_number ) ) {

        error_log( 'Matched: UNIONPAY' );

        $result = array(
            'type' => 'unionpay',
            'name' => 'UnionPay',
            'logo' => 'unionpay.svg',
        );
    }

    // Maestro
    elseif ( preg_match( '/^(50|5[6-9]|6[0-9])/', $card_number ) ) {

        error_log( 'Matched: MAESTRO' );

        $result = array(
            'type' => 'maestro',
            'name' => 'Maestro',
            'logo' => 'maestro.svg',
        );
    }

    // Diners Club
    elseif ( preg_match( '/^(30[0-5]|36|38|39)/', $card_number ) ) {

        error_log( 'Matched: DINERS' );

        $result = array(
            'type' => 'diners',
            'name' => 'Diners Club',
            'logo' => 'diners.svg',
        );
    }

    // Mir
    elseif ( preg_match( '/^220[0-4]/', $card_number ) ) {

        error_log( 'Matched: MIR' );

        $result = array(
            'type' => 'mir',
            'name' => 'Mir',
            'logo' => 'mir.svg',
        );
    }

    // RuPay
    elseif ( preg_match( '/^(60|65|81|82|508)/', $card_number ) ) {

        error_log( 'Matched: RUPAY' );

        $result = array(
            'type' => 'rupay',
            'name' => 'RuPay',
            'logo' => 'rupay.svg',
        );
    }
    else {

        error_log( 'Matched: UNKNOWN' );
    }

    // بررسی وجود فایل لوگو
    if ( ! empty( $result['logo'] ) ) {

        $logo_path = C2CP_RTD_IMAGES_PATH . $result['logo'];

        error_log( 'Logo Path: ' . $logo_path );

        if ( ! file_exists( $logo_path ) ) {

            error_log( 'Logo file not found. Using placeholder.' );

            $result['logo'] = 'placeholder.png';
        }

    } else {

        error_log( 'No logo defined. Using placeholder.' );

        $result['logo'] = 'placeholder.png';
    }

    error_log( 'Final Type: ' . $result['type'] );
    error_log( 'Final Logo: ' . $result['logo'] );

    return $result;
}