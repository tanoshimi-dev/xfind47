// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  ssr: false,
  devtools: { enabled: true },
  css: ['~/assets/css/main.css'],
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },
  plugins: ['~/plugins/pinia.js'],
  app: {
//    baseURL: '/demo/okinawa_trade/',
    baseURL: '/',
  },
  router: {},
  //  middleware: 'redirect'
})
