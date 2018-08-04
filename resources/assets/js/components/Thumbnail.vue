<template>
  <figure class="col-xs-6 col-sm-4 col-md-3 col-lg-2" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
    <a :href="thumbnail.previewUrl" class="thumbnail thumb" itemprop="contentUrl" :data-size="dataSize" @click="onClick">
      <lazy-load-image :src="thumbnail.thumbnailUrl" :classes="classes"></lazy-load-image>
    </a>
  </figure>
</template>

<script>
  import LazyLoadImage from './widgets/LazyLoadImage'

  export default {
    props: {
      thumbnail: {
        type: Object,
        required: true,
      },
      index: Number
    },
    computed: {
      dataSize() {
        return `${this.thumbnail.width}x${this.thumbnail.height}`
      }
    },
    data() {
      return {
        classes: 'img-responsive'
      }
    },
    methods: {
      onClick(e) {
        e.preventDefault()
        this.$emit('click', this.index)
      }
    },
    mounted() {
      this.classes = (this.thumbnail.pixelXDimension >= this.thumbnail.pixelYDimension)
          ? 'img-responsive' : 'img-responsive img-responsive-portrait'
    },
    components: {
      LazyLoadImage
    }
  }
</script>

<style></style>
