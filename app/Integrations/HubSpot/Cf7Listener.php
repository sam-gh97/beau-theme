<?php

namespace Boilerplate\Integrations\HubSpot;

use WPCF7_Submission;

class Cf7Listener
{
    private ?Contacts $contacts = null;

    public function __construct()
    {
        add_action('wpcf7_mail_sent', [$this, 'handle']);
    }

    public function handle($contactForm): void
    {
        $token = Token::get();
        if (!$token) {
            error_log('[HubSpot] Token not configured');
            return;
        }

        $submission = WPCF7_Submission::get_instance();
        if (!$submission) {
            error_log('[HubSpot] Submission error');
            return;
        }

        $client = new Client($token);
        $this->contacts = new Contacts($client);

        $data   = $submission->get_posted_data();
        $formId = (int) $contactForm->id();

        $payload = $this->mapForm($formId, $data);
        if (!$payload) {
            return;
        }

        $response = $this->contacts->upsert($payload);

        if (empty($response['success'])) {
            error_log('[HubSpot] Failed: ' . print_r($response, true));
        } else {
            error_log('[HubSpot] Synced successfully');
        }
    }

    private function normaliseSelect($value): string
    {
        if (is_array($value)) {
            return (string) reset($value);
        }

        return (string) $value;
    }


    private function mapSignupForm(array $data): array
    {
        $nameParts = array_values(array_filter(
            explode(' ', $data['your-name'] ?? '')
        ));
    
        $firstname = $nameParts[0] ?? '';
        $lastname  = count($nameParts) > 1
            ? implode(' ', array_slice($nameParts, 1))
            : '';
    
        $payload = [
            'email'            => $data['your-email'],
            'firstname'        => $firstname,
            'lastname'         => $lastname,
            'phone'            => $data['your-subject'] ?? '',
            'company'          => $data['your-business-name'] ?? '',
            'heard_about_us'   => $this->normaliseSelect($data['select-120'] ?? ''),
            'profession'       => $data['your-profession'] ?? '',
            'area_of_interest' => $this->normaliseSelect($data['select-190'] ?? ''),
            'social_handle'    => $data['your-handle'] ?? '',
            'signup_source'    => 'general_signup',
            'lifecyclestage'   => 'lead',
        ];
    
        if (!empty($data['marketing-check'])) {
            $payload['marketing_opt_in'] = 'true';
        }
    
        return $payload;
    }    

    private function mapForm(int $formId, array $data): ?array
    {
        if (empty($data['your-email'])) {
            return null;
        }
    
        return match ($formId) {
            294 => [
                'email'          => $data['your-email'],
                'signup_source'  => 'newsletter_block',
                'lifecyclestage' => 'subscriber',
            ],
    
            581 => [
                'email'          => $data['your-email'],
                'signup_source'  => 'newsletter_footer',
                'lifecyclestage' => 'subscriber',
            ],
    
            442 => $this->mapSignupForm($data),
    
            default => null,
        };
    }
}
