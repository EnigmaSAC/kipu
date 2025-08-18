<?php

use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer;
use App\Models\Common\Company;
use App\Models\Common\Contact;
use App\Models\Common\Item;
use App\Models\Document\Document;

return [

    Company::class => [
        [
            'location' => [
                'code' => 'common-companies',
                'name' => 'general.companies',
            ],
            'sort_orders' => [
                'name'      => 'general.name',
                'email'     => 'general.email',
                'currency'  => ['general.currencies', 1],
                'country'   => ['general.countries', 1],
            ],
            'views' => [
                'crud' => [
                    'common.companies.create',
                    'common.companies.edit',
                    'settings.company.edit',
                ],
                'show' => [

                ],
            ],
            // 'tests' => [
            //     'factory'   => ['enabled'],
            //     'routes'    => [
            //         'get'       => 'companies.create',
            //         'post'      => 'companies.store',
            //         'patch'     => 'companies.update',
            //         'delete'    => 'companies.destroy',
            //     ],
            // ],
        ],
    ],

    Item::class => [
        [
            'location' => [
                'code' => 'common-items',
                'name' => 'general.items',
            ],
            'sort_orders' => [
                'type'              => ['general.types', 1],
                'name'              => 'general.name',
                'category_id'       => ['general.categories', 1],
                'description'       => 'general.description',
                'sale_price'        => 'items.sale_price',
                'purchase_price'    => 'items.purchase_price',
                'tax_ids'           => ['general.taxes', 1],
            ],
            'views' => [
                'crud' => [
                    'common.items.create',
                    'common.items.edit',
                    'modals.items.create',
                ],
                'show' => [

                ],
            ],
            'tests' => [
                'factory'   => ['enabled'],
                'routes'    => [
                    'get'       => 'items.create',
                    'post'      => 'items.store',
                    'patch'     => 'items.update',
                    'delete'    => 'items.destroy',
                ],
            ],
        ],
        'export' => 'App\Exports\Common\Sheets\Items',
    ],

    Document::class => [
        [
            'type' => Document::INVOICE_TYPE,
            'location' => [
                'code' => 'sales-invoices',
                'name' => 'general.invoices',
            ],
            'location_reference' => [
                'signed-invoices',
                'portal-invoices',
                //'modals-invoices', # disabled because not send email and share 
            ],
            'relations' => [
                'sales-customers' => 'contact',
            ],
            'sort_orders' => [
                'title'                 => 'settings.invoice.title',
                'subheading'            => 'settings.invoice.subheading',
                'company_logo'          => 'settings.company.logo',
                // ------------------------
                'issued_at'             => 'invoices.invoice_date',
                'document_number'       => 'invoices.invoice_number',
                'due_at'                => 'invoices.due_date',
                'order_number'          => 'invoices.order_number',
                'item_custom_fields'    => ['general.items', 2],
                'notes'                 => ['general.notes', 2],
                // ------------------------
                'footer'                => 'general.footer',
                'category_id'           => ['general.categories', 1],
                // 'attachment'            => 'general.attachment',
            ],
            'views' => [
                'crud' => [
                    'sales.invoices.create',
                    'sales.invoices.edit',
                ],
                'show' => [
                    'sales.invoices.print_classic',
                    'sales.invoices.print_default',
                    'sales.invoices.print_modern',
                    'components.documents.template.line-item',
                    'components.documents.show.template',
                ],
            ],
            'show_types' => [
                'print' => [
                    'sales.invoices.print_classic',
                    'sales.invoices.print_default',
                    'sales.invoices.print_modern',
                    'components.documents.show.template'
                ],
            ],
            'tests' => [
                'factory'   => ['invoice', 'items'],
                'routes'    => [
                    'get'       => 'invoices.create',
                    'post'      => 'invoices.store',
                    'patch'     => 'invoices.update',
                    'delete'    => 'invoices.destroy',
                ],
            ],
            'export' => [
                'App\Exports\Sales\Sheets\Invoices',
                'App\Exports\Sales\Sheets\InvoiceItems'
            ],
        ],
        [
            'type' => Document::BILL_TYPE,
            'location' => [
                'code' => 'purchases-bills',
                'name' => 'general.bills',
            ],
            'relations' => [
                'purchases-vendors' => 'contact',
            ],
            'sort_orders' => [
                'issued_at'             => 'bills.bill_date',
                'document_number'       => 'bills.bill_number',
                'due_at'                => 'bills.due_date',
                'order_number'          => 'bills.order_number',
                'item_custom_fields'    => ['general.items', 2],
                'notes'                 => ['general.notes', 2],
                // ------------------------
                'category_id'           => ['general.categories', 1],
                // 'attachment'            => 'general.attachment',
            ],
            'views' => [
                'crud' => [
                    'purchases.bills.create',
                    'purchases.bills.edit',
                ],
                'show' => [
                    'purchases.bills.print',
                ],
            ],
            'show_types' => [
                'print' => [
                    'purchases.bills.print',
                    'components.documents.show.template'
                ],
            ],
            'tests' => [
                'factory'   => ['bill', 'items'],
                'routes'    => [
                    'get'       => 'bills.create',
                    'post'      => 'bills.store',
                    'patch'     => 'bills.update',
                    'delete'    => 'bills.destroy',
                ],
            ],
            'export' => [
                'App\Exports\Purchases\Sheets\Bills',
                'App\Exports\Purchases\Sheets\BillItems'
            ],
        ],
    ],

    Contact::class => [
        [
            'type' => Contact::CUSTOMER_TYPE,
            'location' => [
                'code' => 'sales-customers',
                'name' => 'general.customers',
            ],
            'location_reference' => [
                'modals-customers'
            ],
            'sort_orders' => [
                'name'              => 'general.name',
                'email'             => 'general.email',
                'phone'             => 'general.phone',
                'website'           => 'general.website',
                'reference'         => 'general.reference',
                'create_user'       => 'customers.can_login',
                // ------------------------
                'tax_number'        => 'general.tax_number',
                'currency_code'     => ['general.currencies', 1],
                // ------------------------
                'address'           => 'general.address',
                'city'              => 'general.city',
                'zip_code'          => 'general.zip_code',
                'state'             => 'general.state',
                'country'           => 'general.country',
            ],
            'views' => [
                'crud' => [
                    'sales.customers.create',
                    'sales.customers.edit',
                    'modals.customers.create',
                    'modals.customers.edit',
                ],
                'show' => [
                    'sales.customers.show',
                ],
            ],
            'show_types' => [
                'informations' => [
                    'sales.customers.show',
                ],
                'transactions' => [
                    'components.documents.show.template'
                ]
            ],
            'tests' => [
                'factory'   => ['customer', 'enabled'],
                'routes'    => [
                    'get'       => 'customers.create',
                    'post'      => 'customers.store',
                    'patch'     => 'customers.update',
                    'delete'    => 'customers.destroy',
                ],
            ],
            'export' => 'App\Exports\Sales\Customers',
        ],
        [
            'type' => Contact::VENDOR_TYPE,
            'location' => [
                'code' => 'purchases-vendors',
                'name' => 'general.vendors',
            ],
            'location_reference' => [
                'modals-vendors'
            ],
            'sort_orders' => [
                'name'              => 'general.name',
                'logo'              => ['general.pictures', 1],
                'email'             => 'general.email',
                'phone'             => 'general.phone',
                'website'           => 'general.website',
                'reference'         => 'general.reference',
                // ------------------------
                'tax_number'        => 'general.tax_number',
                'currency_code'     => ['general.currencies', 1],
                // ------------------------
                'address'           => 'general.address',
                'city'              => 'general.city',
                'zip_code'          => 'general.zip_code',
                'state'             => 'general.state',
                'country'           => 'general.country',
            ],
            'views' => [
                'crud' => [
                    'purchases.vendors.create',
                    'purchases.vendors.edit',
                    'modals.vendors.create',
                    'modals.vendors.edit',
                ],
                'show' => [
                    'purchases.vendors.show',
                ],
            ],
            'show_types' => [
                'informations' => [
                    'purchases.vendors.show',
                ],
                'transactions' => [
                    'components.documents.show.template'
                ]
            ],
            'tests' => [
                'factory'   => ['vendor', 'enabled'],
                'routes'    => [
                    'get'       => 'vendors.create',
                    'post'      => 'vendors.store',
                    'patch'     => 'vendors.update',
                    'delete'    => 'vendors.destroy',
                ],
            ],
            'export' => 'App\Exports\Purchases\Vendors',
        ],
    ],

    Account::class => [
        [
            'location' => [
                'code' => 'banking-accounts',
                'name' => 'general.accounts',
            ],
            'sort_orders' => [
                'type'              => ['general.types', 1],
                'name'              => 'general.name',
                'number'            => 'accounts.number',
                'currency_code'     => ['general.currencies', 1],
                'opening_balance'   => 'accounts.opening_balance',
                'default_account'   => 'accounts.default_account',
                'bank_name'         => 'accounts.bank_name',
                'bank_phone'        => 'accounts.bank_phone',
                'bank_address'      => 'accounts.bank_address',
            ],
            'views' => [
                'crud' => [
                    'banking.accounts.create',
                    'banking.accounts.edit',
                    'modals.accounts.create',
                ],
                'show' => [
                    'banking.accounts.show',
                ],
            ],
            'show_types' => [
                'informations' => [
                    'banking.accounts.show',
                ],
            ],
            'tests' => [
                'factory'   => ['enabled'],
                'routes'    => [
                    'get'       => 'accounts.create',
                    'post'      => 'accounts.store',
                    'patch'     => 'accounts.update',
                    'delete'    => 'accounts.destroy',
                ],
            ],
        ],
    ],

    Transaction::class => [
        [
            'location' => [
                'code' => 'banking-transactions',
                'types' => [
                    Transaction::INCOME_TYPE => [
                        'code' => 'transactions-incomes',
                        'name' => 'general.incomes',
                    ],
                    Transaction::EXPENSE_TYPE => [
                        'code' => 'transcations-expenses',
                        'name' => 'general.expenses',
                    ],
                ],
            ],
            'location_reference' => [
                'portal-payments',
            ],
            'sort_orders' => [
                'paid_at'           => 'general.date',
                'payment_method'    => ['general.payment_methods', 1],
                'account_id'        => ['general.accounts', 1],
                'amount'            => 'general.amount',
                'description'       => 'general.description',
                // ------------------------
                'category_id'       => ['general.categories', 1],
                'contact_id'        => ['general.customers', 1],
                // ------------------------
                'number'            => ['general.numbers', 1],
                'reference'         => 'general.reference',
                // 'attachment'        => 'general.attachment',
            ],
            'views' => [
                'crud' => [
                    'banking.transactions.create',
                    'banking.transactions.edit',
                    'modals.documents.payment',
                ],
                'show' => [
                    'components.transactions.show.content',
                    'banking.transactions.print_default',
                    'portal.payments.show'
                ],
            ],
            'show_types' => [
                'transactions' => [
                    'components.transactions.show.content',
                    'banking.transactions.print_default',
                ],
            ],
            'tests' => [
                'factory'   => [],
                'routes'    => [
                    'get'       => 'transactions.create',
                    'post'      => 'transactions.store',
                    'patch'     => 'transactions.update',
                    'delete'    => 'transactions.destroy',
                ],
            ],
        ],
        'export' => 'App\Exports\Banking\Transactions',
    ],

    Transfer::class => [
        [
            'location' => [
                'code' => 'banking-transfers',
                'name' => 'general.transfers',
            ],
            'sort_orders' => [
                'from_account_id'   => 'transfers.from_account',
                'to_account_id'     => 'transfers.to_account',
                'transferred_at'    => 'general.date',
                'amount'            => 'general.amount',
                'description'       => 'general.description',
                // ------------------------
                'payment_method'    => ['general.payment_methods', 1],
                'reference'         => 'general.reference',
                // 'attachment'        => 'general.attachment',
            ],
            'views' => [
                'crud' => [
                    'banking.transfers.create',
                    'banking.transfers.edit',
                ],
                'show' => [
                    'components.transfers.show.content',
                    'banking.transfers.print_default',
                ],
            ],
            'show_types' => [
                'transactions' => [
                    'components.transfers.show.content',
                    'banking.transfers.print_default',
                ],
            ],
            'tests' => [
                'factory'   => [],
                'routes'    => [
                    'get'       => 'transfers.create',
                    'post'      => 'transfers.store',
                    'patch'     => 'transfers.update',
                    'delete'    => 'transfers.destroy',
                ],
            ],
        ],
        'export' => 'App\Exports\Banking\Transfers',
    ],

];
