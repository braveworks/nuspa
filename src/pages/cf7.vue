<template lang="pug">
section.container
  div
    h1.title cf7 test
    h2.subtitle My fantabulous Nuxt.js project
    article
      section(v-for="item in info")
        h3 {{ item.title.rendered }}
        time {{ item.date.slice(0, 10) }}
        div(v-html="item.content.rendered")
    p
      nuxt-link(to="/")
        | index
</template>

<script>
import axios from 'axios'

export default {
  components: {},
  props: {
    theUrl: {
      type: String,
      default: `/wp-json/wp/v2/pages/?slug=cf7`
    }
  },
  data() {
    return {
      cf7: 'cf7'
      // info: ''
    }
  },
  async asyncData({ app, store, params, route, payload }) {
    const { data } = await axios.get(`/wp-json/wp/v2/pages/?slug=cf7`)
    console.log(data)
    return { info: data }
  },
  mounted() {}
}
</script>

<style>
.container {
  margin: 0 auto;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.title {
  font-family: 'Quicksand', 'Source Sans Pro', -apple-system, BlinkMacSystemFont,
    'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  display: block;
  font-weight: 300;
  font-size: 100px;
  color: #35495e;
  letter-spacing: 1px;
}

.subtitle {
  font-weight: 300;
  font-size: 42px;
  color: #526488;
  word-spacing: 5px;
  padding-bottom: 15px;
}

.links {
  padding-top: 15px;
}
</style>
