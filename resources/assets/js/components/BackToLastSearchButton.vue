<template>
  <div v-if="query" class="back-link pull-right">
    <span>
      <router-link
        :to="{ name: 'search', query: query }"
        class="btn btn-primary btn-sm"
      >
        Back to last search result
      </router-link>
    </span>
  </div>
</template>

<script>
  import { mapState } from 'vuex'

  export default {
    computed: {
      ...mapState({
        query: state => state.searchResult.meta.params
      })
    }
  }
</script>
