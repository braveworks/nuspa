// 参考 https://ja.nuxtjs.org/guide/configuration

// import WPAPI from 'wpapi'
import pkg from './package'

const WP_SITE = 'http://nuxtwp.local'
const WP_API = '/wp-json'
const isDev = process.env.NODE_ENV !== 'production'
// const wp = new WPAPI({ endpoint: `${WP_SITE}${WP_API}` })

export default {
  mode: 'spa',
  // mode: 'universal',

  srcDir: 'src/',

  /*
   ** dev proxy
   */
  proxy: { WP_API: isDev ? WP_SITE : '/' },

  /*
   ** Headers of the page
   */
  head: {
    title: pkg.name,
    meta: [
      { charset: 'utf-8' },
      {
        name: 'viewport',
        content: 'width=device-width, initial-scale=1'
      }
      // {
      //   hid: 'description',
      //   name: 'description',
      //   content: pkg.description
      // }
    ]
    // link: [
    //   {
    //     rel: 'icon',
    //     type: 'image/x-icon',
    //     href: '/favicon.ico'
    //   }
    // ]
  },

  /*
   ** Customize the progress-bar color
   */
  loading: { color: '#42B883' },

  /*
   ** Global CSS
   */
  css: [],

  /*
   ** Plugins to load before mounting the App
   */
  plugins: [],

  /*
   ** Nuxt.js modules
   */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    '@nuxtjs/proxy',
    ['wp-nuxt', { endpoint: `${WP_SITE}${WP_API}` }]
  ],
  /*
   ** Axios module configuration
   */
  axios: {
    // See https://github.com/nuxt-community/axios-module#options
  },

  router: {
    extendRoutes(routes, resolve) {
      routes.push({
        name: 'posts',
        path: '/:id',
        component: resolve(__dirname, 'src/pages/index.vue')
      })
    }
  },

  /*
   ** Build configuration
   */
  build: {
    /*
     **  output filename
     */
    filenames: {
      app: ({ isDev }) => (isDev ? '[name].js' : '[name].[chunkhash].js'),
      chunk: ({ isDev }) => (isDev ? '[name].js' : '[name].[chunkhash].js'),
      css: ({ isDev }) => (isDev ? '[name].js' : '[name].[contenthash].css'),
      img: ({ isDev }) =>
        isDev ? '[path][name].[ext]' : 'img/[name].[hash:7].[ext]',
      font: ({ isDev }) =>
        isDev ? '[path][name].[ext]' : 'fonts/[hash:7].[ext]',
      video: ({ isDev }) =>
        isDev ? '[path][name].[ext]' : 'videos/[hash:7].[ext]'
    },
    /*
     ** You can extend webpack config here
     */
    extend(config, ctx) {
      if (!ctx.isDev) {
        config.output.publicPath = '/wp-content/themes/nuxt-only/dist/_nuxt/'
      }
      // Run ESLint on save
      if (ctx.isDev && ctx.isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/
        })
      }
    }
  }

  // generate: {
  //   interval: 300,
  //   routes() {
  //     return Promise.all([
  //       wp
  //         .posts()
  //         .perPage(100)
  //         .page(1)
  //         .embed(1),
  //       wp
  //         .pages()
  //         .perPage(100)
  //         .page(1)
  //         .embed(1)
  //     ]).then(data => {
  //       const posts = data[0]
  //       const pages = data[1]
  //       return posts
  //         .map(post => {
  //           return {
  //             route: '/posts/' + post.id,
  //             payload: post
  //           }
  //         })
  //         .concat(
  //           pages.map(page => {
  //             return {
  //               route: page.slug,
  //               payload: page
  //             }
  //           })
  //         )
  //     })
  //   }
  // }
}
