<template>
    <div id="search">
        <div class="search-form form-horizontal">
            <div class="input-group">
                <vue-suggest class=""
                    v-model="model"
                    :list="getList"
                    :min-length="2"
                    :mode="mode"
                    ref="suggestComponent"
                    @select="onSuggestSelect"
                    @request-start="onRequestStart"
                    @request-done="onRequestDone"
                    @request-failed="onRequestFailed"
                    @change="search"
                >
                    <autocomplete-input :value="model"></autocomplete-input>

                    <div slot="suggestion-item" slot-scope="scope" :title="scope.suggestion.description">
                      <div class="text">
                        <span v-html="boldenSuggestion(scope)"></span>
                      </div>
                    </div>

                    <div class="misc-item" slot="misc-item-below" slot-scope="{ suggestions }" v-if="loading">
                      <span>Loading...</span>
                    </div>
                </vue-suggest>
                <span class="submit input-group-addon" @click="search">
                    <i class="fa fa-search fa-lg"></i>
                </span>
            </div>
        </div>
    </div> <!-- /#search -->
</template>

<script>
    import VueSuggest from 'vue-simple-suggest'
    import AutocompleteInput from './AutocompleteInput'

    export default {
        components: {
            VueSuggest,
            AutocompleteInput
        },
        data() {
            return {
                mode: "input",
                loading: false,
                model: null
            }
        },
        methods: {
            getInitialValue() {
                const searchResult = this.$store.state.searchResult;
                let q = '';
                if (typeof searchResult !== 'undefined') {
                    q = searchresult.data.meta.params.q;
                }
                if (q.indexOf(':') < 0) {
                    return q.replace('""', '').replace('*', '')
                }
                else {
                    return q
                }
            },
            search() {
                let params = this.$store.state.searchResult.meta.params;
                let query = {};
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
                if (params.rows) {
                    query.rows = params.rows;
                }
                this.$router.push({ name: 'search', query: query });
            },
            getList(inputValue) {
                return new Promise((resolve, reject) => {
                    let url=`/api/autocomplete/name?term=${inputValue}`;
                    this.$refs.suggestComponent.clearSuggestions()
                    axios.get(url).then(({data}) => {
                        resolve(data)
                    }).catch((error) => {
                        reject(error);
                    })
                })
            },
            boldenSuggestion({ suggestion, query }) {
              let result = this.$refs.suggestComponent.displayProperty(suggestion);

              if (!query) return result;

              const texts = [query];
              return result.replace(new RegExp('(.*?)(' + texts.join('|') + ')(.*?)','i'), '$1<b>$2</b>$3');
            },
            onSuggestSelect (suggest) {
              this.selected = suggest
            },
            onRequestStart (value) {
              this.loading = true
            },
            onRequestDone (e) {
              this.loading = false
            },
            onRequestFailed (e) {
              this.loading = false
            },

        },
        watch: {
            '$route.query'() {
                this.searchInput = this.$route.query.q;
            }
        }
    }

</script>

<style>

    .vue-simple-suggest.designed .suggestions {
      position: absolute;
      left: 0;
      right: 0;
      top: 100%;
      top: calc(100% + 5px);
      border-radius: 3px;
      border: 1px solid #cde;
      background-color: #fff;
      opacity: 1;
      z-index: 1000;
    }

    .vue-simple-suggest.designed .suggestions .suggest-item {
      cursor: pointer;
      user-select: none;
    }

    .vue-simple-suggest.designed .suggestions .suggest-item,
    .vue-simple-suggest.designed .suggestions .misc-item {
      padding: 5px 10px;
    }

    .vue-simple-suggest.designed .suggestions .suggest-item.hover {
      background-color: #2874D5 !important;
      color: #fff !important;
    }

    .vue-simple-suggest.designed .suggestions .suggest-item.selected {
      background-color: #2832D5;
      color: #fff;
    }

</style>
