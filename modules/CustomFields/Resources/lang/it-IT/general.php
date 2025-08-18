<?php

return [

    'name'              => 'Campi Personalizzati',
    'description'       => 'Aggiungi campi personalizzati illimitati in diverse pagine',

    'fields'            => 'Campo|Campi',
    'locations'         => 'Località|Località',
    'sort'              => 'Ordina',
    'order'             => 'Posizione',
    'depend'            => 'Dipendere',
    'show'              => 'Mostra',

    'form' => [
        'name'          => 'Nome',
        'code'          => 'Codice',
        'icon'          => 'Icona FontAwesome',
        'class'         => 'Classe CSS',
        'required'      => 'Richiesto',
        'rule'          => 'Convalida',
        'before'        => 'Prima',
        'after'         => 'Dopo',
        'value'         => 'Valore',
        'shows'         => [
            'always'    => 'Sempre',
            'never'     => 'Mai',
            'if_filled' => 'Se compilato'
        ],
        'items'         => 'Elementi',
    ],

    'type' => [
        'select'        => 'Seleziona',
        'radio'         => 'Radio',
        'checkbox'      => 'Casella di controllo',
        'text'          => 'Testo',
        'textarea'      => 'Area di testo',
        'date'          => 'Data',
        'time'          => 'Ora',
        'date&time'     => 'Data & Ora',
        'enabled'     => 'Attivato',
    ],

    'item' => [
        'action'   => 'Azione Oggetto',
        'name'     => 'Nome dell\'elemento',
        'quantity' => 'Quantità articolo',
        'price'    => 'Prezzo articolo',
        'taxes'    => 'Tasse articolo',
        'total'    => 'Totale articolo',
    ],

    'validation_rules' => [
        'required' => 'Richiesto',
        'string' => 'Stringa',
        'integer' => 'Intero',
        'date' => 'Data',
        'email' => 'Email',
        'url' => 'URL',
        'password' => 'Password',
    ],

];
