<template>
  <div class="row">
    <div :class="classes">
      <div v-html="treatment"></div>
      <key-button v-if="key" :tokey="key"></key-button>
    </div>
    <div v-if="heroImage || distribution" class="col-md-4 profile-rigth-pane">
      <hero-image v-if="heroImage" :heroImage="taxon.heroImage"></hero-image>
      <distribution-map v-if="distribution"></distribution-map>
    </div>
  </div>
</template>

<script>
  import HeroImage from './HeroImage'
  import DistributionMap from './DistributionMap'
  import KeyButton from './KeyButton'

  export default {
    components: {
      HeroImage,
      DistributionMap,
      KeyButton
    },
    data() {
      return {
        heroImage: false,
        classes: 'col-md-12',
        distribution: false,
        key: false
      }
    },
    computed: {
      taxon() {
        return this.$store.state.taxon
      },
      treatment() {
        return this.taxon.currentTreatment.currentVersion.text
      }
    },
    mounted() {
      this.hasHeroImage()
      this.getClasses()
    },
    methods: {
      hasHeroImage() {
        if (typeof this.taxon.heroImage !== 'undefined') {
          this.heroImage = true
        }
        else {
          this.heroImage = false
        }
      },
      getClasses() {
        if (this.heroImage) {
          this.classes = 'col-md-8'
        }
        else {
          this.classes = 'col-md-12'
        }
      },
      getDistribution() {
        axios.get(`${process.env.MIX_API_URL}/taxa/${ this.$route.params.taxon }/regions`)
            .then(({ data }) => {
              if (data.data.length > 0) {
                this.distribution = data.data
              }
            })
      },
      getKey() {
        axios.get(`${process.env.MIX_API_URL}/taxa/${ this.$route.params.taxon }/key`)
            .then(({ data }) => {
              if (data) {
                data.id = data.id.substr(data.id.lastIndexOf('/') + 1)
                this.key = data
              }
            })
      }
    },
    mounted() {
      this.hasHeroImage()
      this.getClasses()
      this.getDistribution()
      this.getKey()
    },
    watch: {
      '$route.params.taxon'() {
        this.hasHeroImage()
        this.getClasses()
        this.distribution = false
        this.getDistribution()
        this.key = false
        this.getKey()
      }
    }
  }
</script>
