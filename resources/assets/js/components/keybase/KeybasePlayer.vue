<template>
  <div id="keybase-player" class="keybase-panel">
    <div class="keybase-player-window">
      <div class="keybase-player-leftpane">
        <current-node :currentNode="currentNode"></current-node>
        <div class="keybase-player-drag-updown"></div>
        <kb-path :path="kbPath"></kb-path>
      </div>
      <div class="keybase-player-drag-leftright"></div>
      <div class="keybase-player-rightpane">
        <remaining-items :remainingItems="remainingItems"></remaining-items>
        <div class="keybase-player-drag-updown"></div>
        <discarded-items :discardedItems="discardedItems"></discarded-items>
      </div>
    </div>
  </div>
</template>

<script>
import CurrentNode from './CurrentNode'
import Path from './Path'
import RemainingItems from './RemainingItems'
import DiscardedItems from './DiscardedItems'

export default {
  components: {
    CurrentNode,
    "kb-path": Path,
    RemainingItems,
    DiscardedItems
  },
  data() {
    return {
      currentNode: false,
      kbPath: false,
      remainingItems: false,
      discardedItems: false,
      title: false
    }
  },
  methods: {
    getKey(keyID) {

      $('.keybase-link a').attr('href', 'http://keybase.rbg.vic.gov.au/keys/show/' + keyID)
      var wsUrl = 'https://data.rbg.vic.gov.au/keybase-ws'

      $.fn.keybase('player', {
        baseUrl: wsUrl + "/ws/key_get",
        playerDiv: '#keybase-player',
        key: keyID,
        title: false,
        reset: true,
        playerWindow: function() {},
        currentNodeDisplay: function() {},
        pathDisplay: function() {},
        remainingItemsDisplay: function() {},
        discardedItemsDisplay: function() {},
        bracketedKeyDisplay: null,
        onJson: this.onJson,
        onNextCouplet: this.onNextCouplet,
        bracketedKeyDiv: null
      })
    },
    onNextCouplet() {
      this.currentNode = $.fn.keybase.getters.currentNode()
      this.kbPath = $.fn.keybase.getters.path()
      this.remainingItems = $.fn.keybase.getters.remainingItems()
      this.discardedItems = $.fn.keybase.getters.discardedItems()
    },
    onJson() {
      let json = $.fn.keybase.getters.jsonKey()
      let keyInStore = this.$store.state.key
      if (keyInStore && keyInStore.taxonomic_scope.url) {
        json.parent = {
          id: keyInStore.key_id,
          title: keyInStore.key_title,
          taxonomic_scope: keyInStore.taxonomic_scope
        }
      }
      this.$store.dispatch('storeKey', json)
    }
  },
  watch: {
    '$route.params.key'() {
      console.log(this.$route.params.key)
      this.getKey(this.$route.params.key)
    }
  },
  mounted() {
    this.getKey(this.$route.params.key)
    $('.keybase-player-window').on('mousedown', '.keybase-player-drag-leftright',
        $.fn.keybase.dragLeftRight)
    $('.keybase-player-leftpane').on('mousedown', '.keybase-player-drag-updown',
        $.fn.keybase.dragUpDownLeftPane)
    $('.keybase-player-rightpane').on('mousedown', '.keybase-player-drag-updown',
        $.fn.keybase.dragUpDownRightPane)
    $(document).mouseup(function(e){
      $(document).unbind('mousemove')
    })
  }
}
</script>
