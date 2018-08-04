<template>
  <div class="query-result-header">
    <div class="num-matches">
      Showing <b>{{ numMatches.first }}</b> to <b>{{ numMatches.last }}</b> of <b>{{ numMatches.total }}</b> matches
    </div>
  </div>
</template>

<script>
  import { mapState } from 'vuex'

  export default {
    computed: {
      ...mapState({
        numMatches(state) {
          return this.getNumMatches(state.searchResult.meta.pagination)
        }
      })
    },
    methods: {
      getNumMatches(pagination) {
        return {
          total: pagination.total,
          first: ((pagination.current_page-1) * pagination.per_page) +1,
          last: (pagination.current_page * pagination.per_page < pagination.total)
            ? pagination.current_page * pagination.per_page : pagination.total
        }
      }
    }
  }
</script>
