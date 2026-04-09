<?php

namespace App\Livewire\Guide;

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class GuideMessages extends Component
{
    public ?int $selectedConversationId = null;

    public string $messageBody = '';

    public function selectConversation(int $conversationId): void
    {
        $this->selectedConversationId = $conversationId;
    }

    public function sendMessage(): void
    {
        $this->validate([
            'messageBody' => ['required', 'string', 'max:2000'],
        ]);

        if (! Schema::hasTable('conversations') || ! Schema::hasTable('messages')) {
            return;
        }

        $guideId = (int) Auth::id();

        $conversation = Conversation::query()
            ->whereKey($this->selectedConversationId)
            ->where('guide_id', $guideId)
            ->first();

        if (! $conversation) {
            return;
        }

        $conversation->messages()->create([
            'sender_id' => $guideId,
            'message' => trim($this->messageBody),
            'is_read' => false,
        ]);

        $conversation->forceFill(['last_message_at' => now()])->save();
        $this->messageBody = '';
    }

    public function render()
    {
        $conversations = collect();
        $selectedConversation = null;

        if (Schema::hasTable('conversations')) {
            $guideId = (int) Auth::id();

            $conversations = Conversation::query()
                ->with([
                    'tourist:id,name,full_name',
                    'tour:id,name,title',
                ])
                ->withCount([
                    'messages as unread_messages_count' => fn ($query) => $query
                        ->where('sender_id', '!=', $guideId)
                        ->where('is_read', false),
                ])
                ->where('guide_id', $guideId)
                ->orderByDesc('last_message_at')
                ->orderByDesc('updated_at')
                ->limit(30)
                ->get();

            if ($this->selectedConversationId === null && $conversations->isNotEmpty()) {
                $this->selectedConversationId = (int) $conversations->first()->id;
            }

            $selectedConversation = $this->selectedConversationId !== null
                ? Conversation::query()
                    ->with([
                        'tourist:id,name,full_name',
                        'tour:id,name,title',
                        'messages' => fn ($query) => $query
                            ->with('sender:id,name,full_name')
                            ->orderBy('created_at')
                            ->limit(100),
                    ])
                    ->whereKey($this->selectedConversationId)
                    ->where('guide_id', $guideId)
                    ->first()
                : null;

            if ($selectedConversation && Schema::hasTable('messages')) {
                $selectedConversation->messages()
                    ->where('sender_id', '!=', $guideId)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }
        }

        return view('livewire.guide.guide-messages', [
            'conversations' => $conversations,
            'selectedConversation' => $selectedConversation,
        ]);
    }
}
