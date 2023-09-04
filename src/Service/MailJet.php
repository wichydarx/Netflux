<?php

namespace App\Service;


use Mailjet\Client;
use App\Entity\User;
use Mailjet\Resources;
use Symfony\Contracts\Translation\TranslatorInterface;


class MailJet
{
    private string $apiKey;
    private string $apiSecret;

    private $mailJet;

    public function __construct(string $mailJetApiKey, string $mailJetApiKeyPrivate, private TranslatorInterface $translator)
    {
        $this->apiKey = $mailJetApiKey;
        $this->apiSecret = $mailJetApiKeyPrivate;

        $this->mailJet = new Client($this->apiKey, $this->apiSecret, true, ['version' => 'v3.1']);
    }
    public function mailBody(User $user, $subject, $message): array
    {
        return  [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "smarina.veng@gmail.com",
                        'Name' => $this->translator->trans("Netflux Reinitalisation de mot de passe")
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail(),
                            'Name' => $user->getFirstname() . ' ' . $user->getLastname()
                        ]
                    ],
                    'TemplateID' => 5064455,
                    'TemplateLanguage' => true,
                    'Subject' => $this->translator->trans('Reinitialisation de mot de passe'),
                    'Variables' => [
                        'subject' => $subject,
                        'link' => $message,
                    ],
                ]
            ]
        ];
    }

    public function send(User $user, $subject, $message): void
    {
        $body = $this->mailBody($user, $subject, $message);
        $response = $this->mailJet->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    public function sendEmailToUser(User $user, $subject, $message): void
    {
        $this->send($user, $subject, $message);
    }
}
