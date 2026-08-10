@extends('layouts.mobile')

@section('title', 'Chat dengan ' . $contact->name)

@push('styles')
<style>
    /* Custom Scrollbar for chat area */
    #chat-container::-webkit-scrollbar { width: 4px; }
    #chat-container::-webkit-scrollbar-track { background: transparent; }
    #chat-container::-webkit-scrollbar-thumb { background: #bec9c2; border-radius: 4px; }
</style>
@endpush

@section('content')
<div x-data="chatRoom()" x-init="initChat()" class="flex flex-col h-[calc(100vh-10rem)] -mt-4">
    
    {{-- Chat Header --}}
    <div class="flex items-center gap-3 bg-surface p-4 border-b border-outline-variant/20 rounded-t-2xl shadow-sm z-10 shrink-0">
        <a href="{{ route('wali.chat') }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-container hover:bg-surface-container-high transition-colors text-on-surface">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
        </a>
        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
            {{ substr($contact->name, 0, 1) }}
        </div>
        <div>
            <h3 class="text-sm font-bold text-on-surface leading-tight">{{ $contact->name }}</h3>
            <p class="text-[10px] text-primary flex items-center gap-1 mt-0.5">
                <span class="w-1.5 h-1.5 rounded-full bg-primary inline-block"></span>
                Online
            </p>
        </div>
    </div>

    {{-- Chat Messages Area --}}
    <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-surface-container-lowest" x-ref="chatArea">
        
        <template x-if="isLoading">
            <div class="flex justify-center py-4">
                <div class="w-5 h-5 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
            </div>
        </template>
        
        <template x-for="msg in messages" :key="msg.id">
            <div class="flex flex-col max-w-[80%]" :class="msg.sender_id === {{ auth()->id() }} ? 'self-end items-end' : 'self-start items-start'">
                <div class="p-3 rounded-2xl shadow-sm text-sm break-words"
                     :class="msg.sender_id === {{ auth()->id() }} ? 'bg-primary text-on-primary rounded-tr-sm' : 'bg-surface text-on-surface border border-outline-variant/20 rounded-tl-sm'">
                    <span x-text="msg.content"></span>
                </div>
                <div class="flex items-center gap-1 mt-1 px-1">
                    <span class="text-[9px] text-on-surface-variant" x-text="formatTime(msg.created_at)"></span>
                    <template x-if="msg.sender_id === {{ auth()->id() }}">
                        <span class="material-symbols-outlined text-[10px]" :class="msg.is_read ? 'text-primary' : 'text-outline-variant'">done_all</span>
                    </template>
                </div>
            </div>
        </template>
        
        {{-- Empty state --}}
        <template x-if="!isLoading && messages.length === 0">
            <div class="text-center py-10">
                <span class="material-symbols-outlined text-outline-variant text-4xl mb-2">forum</span>
                <p class="text-xs text-on-surface-variant">Belum ada pesan. Mulai percakapan sekarang.</p>
            </div>
        </template>
        
    </div>

    {{-- Chat Input Area --}}
    <div class="p-3 bg-surface border-t border-outline-variant/20 shrink-0">
        <form @submit.prevent="sendMessage" class="flex items-end gap-2">
            <div class="flex-1 bg-surface-container-low rounded-2xl border border-outline-variant/30 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-all overflow-hidden flex items-center px-3 py-1">
                <textarea 
                    x-model="newMessage" 
                    @keydown.enter.prevent="sendMessage"
                    placeholder="Ketik pesan..." 
                    class="w-full bg-transparent border-none focus:ring-0 text-sm resize-none py-2 max-h-24 min-h-[40px] hide-scrollbar"
                    rows="1"
                ></textarea>
            </div>
            <button type="submit" :disabled="isSending || newMessage.trim() === ''" 
                    class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center shrink-0 disabled:opacity-50 disabled:cursor-not-allowed transition-colors active:scale-95 shadow-md">
                <svg x-show="!isSending" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                </svg>
                <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" x-show="isSending" style="display: none;"></div>
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
{{-- Setup Axios CSRF --}}
<script>
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
</script>

{{-- Pusher & Echo CDN --}}
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.min.js"></script>

<script>
    // Initialize Echo for Reverb
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env("REVERB_APP_KEY") }}',
        wsHost: '{{ env("REVERB_HOST") }}',
        wsPort: {{ env("REVERB_PORT", 8080) }},
        wssPort: {{ env("REVERB_PORT", 8080) }},
        forceTLS: false, // Since it's local reverb
        enabledTransports: ['ws', 'wss'],
    });

    function chatRoom() {
        return {
            contactId: {{ $contact->id }},
            myId: {{ auth()->id() }},
            messages: [],
            newMessage: '',
            isLoading: true,
            isSending: false,

            initChat() {
                this.fetchMessages();
                this.listenForNewMessages();
            },

            async fetchMessages() {
                this.isLoading = true;
                try {
                    const res = await axios.get(`/api/chat/messages/${this.contactId}`);
                    this.messages = res.data;
                    this.scrollToBottom();
                } catch (error) {
                    console.error('Failed to fetch messages', error);
                } finally {
                    this.isLoading = false;
                }
            },

            async sendMessage() {
                if (this.newMessage.trim() === '') return;
                
                let tempMsg = {
                    id: 'temp-' + Date.now(),
                    sender_id: this.myId,
                    receiver_id: this.contactId,
                    content: this.newMessage,
                    created_at: new Date().toISOString(),
                    is_read: false
                };
                
                this.messages.push(tempMsg);
                this.scrollToBottom();
                
                const contentToSend = this.newMessage;
                this.newMessage = '';
                this.isSending = true;

                try {
                    const res = await axios.post('/api/chat/send', {
                        receiver_id: this.contactId,
                        content: contentToSend
                    });
                    
                    const index = this.messages.findIndex(m => m.id === tempMsg.id);
                    if (index !== -1) {
                        this.messages[index] = res.data;
                    }
                } catch (error) {
                    console.error('Failed to send message', error);
                    alert('Gagal mengirim pesan');
                    this.messages = this.messages.filter(m => m.id !== tempMsg.id);
                } finally {
                    this.isSending = false;
                }
            },

            listenForNewMessages() {
                if (window.Echo) {
                    window.Echo.private(`chat.${this.myId}`)
                        .listen('MessageSent', (e) => {
                            if (e.message.sender_id === this.contactId) {
                                this.messages.push(e.message);
                                this.scrollToBottom();
                            }
                        });
                }
            },

            scrollToBottom() {
                setTimeout(() => {
                    if (this.$refs.chatArea) {
                        this.$refs.chatArea.scrollTop = this.$refs.chatArea.scrollHeight;
                    }
                }, 50);
            },
            
            formatTime(dateString) {
                const date = new Date(dateString);
                return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            }
        };
    }
</script>
@endpush
