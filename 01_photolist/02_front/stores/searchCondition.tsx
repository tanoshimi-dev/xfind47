import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useSearchConditionStore = defineStore({
  id: 'searchCondition',
  state: () => ({
    count: ref(1),
    searchCondition: {
      keyword: '',
      category: '',
      prefectures: [] as string[],
      prefectureFiltered: [] as string[],
      keyValueObject: {} as { [key: string]: string },
      genres: [] as string[],
      brand: '',
      sort: '',
    },
  }),
  actions: {
    setSearchCondition(condition: any) {
      //this.searchCondition = condition
    },
    toggleSearchPref(pref_code: string, pref: string) {

      console.log('onOfSearchPref called ', pref_code)
      const prefectures = this.searchCondition.prefectures
      const index = prefectures.indexOf(pref_code)
      if (index === -1) {
        prefectures.push(pref_code)
      } else {
        prefectures.splice(index, 1)
      }

      console.log('exist => ', pref_code in this.searchCondition.keyValueObject)

      if(pref_code in this.searchCondition.keyValueObject) {
        delete this.searchCondition.keyValueObject[pref_code]
      } else {
        this.searchCondition.keyValueObject[pref_code] = pref
      }

      console.log(this.searchCondition.keyValueObject)
      console.log(this.searchCondition.prefectures)
    },
    toggleSearchGenre(genre: string) {

      console.log('onOfSearchCategory called ', genre)
      console.log(this.searchCondition.genres)

      let newGenres = [];
      if (this.searchCondition.genres.includes(genre)) {
        // Remove category
        newGenres = this.searchCondition.genres.filter((g) => g !== genre);
      } else {
        // Add category
        newGenres = [...this.searchCondition.genres, genre];
      }
      this.searchCondition.genres = newGenres

      console.log(this.searchCondition.genres)
    }    
  },

  // filteredMakers(maker: string) {
  //   // Computed property to filter the makers array
  //   console.log(`filteredMakers called ${maker}`)
  //   this.searchCondition.makersFiltered = this.searchCondition.makersFiltered.filter((maker) =>
  //     this.searchCondition.makersFiltered.メーカー名.toLowerCase().includes(this.searchQuery.toLowerCase())
  //   );
  // },
  

})