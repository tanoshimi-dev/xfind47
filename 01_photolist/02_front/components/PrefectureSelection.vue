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


const prefectures = ref([])
const prefecturesFiltered = ref([])
const prefectureFilter = ref('')

const loading = ref(true);
const error = ref(null);
const prefTabActive = ref(true);

const fetchPrefectures = async () => {
  try {
    // const response = await useFetch(`${apiUrl + apiUrlSuffix}api/users`)
    // const response = await useFetch(`https://mychat.tanoshimi.dev/backend/public/api/users`)

    // const { data, pending, error: fetchError } = await useFetch('http://localhost:40811/api/users');
    // const { data, pending, error: fetchError } = await useFetch('https://mychat.tanoshimi.dev/backend/public/api/users');
    // const { data, pending, error: fetchError } = await useFetch('http://localhost:40811/api/maker_test');
    const { data, pending, error: fetchError } = await useFetch(`${apiUrl + apiUrlSuffix}prefectures`);
    //console.log(data.value.data)
    prefectures.value = data.value.data;
    prefecturesFiltered.value = data.value.data;
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
  fetchPrefectures();
});

const filteredMakers = (prefName: string) => {
  console.log('prefName', prefName)
  // Computed property to filter the makers array
  prefecturesFiltered.value = prefectures.value
    .filter((prefecture) =>
    prefecture.pref.toLowerCase().includes(prefName.toLowerCase())
    );
};

const clearFilter = () => {
  prefectureFilter.value = '';
  prefecturesFiltered.value = prefectures.value;
};


const genres = [
  { name: '生き物' },
  { name: '風景' },
  { name: '生活' },
  { name: '春' },
  { name: '夏' },
  { name: '秋' },
  { name: '冬' },
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
      :class="[prefTabActive ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:text-gray-700', 'rounded-md px-3 py-2 text-sm font-medium']" 
      @click="prefTabActive = true"
    >
      都道府県
    </a>
    <a 
      class="cursor-pointer"
      :class="[!prefTabActive ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:text-gray-700', 'rounded-md px-3 py-2 text-sm font-medium']" 
      @click="prefTabActive = false"
    >
      タグ
    </a>
  </nav>

  <div v-if="prefTabActive" class="flex justify-center mt-2">
    <input
      type="text"
      placeholder="都道府県絞り込み"
      class="mb-4 p-2 border border-gray-300 rounded-md"
      v-model="prefectureFilter" 
      @input="filteredMakers(prefectureFilter)"
    />
    <button
      v-if="prefectureFilter"
      class="absolute text-gray-500 mt-2"
      @click="clearFilter"
      :style="{ right: '60px', zIndex: 10 }"
    >
      &times;
    </button>
  </div>
  
  <div v-else-if="!prefTabActive">
  </div>
    
  <div v-if="prefTabActive" >
    <li >
      <ul role="list" class="-mx-2 space-y-2 divide-y divide-gray-100 ">
        <li v-for="prefecture in prefecturesFiltered" :key="prefecture.id" 
          class="pt-2 pb-1 cursor-pointer justify-center"
          :class="[store.searchCondition.keyValueObject?.hasOwnProperty(prefecture.pref_code) ? 'bg-yellow-50 text-gray-600' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-600', 'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6']"
          @click="store.toggleSearchPref(prefecture.pref_code, prefecture.pref)">
          
          <!-- <NuxtLink :to="getProductLink(maker.仕入先)">
            <span class="truncate">{{ maker.メーカー名 }}</span>
          </NuxtLink> -->
          <span class="truncate">{{ prefecture.pref }}</span>
          <!-- <a :href="`http://localhost:3000/products?maker=${maker.仕入先}`" :class="[maker.仕入先 ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600', 'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6']">
            <span class="truncate">{{ maker.メーカー名 }}</span>
          </a> -->
        </li>
      </ul>
    </li>
  </div>

  <div v-else-if="!prefTabActive">
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