<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { marked } from 'marked';

marked.setOptions({
  breaks: true,
})

const props = defineProps({
  chat_list: Array,
  chat: null | Object
})

const form = useForm({
  prompt: ""
});

const open_sidebar = ref(false);

const loading = ref(false);

const sendMessage = () => {
  if(!form.prompt) return;

  form.post(route('chat.store', {id: props.chat ? props.chat.id : null }), {
    onSuccess: () => form.reset(),
    onStart: () => loading.value = true,
    onFinish: () => loading.value = false,
  });
}

const deleteChat = () => {
  if(!props.chat) return;

  router.delete(route('chat.delete', {id: props.chat.id}), {
    onBefore: () => confirm('本当に削除してもよろしいですか？')
  })
}
</script>

<template>
  <Head title="Chat" />

  <div>
    <!-- open sidebar -->
    <button type="button" @click="open_sidebar = true" class="fixed top-0 z-10 bg-slate-400 inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-200">
      <svg class="w-6 h-6 text-gray-600" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
      </svg>
    </button>

    <!-- sidebar -->
    <Sidebar :show="open_sidebar" @close="open_sidebar = false">
      <div class="h-full px-3 py-1 overflow-y-auto bg-gray-50 dark:bg-gray-800">
        <button @click="open_sidebar = false" class="fixed top-0 right-0 m-1 lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <ul class="mt-8 space-y-3">
          <li class="text-gray-400 rounded p-1 bg-gray-800 hover:bg-gray-600 hover:text-gray-200">
            <Link 
              :href="route('profile.edit')"
              class="w-full text-start flex items-center"
              as="button"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
              </svg>
              プロフィール編集
            </Link>
          </li>

          <li class="text-gray-400 rounded p-1 bg-gray-800 hover:bg-gray-600 hover:text-gray-200">
            <Link 
              :href="route('logout')" 
              method="post"
              class="w-full text-start flex items-center"
              as="button"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
              </svg>

              ログアウト
            </Link>
          </li>
        </ul>
        
        <ul class="border-t pt-6 space-y-4 font-medium mt-4">
          <li class="text-gray-400 border border-gray-400 rounded mb-6 bg-gray-800 hover:bg-gray-600 hover:text-gray-200">
              <Link 
                :href="route('chat.show')" 
                class="flex items-center p-2 w-full"
                as="button"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span class="ml-3">New Chat</span>
              </Link>
          </li>
          
          <li 
            v-for="c in chat_list" 
            :key="c.id" 
            :class="[chat && c.id === chat.id ? 'bg-blue-500/70 rounded-none': '']" 
            class="flex items-center justify-between border-b"
          >
              <Link 
                :href="route('chat.show', {'id' : c.id})" 
                :class="[chat && c.id === chat.id ? '' : 'hover:text-indigo-400']"
                class="flex items-center p-2 text-gray-100 w-full"
                as="button"
                :disabled="chat && c.id === chat.id"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                </svg>
                <div class="ml-3">Chat {{ c.id }}</div>
              </Link>

              <div class="flex items-center space-x-2 pr-2">
                <button @click="updateChatName">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-blue-400 hover:text-blue-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                  </svg>
                </button>
                
                <button @click="deleteChat">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-400 hover:text-red-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                  </svg>
                </button>
              </div>
          </li>
        </ul>
      </div>
    </Sidebar>

    <!-- chat -->
    <div class="lg:pl-72">
      <div class="max-w-3xl mx-auto bg-slate-600 min-h-screen h-full">
        <div class="w-full bg-slate-800 py-3.5 border-b border-gray-400">
          <h2 class="text-lg md:text-xl mb-1">
            <div v-if="chat" class="text-center">Chat {{ chat.id }}</div>
            <div v-else class="text-center">New Chat</div>
          </h2>
        </div>

        <!--messages -->
        <div v-if="chat" class="mx-3 pb-20 pt-6">
          <div 
            v-for="(message, index) in chat.context" 
            :key="index" 
            :class="[message.role === 'user' ? 'chat-end' : 'chat-start']"
            class="chat mb-6"
          >
            <div v-if="message.role === 'user'" class="chat-image avatar">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div 
              class="chat-bubble" 
              :class="[message.role === 'user' ? 'chat-bubble-info' : '']" 
              v-html="marked(message.content)"
            ></div>
          </div>
        </div>

        <div v-else class="text-center pt-40">
          <h3 class="flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 text-gray-400">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
            </svg>
          </h3>
        </div>

        <!--chat input-->
        <form @submit.prevent="sendMessage" class="fixed bottom-0 max-w-3xl w-full">
          <div class="flex items-center px-3 py-3">
            <textarea v-model="form.prompt" rows="2" class="block py-2.5 w-full text-sm text-gray-300 bg-slate-700 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="メッセージを入力"></textarea>
            <button v-if="!loading" type="submit" :disabled="!form.prompt" :class="[form.prompt ? 'text-blue-500 hover:text-blue-600 hover:scale-110' : 'text-gray-500']" class="inline-flex justify-center p-2 rounded-full">
              <svg aria-hidden="true" class="w-6 h-6 rotate-90" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
            </button>
            <div v-else class="ml-3 animate-spin h-6 w-6 border-4 border-blue-500 rounded-full border-t-transparent"></div>
          </div>
        </form>
      </div>  
    </div>
  </div>
  
  
  
</template>
