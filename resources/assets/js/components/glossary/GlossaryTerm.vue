<template>
  <div id="definition">
    <h2>{{ termData.name }}</h2>
    <div class="definition" v-html="termData.definition"></div>
    <div v-if="termData.relationships && termData.relationships.data.length"
        class="glossary-relationships">
      <h4>Relationships</h4>
      <div class="row" v-for="rel in termData.relationships.data">
        <div class="col-xs-6 col-md-8">
          <span class="glossary-rel-type">{{ rel.relationshipType.label }}</span>
        </div>
        <div class="col-xs-6 col-md-4 text-right">
          <router-link :to="{ name: 'glossary', hash: '#' + encodeURIComponent(rel.relatedTerm.name) }">{{ rel.relatedTerm.name }}</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    'term'
  ],
  data() {
    return {
      termData: {}
    }
  },
  methods: {
    getTerm() {
      axios.get(`${process.env.MIX_GLOSSARY_URL}/terms/${this.term}`)
          .then(response => {
            this.termData = response.data
          })
    },
    findTerm() {
      axios.get(`${process.env.MIX_GLOSSARY_URL}/search`, {params: {
          'term': this.$route.hash.substr(1),
          'include': 'relationships'
        }
      }).then(
        response => {
          if (response.data && response.data.data.length) {
            this.termData = response.data.data[0]
          }
        }
      )
    }
  },
  watch: {
    '$route.hash': function() {
      if (this.$route.hash.length > 2) {
        this.findTerm()
      }
    }
  }
}
</script>
