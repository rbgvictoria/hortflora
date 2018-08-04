<template>
  <input
    :class="classes"
    :placeholder="placeholder"
    @input="onInput"
    @change="onChange"/>
</template>

<script>
  import 'jquery-ui/ui/core'
  import 'jquery-ui/ui/widgets/autocomplete'
  import '../../../../../node_modules/jquery-ui/themes/base/autocomplete.css'
  import '../../../../../node_modules/jquery-ui/themes/base/menu.css'
  import '../../../../../node_modules/jquery-ui/themes/base/theme.css'

  export default {
    props: {
      source: {
        type: [String, Array, Function],
        required: true
      },
      minLength: {
        type: Number,
        default: 2
      },
      placeholder: String,
      classes: String
    },
    mounted() {
      const self = this
      $(this.$el).autocomplete({
        source: this.source,
        minLength: this.minLength,
        select: function(event, ui) {
          self.$emit('select', ui.item)
        }
      })
    },
    beforeDestroy() {
      $(this.$el).autocomplete('destroy')
    },
    methods: {
      onInput (e) {
        this.$emit('input', e.target.value)
      },
      onKeypressEnter (e) {
        this.$emit('keypress-enter', e)
      },
      onChange (e) {
        this.$emit('change', e)
      }
    }
  }
</script>

<style>
  .ui-helper-hidden-accessible {
    display: none;
  }
</style>
