<script setup lang="ts">

import { ref, onMounted } from 'vue';
import type { NuxtError } from '#app'
import { useFetch } from '#app';

import {apiUrl, apiUrlSuffix } from '../env'

// http://localhost:40811/api/users
// https://mychat.tanoshimi.dev/backend/public/api/users


import { useSearchConditionStore } from '../stores/searchCondition'
const store = useSearchConditionStore()

// console.log('searchCondition ｍｅｎｕ', store.count)

const props = defineProps({
  error: Object as () => NuxtError
})


const makers = ref([])
const makersFiltered = ref([])
const makerFilter = ref('')

const loading = ref(true);
const error = ref(null);
const makerTabActive = ref(true);

const fetchMakers = async () => {
  try {
    // const response = await useFetch(`${apiUrl + apiUrlSuffix}api/users`)
    // const response = await useFetch(`https://mychat.tanoshimi.dev/backend/public/api/users`)

    // const { data, pending, error: fetchError } = await useFetch('http://localhost:40811/api/users');
    // const { data, pending, error: fetchError } = await useFetch('https://mychat.tanoshimi.dev/backend/public/api/users');
    // const { data, pending, error: fetchError } = await useFetch('http://localhost:40811/api/maker_test');
    const { data, pending, error: fetchError } = await useFetch(`${apiUrl + apiUrlSuffix}maker_test`);
    // console.log(data.value.data)
    makers.value = data.value.data;
    makersFiltered.value = data.value.data;
    error.value = fetchError.value;
    loading.value = pending.value;
  } catch (err) {
    error.value = err;
    loading.value = false;
  }
};

const getProductLink = (makerId: string) => {
  return `/products?maker=${makerId}`;
};

onMounted(() => {
  fetchMakers();
});

const filteredMakers = (makerName: string) => {
  // console.log('makerName', makerName)
  // Computed property to filter the makers array
  makersFiltered.value = makers.value
    .filter((maker) =>
      maker.メーカー名.toLowerCase().includes(makerName.toLowerCase())
    );
};

const clearFilter = () => {
  makerFilter.value = '';
  makersFiltered.value = makers.value;
};


const genres = [
  { name: 'スクール' },
  { name: '子供肌着' },
  { name: '婦人肌着' },
  { name: 'レッグウェア' },
  { name: '紳士肌着' },
  { name: '介護肌着' },
  { name: '介護アウター' },
  { name: 'タオル' },
  { name: 'アウター' },
  { name: 'その他' },
]

</script>

<template>

    <!-- <button @click="searchCondition.count++">+1</button>
    <p>{{ searchCondition.count }}</p>

    <p>{{ searchCondition.searchCondition.makers }}</p>
    <div v-for="maker in searchCondition.searchCondition.makers" :key="maker">
      <p>{{ maker }}</p>
    </div>
 -->

  <nav class="flex justify-center space-x-4" aria-label="Tabs">
    <a 
      class="cursor-pointer"
      :class="[makerTabActive ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:text-gray-700', 'rounded-md px-3 py-2 text-sm font-medium']" 
      @click="makerTabActive = true"
    >
      都道府県
    </a>
    <a 
      class="cursor-pointer"
      :class="[!makerTabActive ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:text-gray-700', 'rounded-md px-3 py-2 text-sm font-medium']" 
      @click="makerTabActive = false"
    >
      タグ
    </a>
  </nav>

  <div v-if="makerTabActive" class="flex justify-center mt-2">
    <input
      type="text"
      placeholder="都道府県絞り込み"
      class="mb-4 p-2 border border-gray-300 rounded-md"
      v-model="makerFilter" 
      @input="filteredMakers(makerFilter)"
    />
    <button
      v-if="makerFilter"
      class="absolute text-gray-500 mt-2"
      @click="clearFilter"
      :style="{ right: '60px', zIndex: 10 }"
    >
      &times;
    </button>
  </div>
  
  <div v-else-if="!makerTabActive">
  </div>
    
  <div v-if="makerTabActive" >
    <li >
      <ul role="list" class="-mx-2 space-y-2 divide-y divide-gray-100 ">
        <li v-for="maker in makersFiltered" :key="maker.仕入先" 
          class="pt-2 pb-1 cursor-pointer justify-center"
          :class="[store.searchCondition.keyValueObject?.hasOwnProperty(maker.仕入先) ? 'bg-yellow-50 text-gray-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-600', 'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6']"
          @click="store.toggleSearchMaker(maker.仕入先, maker.メーカー名)">
          
          <!-- <NuxtLink :to="getProductLink(maker.仕入先)">
            <span class="truncate">{{ maker.メーカー名 }}</span>
          </NuxtLink> -->
          <span class="truncate">{{ maker.メーカー名 }}</span>
          <!-- <a :href="`http://localhost:3000/products?maker=${maker.仕入先}`" :class="[maker.仕入先 ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600', 'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6']">
            <span class="truncate">{{ maker.メーカー名 }}</span>
          </a> -->
        </li>
      </ul>
    </li>
  </div>

  <div v-else-if="!makerTabActive">
    <li class="mt-4">
      <ul role="list" class="-mx-2 space-y-2 divide-y divide-gray-100 ">
        <li v-for="genre in genres" :key="genre.name" 
        class="pt-2 pb-1 cursor-pointer justify-center"
         :class="[store.searchCondition.genres.includes(genre.name) ? 'bg-yellow-50 text-gray-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-600', 'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6']"
          @click="store.toggleSearchGenre(genre.name)">          
          <span class="truncate">{{ genre.name }}</span>
        </li>
      </ul>
    </li>
  </div>

  <div v-else="loading" class="loading-icon">
    loading...
  </div>

</template>

<style>
.loading-icon {
  /* Add your loading icon styles here */
  font-size: 24px;
  text-align: center;
}
</style>