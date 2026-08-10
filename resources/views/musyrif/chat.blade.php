@extends('layouts.musyrif')
@section('title', 'Pusat Pesan (Chat)')
@section('header', 'Pusat Pesan')

@push('styles')
<style>
    .chat-scroll::-webkit-scrollbar { width: 5px; }
    .chat-scroll::-webkit-scrollbar-track { background: transparent; }
    .chat-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }
</style>
@endpush

@section('content')
{{-- Full-height container --}}
<div class="-mx-4 -mt-4 md:mx-0 md:mt-0 relative" x-data="musyrifChat()" x-init="init()">

    {{-- ===== MOBILE: Panel Switch ===== --}}
    {{-- ===== MOBILE ===== --}}
    <div class="md:hidden flex flex-col" style="height: calc(100dvh - 8rem);">

        {{-- PANEL A: Kontak (default visible) --}}
        <div class="flex flex-col h-full" x-show="!mobileChat" x-transition:leave="transition-transform duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
            <div class="px-4 pt-4 pb-3 bg-white border-b border-slate-100">
                <h2 class="font-bold text-base text-slate-800 mb-3">Pesan</h2>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                    <input type="text" x-model="searchQuery" placeholder="Cari kontak..." class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all">
                </div>
            </div>
            <div class="flex-1 overflow-y-auto chat-scroll divide-y divide-slate-100 bg-white">
                <template x-for="contact in filteredContacts" :key="contact.id">
                    <button @click="selectContact(contact); mobileChat = true" class="w-full text-left px-4 py-3.5 flex items-center gap-3 hover:bg-slate-50 active:bg-slate-100 transition-colors">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 shadow-sm" :class="'bg-' + contact.color + '-100 text-' + contact.color + '-600'">
                            <span class="material-symbols-outlined text-xl" x-text="contact.icon"></span>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="font-semibold text-sm text-slate-800 truncate" x-text="contact.name"></p>
                            <p class="text-xs text-slate-500 truncate mt-0.5" x-text="contact.role"></p>
                        </div>
                        <template x-if="contact.unread_count > 0">
                            <div class="w-5 h-5 rounded-full bg-error text-white text-[10px] font-bold flex items-center justify-center shrink-0" x-text="contact.unread_count"></div>
                        </template>
                        <span class="material-symbols-outlined text-slate-300 text-lg shrink-0">chevron_right</span>
                    </button>
                </template>
                <div x-show="filteredContacts.length === 0" class="flex flex-col items-center justify-center py-16 text-slate-400">
                    <span class="material-symbols-outlined text-5xl mb-3 opacity-40">search_off</span>
                    <p class="text-sm">Tidak ada kontak.</p>
                </div>
            </div>
        </div>

        {{-- PANEL B: Chat Room --}}
        <div class="flex flex-col bg-white z-10 fixed inset-x-0" style="top: 57px; bottom: 64px; display: none;" x-show="mobileChat" x-transition:enter="transition-transform duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0">
            {{-- Mobile Chat Header --}}
            <div class="flex items-center gap-3 px-3 py-3 bg-white border-b border-slate-100 shrink-0 shadow-sm">
                <button @click="mobileChat = false; activeContact = null" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-slate-100 transition-colors text-slate-600">
                    <span class="material-symbols-outlined">arrow_back</span>
                </button>
                <template x-if="activeContact">
                    <div class="flex items-center gap-3 flex-1 overflow-hidden">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0" :class="'bg-' + activeContact.color + '-100 text-' + activeContact.color + '-600'">
                            <span class="material-symbols-outlined text-lg" x-text="activeContact.icon"></span>
                        </div>
                        <div class="overflow-hidden">
                            <p class="font-bold text-sm text-slate-800 truncate" x-text="activeContact.name"></p>
                            <p class="text-[11px] text-green-600 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Online
                            </p>
                        </div>
                    </div>
                </template>
            </div>
            {{-- Mobile Messages --}}
            <div class="flex-1 overflow-y-auto chat-scroll p-4 space-y-4 bg-slate-50" x-ref="mobileChatArea">
                <template x-if="isLoading">
                    <div class="flex justify-center py-8">
                        <div class="w-7 h-7 border-3 border-primary/30 border-t-primary rounded-full animate-spin"></div>
                    </div>
                </template>
                <template x-for="msg in messages" :key="msg.id">
                    <div class="flex flex-col max-w-[80%]" :class="msg.sender_id === {{ auth()->id() }} ? 'self-end items-end' : 'self-start items-start'">
                        <div class="px-4 py-2.5 rounded-2xl text-sm shadow-sm" :class="msg.sender_id === {{ auth()->id() }} ? 'bg-primary text-white rounded-tr-sm' : 'bg-white border border-slate-200 text-slate-700 rounded-tl-sm'">
                            <span x-text="msg.content"></span>
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 px-1" x-text="formatTime(msg.created_at)"></span>
                    </div>
                </template>
                <template x-if="!isLoading && messages.length === 0">
                    <div class="text-center py-16 flex flex-col items-center gap-2">
                        <span class="material-symbols-outlined text-4xl text-slate-300">waving_hand</span>
                        <p class="text-sm text-slate-400">Belum ada pesan. Mulai menyapa!</p>
                    </div>
                </template>
            </div>
            {{-- Mobile Input --}}
            <div class="p-3 bg-white border-t border-slate-100 shrink-0">
                <form @submit.prevent="sendMessage" class="flex items-center gap-2">
                    <input type="text" x-model="newMessage" placeholder="Ketik pesan..." class="flex-1 px-4 py-3 bg-slate-50 border border-slate-200 rounded-full text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <button type="submit" :disabled="isSending || newMessage.trim() === ''" class="w-11 h-11 bg-primary text-white rounded-full flex items-center justify-center shrink-0 disabled:opacity-50 shadow-md active:scale-95 transition-all">
                        <svg x-show="!isSending" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                        </svg>
                        <div x-show="isSending" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" style="display:none;"></div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== DESKTOP: Side-by-side ===== --}}
    <div class="hidden md:flex h-[calc(100vh-12rem)] bg-white/90 backdrop-blur-xl shadow-lg border border-slate-200/60 rounded-3xl overflow-hidden">

        {{-- Left: Contacts --}}
        <div class="w-80 border-r border-slate-200 flex flex-col bg-slate-50/60 shrink-0">
            <div class="p-5 border-b border-slate-200">
                <h2 class="font-bold text-lg text-slate-800 mb-3">Pesan</h2>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                    <input type="text" x-model="searchQuery" placeholder="Cari kontak..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-white shadow-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>
            </div>
            <div class="flex-1 overflow-y-auto chat-scroll p-3 space-y-1">
                <template x-for="contact in filteredContacts" :key="contact.id">
                    <button @click="selectContact(contact)" class="w-full text-left p-3 rounded-2xl transition-all duration-200 flex items-center gap-3 group" :class="activeContact && activeContact.id === contact.id ? 'bg-primary/10 border border-primary/20' : 'border border-transparent hover:bg-white hover:border-slate-200 hover:shadow-sm'">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 shadow-sm" :class="activeContact && activeContact.id === contact.id ? 'bg-primary text-white' : ('bg-' + contact.color + '-100 text-' + contact.color + '-600')">
                            <span class="material-symbols-outlined text-xl" x-text="contact.icon"></span>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="font-semibold text-sm truncate" :class="activeContact && activeContact.id === contact.id ? 'text-primary' : 'text-slate-700'" x-text="contact.name"></p>
                            <p class="text-xs text-slate-500 truncate mt-0.5" x-text="contact.role"></p>
                        </div>
                        <template x-if="contact.unread_count > 0">
                            <div class="w-5 h-5 rounded-full bg-error text-white text-[10px] font-bold flex items-center justify-center shrink-0" x-text="contact.unread_count"></div>
                        </template>
                    </button>
                </template>
                <div x-show="filteredContacts.length === 0" class="flex flex-col items-center py-12 text-slate-400">
                    <span class="material-symbols-outlined text-4xl opacity-40 mb-2">search_off</span>
                    <p class="text-sm">Tidak ada kontak.</p>
                </div>
            </div>
        </div>

        {{-- Right: Chat --}}
        <div class="flex-1 flex flex-col bg-white">
            <template x-if="!activeContact">
                <div class="flex-1 flex flex-col items-center justify-center gap-4 text-slate-400">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-5xl text-slate-300">chat</span>
                    </div>
                    <p class="font-medium text-slate-500">Pilih kontak untuk mulai chat</p>
                </div>
            </template>
            <template x-if="activeContact">
                <div class="flex-1 flex flex-col h-full">
                    <div class="p-4 border-b border-slate-200 flex items-center gap-3 shrink-0 shadow-sm">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center shadow-sm" :class="'bg-' + activeContact.color + '-100 text-' + activeContact.color + '-600'">
                            <span class="material-symbols-outlined" x-text="activeContact.icon"></span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800" x-text="activeContact.name"></p>
                            <p class="text-xs text-green-600 flex items-center gap-1.5 mt-0.5">
                                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span>
                                Terhubung
                            </p>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto chat-scroll p-5 space-y-4 bg-slate-50/50" x-ref="chatArea">
                        <template x-if="isLoading">
                            <div class="flex justify-center"><div class="w-7 h-7 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div></div>
                        </template>
                        <template x-for="msg in messages" :key="msg.id">
                            <div class="flex flex-col max-w-[70%]" :class="msg.sender_id === {{ auth()->id() }} ? 'self-end items-end' : 'self-start items-start'">
                                <div class="px-4 py-3 rounded-2xl text-sm shadow-sm" :class="msg.sender_id === {{ auth()->id() }} ? 'bg-primary text-white rounded-tr-sm' : 'bg-white border border-slate-200 text-slate-700 rounded-tl-sm'">
                                    <span x-text="msg.content"></span>
                                </div>
                                <div class="flex items-center gap-1.5 mt-1 px-1">
                                    <span class="text-[11px] text-slate-400" x-text="formatTime(msg.created_at)"></span>
                                    <template x-if="msg.sender_id === {{ auth()->id() }}">
                                        <span class="material-symbols-outlined text-[13px]" :class="msg.is_read ? 'text-blue-500' : 'text-slate-300'">done_all</span>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template x-if="!isLoading && messages.length === 0">
                            <div class="text-center py-16 flex flex-col items-center gap-3">
                                <div class="w-14 h-14 bg-primary/10 text-primary rounded-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-2xl">waving_hand</span>
                                </div>
                                <p class="text-slate-500">Belum ada pesan. Sapa mereka sekarang!</p>
                            </div>
                        </template>
                    </div>
                    <div class="p-4 bg-white border-t border-slate-200 shrink-0">
                        <form @submit.prevent="sendMessage" class="flex gap-3 items-center">
                            <input type="text" x-model="newMessage" placeholder="Ketik pesan Anda..." class="flex-1 px-5 py-3 bg-slate-50 border border-slate-200 rounded-full text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <button type="submit" :disabled="isSending || newMessage.trim() === ''" class="w-11 h-11 bg-primary text-white rounded-full flex items-center justify-center shrink-0 disabled:opacity-50 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all active:translate-y-0">
                                <svg x-show="!isSending" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                                </svg>
                                <div x-show="isSending" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" style="display:none;"></div>
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
    if (typeof Echo === 'undefined' && window.Pusher) {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env("REVERB_APP_KEY") }}',
            wsHost: '{{ env("REVERB_HOST") }}',
            wsPort: {{ env("REVERB_PORT", 8080) }},
            wssPort: {{ env("REVERB_PORT", 8080) }},
            forceTLS: false,
            enabledTransports: ['ws', 'wss'],
        });
    }

    function musyrifChat() {
        return {
            myId: {{ auth()->id() }},
            contacts: @json($contacts),
            searchQuery: '',
            activeContact: null,
            messages: [],
            newMessage: '',
            isLoading: false,
            isSending: false,
            mobileChat: false,

            get filteredContacts() {
                if (!this.searchQuery) return this.contacts;
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
                } catch (e) {
                    console.error('Fetch messages failed', e);
                } finally {
                    this.isLoading = false;
                }
            },

            async sendMessage() {
                if (!this.newMessage.trim() || !this.activeContact) return;
                const content = this.newMessage;
                const temp = { id: 'tmp-' + Date.now(), sender_id: this.myId, receiver_id: this.activeContact.id, content, created_at: new Date().toISOString(), is_read: false };
                this.messages.push(temp);
                this.newMessage = '';
                this.isSending = true;
                this.scrollToBottom();
                try {
                    const res = await axios.post('/api/chat/send', { receiver_id: this.activeContact.id, content });
                    const idx = this.messages.findIndex(m => m.id === temp.id);
                    if (idx !== -1) this.messages[idx] = res.data;
                } catch (e) {
                    alert('Gagal mengirim pesan');
                    this.messages = this.messages.filter(m => m.id !== temp.id);
                } finally {
                    this.isSending = false;
                }
            },

            listenForNewMessages() {
                if (window.Echo) {
                    window.Echo.private(`chat.${this.myId}`).listen('MessageSent', (e) => {
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
                    const el = this.$refs.chatArea || this.$refs.mobileChatArea;
                    if (el) el.scrollTop = el.scrollHeight;
                }, 50);
            },

            formatTime(d) {
                return new Date(d).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            }
        };
    }
</script>
@endpush
