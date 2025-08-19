<?php

return [
    'title'                    => 'Modelli Email',
    'estimate_new_customer'    => [
        'subject' => 'Preventivo {estimate_number} creato',
        'body'    => 'Gentile {customer_name},' .
                     '<br /><br />' .
                     'Abbiamo preparato il seguente preventivo per voi: ' .
                     '<strong>{estimate_number}</strong>.' .
                     '<br /><br />' .
                     'Potete visualizzare i dettagli del preventivo dal seguente link: ' .
                     '<a href="{estimate_guest_link}">{estimate_number}</a>.' .
                     '<br /><br />' .
                     'Non esitate a contattarci per qualsiasi domanda.' .
                     '<br /><br />' .
                     'Cordiali Saluti,' .
                     '<br />' .
                     '{company_name}',
    ],
    'estimate_remind_customer' => [
        'subject' => 'Promemoria preventivo {estimate_number}',
        'body'    => 'Caro {customer_name},' .
                     '<br /><br />' .
                     'Questo è un promemoria per il preventivo <strong>{estimate_number}</strong>.' .
                     '<br /><br />' .
                     'Il totale del preventivo è {estimate_total} e scadrà il <strong>{estimate_expiry_date}</strong>.'
                     .
                     '<br /><br />' .
                     'Potete visualizzare i dettagli del preventivo dal seguente link: ' .
                     '<a href="{estimate_guest_link}">{estimate_number}</a>.' .
                     '<br /><br />' .
                     'Cordiali Saluti,' .
                     '<br />' .
                     '{company_name}',
    ],
    'estimate_remind_admin'    => [
        'subject' => '{estimate_number} stima avviso di promemoria',
        'body'    => 'Ciao,' .
                     '<br /><br />' .
                     'Questo è un promemoria per il preventivo <strong>{estimate_number}</strong> di {customer_name}.'
                     .
                     '<br /><br />' .
                     'Il totale del preventivo è {estimate_total} e scadrà il <strong>{estimate_expiry_date}</strong>.'
                     .
                     '<br /><br />' .
                     'Potete visualizzare i dettagli del preventivo dal seguente link: ' .
                     '<a href="{estimate_admin_link}">{estimate_number}</a>.' .
                     '<br /><br />' .
                     'Cordiali Saluti,' .
                     '<br />' .
                     '{company_name}',
    ],
    'estimate_approved_admin'  => [
        'subject' => 'Preventivo {estimate_number} approvato',
        'body'    => 'Salve,' .
                     '<br /><br />' .
                     '{customer_name} ha approvato il preventivo <strong>{estimate_number}</strong>.' .
                     '<br /><br />' .
                     'Potete visualizzare i dettagli del preventivo dal seguente link: ' .
                     '<a href="{estimate_admin_link}">{estimate_number}</a>.' .
                     '<br /><br />' .
                     'Cordiali Saluti,' .
                     '<br />' .
                     '{company_name}',
    ],
    'estimate_refused_admin'   => [
        'subject' => 'Preventivo {estimate_number} rifiutato',
        'body'    => 'Salve,' .
                     '<br /><br />' .
                     '{customer_name} ha rifiutato il preventivo <strong>{estimate_number}</strong>.' .
                     '<br /><br />' .
                     'Potete visualizzare i dettagli del preventivo dal seguente link: ' .
                     '<a href="{estimate_admin_link}">{estimate_number}</a>.' .
                     '<br /><br />' .
                     'Cordiali Saluti,' .
                     '<br />' .
                     '{company_name}',
    ],

];
