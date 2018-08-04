<template>
  <figure v-if="src" class="profile-map col-sm-6 col-md-12">
    <lazy-load-image :src="src"
      classes="img-responsive"
      alt="distribution map"
      :spinner="false"
    ></lazy-load-image>
  </figure>
</template>

<script>
  import LazyLoadImage from './widgets/LazyLoadImage'

  export default {
    props: ['classes'],
    data() {
      return {
        src: false
      }
    },
    components: {
      LazyLoadImage
    },
    methods: {
      getSrc() {
        this.src = process.env.MIX_GEOSERVER_URL
          + '/wms?service=WMS&version=1.1.0&request=GetMap'
          + '&layers=wgs%3Alevel3%2Chortflora%3Adistribution_view'
          + '&styles=polygon%2Cred_polygon'
          + '&bbox=-180.00005538%2C-55.9197235107422%2C180.0%2C83.6236064951172'
          + '&width=851&height=400'
          + '&srs=EPSG%3A4326'
          + '&format=image%2Fsvg'
          + `&cql_filter=INCLUDE%3Btaxon_id%3D%27${ this.$route.params.taxon }%27`
      }
    },
    mounted() {
      this.getSrc()
    },
    watch: {
      '$route.params.taxon'() {
        this.getSrc()
      }
    }
  }
</script>

<style>
  .profile-map {
    position: relative;
  }

  .profile-map .image-padding {
    position: relative;
  }
</style>
