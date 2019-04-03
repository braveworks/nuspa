<template lang="pug">
section.container
  div
    logo
    h1.title
      | nuxt-only
    h2.subtitle
      | My finest Nuxt.js project
    .links
      a.button--green(href="https://nuxtjs.org/" target="_blank")
        | Documentation
      a.button--grey(href="https://github.com/nuxt/nuxt.js" target="_blank")
        | GitHub
    .row
      .col
        article.article(v-for="page in pages")
          h1.title
            nuxt-link(:to="`${removeDomain(page.link)}`")
              | {{ page.title.rendered }}
          time {{ page.date.slice(0, 10) }}
      .col
        article.article(v-for="post in posts")
          h1.title
            nuxt-link(:to="{ path: `/posts/${post.id}/`, params: { id: post.id } }")
              | {{ post.title.rendered }}
          time {{ post.date.slice(0, 10) }}
</template>

<script>
import Logo from '~/components/Logo.vue'

export default {
  components: { Logo },
  async asyncData({ app, params, error, payload }) {
    // const pages = await app.$wp
    //   .pages()
    //   .perPage(100)
    //   .then(page => (page.length ? page : error(error404)))
    //   .catch(e => error(error404))
    // const posts = await app.$wp
    //   .posts()
    //   .perPage(100)
    //   .then(page => (page.length ? page : error(error404)))
    //   .catch(e => error(error404))

    const article = await Promise.all([
      app.$wp.pages().perPage(100),
      app.$wp.posts().perPage(100)
    ]).then(data => {
      const pages = data[0]
      const posts = data[1]
      return { pages, posts }
    })

    return { pages: article.pages, posts: article.posts }
  },
  methods: {
    removeDomain(link) {
      if (!link) {
        return ''
      }
      const text = new URL(link)
      return text.pathname || '/'
    }
  }
}
</script>

<style>
.container {
  margin: 0 auto;
  min-height: 100vh;
  max-width: 960px;
  display: flex;
  justify-content: center;
  align-items: center;
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

.article {
  padding-top: 1rem;
  padding-bottom: 1rem;
}

.row {
  display: flex;
  flex-wrap: wrap;
}

.col {
  flex: 0 0 50%;
  max-width: 50%;
}
</style>
