<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <breadcrumbs></breadcrumbs>
                <back-to-last-search-button v-if="backToLastSearch"></back-to-last-search-button>
            </div>
            <div class="col-md-12">
                <h2 class="scientific-name pull-left"
                    v-html="scientificName"
                ></h2>
                <h2 v-if="vernacularName" class="preferred-common-name">
                    {{ vernacularName }}
                </h2>

                <taxon-tabs></taxon-tabs>
            </div>
        </div>
    </div>
</template>

<script>
  import { formatName } from '../helpers/name_helper';
  import { mapState } from 'vuex'

  import TaxonTabs from './TaxonTabs';
  import Breadcrumbs from './Breadcrumbs';
  import BackToLastSearchButton from './BackToLastSearchButton';

  export default {
    computed: {
      ...mapState({
        taxon: state => state.taxon,
        backToLastSearch(state) {
          return state.searchResult !== null
        }
      }),
      scientificName() {
          return formatName(this.taxon, true);
      },
      vernacularName() {
          if (this.taxon) {
              if (typeof this.taxon.vernacularNames === 'undefined') {
                  return false;
              }
              let vnames = this.taxon.vernacularNames.data;
              let vname = vnames.find(item => item.isPreferredName === true);
              return vname.vernacularName;
          }
      }
    },
    components: {
        TaxonTabs,
        Breadcrumbs,
        BackToLastSearchButton
    }
  }
</script>
