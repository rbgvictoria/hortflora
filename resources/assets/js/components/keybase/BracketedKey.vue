<template>
  <div id="keybase-bracketed" class="keybase-panel">
    <div class="keybase-bracketed-key">
      <div v-for="couplet in bracketedKey.children" class="keybase-couplet" :id="`l_${ couplet.children[0].parent_id}`">
        <div v-for="lead in couplet.children" class="keybase-lead">
          <span class="keybase-from-node">{{ lead.fromNode  }}</span>
          <span class="keybase-lead-text">{{ lead.title }}
            <span v-if="lead.toNode !== undefined" class="keybase-to-node">
              <a :href="`#l_${ lead.lead_id }`" @click="event =>event.preventDefault()">{{ lead.toNode }}</a>
            </span>
            <span v-else class="keybase-to-item">
              <item :item="getItem(lead.children[0].children[0].item_id)"></item>
            </span>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import Item from './Item'

export default {
  components: {
    Item
  },
  data() {
    return {
      bracketedKey: false
    }
  },
  computed: {
    ...mapState({
      items: (state) => {
        return state.key.items
      }
    })
  },
  methods: {
    getKey() {
        $.fn.keybase('bracketedKey', {
          bracketedKeyDiv: '#keybase-bracketed',
          onBracketedKey: this.onBracketedKey,
          bracketedKeyDisplay: function() {}
        })
    },
    onBracketedKey() {
      this.bracketedKey = $.fn.keybase.getters.bracketedKey()[0]
    },
    getItem(id) {
      return this.items.filter(item => {
        return item.item_id === id
      })[0]
    }
  },
  mounted() {
    this.getKey();
  }
}
</script>
