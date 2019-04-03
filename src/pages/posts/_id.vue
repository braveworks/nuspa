<template lang="pug">
section
  div.container
    article
      h1.title {{ article.title.rendered }}
      time {{ article.date.slice(0, 10) }}
      div(v-html="article.content.rendered")
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
      title: this.article.title.rendered,
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: this.article.yoast_wpseo_metadesc || ''
        }
      ]
    }
  },
  async asyncData({ app, params, error, payload }) {
    const article = await app.$wp
      .posts()
      .id(params.id)
      .then(post => {
        console.log(post.id)
        return post || error(error404)
      })
      .catch(e => error(error404))

    return { article }
  }
}
</script>
