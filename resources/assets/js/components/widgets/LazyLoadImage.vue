<template>
  <div class="lazy">
    <img :class="classes" :data-src="src" :alt="alt"/>
    <loading-spinner v-if="spinner"></loading-spinner>
  </div>
</template>

<script>
  import LoadingSpinner from './LoadingSpinner'

  export default {
    props: {
      classes: {
        type: String
      },
      src: {},
      alt: {
        type: String,
        default: ''
      },
      spinner: {
        type: Boolean,
        default: true
      }
    },
    methods: {
      lazyLoad() {
        [].forEach.call(document.querySelectorAll('img[data-src]'), function(img) {
          img.setAttribute('src', img.getAttribute('data-src'))
          img.onload = function() {
            img.removeAttribute('data-src')
            let loadingIcon = img.nextElementSibling
            if (loadingIcon) {
              img.parentElement.removeChild(loadingIcon)
            }
          }
        })
      }
    },
    mounted() {
      this.lazyLoad()
    },
    components: {
      LoadingSpinner
    }

  }
</script>

<style>
  .lazy {
    position: relative;
    width: 100%;
    height: 100%;
  }

  .lazy > img {
    opacity: 1;
    transition: opacity 0.3s;
  }

  .lazy > img[data-src] {
    opacity: 0;
  }

</style>
