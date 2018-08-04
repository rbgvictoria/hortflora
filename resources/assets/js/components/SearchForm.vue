<template>
  <div class="search-form form-horizontal">
    <div class="input-group">
      <auto-complete
        :source="source"
        placeholder="Enter taxon name..."
        classes="form-control"
        @select="getSelected"
        @input="onInput"
        @change="search"
        v-once></auto-complete>
      <span class="input-group-addon" @click="search">
        <i class="fa fa-search fa-lg"></i>
      </span>
    </div>
  </div>
</template>

<script>

  import AutoComplete from './widgets/AutoComplete'


  export default {
    components: {
      AutoComplete
    },
    data() {
      return {
        source: '/api/autocomplete/name',
        model: null,
        selected: null
      }
    },
    methods: {
      onInput(val) {
        this.selected = null
        this.model = val
      },
      getSelected(item) {
        this.selected = item.value
        this.model = item.value
      },
      search() {
          let query = {}
          if (this.model) {
              if (this.model.indexOf(':') < 0) {
                  if (this.model.indexOf(' ') > -1) {
                      query.q = `"${ this.model }"`
                  }
                  else {
                      query.q = `${ this.model }*`
                  }
              }
              else {
                  query.q = this.model
              }
          }
          else {
              query.q = '*:*'
          }
          if (typeof this.$store.state.searchResult !== 'undefined'
              && this.$store.state.searchResult) {
            let params = this.$store.state.searchResult.meta.params
            if (params.rows) {
                query.rows = params.rows;
            }
          }
          this.$router.push({ name: 'search', query: query })
      }
    }
  }

</script>

<style>
  .search-form .input-group-addon {
    cursor: pointer;
  }
</style>
