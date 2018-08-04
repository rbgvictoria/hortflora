<template>
  <div class="container">
    <div class="keybase-container" data-key-id="8099">
      <div class="row">
        <div class="col-md-12">
          <ol class="breadcrumb">
            <li v-if="taxonomicScope && taxonomicScope.id">
                <router-link :to="{ name: 'taxa', params: { taxon: taxonomicScope.id } }">{{ taxonomicScope.item_name }}</router-link>
            </li>
            <li v-if="parentKey">
              <router-link :to="{ name: 'keys', params: { key: parentKey.id } }">{{ parentKey.title }}</router-link>
            </li>
          </ol>
          <h1 class="key-title">{{ title }}</h1>
          <div class="vicflora-tab clearfix">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active">
                <a href="#tab_player" aria-controls="player" role="tab" data-toggle="tab">Interactive key</a>
              </li>
              <li role="presentation">
                <a href="#tab_bracketed" aria-controls="bracketed" role="tab" data-toggle="tab">Bracketed key</a>
              </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="tab_player">
                <keybase-player></keybase-player>
              </div>
              <div role="tabpanel" class="tab-pane" id="tab_bracketed">
                <bracketed-key></bracketed-key>
              </div>
            </div> <!-- /.tab-content -->

          </div> <!-- /role:tabpanel -->
          <div class="keybase-key-source"></div>
          <div class="keybase-link text-right">
            <a href="" target="_blank">Open key in KeyBase <i class="fa fa-external-link"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import KeybasePlayer from './keybase/KeybasePlayer'
import BracketedKey from './keybase/BracketedKey'

export default {
  components: {
    KeybasePlayer,
    BracketedKey
  },
  computed: {
    ...mapState({
      title: state => {
        if (state.key) {
          return state.key.key_title
        }
      },
      taxonomicScope(state) {
        let tscope
        if (state.key) {
          tscope = state.key.taxonomic_scope
          if (tscope.url) {
            tscope.id = tscope.url.substr(tscope.url.lastIndexOf('/') + 1)
          }
          else if (typeof state.key.parent !== 'undefined') {
            tscope = state.key.parent.taxonomic_scope
            tscope.id = tscope.url.substr(tscope.url.lastIndexOf('/') + 1)
          }
        }
        return tscope
      },
      parentKey(state) {
        let parent
        if (state.key) {
          if (typeof state.key.parent !== 'undefined') {
            parent = state.key.parent
          }
        }
        return parent
      }
    })
  }
}

</script>
