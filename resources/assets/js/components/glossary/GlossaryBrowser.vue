<template>
  <div id="glossary-terms">
    <div id="glossary-first-letter">
        <router-link
            v-for="letter in alpha" :key="letter"
            :class="letter === firstLetter ? 'active' : false"
            :to="{ name: 'glossary', hash: '#' + letter }"
        >{{ letter.toUpperCase() }}</router-link>
    </div>
    <glossary-term-list></glossary-term-list>
  </div>
</template>

<script>
import GlossaryTermList from './GlossaryTermList'

export default {
  components: {
    GlossaryTermList
  },
  data() {
    return {
      alpha: this.generateAlphaArray(),
      firstLetter: 'a'
    }
  },
  methods: {
    generateAlphaArray() {
      var alpha = [], i = 'a'.charCodeAt(0), j = 'z'.charCodeAt(0)
      for (i; i <= j; ++i) {
          alpha.push(String.fromCharCode(i))
      }
      return alpha
    }
  },
  mounted() {
    if (this.$route.hash) {
      this.firstLetter = this.$route.hash.substr(1, 1)
    }
  },
  watch: {
    '$route.hash': function() {
      this.firstLetter = this.$route.hash.substr(1, 1)
    }
  }
}

</script>

<style>
#glossary-terms .letter {
  cursor: default;
}
</style>
