<script setup lang="ts">

import { ref, onMounted } from 'vue';
import type { NuxtError } from '#app'
import { useFetch } from '#app';

import {apiUrl, apiUrlSuffix } from '../env'

// http://localhost:40811/api/users
// https://mychat.tanoshimi.dev/backend/public/api/users


const props = defineProps({
  error: Object as () => NuxtError
})


const users = ref([])
const loading = ref(true);
const error = ref(null);

/*
const fetchUsers = async() => {
  // const response = await useFetch(`${apiUrl + apiUrlSuffix}api/users`)
  const response = await useFetch(`http://localhost:40811/api/users`)

  // const response = await useFetch(`https://mychat.tanoshimi.dev/backend/public/api/users`)
  // console.log(`${apiUrl + apiUrlSuffix}api/users`)
  console.log(response.data.value)

  if (response.data) {
    users.value = response.data.value.data
  }

}
*/


const fetchUsers = async () => {
  try {
    // const response = await useFetch(`${apiUrl + apiUrlSuffix}api/users`)
    // const response = await useFetch(`https://mychat.tanoshimi.dev/backend/public/api/users`)

    const { data, pending, error: fetchError } = await useFetch('http://localhost:40811/api/users');
    // const { data, pending, error: fetchError } = await useFetch('https://mychat.tanoshimi.dev/backend/public/api/users');
    console.log(data.value.data)
    users.value = data.value.data;
    error.value = fetchError.value;
    loading.value = pending.value;
  } catch (err) {
    error.value = err;
    loading.value = false;
  }
};


onMounted(() => {
  fetchUsers();
});

</script>

<template>
  <h1>バックエンド API 呼び出し</h1>
  <section>
    <p>This page will be displayed at the /users route.</p>
  </section>
  <div>
    <NuxtLink to="/">Go back home</NuxtLink>
  </div>
  
  <table class="min-w-full divide-y divide-gray-300">
    <thead class="bg-gray-50">
      <tr>
        <th>名称</th>
        <th>email</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 bg-white">
      <tr v-for="user in users" :key="user.id">
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
      </tr>
    </tbody>
  </table>

  <div>
    <div v-if="loading" class="loading-icon">
      <!-- Replace with your loading icon -->
      <span>Loading...</span>
    </div>
    <div v-else>
      <ul>
        <li v-for="user in users" :key="user.id">{{ user.name }}</li>
      </ul>
    </div>
  </div>

</template>

<style>
.loading-icon {
  /* Add your loading icon styles here */
  font-size: 24px;
  text-align: center;
}
</style>