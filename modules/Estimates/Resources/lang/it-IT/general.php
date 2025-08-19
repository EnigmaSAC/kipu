<?php

return [

    'estimates'        => 'Preventivo|Preventivi',
    'estimate_summary' => 'Riepilogo Preventivo',
    'description'      => 'Trasforma un preventivo approvato in una fattura con un solo click di un bottone.',
    'estimate_number'  => 'Numero di stima',
    'estimate_date'    => 'Data di stima',
    'total_price'      => 'Prezzo Totale',
    'expiry_date'      => 'Data di scadenza',
    'bill_to'          => 'Fattura a',

    'empty' => [
        'estimates' => 'Trasforma un preventivo approvato in una fattura con un solo click di un bottone.',
    ],

    'quantity'  => 'Quantità',
    'price'     => 'Prezzo',
    'sub_total' => 'Subtotale',
    'discount'  => 'Sconto',
    'tax_total' => 'Totale tasse',
    'total'     => 'Totale',

    'item_name' => 'Nome dell\'articolo|Nomi degli articoli',

    'show_discount' => ': discount% Sconto',
    'add_discount'  => 'Aggiungi Sconto',
    'discount_desc' => 'di subtotale',

    'convert_to_invoice'       => 'Trasforma in Fattura',
    'converted_to_invoice'     => 'Convertito in fattura :document_number',
    'convert_to_sales_order'   => 'Converti in ordine di vendita',
    'converted_to_sales_order' => ':document_number convertito in ordine di vendita',
    'created_from_estimate'    => 'Creato da :type :document_number',
    'histories'                => 'Storico',
    'mark_sent'                => 'Segna come inviata',
    'mark_approved_or_refused' => 'Approva o Rifiuta Preventivo',
    'mark_approved'            => 'Segna come Approvato',
    'mark_refused'             => 'Segna come Rifiutato',
    'download_pdf'             => 'Scarica PDF',
    'send_mail'                => 'Invia email',
    'create_estimate'          => 'Crea Preventivo',
    'send_estimate'            => 'Invia Preventivo',
    'approve'                  => 'Approva',
    'refuse'                   => 'Rifiuta',
    'share'                    => 'Condividi',
    'all_estimates'            => 'Accedi per visualizzare tutte le stime',

    'messages' => [
        'marked_sent'      => 'Preventivo contrassegnato come inviato!',
        'marked_approved'  => 'Preventivo contrassegnato come approvato!',
        'marked_refused'   => 'Preventivo contrassegnato come rifiutato!',
        'email_required'   => 'Nessun indirizzo email per questo cliente!',
        'expired_estimate' => 'La stima scaduta non può essere modificata!',

        'status' => [
            'created'      => 'Creato il :date',
            'viewed'       => 'Visto',
            'sent'         => [
                'draft' => 'Non inviato',
                'sent'  => 'Inviato il :date',
            ],
            'invoiced'     => 'Fatturato',
            'not_invoiced' => 'Non Fatturato',
            'approved'     => 'Approvato',
            'refused'      => 'Rifiutato',
            'await_action' => 'Attesa azione del contatto',
        ],
    ],
];
