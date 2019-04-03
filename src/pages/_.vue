<template lang="pug">
section
  div.container
    article(v-for="item in article")
      h1.title {{ item.title.rendered }}
      time {{ item.date.slice(0, 10) }}
      div(v-html="item.content.rendered")
    p
      nuxt-link(to="/")
        | index
</template>

<script>
import error404 from '~/lib/error404'

export default {
  data() {
    return { article: '' }
  },
  head() {
    return {
      title: this.article[0].title.rendered,
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
    const path = params.pathMatch.split('/')
    const slug = path[path.length - 2]
    const article = await app.$wp
      .pages()
      .slug(slug)
      .then(page => (page.length ? page : error(error404)))
      .catch(e => error(error404))
    return { article }
  }
}
</script>
