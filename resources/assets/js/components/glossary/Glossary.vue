<template>
  <div class="container">
      <div class="row">

          <div class="col-lg-12">
              <h1>Glossary</h1>
          </div>


          <div class="col-sm-4 col-md-3">
            <glossary-browser @term-selected="onTermSelected"></glossary-browser>
          </div>

          <div class="col-sm-8 col-md-9">
              <glossary-term :term="selectedTerm"></glossary-term>
          </div>
      </div>
  </div> <!-- /.container -->

</template>

<script>
import GlossaryBrowser from './GlossaryBrowser'
import GlossaryTerm from './GlossaryTerm'

export default {
  components: {
    GlossaryBrowser,
    GlossaryTerm
  },
  data() {
    return {
      selectedTerm: null
    }
  },
  methods: {
    onTermSelected(term) {
      this.selectedTerm = term
    }
  }
}
</script>
