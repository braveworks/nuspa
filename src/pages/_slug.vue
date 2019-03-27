<template lang="pug">
section
  div.container
    article(v-for="item in article")
      h1.title {{ item.title.rendered }}
      //- h2.subtitle My fantabulous Nuxt.js project
      time {{ item.date.slice(0, 10) }}
      div(v-html="item.content.rendered")
    p
      nuxt-link(to="/")
        | index
</template>

<script>
const ERROR_STATUS = { statusCode: 404, message: 'ページが見つかりません' }
export default {
  data() {
    return { article: '' }
  },
  head() {
    return {
      title: this.title,
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: this.article[0].yoast_wpseo_metadesc || ''
        }
      ]
    }
  },
  async asyncData({ app, params, error, payload }) {
    const slug = params.slug
    const article = await app.$wp
      .pages()
      .slug(slug)
      .then(page => (page.length ? page : error(ERROR_STATUS)))
      .catch(e => error(ERROR_STATUS))
    return { article }
  }
}
</script>

<style>
.container {
  margin: 0 auto;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  max-width: 960px;
}

.title {
  font-family: 'Quicksand', 'Source Sans Pro', -apple-system, BlinkMacSystemFont,
    'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  display: block;
  font-weight: 300;
  font-size: 30px;
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
