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
                "to" => "colinxr@parse.usepigment.com"
            ]),
            "subject" => "Test Email",
            "text" => "testing testing testing



            On Wed, Jul 19, 2023 at 2:04 PM Colin Rabyniuk <colinxr+client@gmail.com> wrote:
            
            
            
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
            "headers" => [
                "header1" => "value1",
                "header2" => "value2"
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
        $username = $this->incomingMessageService->getUsername($envelope->to);

        $this->assertEquals($username, 'colinxr');
        // inbound message service 

        // find the user with the same username. 
        // identify  the from sender, find or create 
        // can get the message text and store it in a new messsage 
        // can deal with the attachments 

        // submission has a new 
    }

    public function test_can_find_user_from_payload_from_address(): void
    {
        $envelope = json_decode($this->inboundPayload['envelope']);
        $user = $this->incomingMessageService->findUser($envelope->to);

        $this->assertNotNull($user);
        $this->assertEquals($user->email, $this->user->email);
        // find the user with the same username. 
        // identify  the from sender, find or create 

        // can get the message text and store it in a new messsage 

        // can deal with the attachments 

        // submission has a new 
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

    public function test_can_get_only_new_text_from_email(): void
    {
        $text = $this->incomingMessageService->extractReply($this->inboundPayload['text']);

        $this->assertNotNull($text);
        $this->assertEquals($text, 'testing testing testing');
    }
}
