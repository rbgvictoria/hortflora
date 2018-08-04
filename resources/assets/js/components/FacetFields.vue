<template>
  <div class="facets">
    <h3>Filters</h3>
    <div class="form-horizontal">
      <div
        v-for="field in facetFields"
        class="facet collapsible"
        :data-hortflora-facet-name="field.name"
      >
        <h4>{{ field.label }}</h4>
        <ul class="form-group">
          <li v-for="facet in field.facets" class="checkbox">
            <label>
              <input type="checkbox" :value="facet.val" :disabled="field.disabled"/>
              <router-link
                v-if="!field.disabled"
                :to="{path: '/search?' + query + '&fq=' + facet.fq}"
              >{{ facet.value }}</router-link>
              <span v-else>{{ facet.value }}</span>
              <span class="facet-count">({{ facet.count }})</span>
            </label>
          </li>
        </ul>
        <a
          v-if="!field.disabled"
          class="more-facets-link"
          :data-facet-field="field.name"
          @click="openModal">More...</a>
      </div>
    </div>
    <facet-modal
      ref="facetModal"
      :facetFields=facetFields
      :activeFacetField="activeFacetField"
      :query="query"
    ></facet-modal>
  </div> <!-- /.facets -->
</template>

<script>
  import FacetModal from './FacetModal'
  import { snakeCaseToTitleCase, camelCaseToSnakeCase, parseFq } from '../helpers/helper'
  import { mapState } from 'vuex'

  export default {
    data() {
      return {
        activeFacetField: null,
      }
    },
    computed: {
      ...mapState({
        query(state) {
          return this.getQuery(state.searchResult.meta.query)
        },
        filterFields(state) {
          return this.getFilterFields(state.searchResult.meta.params)
        },
        facetFields(state) {
          return this.getFacetFields(state.searchResult.facetFields)
        }
      })
    },
    methods: {
      getQuery(query) {
        let qry = query.split('&')
        return qry.filter(item => {
          return item.substr(0, 5) !== 'page=';
        }).join('&');
      },
      getFilterFields(params) {
        if (params.fq) {
          if (typeof params.fq === 'string') {
            params.fq = [params.fq]
          }
          return params.fq.map(item => {
            let parsed = parseFq(item)
            return parsed.label
          });
        }
        else {
          return []
        }
      },
      getFacetFields(fields) {
        return fields.map(field => {
          return {
            name: field.fieldName,
            label: snakeCaseToTitleCase(field.fieldName),
            disabled: this.filterFields.indexOf(camelCaseToSnakeCase(field.fieldName)) > -1,
            facets: field.facets.map(facet => {
              return {
                count: facet.count,
                fq: facet.fq,
                val: facet.val,
                value: snakeCaseToTitleCase(facet.val)
              }
            })
          }
        });
      },
      openModal: function(event) {
        this.activeFacetField = event.target.getAttribute('data-facet-field')
        this.$refs.facetModal.modalOpen = true
      }
    },
    watch: {
      '$route.query'() {
        this.$refs.facetModal.modalOpen = false
      }
    },
    components: {
      FacetModal
    }
  }

</script>

<style>
    .facet ul>li:nth-child(n+4) {
        display: none
    }

    .more-facets-link {
        cursor: pointer
    }
</style>
