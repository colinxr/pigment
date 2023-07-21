<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use App\Services\IncomingMessageService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InboundMessagesTest extends TestCase
{
    use RefreshDatabase;

    public $user;
    public $inboundPayload;
    public $client;
    public $submisison;
    public $incomingMessageService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->incomingMessageService = new IncomingMessageService();
        $this->user = User::factory()->create(['username' => 'colinxr']);

        $this->client = Client::factory()->create([
            'email' => "colinxr+client@gmail.com",
            'user_id' => $this->user->id,
        ]);

        $this->submisison = Submission::factory()->create([
            'client_id' => $this->client->id,
            'user_id' => $this->user->id,
        ]);

        $this->inboundPayload = [
            "envelope" => json_encode([
                "from" => "colinxr+client@gmail.com",
                "to" => ["colinxr@mail.usepigment.com"]
            ]),
            "headers" => "Content-Type: multipart/alternative; boundary=\"----=_Part_1888458_1536976940.1689975059919\"
DKIM-Signature: v=1; a=rsa-sha256; c=simple/simple; d=workingfrombed.org;\ts=default; t=1689975060;\tbh=+vN6LSpcCg71LaGHK38IEKTPTmxh9OKG0s1oDMwPzdg=;\th=Date:From:To:In-Reply-To:References:Subject:From;\tb=cxlNKFOUoiBaDcClj+Udy0FCnPzzxpDty+jnKdx3cl1wjAvtolbll5UrTeyn5d/GD\t nZWF2o3jN128OMvlloEkjfBuSPifISRaqBIxrIp9y5pNhCiK9T6C4UmzDOzlTe/9uW\t alPua0B/CXNPIC0yERZ9NitcpSXEaKNxgoW8SCOWYfbjPIjtwueDL/LR1X5g/lcbVF\t zCP1KMoRHXR9faqHFcaJZx6MVjWvjkCipZ9Ek8v+hIrFpQG1oH0wX7niILmb286vra\t 68rYHVk6xrObZQtwJ31iEHXeyHr8Q01z7EtyjgLchUGDmcT4JtZMVUDyJnYhpRrds4\t ebe7Y1Y5f+gcA==
Date: Fri, 21 Jul 2023 14:30:59 -0700 (PDT)
From: Colin Rabyniuk <colin@workingfrombed.org>
Importance: Normal
In-Reply-To: <5b1101844169d23f1b9d7f2ff483b4ad@usepigment.com>
MIME-Version: 1.0
Message-ID: <395418387.1888459.1689975059921@privateemail.com>
Received: from mailout-pe-b.jellyfish.systems (mxd [198.54.127.81]) by mx.sendgrid.net with ESMTP id hvJwCrBEQSaFsmddRZBidA for <colinxr@mail.usepigment.com>; Fri, 21 Jul 2023 21:31:01.464 +0000 (UTC)
Received: from output-router-84cf8f76b8-cg68g (unknown [10.35.5.64])\tby mailout-pe-b.jellyfish.systems (Postfix) with ESMTPA id 4R72mN2fZJzGpBF\tfor <colinxr@mail.usepigment.com>; Fri, 21 Jul 2023 21:31:00 +0000 (UTC)
Received: from MTA-09.privateemail.com (unknown [10.50.14.19])\t(using TLSv1.3 with cipher TLS_AES_256_GCM_SHA384 (256/256 bits)\t key-exchange X25519 server-signature RSA-PSS (2048 bits))\t(No client certificate requested)\tby NEW-01.privateemail.com (Postfix) with ESMTPS id 4763E18000D0\tfor <colinxr@mail.usepigment.com>; Fri, 21 Jul 2023 17:31:00 -0400 (EDT)
Received: from mta-09.privateemail.com (localhost [127.0.0.1])\tby mta-09.privateemail.com (Postfix) with ESMTP id 1E5BF180006B\tfor <colinxr@mail.usepigment.com>; Fri, 21 Jul 2023 17:31:00 -0400 (EDT)
Received: from APP-07 (unknown [10.50.14.157])\tby mta-09.privateemail.com (Postfix) with ESMTPA id ED4E9180005D\tfor <colinxr@mail.usepigment.com>; Fri, 21 Jul 2023 17:30:59 -0400 (EDT)
References: <5b1101844169d23f1b9d7f2ff483b4ad@usepigment.com>
Subject: Re: You've received a new message
To: colinxr@mail.usepigment.com
X-Mailer: Open-Xchange Mailer v7.10.6-Rev47
X-Originating-Client: open-xchange-appsuite
X-Priority: 3
X-Virus-Scanned: ClamAV using ClamSMTP",
            "subject" => "Test Email",
            "text" => "testing testing testing > On 07/21/2023 2:05 PM PDT colinxr@usepigment.com wrote: > > > sending an email in response > Cheers, Colin ----- workingfrombed.org https://workingfrombed.org",
            "html" => "<div dir=\"ltr\">testing testing testing</div><br><div class=\"gmail_quote\"><div dir=\"ltr\" class=\"gmail_attr\">On Wed, Jul 19, 2023 at 2:04 PM Colin Rabyniuk &lt;<a href=\"mailto:colinxr@gmail.com\">colinxr@gmail.com</a>&gt; wrote:<br></div><blockquote class=\"gmail_quote\" style=\"margin:0px 0px 0px 0.8ex;border-left:1px solid rgb(204,204,204);padding-left:1ex\"><div dir=\"ltr\"><br></div></blockquote></div>",
            "from" => "Sender Name <example@example.com>",
            "attachments" => [
                [
                    "filename" => "example.pdf",
                    "content" => "SGVsbG8gV29ybGQh",
                    "type" => "application/pdf"
                ]
            ],
            "spam_report" => [
                "score" => 0.1,
                "result" => "ham"
            ]
        ];
    }

    public function test_can_parse_username_from_paypload(): void
    {
        // $resp = $this->post('/api/messages/parse', $this->inboundPayload);

        $envelope = json_decode($this->inboundPayload['envelope']);
        $username = $this->incomingMessageService->getUsername($envelope->to[0]);

        $this->assertEquals($username, 'colinxr');
    }

    public function test_can_find_user_from_payload_from_address(): void
    {
        $envelope = json_decode($this->inboundPayload['envelope']);
        $user = $this->incomingMessageService->findUser($envelope->to[0]);

        $this->assertNotNull($user);
        $this->assertEquals($user->email, $this->user->email);
    }

    public function test_can_find_the_client(): void
    {
        $user = $this->user;
        $envelope = json_decode($this->inboundPayload['envelope']);
        $client = $this->incomingMessageService->findClient($user, $envelope->from, $this->inboundPayload['from']);

        $this->assertNotNull($client);
        $this->assertEquals($client->email, $this->client->email);
    }

    public function test_can_create_a_new_client(): void
    {
        // $this->inboundPayload['envelope']['from'] = 'newclient@gmail.com';
        $user = $this->user;
        $client = $this->incomingMessageService->findClient($user, 'newclient@gmail.com', 'New Client <newclient@gmail.com>');

        $this->assertNotNull($client);
        $this->assertEquals($client->email, 'newclient@gmail.com');
    }

    public function test_can_get_only_new_text_from_email_with_reply(): void
    {
        $text = $this->incomingMessageService->extractReply($this->inboundPayload['text']);

        $this->assertNotNull($text);
        $this->assertEquals($text, 'testing testing testing >');
    }

    public function test_can_get_only_new_text_from_email(): void
    {
        $text = $this->incomingMessageService->extractReply('testing testing testing');

        $this->assertNotNull($text);
        $this->assertEquals($text, 'testing testing testing');
    }

    public function test_can_get_message_ID_from_headers(): void
    {
        $message_id = $text = $this->incomingMessageService->getMessageId($this->inboundPayload['headers']);

        $this->assertEquals($message_id, '395418387.1888459.1689975059921@privateemail.com');
    }
}
