<template>
  <div
      :class="{ modal: true, show:modalOpen}"
      id="modal" tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      @click.self="hide">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hide"><span aria-hidden="true">×</span></button>
          <h4 v-if="field" class="modal-title">{{ field.label }}</h4>
        </div>
        <div v-if="field" class="modal-body" @scroll="getMoreFacets">
          <table id="facet-table" class="table table-condensed table-bordered">
              <thead>
                  <tr>
                      <th :class="{ 'sort-column': sortByIndex }" @click="sortFacets(true)">
                          Value<span class="pull-right"><i class="fa fa-sort-alpha-asc"></i></span>
                      </th>
                      <th :class="{ 'sort-column': !sortByIndex }" @click="sortFacets(false)">
                          Count<span class="pull-right"><i class="fa fa-sort-amount-desc"></i></span>
                      </th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-for="facet in field.facets" :key="facet.val">
                      <td>
                          <label>
                              <input :value="facet.val" type="checkbox">
                              <router-link :to="{ path: '/search?' + query + '&fq=' + facet.fq}">
                                  {{ facet.value }}
                              </router-link>
                          </label>
                      </td>
                      <td class="text-right">{{ facet.count }}</td>
                  </tr>
              </tbody>
          </table>
        </div>
        <div class="modal-footer clearfix">
          <button type="button" class="btn btn-primary include-selected" @click="runQuery">Include selected</button>
          <button type="button" class="btn btn-primary exclude-selected" @click="runQuery">Exclude selected</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { snakeCaseToTitleCase } from '../helpers/helper.js'

  import { mapState } from 'vuex'

  export default {
    props: ['activeFacetField', 'facetFields', 'query'],
    data() {
      return {
        modalOpen: false,
        field: null,
        sortByIndex: false,
        offset: 0
      }
    },
    computed: {
      ...mapState({
        params: state => state.searchResult.meta.params
      })
    },
    methods: {
      escapeKeyListener(evt) {
        if (evt.keyCode === 27 && this.modalOpen) {
            this.modalOpen = false;
        }
      },
      hide() {
        this.modalOpen = false;
      },
      getField() {
        let activeFacetField = this.activeFacetField;
        return this.facetFields.find(field => {
            return field.name === activeFacetField
        });
      },
      sortFacets: function(sortByIndex) {
        if (sortByIndex != this.sortByIndex) {
          const vm = this;
          let params = Object.assign({}, this.params)
          let facets = vm.field.facets
          params.rows = 1
          params['facet.field'] = vm.field.name
          params['facet.sort'] = sortByIndex ? 'index' : 'count'
          if (vm.offset) {
            params['facet.offset'] = vm.offset
          }
          axios.get('/api/search', {
            params: params
          }).then((response) => {
            vm.field.facets = response.data.facetFields[0].facets.map(facet => {
              return {
                count: facet.count,
                fq: facet.fq,
                val: facet.val,
                value: snakeCaseToTitleCase(facet.val)
              }
            })
            vm.offset = 0
            vm.sortByIndex = sortByIndex
          });
        }
      },
      moveBlanks: function() {
        if (this.field.facets[0].val === '(blank)') {
          let blank = this.field.facets.shift()
          this.field.facets.push(blank)
        }
      },
      getMoreFacets: function(event) {
        const vm = this
        var element = event.target
        if (element.scrollHeight - element.scrollTop === element.clientHeight) {
          if (this.field.facets.length >= vm.offset + 100) {
            if (vm.field.facets[vm.field.facets.length - 1].val === '(blank)') {
              vm.field.facets.pop()
            }
            let params = Object.assign({}, this.params)
            params.rows = 1
            params['facet.field'] = vm.field.name
            params['facet.sort'] = vm.sortByIndex ? 'index' : 'count'
            vm.offset += 100
            params['facet.offset'] = vm.offset
            axios.get('/api/search', {
              params: params
            }).then((response) => {
              let newFacets = response.data.facetFields[0].facets.map(facet => {
                return {
                    count: facet.count,
                    fq: facet.fq,
                    val: facet.val,
                    value: snakeCaseToTitleCase(facet.val)
                }
              })
              vm.field.facets = vm.field.facets.concat(newFacets)
            })
          }
        }
      },
      runQuery: function(event) {
        let elem = event.target
        let classes = elem.getAttribute('class').split(' ')
        let include = false
        if (classes.indexOf('include-selected') > -1) {
          include = true
        }
        const values = [...document.querySelectorAll('.modal input[type="checkbox"]')]
            .map(cbx => cbx.getAttribute('value'))
        let selectedValues = [...document.querySelectorAll('.modal input[type="checkbox"]:checked')]
            .map(cbx => cbx.getAttribute('value'))
        if (selectedValues.indexOf('(blank)') > -1) {
          selectedValues = values.filter(val => selectedValues.indexOf(val) === -1);
        }

        if (selectedValues.length > 0 && selectedValues.length <= 15) {
          const fq = (include ? '' : '-')
              + this.field.name
              + ':(' + selectedValues.map(val => {
                  return '"' + val + '"';
              }).join(' OR ') + ')';
          this.$router.push({path: '/search?' + this.query + '&fq=' + fq})
        }
        else if (selectedValues.length > 0) {
          alert('You can only select up to 15 facets')
        }
        else {
          alert("You haven't selected any facets yet")
        }
      }
    },
    watch: {
      modalOpen() {
        const className = 'modal-open'
        if (this.modalOpen) {
          document.body.classList.add(className)
        }
        else {
          document.body.classList.remove(className)
        }
        this.offset = 0
        this.sortByIndex = false
        let modalBody = document.getElementsByClassName('modal-body')[0]
        if (modalBody) {
          modalBody.scrollTop = 0
        }
      },
      activeFacetField() {
        if (this.activeFacetField) {
          this.field = this.getField()
        }
      }
    },
    created() {
      document.addEventListener('keyup', this.escapeKeyListener);
    },
    destroyed() {
      document.removeEventListener('keyup', this.escapeKeyListener);
    }
  }
</script>

<style>
  .modal {
    background-color: rgba(0, 0, 0, 0.5);
  }

  .modal-footer {
    padding-bottom: 20px;
  }
</style>
