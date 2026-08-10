@extends('layouts.app')
@section('title', 'Pusat Pesan (Chat)')
@section('header', 'Pusat Pesan')

@push('styles')
<style>
    /* Custom Scrollbar */
    .chat-scroll::-webkit-scrollbar { width: 6px; }
    .chat-scroll::-webkit-scrollbar-track { background: transparent; }
    .chat-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }
    .chat-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endpush

@section('content')
<div class="h-[calc(100vh-12rem)]" x-data="adminChat()">
    <div class="bg-white/90 backdrop-blur-xl shadow-lg border border-slate-200/60 rounded-3xl h-full flex overflow-hidden">
        
        {{-- Left Pane: Contact List --}}
        <div class="w-1/3 border-r border-slate-200 flex flex-col bg-slate-50/50">
            <div class="p-5 border-b border-slate-200">
                <h2 class="font-bold text-lg text-slate-800 mb-4">Kontak Wali Santri</h2>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                    <input type="text" x-model="searchQuery" placeholder="Cari nama..." 
                           class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm bg-white shadow-sm">
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto chat-scroll p-3 space-y-2">
                <template x-for="contact in filteredContacts" :key="contact.id">
                    <button @click="selectContact(contact)" 
                            class="w-full text-left p-3 rounded-2xl transition-all duration-300 flex items-center gap-4 group"
                            :class="activeContact && activeContact.id === contact.id ? 'bg-primary/10 border border-primary/20 shadow-sm' : 'border border-transparent hover:bg-white hover:border-slate-200 hover:shadow-sm'">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 transition-transform group-hover:scale-105 shadow-sm"
                             :class="activeContact && activeContact.id === contact.id ? 'bg-primary text-white' : ('bg-' + contact.color + '-100 text-' + contact.color + '-600')">
                            <span class="material-symbols-outlined" x-text="contact.icon"></span>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <h3 class="font-bold truncate transition-colors"
                                :class="activeContact && activeContact.id === contact.id ? 'text-primary' : 'text-slate-700'" 
                                x-text="contact.name"></h3>
                            <p class="text-xs text-slate-500 truncate mt-0.5" x-text="contact.role"></p>
                        </div>
                        <template x-if="contact.unread_count > 0">
                            <div class="w-5 h-5 rounded-full bg-error text-white text-[10px] font-bold flex items-center justify-center shrink-0" x-text="contact.unread_count"></div>
                        </template>
                    </button>
                </template>
                <div x-show="filteredContacts.length === 0" class="text-center p-8 text-slate-500 flex flex-col items-center gap-2">
                    <span class="material-symbols-outlined text-4xl opacity-50">search_off</span>
                    <p class="text-sm">Tidak ada kontak ditemukan.</p>
                </div>
            </div>
        </div>

        {{-- Right Pane: Chat Window --}}
        <div class="flex-1 flex flex-col bg-white">
            
            <template x-if="!activeContact">
                <div class="flex-1 flex flex-col items-center justify-center text-slate-400">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-5xl text-slate-300">chat_bubble</span>
                    </div>
                    <p class="font-medium text-slate-500">Pilih kontak untuk mulai mengobrol</p>
                </div>
            </template>

            <template x-if="activeContact">
                <div class="flex-1 flex flex-col h-full">
                    {{-- Chat Header --}}
                    <div class="p-5 border-b border-slate-200 bg-white/50 backdrop-blur-md flex items-center gap-4 shrink-0 shadow-sm z-10">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 shadow-sm"
                             :class="'bg-' + activeContact.color + '-100 text-' + activeContact.color + '-600'">
                            <span class="material-symbols-outlined" x-text="activeContact.icon"></span>
                        </div>
                        <div>
                            <h2 class="font-bold text-lg text-slate-800" x-text="activeContact.name"></h2>
                            <p class="text-xs text-green-600 font-medium flex items-center gap-1.5 mt-0.5">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                Terhubung
                            </p>
                        </div>
                    </div>

                    {{-- Chat Messages --}}
                    <div class="flex-1 overflow-y-auto chat-scroll p-6 space-y-6 bg-slate-50/50" x-ref="chatArea">
                        <template x-if="isLoading">
                            <div class="flex justify-center">
                                <div class="w-8 h-8 border-4 border-primary/30 border-t-primary rounded-full animate-spin"></div>
                            </div>
                        </template>

                        <template x-for="msg in messages" :key="msg.id">
                            <div class="flex flex-col max-w-[75%]" :class="msg.sender_id === {{ auth()->id() }} ? 'self-end items-end' : 'self-start items-start'">
                                <div class="px-5 py-3 rounded-2xl shadow-sm text-sm/relaxed relative group"
                                     :class="msg.sender_id === {{ auth()->id() }} ? 'bg-primary text-white rounded-tr-sm' : 'bg-white border border-slate-200 text-slate-700 rounded-tl-sm'">
                                    <span x-text="msg.content"></span>
                                </div>
                                <div class="flex items-center gap-1.5 mt-1.5 px-1">
                                    <span class="text-[11px] text-slate-400 font-medium" x-text="formatTime(msg.created_at)"></span>
                                    <template x-if="msg.sender_id === {{ auth()->id() }}">
                                        <span class="material-symbols-outlined text-[14px]" :class="msg.is_read ? 'text-blue-500' : 'text-slate-300'">done_all</span>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="!isLoading && messages.length === 0">
                            <div class="text-center py-20 flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-2">
                                    <span class="material-symbols-outlined text-3xl">waving_hand</span>
                                </div>
                                <p class="text-slate-500 font-medium">Belum ada pesan. Sapa mereka sekarang!</p>
                            </div>
                        </template>
                    </div>

                    {{-- Chat Input --}}
                    <div class="p-4 bg-white border-t border-slate-200 shrink-0">
                        <form @submit.prevent="sendMessage" class="flex gap-3 items-center max-w-4xl mx-auto">
                            <input type="text" x-model="newMessage" placeholder="Ketik pesan Anda..." 
                                   class="flex-1 px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-full focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm shadow-inner text-slate-700">
                            <button type="submit" :disabled="isSending || newMessage.trim() === ''"
                                    class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center hover:bg-primary-dark transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 active:shadow-sm shrink-0">
                                <svg x-show="!isSending" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                </svg>
                                <div x-show="isSending" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" style="display: none;"></div>
                            </button>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
</script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.min.js"></script>
<script>
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env("REVERB_APP_KEY") }}',
        wsHost: '{{ env("REVERB_HOST") }}',
        wsPort: {{ env("REVERB_PORT", 8080) }},
        wssPort: {{ env("REVERB_PORT", 8080) }},
        forceTLS: false,
        enabledTransports: ['ws', 'wss'],
    });

    function adminChat() {
        return {
            myId: {{ auth()->id() }},
            contacts: @json($contacts),
            searchQuery: '',
            activeContact: null,
            messages: [],
            newMessage: '',
            isLoading: false,
            isSending: false,

            get filteredContacts() {
                if (this.searchQuery === '') return this.contacts;
                return this.contacts.filter(c => c.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
            },

            init() {
                this.listenForNewMessages();
            },

            selectContact(contact) {
                this.activeContact = contact;
                if (contact.unread_count) {
                    contact.unread_count = 0;
                }
                this.fetchMessages();
            },

            async fetchMessages() {
                if (!this.activeContact) return;
                this.isLoading = true;
                this.messages = [];
                try {
                    const res = await axios.get(`/api/chat/messages/${this.activeContact.id}`);
                    this.messages = res.data;
                    this.scrollToBottom();
                } catch (error) {
                    console.error('Failed to fetch messages', error);
                } finally {
                    this.isLoading = false;
                }
            },

            async sendMessage() {
                if (this.newMessage.trim() === '' || !this.activeContact) return;
                
                let tempMsg = {
                    id: 'temp-' + Date.now(),
                    sender_id: this.myId,
                    receiver_id: this.activeContact.id,
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
                        receiver_id: this.activeContact.id,
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
                            if (this.activeContact && e.message.sender_id === this.activeContact.id) {
                                this.messages.push(e.message);
                                this.scrollToBottom();
                            } else {
                                const sender = this.contacts.find(c => c.id === e.message.sender_id);
                                if (sender) {
                                    sender.unread_count = (sender.unread_count || 0) + 1;
                                }
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
