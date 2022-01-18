<?php
declare(strict_types=1);

namespace Mailer\Serializer\Messenger;

use Mailer\Messenger\Message\UserRegisteredMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;

class EventSerializer extends Serializer
{

    public function decode(array $encodedEnvelope): Envelope
    {
        $translatedType = $this->translateType($encodedEnvelope['headers']['type']);

        $encodedEnvelope ['headers']['type'] = $translatedType;

        return parent::decode($encodedEnvelope);
    }

    private function translateType(string $type) : string
    {
        $map = [
            #origen => equivalencia en el propio dominio
            #Conversión de mensagje de este tipo => al tipo de mensaje local
            'App\Messenger\Message\UserRegisteredMessage' => UserRegisteredMessage::class
        ];

        if(array_key_exists($type, $map)){
           return $map[$type];
        }
        return $type;
    }

}