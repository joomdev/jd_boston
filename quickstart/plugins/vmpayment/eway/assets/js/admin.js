/**
 *
 * Eway payment plugin
 *
 * @author Valérie Isaksen
 * @version $Id: admin.js 10139 2019-09-12 18:50:21Z Milbo $
 * @package VirtueMart
 * @subpackage payment
 * Copyright (C) 2018 Virtuemart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

jQuery().ready(function ($) {

    /************/
    /* Handlers */
    /************/
    showOnSandbox = function () {
        var sandbox = $('#params_sandbox').val();

        $('.showOnSandbox ').parents('.control-group').hide();

        if (sandbox == 1) {
            $('.showOnSandbox').parents('.control-group').show();
        }
    }

    showOnPre_Auth = function () {
        var Pre_Auth = $('#params_Pre_Auth').val();

        $('.showOnPre_Auth ').parents('.control-group').hide();

        if (Pre_Auth == 'Authorisation') {
            $('.showOnPre_Auth').parents('.control-group').show();
        }
    }

    showOnCapture = function () {
        var Pre_Auth = $('#params_Pre_Auth').val();

        $('.showOnCapture ').parents('.control-group').hide();

        if (Pre_Auth == 'Capture') {
            $('.showOnCapture').parents('.control-group').show();
        }
    }

    showOnCaptureEnabledPre_Auth = function () {
        var Pre_Auth = $('#params_Pre_Auth').val();
        var status_capture_enabled = $('#params_status_capture_enabled').val();

        $('.showOnCaptureEnabledPre_Auth ').parents('.control-group').hide();

        if (Pre_Auth == 'Authorisation' && status_capture_enabled == 1) {
            $('.showOnCaptureEnabledPre_Auth').parents('.control-group').show();
        }
    }

    showOnCanceledEnablePre_Auth = function () {
        var Pre_Auth = $('#params_Pre_Auth').val();
        var status_canceled_enabled = $('#params_status_canceled_enabled').val();

        $('.showOnCanceledEnablePre_Auth ').parents('.control-group').hide();

        if (Pre_Auth == 'Authorisation' && status_canceled_enabled == 1) {
            $('.showOnCanceledEnablePre_Auth').parents('.control-group').show();
        }
    }

    showOnRefundEnabled = function () {
        var status_refund_enabled = $('#params_status_refund_enabled').val();

        $('.showOnRefundEnabled ').parents('.control-group').hide();

        if (status_refund_enabled == 1) {
            $('.showOnRefundEnabled').parents('.control-group').show();
        }
    }

    showOnPayPal = function () {
        var payment_type = $('#params_payment_type').val();

        $('.showOnPayPal ').parents('.control-group').hide();

        if (payment_type == 'PayPal') {
            $('.showOnPayPal').parents('.control-group').show();
        }
    }

    showOnCreditCard = function () {
        var payment_type = $('#params_payment_type').val();

        $('.showOnCreditCard ').parents('.control-group').hide();

        if (payment_type == 'Credit Card') {
            $('.showOnCreditCard').parents('.control-group').show();
        }
    }

    showOnSaveCard = function () {
        var save_card_enabled = $('#params_save_card_enabled').val();

        $('.showOnSaveCard ').parents('.control-group').hide();

        if (save_card_enabled==1) {
            $('.showOnSaveCard').parents('.control-group').show();
        }
    }


    showOnMasterpass = function () {
        var payment_type = $('#params_payment_type').val();

        $('.showOnMasterpass ').parents('.control-group').hide();

        if (payment_type == 'MasterPass') {
            $('.showOnMasterpass').parents('.control-group').show();
        }
    }

    showOnVisaCheckout = function () {
        var payment_type = $('#params_payment_type').val();

        $('.showOnVisaCheckout ').parents('.control-group').hide();

        if (payment_type == 'VisaCheckout') {
            $('.showOnVisaCheckout').parents('.control-group').show();
        }
    }

    /**********/
    /* Events */
    /**********/
    $('#params_sandbox').change(function () {
        showOnSandbox();
    });

    $('#params_Pre_Auth').change(function () {
        showOnPre_Auth();
        showOnCapture();
        showOnCanceledEnablePre_Auth();
        showOnCaptureEnabledPre_Auth();
    });

    $('#params_status_capture_enabled').change(function () {
        showOnCaptureEnabledPre_Auth();
    });
    $('#params_save_card_enabled').change(function () {
        showOnSaveCard();
    });
    $('#params_status_canceled_enabled').change(function () {
        showOnCanceledEnablePre_Auth();
    });

    $('#params_status_refund_enabled').change(function () {
        showOnRefundEnabled();
    });
    $('#params_payment_type').change(function () {
        showOnPayPal();
        showOnCreditCard();
        showOnMasterpass();
        showOnVisaCheckout();
    });
    /*****************/
    /* Initial calls */
    /*****************/
    showOnSandbox();
    showOnPre_Auth();
    showOnCapture();
    showOnSaveCard();
    showOnCaptureEnabledPre_Auth();
    showOnCanceledEnablePre_Auth();
    showOnRefundEnabled();
    showOnPayPal();
    showOnCreditCard();
    showOnMasterpass();
    showOnVisaCheckout();
});
