<!--
  This example requires some changes to your config:
  
  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
      require('@tailwindcss/aspect-ratio'),
    ],
  }
  ```
-->
<template>
  <div class="bg-white">
    <!-- Mobile menu -->
    <TransitionRoot as="template" :show="open">
      <Dialog class="relative z-40 lg:hidden" @close="open = false">
        <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0">
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 z-40 flex">
          <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
            <DialogPanel class="relative flex w-full max-w-xs flex-col overflow-y-auto bg-white pb-12 shadow-xl">
              <div class="flex px-4 pb-2 pt-5">
                <button type="button" class="-m-2 inline-flex items-center justify-center rounded-md p-2 text-gray-400" @click="open = false">
                  <span class="sr-only">Close menu</span>
                  <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                </button>
              </div>

              <!-- Links -->
              <TabGroup as="div" class="mt-2">
                <div class="border-b border-gray-200">
                  <TabList class="-mb-px flex space-x-8 px-4">
                    <Tab as="template" v-for="category in navigation.categories" :key="category.name" v-slot="{ selected }">
                      <button :class="[selected ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-900', 'flex-1 whitespace-nowrap border-b-2 px-1 py-4 text-base font-medium']">{{ category.name }}</button>
                    </Tab>
                  </TabList>
                </div>
                <TabPanels as="template">
                  <TabPanel v-for="category in navigation.categories" :key="category.name" class="space-y-10 px-4 pb-8 pt-10">
                    <div class="space-y-4">
                      <div v-for="(item, itemIdx) in category.featured" :key="itemIdx" class="group aspect-h-1 aspect-w-1 relative overflow-hidden rounded-md bg-gray-100">
                        <img :src="item.imageSrc" :alt="item.imageAlt" class="object-cover object-center group-hover:opacity-75" />
                        <div class="flex flex-col justify-end">
                          <div class="bg-white bg-opacity-60 p-4 text-base sm:text-sm">
                            <a :href="item.href" class="font-medium text-gray-900">
                              <span class="absolute inset-0" aria-hidden="true" />
                              {{ item.name }}
                            </a>
                            <p aria-hidden="true" class="mt-0.5 text-gray-700 sm:mt-1">Shop now</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div v-for="(column, columnIdx) in category.sections" :key="columnIdx" class="space-y-10">
                      <div v-for="section in column" :key="section.name">
                        <p :id="`${category.id}-${section.id}-heading-mobile`" class="font-medium text-gray-900">{{ section.name }}</p>
                        <ul role="list" :aria-labelledby="`${category.id}-${section.id}-heading-mobile`" class="mt-6 flex flex-col space-y-6">
                          <li v-for="item in section.items" :key="item.name" class="flow-root">
                            <a :href="item.href" class="-m-2 block p-2 text-gray-500">{{ item.name }}</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </TabPanel>
                </TabPanels>
              </TabGroup>

              <div class="space-y-6 border-t border-gray-200 px-4 py-6">
                <div v-for="page in navigation.pages" :key="page.name" class="flow-root">
                  <a :href="page.href" class="-m-2 block p-2 font-medium text-gray-900">{{ page.name }}</a>
                </div>
              </div>

              <div class="border-t border-gray-200 px-4 py-6">
                <a href="#" class="-m-2 flex items-center p-2">
                  <img src="https://tailwindui.com/img/flags/flag-canada.svg" alt="" class="block h-auto w-5 flex-shrink-0" />
                  <span class="ml-3 block text-base font-medium text-gray-900">CAD</span>
                  <span class="sr-only">, change currency</span>
                </a>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </Dialog>
    </TransitionRoot>


    <main class="pb-12">

      <!-- Current Maker -->
      <div class="">
        <button type="button" class="mb-1 text-gray-500 underline decoration-double underline-offset-4">
          <span v-for="(value, key) in store.searchCondition.keyValueObject" :key="key">
            {{ `${value}(${key}) ` }}
          </span>
        </button>
        <button type="button" class="mb-1 text-orange-600 underline decoration-double underline-offset-4">
          <span v-for="genre in store.searchCondition.genres" :key="genre">
            {{ `${genre} ` }}
          </span>
        </button>
      </div>

      <!-- Filters -->
      <Disclosure v-slot="{ open }" defaultOpen="true">
        
        <div aria-labelledby="filter-heading" class="grid items-center">

          <h2 id="filter-heading" class="sr-only">Filters</h2>
          <div class="relative col-start-1 row-start-1 ">
            <div class="mx-auto flex max-w-8xl space-x-6 divide-x divide-gray-200 px-2 text-sm sm:px-6 lg:px-2">
              <div>
                <DisclosureButton class="group flex items-center font-medium text-gray-700">
                  <ChevronUpIcon
                    :class="open ? 'rotate-180 transform' : ''"
                    class="mr-1 h-5 w-5"
                  />
                  
                  追加条件
                </DisclosureButton>
              </div>
              <div class="pl-6">
                <button type="button" class="text-gray-500" @click="clearColorFilter">クリア</button>
              </div>
            </div>
          </div>

          <DisclosurePanel class="border-t border-gray-100 pt-4 py-4">

            <!-- <div class="grid auto-rows-min grid-cols-3 gap-x-3 md:gap-y-4 md:grid-cols-6 md:gap-x-7"> -->
            <div class="flex flex-row">
              
              <div class="flex mt-6 pl-4 md:pl-5 md:pr-2" style="width:100px">
                <button type="button" @click="search" class="rounded-md bg-yellow-50 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-yellow-100">
                  検索
                </button>
              </div>

              <div class="flex">
                <fieldset class="px-4">
                  <div>
                    <label for="no_filter" class="block text-sm font-medium leading-6 text-gray-900">タイトル</label>
                    <div class="mt-1">
                      <input 
                        v-model="noFilter"
                        type="text" name="no_filter" id="no_filter" 
                        class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
                        placeholder="" />
                    </div>
                  </div>
                </fieldset>
                <fieldset class="px-4">
                  <div>
                    <label for="sku_filter" class="block text-sm font-medium leading-6 text-gray-900">撮影者</label>
                    <div class="mt-1">
                      <input 
                        v-model="skuFilter"
                        type="text" name="sku_filter" id="sku_filter" 
                        class="block w-full rounded-md border-0 px-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
                        placeholder="" />
                    </div>
                  </div>
                </fieldset>
              </div>

            </div>


          </DisclosurePanel>
          
          <div class="col-start-1 row-start-1 py-4">
            <div class="mx-auto flex max-w-8xl justify-end px-4 sm:px-6 lg:px-8">
              <Menu as="div" class="relative inline-block">
                <div class="flex">
                  <MenuButton class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                    表示順
                    <FunnelIcon class="ml-2 mt-0.5 h-4 w-5 flex-none text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
                  </MenuButton>
                </div>

                <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                  <MenuItems class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="py-1">
                      <MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
                        <a 
                          @click="changeSortKey(option.key)"
                          :class="[option.current ? 'font-medium text-gray-900' : 'text-gray-500', active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm']">{{ option.name }}</a>
                      </MenuItem>
                    </div>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
          </div>

        </div>

      </Disclosure>
  
      <!-- Pagination -->
      <div class="mx-auto max-w-8xl lg:px-8 md:flex flex-sm-col items-center justify-between border-gray-200 bg-white">

        <div class="hidden md:-mt-px md:flex pb-0">
          <span class="flex items-center h-10 bg-white hover:bg-gray-100 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-25 focus:ring-offset-1 focus:ring-offset-indigo-600">
            {{ !loading ? ((totalRowCount>0)? currentPage: 0) : '...' }} of {{ maxPageCount }}ページ ({{ totalRowCount }}件)
          </span>
        </div>

        <!-- <nav aria-label="Pagination" class="mx-auto mt-3 mb-2 flex justify-center ps-0 pr-2 text-sm font-medium text-gray-700 sm:px-1 lg:px-6"> -->
        <nav aria-label="Pagination" class="mx-auto mt-3 mb-2 flex justify-center text-sm font-medium text-gray-700">
            <div class="space-x-2 sm:flex">
            <!-- Current: "border-indigo-600 ring-1 ring-indigo-600", Default: "border-gray-300" -->

            <!-- 8ページ以上ある場合 -->
            <div v-if="!loading && maxPageCount>7" class="flex">

              <div 
                class="cursor-pointer mx-1 mt-1 inline-flex h-8 items-center rounded-md border border-gray-300 bg-white px-1 hover:bg-gray-100 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-25 focus:ring-offset-1 focus:ring-offset-indigo-600" 
                @click="nextPage( Number(currentPage) - 1 )">
                <ChevronLeftIcon class="h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
              </div>

              <!-- 現在ページが3以下、またはmaxpage-2以上の場合
                   1,2,3,...,max-2,max-1,maxでページングボタンを生成 -->
              <div v-if="(currentPage < 4) || ( currentPage > (maxPageCount - 3))">

                <div v-for="pageIndex in Array(3).fill().map((_, i) => i)" :key="_" 
                  class="mx-1 px-3 inline-flex h-8 items-center rounded-md border border-gray-300 px-3 hover:bg-gray-100 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-25 focus:ring-offset-1 focus:ring-offset-indigo-600"
                  :class="{'bg-yellow-50': !loading && ((pageIndex + 1) == currentPage)}"
                >

                  <span 
                    class="cursor-pointer" 
                    @click="nextPage(pageIndex + 1)"
                  >
                    {{ pageIndex + 1 }}
                  </span>

                </div>

                <span class="inline-flex h-10 items-center px-1.5 text-gray-500">...</span>

                <div v-for="pageIndex in Array(3).fill().map((_, i) => i)" :key="(maxPageCount-3) + pageIndex" 
                  class="mx-1 px-3 inline-flex h-8 items-center rounded-md border border-gray-300 px-3 hover:bg-gray-100 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-25 focus:ring-offset-1 focus:ring-offset-indigo-600"
                  :class="{'bg-yellow-50': !loading && (((maxPageCount-3) + pageIndex + 1) == currentPage)}"
                >

                  <span 
                    class="cursor-pointer" 
                    @click="nextPage((maxPageCount-3) + pageIndex + 1)"
                  >
                    {{ (maxPageCount-3) + pageIndex + 1 }}
                  </span>

                </div>                
                
              </div>
                           
              <!-- 現在ページが4以上、またはmaxpage-2未満の場合
                   1,..., 現在ページ-1,現在ページ,現在ページ+1 ,...,maxでページングボタンを生成 -->
              <div v-else>
                
                <div key="1" 
                  class="mx-1 px-3 inline-flex h-8 items-center rounded-md border border-gray-300 bg-white px-3 hover:bg-gray-100 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-25 focus:ring-offset-1 focus:ring-offset-indigo-600">
                  <span 
                    class="cursor-pointer" 
                    :class="{'bg-yellow-50': !loading && ((1) == currentPage)}"
                    @click="nextPage(1)">
                    1
                  </span>
                </div>
                <span class="inline-flex h-10 items-center px-1.5 text-gray-500">
                  ...
                </span>

                <div key="{{currentPage-1}}"
                  class="mx-1 px-3 inline-flex h-8 items-center rounded-md border border-gray-300 px-3 hover:bg-gray-100 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-25 focus:ring-offset-1 focus:ring-offset-indigo-600">
                  
                  <span 
                    class="cursor-pointer" 
                    @click="nextPage(currentPage - 1)">
                      {{ Number(currentPage)-1 }}
                  </span>
                </div>
                <div key="{{currentPage}}"
                  class="mx-1 px-3 inline-flex bg-yellow-50 h-8 items-center rounded-md border border-gray-300 px-3 hover:bg-gray-100 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-25 focus:ring-offset-1 focus:ring-offset-indigo-600">
                  <span>
                    {{ currentPage }}
                  </span>
                </div>
                <div key="{{currentPage+1}}"
                  class="mx-1 px-3 inline-flex h-8 items-center rounded-md border border-gray-300 px-3 hover:bg-gray-100 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-25 focus:ring-offset-1 focus:ring-offset-indigo-600">
                  <span 
                    class="cursor-pointer" 
                    @click="nextPage(Number(currentPage) +1)">
                      {{  Number(currentPage) +1 }}
                  </span>
                </div>

                <span class="inline-flex h-10 items-center px-1.5 text-gray-500">
                  ...
                </span>
                <div key="{{maxPageCount}}"
                  class="mx-1 px-3 inline-flex h-8 items-center rounded-md border border-gray-300 px-3 hover:bg-gray-100 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-25 focus:ring-offset-1 focus:ring-offset-indigo-600">
                  <span 
                    class="cursor-pointer" 
                    :class="{'bg-yellow-50': !loading && (maxPageCount == currentPage)}"
                    @click="nextPage(maxPageCount)">{{ maxPageCount }}
                  </span>
                </div>

              </div>
              
              <div 
                class="cursor-pointer mx-1  mt-1 inline-flex h-8 items-center rounded-md border border-gray-300 bg-white px-1 hover:bg-gray-100 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-25 focus:ring-offset-1 focus:ring-offset-indigo-600" 
                @click="nextPage( Number(currentPage) + 1 )">
                <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
              </div>

            </div>

            <!-- 7ページ以下の場合 -->
            <div v-else-if="!loading">
              <!-- 1,2,3...,7でページングボタンを生成 -->
              <div v-for="pageIndex in Array(maxPageCount).fill().map((_, i) => i)" :key="_" 
                class="mx-1 px-3 inline-flex h-8 items-center rounded-md border border-gray-300 px-3 hover:bg-gray-100 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-25 focus:ring-offset-1 focus:ring-offset-indigo-600"
                :class="{'bg-yellow-50': !loading && ((pageIndex + 1) == currentPage)}"
              >

                <span 
                  class="cursor-pointer text-gray-800" 
                  @click="nextPage(pageIndex + 1)"
                >
                  {{ pageIndex + 1 }}
                </span>
                
                <!-- <span class="text-rose-300">
                  {{ pageIndex + 1 }} : {{ currentPage }}
                </span> -->

              </div>
            </div>

          </div>

        </nav>

      </div>

      <div class="block md:hidden">
        <div class="pb-0 ps-1">
          <span class="flex tems-start h-8 bg-white ms-4 ps-5 hover:bg-gray-100 focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-opacity-25 focus:ring-offset-1 focus:ring-offset-indigo-600">
            {{ !loading ? ((totalRowCount>0)? currentPage: 0) : '...' }} of {{ maxPageCount }}ページ ({{ totalRowCount }}件)
          </span>
        </div>
      </div>

      <!-- Product grid -->
      <section aria-labelledby="products-heading" class="mx-auto max-w-8xl overflow-hidden sm:px-4 lg:px-4">
        <h2 id="products-heading" class="sr-only">Products</h2>

        <div v-if="loading" class="loading-icon">
          <!-- Replace with your loading icon -->
          <span>Loading...</span>
        </div>

        <div v-else>
          <div class="-mx-px grid grid-cols-2 border-l border-gray-200 sm:mx-0 md:grid-cols-3 lg:grid-cols-5">
          <div v-for="product in resultProducts" :key="product.id" class="group relative border-t border-b border-r border-gray-200 p-4 sm:p-4">
            <div class="aspect-h-1 aspect-w-1 overflow-hidden rounded-lg bg-gray-200 group-hover:opacity-75">
              <img :src="`${ product.url_thumb }`" :alt="product.imageAlt" class="h-full w-full object-cover object-center" />
            </div>
            <div class="divide-y pb-4 pt-10 text-center">
              <!-- <h3 class="text-sm font-medium text-gray-900">
                <span aria-hidden="true" class="absolute inset-0" />
                {{ product.商品名 }}
              </h3> -->

              <div class="mt-1 flex flex-col">
                <div class="text-sm text-gray-700 flex flex-row">
                  <div class="text-sm text-gray-700 font-semibold min-w-[80px] border-r">タイトル</div>
                  <div class="text-center w-full">
                    {{ product.title }}
                  </div>
                </div>
              </div>
              <div class="mt-1 flex flex-col">
                <div class="text-sm text-gray-700 flex flex-row">
                  <div class="text-sm text-gray-700 font-semibold min-w-[80px] border-r">都道府県</div>
                  <div class="text-center w-full">
                    {{ product.pref }}
                  </div>
                </div>
              </div>
              <div class="mt-1 flex flex-col">
                <div class="text-sm text-gray-700 flex flex-row">
                  <div class="text-sm text-gray-700 font-semibold min-w-[80px] border-r">撮影者</div>
                  <div class="text-center w-full">
                    {{ product.author }}
                  </div>
                </div>
              </div>
              <div class="mt-1 flex flex-col">
                <div class="text-sm text-gray-700 flex flex-row">
                  <div class="text-sm text-gray-700 font-semibold min-w-[80px] border-r">カメラ</div>
                  <div class="text-center w-full">
                    {{ product.camera }}
                  </div>
                </div>
              </div>


            </div>
          </div>
        </div>

        </div>

        
      </section>


    </main>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { apiUrl, apiUrlSuffix } from '~/env';
import {
  Dialog,
  DialogPanel,
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
  Popover,
  PopoverButton,
  PopoverGroup,
  PopoverPanel,
  Tab,
  TabGroup,
  TabList,
  TabPanel,
  TabPanels,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import { Bars3Icon, MagnifyingGlassIcon, ShoppingBagIcon, UserIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { ChevronDownIcon, FunnelIcon, StarIcon, ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'
import { ChevronUpIcon } from '@heroicons/vue/20/solid'

import { useSearchConditionStore } from '../stores/searchCondition'
const store = useSearchConditionStore()

// console.log('searchCondition 🍍', store.count)


const route = useRoute();
const router = useRouter();
const makerName = ref('');

// search
const resultProducts = ref([]);
const loading = ref(true);
const error = ref(null);
// const currentPage = ref(1);
const currentPage = ref(null);
const totalRowCount = ref(0);
const maxPageCount = ref(0);
const rowCountPerPage = 50;

const open = ref(false)
const searchPanelOpen = ref(true)

const noFilter = ref('')
const skuFilter = ref('')
const nameFilter = ref('')
const sizeFilter = ref('')
const colorFilter = ref('')
const brandNameFilter = ref('')

// const sortKey = ref('1')
const sortOptions = ref([
  { name: '登録日（昇順）', key: '0', current: false },
  { name: '登録日（降順）', key: '1', current: true },
  { name: '商品番号（昇順）', key: '2', current: false },
  { name: '商品番号（降順）', key: '3', current: false },
])

// Flag to prevent recursive updates
let isUpdating = false;

const fetchProducts = async () => {

  const queryParams = route.query;
  // console.log('Query Parameters:', queryParams?.makers);  
  // console.log('store.searchCondition.keyValueObject:', store.searchCondition.keyValueObject);
  let prefectures = queryParams?.prefectures;

  if (!prefectures) {
    
    prefectures = '';
    // store内も検索
    if (store.searchCondition.keyValueObject) {
      Object.entries(store.searchCondition.keyValueObject).forEach(([key, value]) => {
        //console.log(`${key}: ${value}`);
        prefectures += `${key},`;
      });

      if ((prefectures) && prefectures.length > 0) {
        prefectures = prefectures.slice(0, -1);
      }

    }

  }
  
  let genres = queryParams?.genre;
  if (!genres) {
    
    genres = '';
    // store内も検索
    if (store.searchCondition.genres) {
      Object.entries(store.searchCondition.genres).forEach(([key, value]) => {
        //console.log(`${key}: ${value}`);
        genres += `${value},`;
      });

      if ((genres) && genres.length > 0) {
        genres = genres.slice(0, -1);
      }
    }

  }

  const sortKey = computed(() => {
    return sortOptions.value.find(option => option.current === true).key;
  });

  // return sortOptions.find(option => option.key === selectedSortKey.value);

  // console.log('makers:', makers);
  // console.log('sortKey:', sortKey.value);

  let page = queryParams?.page;
  if (!page) {
    page = 1;
  }
  try {
    // const response = await useFetch(`${apiUrl + apiUrlSuffix}api/users`)
    // const response = await useFetch(`https://mychat.tanoshimi.dev/backend/public/api/users`)
    loading.value = true;

    // console.log('url1=>', 'http://localhost:40811/api/search_test?maker=' + maker + '&page=' + page)
    // console.log('url2=>', `${apiUrl + apiUrlSuffix}search_test?maker=${maker}&page=${page}`)

    //const { data, pending, error: fetchError } = await useFetch('http://localhost:40811/api/search_test?maker=' + maker + '&page=' + page);
    // const { data, pending, error: fetchError } = await useFetch('https://mychat.tanoshimi.dev/backend/public/api/users');
    
    const { data, pending, error: fetchError } = await useFetch(
    // const { data, pending, error: fetchError } = await $fetch(
      `${apiUrl + apiUrlSuffix}photos?prefectures=${prefectures}&page=${page}`
      + `&no=${noFilter.value}`
      + `&sku=${skuFilter.value}`
      + `&name=${nameFilter.value}`
      + `&size=${sizeFilter.value}`
      + `&color=${colorFilter.value}`
      + `&brand_name=${brandNameFilter.value}`
      + `&sort=${sortKey.value}`
      + `&genres=${genres}`
    );

console.log(
  `${apiUrl + apiUrlSuffix}photos?prefectures=${prefectures}&page=${page}`
      + `&no=${noFilter.value}`
      + `&sku=${skuFilter.value}`
      + `&name=${nameFilter.value}`
      + `&size=${sizeFilter.value}`
      + `&color=${colorFilter.value}`
      + `&brand_name=${brandNameFilter.value}`
      + `&sort=${sortKey.value}`
      + `&genres=${genres}`
)

    // console.log(data.value.data)
    // console.log(data.value.current_page)
    // console.log(data.value.total_row_count)
    makerName.value = data.value.maker_name;
    resultProducts.value = data.value.data;
    currentPage.value = data.value.current_page;
    totalRowCount.value = data.value.total_row_count;
    maxPageCount.value = Math.floor(totalRowCount.value/rowCountPerPage) + (totalRowCount.value%rowCountPerPage>0? 1:0);

    //console.log(`maxPageCount=${maxPageCount.value} totalRowCount=${totalRowCount.value} rowCountPerPage=${rowCountPerPage}`)

    error.value = fetchError.value;
    loading.value = pending.value;
  } catch (err) {
    error.value = err;
    loading.value = false;
  }
};

const formatColorVariations = (colorVariations) => {
  return (colorVariations) ? colorVariations.replace(/\n/g, '<br>') : '';
};

const clearColorFilter = () => {
  // noFilter.value = '';
  // skuFilter.value = '';
  // nameFilter.value = '';
  // sizeFilter.value = '';
  // colorFilter.value = '';
  window.location.href = '/';
};

onMounted(() => {
  fetchProducts();
});

const nextPage = (nextPage) => {

  //console.log(`nextPage=${nextPage}`);

  isUpdating = true;
  if (maxPageCount.value < nextPage) {
    nextPage = maxPageCount.value;
    //console.log(`1 nextPage=${nextPage}`);

  } else if (1 > nextPage) {
    nextPage = 1;
    //console.log(`2 nextPage=${nextPage}`);
  }

  currentPage.value = nextPage;
  isUpdating = false;

  const queryParams = route.query;
  let prefectures = queryParams?.prefectures;

  if (!prefectures) {
    
    prefectures = '';
    // store内も検索
    if (store.searchCondition.keyValueObject) {
      Object.entries(store.searchCondition.keyValueObject).forEach(([key, value]) => {
        //console.log(`${key}: ${value}`);
        prefectures += `${key},`;
      });

      if ((prefectures) && prefectures.length > 0) {
        prefectures = prefectures.slice(0, -1);
      }
    }

  }

  let genres = queryParams?.genre;
  if (!genres) {
    
    genres = '';
    // store内も検索
    if (store.searchCondition.genres) {
      Object.entries(store.searchCondition.genres).forEach(([key, value]) => {
        //console.log(`${key}: ${value}`);
        genres += `${value},`;
      });

      if ((genres) && genres.length > 0) {
        genres = genres.slice(0, -1);
      }
    }

  }

  const sortKey = computed(() => {
    return sortOptions.value.find(option => option.current === true).key;
  });

  //console.log(`next router => /products?makers=${makers}&page=${nextPage}`)
  // router.push(`/products?makers=${makers}&genres=${genres}&page=${nextPage}`
  router.push(`/?prefectures=${prefectures}&page=${nextPage}`
      + `&no=${noFilter.value}`
      + `&sku=${skuFilter.value}`
      + `&name=${nameFilter.value}`
      + `&size=${sizeFilter.value}`
      + `&color=${colorFilter.value}`
      + `&brand_name=${brandNameFilter.value}`
      + `&sort=${sortKey.value}`
      + `&genres=${genres}`
  );

};

const changeSortKey = (sortKey) => {
  //console.log(`sortKey=${sortKey}`);
  sortOptions.value.forEach(option => {
    // if (option.key == sortKey) {
    //   option.current = true;
    // } else {
    //   option.current = false;
    // }
    option.current = (option.key == sortKey);
  });
  fetchProducts();
};

const keyValueKeys = computed(() => {
  return Array.from(store.searchCondition.keyValueObject.keys());
});

const search = () => {

  //console.log(`☆彡　search called`);

  isUpdating = true;
  currentPage.value = 1;
  isUpdating = false;

  //console.log(store.searchCondition.keyValueObject)
  let prefectures = '';
  Object.entries(store.searchCondition.keyValueObject).forEach(([key, value]) => {
    //console.log(`${key}: ${value}`);
    prefectures += `${key},`;
  });

  if (prefectures.length > 0) {
    prefectures = prefectures.slice(0, -1);
  }

  let genres = '';
  Object.entries(store.searchCondition.genres).forEach(([key, value]) => {
    //console.log(`${key}: ${value}`);
    genres += `${value},`;
  });

  if (genres.length > 0) {
    genres = genres.slice(0, -1);
  }
  const sortKey = computed(() => {
    return sortOptions.value.find(option => option.current === true).key;
  });
  //console.log(`makers=${makers}`);

  // console.log(`next router => /products?makers=${makers}&page=${currentPage.value}`)
  // router.push(`/products?makers=${makers}&page=${currentPage.value}`);
  /*
  console.log(`/products?makers=${makers}&page=${currentPage.value}`
      + `&no=${noFilter.value}`
      + `&sku=${skuFilter.value}`
      + `&name=${nameFilter.value}`
      + `&size=${sizeFilter.value}`
      + `&color=${colorFilter.value}`
      + `&sort=${sortKey.value}`
  );
  */

  // router.push(`/products?makers=${makers}&page=${currentPage.value}`
  router.push(`/?prefectures=${prefectures}&page=${currentPage.value}`
      + `&no=${noFilter.value}`
      + `&sku=${skuFilter.value}`
      + `&name=${nameFilter.value}`
      + `&size=${sizeFilter.value}`
      + `&color=${colorFilter.value}`  
      + `&brand_name=${brandNameFilter.value}`
      + `&sort=${sortKey.value}`
      + `&genres=${genres}`
  );
  

};

// const getPrevNextPage = (nextPage) => {
  
//   const queryParams = route.query;
//   const maker = queryParams?.maker;
//   console.log('Query Parameters:', queryParams?.maker);  
  
//   isUpdating = true;
//   currentPage.value = nextPage;
//   isUpdating = false;
  
//   return `/products?maker=${maker}&page=${nextPage}`;

// };

/*
watch(
  () => route.query.maker,
  (newMaker, oldMaker) => {

    console.log('Query Parameters:', newMaker, oldMaker);
    if (newMaker !== oldMaker) {
      fetchProducts();
    }
  }
);
*/

// watch(
//   [() => route.query.maker, currentPage],
//   ([newMaker, newPage], [oldMaker, oldPage]) => {
// //    console.log('Query Parameters:', newMaker, oldMaker);
// //    console.log(`Page changed from ${oldPage} to ${newPage}`);
//     if (newMaker !== oldMaker || newPage !== oldPage) {
//       fetchProducts();
//     }
//   }
// );

watch(
  [() => route.query.prefectures, currentPage],
  ([newPrefecture, newPage], [oldPrefecture, oldPage]) => {
//    console.log('Query Parameters:', newMaker, oldMaker);
//    console.log(`Page changed from ${oldPage} to ${newPage}`);
    if (newPrefecture !== oldPrefecture || newPage !== oldPage) {
      fetchProducts();
    }
  }
);

</script>

<style scoped>
.loading-icon {
  /* Add your loading icon styles here */
  font-size: 24px;
  text-align: center;
}
.text-wrap {
  word-wrap: break-word;
  word-break: break-all;
  white-space: normal;
}
</style>