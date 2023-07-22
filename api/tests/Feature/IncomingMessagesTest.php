<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Submission;
use App\Mail\NewMessageAlert;
use Illuminate\Support\Facades\Mail;
use App\Services\IncomingMessageService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IncomingMessagesTest extends TestCase
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
            "subject" => "Test Email",
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
            "text" => "testing testing testing



            On 07/21/2023 1:43 PM PDT colinxr@usepigment.com wrote: 
            
            test tesing whtf test
            
            >
            
            >",
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


    public function test_can_parse_incoming_message(): void
    {
        $this->withoutExceptionHandling();
        Mail::fake();

        $incomingMessageService = new IncomingMessageService();
        $response = $this->post('/api/messages/parse', $this->inboundPayload);

        $response->assertStatus(204);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $this->client->id,
            'sender_type' => get_class($this->client),
            'body' => $incomingMessageService->extractReply($this->inboundPayload['text']),
            'message_id' => $incomingMessageService->getMessageId($this->inboundPayload['headers']),
        ]);

        Mail::assertQueued(NewMessageAlert::class);
    }
}
