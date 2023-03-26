<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';

const form = useForm({
  prompt: ""
});

const open_sidebar = ref(false);

const sendMessage = () => {
  
}
</script>

<template>
  <Head title="Chat" />

  <button type="button" @click="open_sidebar = true" class="sticky top-3 z-10 bg-slate-300 inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
    </svg>
  </button>

  <Sidebar :show="open_sidebar" @close="open_sidebar = false">
    <div class="h-full px-3 py-1 overflow-y-auto bg-gray-50 dark:bg-gray-800">
      <div class="flex items-center justify-between py-2">
        <h3 class="text-lg">Chat Room</h3>
        <button @click="open_sidebar = false" class="m-1 lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>  
      </div>
      
      <ul class="space-y-2 font-medium mt-4">
        <li class="border-b">
            <Link href="/chat" class="flex items-center p-2 text-gray-100 rounded-lg hover:bg-gray-500">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
              </svg>

              <span class="ml-3">Chat1</span>
            </Link>
        </li>
      </ul>
    </div>
  </Sidebar>

  <div class="max-w-3xl mx-auto md:ml-80">
    <!--chat message-->
    <div class="mx-8 mt-8 mb-20">
      <div 
        v-for="(message, index) in messages" 
        :key="index" 
        :class="[message.user === 'user' ? 'chat-end' : 'chat-start']"
        class="chat"
      >
        <div class="chat-bubble">{{ message.text }}</div>
      </div>
    </div>

    <!--chat input-->
    <form @submit.prevent="sendMessage" class="fixed bottom-0 max-w-3xl w-full">
        <div class="flex items-center px-3 py-3 bg-slate-800">
            <textarea v-model="form.prompt" rows="1" class="block py-3 w-full text-sm text-gray-300 bg-slate-700 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="message"></textarea>
                <button type="submit" class="inline-flex justify-center p-2 text-blue-600 rounded-full cursor-pointer hover:bg-blue-100">
                <svg aria-hidden="true" class="w-6 h-6 rotate-90" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
            </button>
        </div>
    </form>
  </div>
</template>