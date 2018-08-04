<template>
  <div v-if="isAccepted" class="vicflora-tab">
    <ul class="nav nav-tabs" role="tab-list">
      <li v-if="taxon.currentTreatment" role="presentation">
        <a href="#treatment" aria-controls="treatment" role="tab" data-toggle="tab">Treatment</a>
      </li>
      <li v-if="hasImages" role="presentation">
        <a href="#images" aria-controls="images" role="tab" data-toggle="tab">Images</a>
      </li>
      <li v-if="taxon.cultivars  && taxon.cultivars.data.length > 0" role="presentation">
        <a href="#cultivars" aria-controls="cultivars" role="tab" data-toggle="tab">Cultivars</a>
      </li>
      <li role="presentation">
        <a href="#classification" aria-controls="classification" role="tab" data-toggle="tab">Classification</a>
      </li>
    </ul>
    <div class="tab-content">
      <div v-if="taxon.currentTreatment" id="treatment" class="tab-pane" role="tab-panel">
        <taxon-treatment></taxon-treatment>
      </div>
      <images v-if="hasImages" :key="$route.params.taxon"></images>
      <div v-if="taxon.cultivars" id="cultivars" class="tab-pane" role="tab-panel">
        <cultivars-tab></cultivars-tab>
      </div>
      <div id="classification" class="tab-pane" role="tab-panel">
        <classification-tab></classification-tab>
      </div>
    </div>
  </div>
</template>

<script>
  import store from '../store';
  import TaxonTreatment from './TaxonTreatment.vue';
  import ClassificationTab from './ClassificationTab.vue';
  import CultivarsTab from './CultivarsTab.vue';
  import Images from './Images.vue';

  export default {
    computed: {
      taxon() {
        return this.$store.state.taxon;
      },
      isAccepted() {
        if (this.taxon) {
          let taxonomicStatus = this.taxon.taxonomicStatus;
          return taxonomicStatus.name === 'accepted';
        }
      },
      treatment() {
        if (this.taxon) {
          return this.taxon.currentTreatment;
        }
      },
      hasImages() {
        return (typeof this.taxon.heroImage !== 'undefined' ? true : false)
      }
    },
    components: {
      TaxonTreatment,
      ClassificationTab,
      CultivarsTab,
      Images
    },
    mounted() {
      this.showFirstTab();
    },
    updated() {
      this.showFirstTab();
    },
    methods: {
      showFirstTab() {
          $('.vicflora-tab a:first').tab('show');
      }
    }
  }
</script>
