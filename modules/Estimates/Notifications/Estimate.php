<?php

namespace Modules\Estimates\Notifications;

use App\Abstracts\Notification;
use App\Models\Document\Document;
use App\Models\Setting\EmailTemplate;
use App\Traits\Documents;
use Illuminate\Mail\Attachment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Modules\Estimates\Models\Estimate as Model;

class Estimate extends Notification
{
    use Documents;

    /**
     * Should attach pdf or not.
     *
     * @var bool
     */
    private $attach_pdf;

    /**
     * The estimate model.
     *
     * @var Document
     */
    public $estimate;

    /**
     * The email template.
     *
     * @var string
     */
    public $template;

    public function __construct(Document $estimate, string $template_alias = null, bool $attach_pdf = false, array $custom_mail = [])
    {
        parent::__construct();

        $this->estimate   = Model::find($estimate->id);
        $this->template   = EmailTemplate::alias($template_alias)->first();
        $this->attach_pdf = $attach_pdf;
        $this->custom_mail = $custom_mail;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        if (!empty($this->custom_mail['to'])) {
            $notifiable->email = $this->custom_mail['to'];
        }

        $message = $this->initMailMessage();

        // Attach the PDF file
        if ($this->attach_pdf) {
            $this->estimate->template_path = 'estimates::estimates.print_' . setting('estimates.estimate.template');

            $func = is_local_storage() ? 'fromPath' : 'fromStorage';
            $path = $this->storeDocumentPdfAndGetPath($this->estimate);

            $file = Attachment::$func($path)->withMime('application/pdf');

            $message->attach($file);
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        $this->initArrayMessage();

        return [
            'template_alias'  => $this->template->alias,
            'title'           => trans('estimates::notifications.menu.' . $this->template->alias . '.title'),
            'description'     => trans(
                'estimates::notifications.menu.' . $this->template->alias . '.description',
                $this->getTagsBinding()
            ),
            'estimate_id'     => $this->estimate->id,
            'estimate_number' => $this->estimate->document_number,
            'customer_name'   => $this->estimate->contact_name,
            'amount'          => $this->estimate->amount,
            'expiry_date'     => company_date($this->estimate->extra_param->expire_at),
            'status'          => $this->estimate->status,
        ];
    }

    public function getTags(): array
    {
        return [
            '{estimate_number}',
            '{estimate_total}',
            '{estimate_expiry_date}',
            '{estimate_status}',
            '{estimate_guest_link}',
            '{estimate_admin_link}',
            '{estimate_portal_link}',
            '{customer_name}',
            '{company_name}',
        ];
    }

    public function getTagsReplacement(): array
    {
        return [
            $this->estimate->document_number,
            money($this->estimate->amount, $this->estimate->currency_code, true),
            company_date($this->estimate->extra_param->expire_at),
            trans('estimates::general.statuses.' . $this->estimate->status),
            URL::signedRoute(
                'signed.estimates.estimates.show',
                [$this->estimate->id, 'company_id' => $this->estimate->company_id]
            ),
            route('estimates.estimates.show', $this->estimate->id),
            route('portal.estimates.estimates.show', $this->estimate->id),
            $this->estimate->contact_name,
            $this->estimate->company->name,
        ];
    }
}
