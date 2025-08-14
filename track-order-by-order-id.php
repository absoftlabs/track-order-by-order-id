<?php
/**
 * Plugin Name: Order Tracking by Order Number Only
 * Description: Allows users to track their WooCommerce orders using only the order number, without email or login. [track_order_by_number]. 📞 <a style="text-decoration:none; color:blue; font-weight:bold;" href="https://wa.me/8801798930232" target="_blank">WhatsApp me for custom plugin/solutions</a>
 * Version: 1.1.0
 * Author: absoftlab
 * Author Email: absoftlab@gmail.com
 * Author URI: https://absoftlab.com
 * License: GPL2
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

add_shortcode('track_order_by_number', 'otbn_track_order_shortcode');

function otbn_track_order_shortcode() {
    ob_start();

    if (!class_exists('WooCommerce')) {
        echo '<p><strong>WooCommerce is not active.</strong></p>';
        return ob_get_clean();
    }

    ?>
    <form method="post" class="otbn-order-form" style="margin-bottom:20px;">
        <label style="color:black;" for="otbn_order_id">নিচের বক্সে অর্ডার নাম্বার দিন:</label><br>
        <input type="number" name="otbn_order_id" required style="padding: 8px; margin: 10px 0; width: 100%; border-radius: 8px; border: 1px solid #ccc; box-sizing: border-box;" placeholder="অর্ডার নাম্বার" />
        <button type="submit" style="padding: 13px 16px; font-size:18px; width:100%; border-radius:8px; background-color: #046BD2; color: white; border: none; cursor: pointer;">অর্ডার ট্রাক করুন</button>
    </form>
    <?php

    if (isset($_POST['otbn_order_id'])) {
        $order_id = absint($_POST['otbn_order_id']);
        $order = wc_get_order($order_id);

        if ($order) {
            $status = $order->get_status();

            // Custom messages for each status
            $status_messages = array(
                'pending'    => '📦 আপনার অর্ডারটি রিসিভ করা হয়েছে এবং পেমেন্টের অপেক্ষায় আছে।',
                'processing' => '⚙️ আপনার অর্ডারটি প্রক্রিয়াধীন রয়েছে এবং খুব শীঘ্রই ডেলিভারি হবে।',
                'on-hold'    => '⏸️ আপনার অর্ডারটি সাময়িকভাবে স্থগিত রয়েছে।',
                'completed'  => '✅ আপনার অর্ডারটি সফলভাবে সম্পন্ন হয়েছে। ধন্যবাদ!',
                'cancelled'  => '❌ আপনার অর্ডারটি বাতিল করা হয়েছে।',
                'refunded'   => '💰 আপনার অর্ডারের টাকা ফেরত দেওয়া হয়েছে।',
                'failed'     => '⚠️ আপনার অর্ডারের পেমেন্ট ব্যর্থ হয়েছে। অনুগ্রহ করে পুনরায় চেষ্টা করুন।',
            );

            if (isset($status_messages[$status])) {
                echo "<p style=\"color:black;\">{$status_messages[$status]}</p>";
            } else {
                echo "<p style=\"color:black;\">📢 আপনার অর্ডারের স্ট্যাটাস: <strong>" . wc_get_order_status_name($status) . "</strong></p>";
            }

        } else {
            echo "<p style=\"color:black;\">❌ <strong>#$order_id</strong> এই নাম্বারের অর্ডার পাওয়া যায়নি।</p>";
        }
    }

    return ob_get_clean();
}
