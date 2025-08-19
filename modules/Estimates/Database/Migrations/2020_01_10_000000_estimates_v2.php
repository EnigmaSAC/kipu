<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EstimatesV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $rename_estimates = [
            'estimate_status_code' => 'status',
            'customer_id'          => 'contact_id',
            'customer_name'        => 'contact_name',
            'customer_email'       => 'contact_email',
            'customer_tax_number'  => 'contact_tax_number',
            'customer_phone'       => 'contact_phone',
            'customer_address'     => 'contact_address',
        ];

        foreach ($rename_estimates as $from => $to) {
            Schema::table(
                'estimates',
                function (Blueprint $table) use ($from, $to) {
                    $table->renameColumn($from, $to);
                }
            );
        }

        Schema::table(
            'estimate_histories',
            function (Blueprint $table) {
                $table->renameColumn('status_code', 'status');
            }
        );

        Schema::drop('estimate_statuses');

        Schema::table(
            'estimates',
            function ($table) {
                $table->text('footer')->nullable();
            }
        );

        Schema::create(
            'estimates_extra_parameters',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('company_id');
                $table->integer('document_id');
                $table->dateTime('expire_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index('document_id');
            }
        );

        Schema::create(
            'estimates_documents',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('company_id');
                $table->integer('document_id');
                $table->integer('item_id');
                $table->string('item_type');
                $table->timestamps();
                $table->softDeletes();

                $table->index(['company_id', 'item_id', 'item_type']);
                $table->index(['company_id', 'document_id', 'item_type']);
            }
        );

        Schema::dropIfExists('estimate_invoice');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $rename_estimates = [
            'status'             => 'estimate_status_code',
            'contact_id'         => 'customer_id',
            'contact_name'       => 'customer_name',
            'contact_email'      => 'customer_email',
            'contact_tax_number' => 'customer_tax_number',
            'contact_phone'      => 'customer_phone',
            'contact_address'    => 'customer_address',
        ];

        foreach ($rename_estimates as $from => $to) {
            Schema::table(
                'estimates',
                function (Blueprint $table) use ($from, $to) {
                    $table->renameColumn($from, $to);
                }
            );
        }

        Schema::table(
            'estimate_histories',
            function (Blueprint $table) {
                $table->renameColumn('status', 'status_code');
            }
        );

        Schema::create(
            'estimate_statuses',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('company_id');
                $table->string('name');
                $table->string('code');
                $table->timestamps();
                $table->softDeletes();

                $table->index('company_id');
            }
        );

        Schema::table(
            'estimates',
            function ($table) {
                $table->dropColumn('footer');
            }
        );

        Schema::dropIfExists('estimates_extra_parameters');
        Schema::dropIfExists('estimates_documents');

        Schema::create(
            'estimate_invoice',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('estimate_id');
                $table->integer('invoice_id');
                $table->timestamps();
                $table->softDeletes();

                $table->index('estimate_id');
                $table->unique(['estimate_id', 'invoice_id', 'deleted_at']);
            }
        );
    }
}
