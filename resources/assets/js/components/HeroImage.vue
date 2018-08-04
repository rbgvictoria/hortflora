<template>
  <figure class="hero-image col-sm-6 col-md-12">
    <div class="image-padding">
      <div>
        <div class="image-box">
          <div class="content">
            <lazy-load-image :classes="classes" :src="src" alt="Hero image"></lazy-load-image>
          </div>
        </div>
      </div>
    </div>
  </figure>

</template>

<script>

  import LazyLoadImage from './widgets/LazyLoadImage'

  export default {
    props: {
      heroImage: {
        type: Object,
        required: true
      }
    },
    data() {
      return {
        accessPoint: null,
        src: null,
        classes: null
      }
    },
    mounted() {
      this.getAccessPoint()
      this.getSrc()
      this.getClasses()
    },
    methods: {
      getAccessPoint() {
        this.accessPoint = this.heroImage.accessPoints.data.filter((item) => {
          return item.variant === 'preview'
        })[0]
      },
      getSrc() {
        this.src = this.accessPoint.accessURI.replace('1024', '512')
      },
      getClasses() {
        this.classes = this.accessPoint.pixelXDimension >= this.accessPoint.pixelYDimension ? 'img-responsive' : 'img-responsive img-responsive-portrait'
      }
    },
    watch: {
      '$route.params.taxon'() {
        this.heroImage = this.$store.state.taxon.heroImage
        this.getAccessPoint()
        this.getSrc()
        this.getClasses()
      }
    },
    components: {
      LazyLoadImage
    }
  }

</script>

<style>
  .img-responsive.img-responsive-portrait {
    height: 100%;
  }
</style>
