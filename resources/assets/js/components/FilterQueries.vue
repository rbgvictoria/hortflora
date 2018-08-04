<template>
  <div class="query">
    <h3>Query</h3>
    <div class="content">
      <div class="query-term">
        <span class="h4">Query term:</span> {{ params.q }}
      </div>
      <div v-if="activeFilters.length">
        <h4>Filter queries</h4>
        <ul>
          <li v-for="filter in activeFilters">
            <b>{{ filter.label }}:</b> {{ filter.value }}
            <router-link :to="{ name: 'search', query: filter.query }">
              <i class="fa fa-times"></i>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  import { parseFq } from '../helpers/helper.js'

  export default {
    computed: {
      ...mapState({
        params: (state) => state.searchResult.meta.params,
        activeFilters(state) {
          return this.getActiveFilters(state.searchResult.meta.params)
        }
      })
    },
    methods: {
      getActiveFilters(params) {
        if (params.fq) {
          if (typeof params.fq === 'string') {
            params.fq = [params.fq]
          }
          delete(params.page);
          return params.fq.map(item => {
            let parsed = parseFq(item)
            let qry = Object.assign({}, params)
            qry.fq = params.fq.filter(fq => fq !== item);
            return {
                label: parsed.label,
                value: parsed.value,
                query: qry
            }
          })
        }
        else {
            return []
        }
      }
    }
  }
</script>
