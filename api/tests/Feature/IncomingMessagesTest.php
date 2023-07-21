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
        ]);

        Mail::assertQueued(NewMessageAlert::class);
    }
}
