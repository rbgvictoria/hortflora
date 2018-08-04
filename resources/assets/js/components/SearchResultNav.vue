<template>
  <div class="query-result-nav text-right">
    <ul class="pagination">
      <li :class="paginationLinks.prev.classes">
        <router-link :to="{
          name: 'search',
          query: paginationLinks.prev.query }"
        >Previous</router-link>
      </li>
      <li :class="paginationLinks.first.classes">
        <router-link :to="{
          name: 'search',
          query: paginationLinks.first.query }"
        >{{ paginationLinks.first.label }}</router-link>
      </li>
      <li v-for="page in paginationLinks.pages" :class="page.classes">
        <router-link :to="{ name: 'search', query: page.query }">{{ page.label }}</router-link>
      </li>
      <li :class="paginationLinks.last.classes">
        <router-link :to="{
          name: 'search',
          query: paginationLinks.last.query }"
        >{{ paginationLinks.last.label }}</router-link>
      </li>
      <li :class="paginationLinks.next.classes">
        <router-link :to="{ name: 'search',
          query: paginationLinks.next.query }"
        >Next</router-link>
      </li>
    </ul>
  </div>
</template>

<script>

  export default {
    props: {
      paginationLinks: {
        type: Object,
        required: true
      }
    }
  }
</script>
