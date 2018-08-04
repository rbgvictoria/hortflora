<template>
  <div class="query-result">
    <search-result-header></search-result-header>
    <div
      class="search-name-entry"
      v-for="doc in results"
    >
      <div class="row" v-if="doc.taxonomicStatus.name === 'accepted'">
        <div class="col-lg-9 col-sm-8">
          <router-link :to="{ name: 'taxa', params: { taxon: doc.id } }">
              <span v-html="doc.formattedName"></span>
          </router-link>
          <span v-if="doc.vernacularName"
              class="vernacular-name"
              v-html="doc.vernacularName"></span>
        </div>
        <div class="fam col-lg-3 col-sm-4">
          {{ doc.family }}
        </div>
      </div>
      <div class="row" v-else>
        <div class="col-lg-12">
            <router-link :to="{ name: 'taxa', params: { taxon: doc.id } }">
              <span v-html="doc.formattedName"></span>
            </router-link>
            <div v-if="doc.acceptedNameUsage" class="accepted-name">
              <router-link :to="{ name: 'taxa', params: { taxon: doc.acceptedNameUsage.id }}">
                <span v-html="doc.acceptedNameUsage.formattedName"></span>
              </router-link>
            </div>
        </div>
      </div>
    </div>
    <search-result-nav :paginationLinks="paginationLinks"></search-result-nav>
  </div>
</template>

<script>
  import { formatName } from '../helpers/name_helper'
  import SearchResultHeader from './SearchResultHeader'
  import SearchResultNav from './SearchResultNav'

  import { mapState } from 'vuex'

  export default {
    computed: {
      ...mapState({
        results(state) {
          return this.getDocs(state.searchResult.docs)
        },
        paginationLinks(state) {
          return this.getPaginationLinks(state.searchResult.meta.pagination, state.searchResult.meta.params)
        }
      })
    },

    methods: {
      getDocs(docs) {
        return docs.map(doc => {
          doc.name = {
            scientificName: doc.scientificName,
            scientificNameAuthorship: doc.scientificNameAuthorship
          };
          if (typeof doc.taxonomicStatus.name === 'undefined') {
            doc.taxonomicStatus = {
              name: doc.taxonomicStatus
            };
          }
          doc.type = doc.entryType
          doc.formattedName = formatName(doc, true)
          if (doc.acceptedNameUsageId && !doc.acceptedNameUsage) {
            doc.acceptedNameUsage = {
              id: doc.acceptedNameUsageId,
              type: 'taxon',
              name: {
                scientificName: doc.acceptedNameUsage,
                scientificNameAuthorship: doc.aceeptedNameUsageAuthorship
              },
              taxonRank: doc.acceptedNameUsageTaxonRank,
              taxonomicStatus: {
                name: 'accepted'
              }
            };
            doc.acceptedNameUsage.formattedName = formatName(doc.acceptedNameUsage, true)
          }
          return doc
        })
      },
      getPaginationLinks(pagination, params) {
        pagination.per_page = Number(pagination.per_page)
        pagination.current_page = Number(pagination.current_page)
        let pageLinks = {}

        // self
        params.page = pagination.current_page
        pageLinks.self = {
          query: Object.assign({}, params)
        }

        // first
        params.page = 1
        pageLinks.first = {
          label: "1",
          query: Object.assign({}, params),
          classes: (pagination.current_page === 1) ? 'paginate-button active' : 'paginate-button'
        }

        // prev
        pageLinks.prev = {
          query: null,
          classes: 'paginate-button previous disabled'
        }
        if (pagination.current_page > 1) {
          params.page = pagination.current_page - 1
          pageLinks.prev = {
            query: Object.assign({}, params),
            classes: 'paginate-button previous'
          }
        }

        // next
        pageLinks.next = {
          query: null,
          classes: 'paginate-button next disabled'
        }
        if (pagination.current_page < pagination.total_pages) {
          params.page = pagination.current_page + 1
          pageLinks.next = {
            query: Object.assign({}, params),
            classes: 'paginate-button next'
          }
        }

        // last
        params.page = pagination.total_pages
        pageLinks.last = {
          label: pagination.total_pages.toString(),
          query: Object.assign({}, params),
          classes: (pagination.current_page === pagination.total_pages)
              ? 'paginate-button active' : 'paginate-button'
        }

        // pages
        pageLinks.pages = []

        let n = pagination.total_pages
        let p = pagination.current_page

        if (n > 6 && p > 4) {
          pageLinks.pages.push({
            label: '...',
            query: null,
            classes: 'paginate-button disabled'
          })
        }

        for (let i = p - 4; i <= p + 4; i++) {
          if (i > 1 && i < n && ((i == p) || (i == p-1) || (i == p+1)
              || (p < 5 && i <= 5) || (p > n-4 && i >= n-4))) {
            params.page = i
            pageLinks.pages.push({
              label: i.toString(),
              query: Object.assign({}, params),
              classes: (i === p) ? 'paginate-button active' : 'paginate-button'
            })
          }
        }

        if (n > 6 && p < n-3) {
          pageLinks.pages.push({
            label: '...',
            query: null,
            classes: 'paginate-button disabled'
          })
        }

        return pageLinks

      }
    },
    watch: {
      '$store'() {
        (state) => {
          this.getDocs()
          this.getPaginationLinks()
        }
      }
    },
    components: {
      SearchResultHeader,
      SearchResultNav
    }
  }

</script>
