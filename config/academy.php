<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Emisha Academy Payment Credentials & Information
    |--------------------------------------------------------------------------
    */
    'payments' => [
        'company_name' => 'Emisha Tours & Travels',
        'academy_name' => 'Emisha Academy',

        // BRAC Bank Details
        'brac_bank' => [
            'merchant_name' => 'EMISHA TOURS & TRAVELS',
            'merchant_id' => '460000000031191',
            'terminal_id' => '86031191',
            'account_type' => 'Merchant Account',
            'bank_name' => 'BRAC Bank PLC',
            'branch' => 'Principal Branch / Corporate',
            'instructions_bn' => 'BRAC Bank অ্যাকাউন্ট, ইন্টারনেট ব্যাংকিং অথবা যেকোনো ব্যাংকের BEFTN/NPSB/Card ট্রান্সফারের মাধ্যমে মার্চেন্ট আইডিতে পেমেন্ট করুন।',
            'instructions_en' => 'Pay via BRAC Bank, Internet Banking, or BEFTN/NPSB/Cards using our Merchant & Terminal ID.',
        ],

        // bKash Merchant (Make Payment)
        'bkash_merchant' => [
            'number' => '01805464290',
            'type' => 'Make Payment',
            'type_bn' => 'মার্চেন্ট পেমেন্ট (Make Payment)',
            'qr_image' => '/images/payments/bkash-qr.jpg',
            'instructions_bn' => '১. বিকাশ অ্যাপে গিয়ে "Make Payment" নির্বাচন করুন বা QR কোড স্ক্যান করুন। ২. মার্চেন্ট নম্বর 01805464290 দিন। ৩. নির্দিষ্ট কোর্স/রিসোর্স ফি পরিশোধ করে TrxID নিচে লিখুন।',
            'instructions_en' => '1. Open bKash app and tap "Make Payment" or scan QR. 2. Enter Merchant Number 01805464290. 3. Pay the exact fee and enter TrxID below.',
        ],

        // bKash Personal (Send Money)
        'bkash_personal' => [
            'number' => '01712-857909',
            'type' => 'Send Money',
            'type_bn' => 'পার্সোনাল (Send Money)',
            'instructions_bn' => '১. বিকাশ অ্যাপে "Send Money" নির্বাচন করুন। ২. 01712-857909 নম্বরে ফি সেন্ড করুন। ৩. ট্রানজেকশন শেষে প্রাপ্ত TrxID নিচে প্রদান করুন।',
            'instructions_en' => '1. Open bKash app and tap "Send Money". 2. Send the fee to 01712-857909. 3. Enter the TrxID below.',
        ],

        // Office / Physical Payment
        'office' => [
            'title_bn' => 'সরাসরি অফিসে ক্যাশ / কার্ড পেমেন্ট',
            'title_en' => 'Direct Office Cash / Card Payment',
            'address_bn' => 'ইমিশা ট্যুরস অ্যান্ড ট্রাভেলস, মিরপুর-১০/কাজীপাড়া, ঢাকা।',
            'address_en' => 'Emisha Tours & Travels, Mirpur Campus, Dhaka, Bangladesh.',
            'phone' => '01805464290',
            'instructions_bn' => 'সরাসরি আমাদের মিরপুর ক্যাম্পাসে এসে ক্যাশ বা কার্ডে ফি জমা দিয়ে তাৎক্ষণিক ভর্তি ও রসিদ গ্রহণ করতে পারবেন।',
            'instructions_en' => 'Visit our campus directly to complete registration with cash or POS card payment.',
        ],
    ],
];
