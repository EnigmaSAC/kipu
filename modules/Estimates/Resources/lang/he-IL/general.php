<?php

return [

    'estimates'        => 'הערכה|הערכות',
    'estimate_summary' => 'סיכום הערכות ',
    'description'      => 'הפוך הצעת מחיר לחשבונית בלחיצת כפתור.',
    'estimate_number'  => 'מספר הערכה',
    'estimate_date'    => 'תאריך הערכה',
    'total_price'      => 'סה"כ מחיר',
    'expiry_date'      => 'תאריך תפוגה',
    'bill_to'          => 'חייב',

    'empty' => [
        'estimates' => 'הפוך הצעת מחיר לחשבונית בלחיצת כפתור.',
    ],

    'quantity'  => 'כמות',
    'price'     => 'מחיר',
    'sub_total' => 'סכום ביניים',
    'discount'  => 'הנחה',
    'tax_total' => 'סה"כ מס',
    'total'     => 'סה"כ',

    'item_name' => 'שם פריט | שמות הפריטים',

    'show_discount' => ':discount % הנחה',
    'add_discount'  => 'הוסף הנחה',
    'discount_desc' => 'של ביניים',

    'convert'                  => 'המר',
    'convert_to_invoice'       => 'המר לחשבונית',
    'converted_to_invoice'     => 'המר לחשבונות document_number:',
    'convert_to_sales_order'   => 'המר להזמנה',
    'converted_to_sales_order' => 'המר לחשבונית document_number:',
    'created_from_estimate'    => 'צור מ-type: מספר document_number:',
    'histories'                => 'היסטוריה',
    'mark_sent'                => 'סמן כנשלח',
    'mark_approved_or_refused' => 'אשר/דחה הערכה',
    'mark_approved'            => 'סמן כמאושר',
    'mark_refused'             => 'סמן כנדחה',
    'download_pdf'             => 'הורדה כקובץ PDF',
    'send_mail'                => 'שלח דואר אלקטרוני',
    'create_estimate'          => 'צור הצעת מחיר',
    'send_estimate'            => 'שלח הצעת מחיר',
    'approve'                  => 'אשר',
    'refuse'                   => 'דחה',
    'share'                    => 'שתף',
    'all_estimates'            => 'היכנס כדי לצפות בכל הצעות המחיר',

    'messages' => [
        'marked_sent'      => 'הצעת מחיר סומנה שנשלחה בהצלחה!',
        'marked_approved'  => 'הצעת מחיר אושרה!',
        'marked_refused'   => 'הצעת מחיר נדחתה!',
        'email_required'   => 'אין דואר אלקטרוני מעודכן ללקוח זה!',
        'expired_estimate' => 'הצעת מחיר פגה תוקף. לא ניתן לעדכן',

        'status' => [
            'created'      => 'נוצר ב :date',
            'viewed'       => 'נצפה',
            'sent'         => [
                'draft' => 'לא נשלח',
                'sent'  => 'נשלח ב :date',
            ],
            'invoiced'     => 'חויב',
            'not_invoiced' => 'לא חויב',
            'approved'     => 'אושר',
            'refused'      => 'נדחה',
            'await_action' => 'ממתין ללקוח',
        ],
    ],
];
