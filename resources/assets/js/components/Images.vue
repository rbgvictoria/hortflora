<template>
  <div id="images" class="tab-pane" role="tab-panel">

    <div class="row thumbnail-row" itemscope itemtype="http://schema.org/ImageGallery">
      <thumbnail
        v-if="thumbnails.length"
        v-for="(thumbnail, index) in thumbnails"
        :thumbnail="thumbnail"
        :key="thumbnail.id"
        :index="index"
        @click="onClick"
      ></thumbnail>
    </div>

    <div class="more-images text-right">
      <button v-if="moreButton"
        class="btn btn-primary"
        @click="getMoreImages"
      ><i class="fa fa-angle-double-down fa-lg"></i></button>
    </div>

    <photo-swipe-element ref="photoswipe" :items="items"></photo-swipe-element>

  </div>
</template>

<script>
  import Thumbnail from './Thumbnail'
  import PhotoSwipeElement from './widgets/PhotoSwipeElement'

  export default {
    components: {
      Thumbnail,
      PhotoSwipeElement
    },
    data() {
      return {
        thumbnails: [],
        items: [],
        pagination: null,
      }
    },
    computed: {
      moreButton() {
        return this.setMoreButton()
      }
    },
    mounted() {
      this.getImages()
    },
    methods: {
      getImages(page=1, perPage=12) {
        let self = this
        const id = this.$route.params.taxon
        axios.get(`/api/taxa/${id}/images`, {
          params: {
            page: page,
            perPage: perPage
          }
        }).then(({data}) => {
          this.$store.dispatch('addImages', data.data)
          this.pagination = data.meta.pagination
          data.data.forEach(item => {
            this.getThumbnail(item)
            this.getData(item)
          })
        })
      },
      setMoreButton() {
        return this.pagination && this.pagination.total > this.thumbnails.length
      },
      onScroll(event) {
        var element = $('html')[0]
        if (element.scrollHeight - element.scrollTop === element.clientHeight) {
          this.getMoreImages()
        }
      },
      getMoreImages() {
        if (this.pagination && this.pagination.total > this.$store.state.taxon.images.length) {
          this.getImages(this.pagination.current_page + 1, this.pagination.per_page)
        }
      },
      getPreview(item) {
        return item.accessPoints.data.filter(ap => {return ap.variant === 'preview'})[0]
      },
      getThumbnail(item) {
        let thumbnail = {}
        thumbnail.id = item.id
        let preview = this.getPreview(item)
        thumbnail.previewUrl = preview.accessURI
        thumbnail.width = preview.pixelXDimension
        thumbnail.height = preview.pixelYDimension
        thumbnail.thumbnailUrl = item.accessPoints.data.filter(ap => {return ap.variant === 'thumbnail'})[0].accessURI
        this.thumbnails.push(thumbnail)
      },
      getData(item) {
        let data = {}
        let preview = this.getPreview(item)
        data.preview1024 = {}
        data.preview1024.src = preview.accessURI
        data.preview1024.w = preview.pixelXDimension
        data.preview1024.h = preview.pixelYDimension
        data.preview2048 = {}
        data.preview2048.src = preview.accessURI.replace('1024', '2048')
        data.preview2048.w = preview.pixelXDimension * 2
        data.preview2048.h = preview.pixelYDimension * 2
        data.title = this.createCaption(item)
        this.items.push(data)
      },
      createCaption(item) {
        let caption = item.scientificName
        caption += '. '
        caption += '<br/>Creator: ' + item.creator.name
        if (typeof item.creator.organization !== 'undefined') {
          caption += ', ' + item.creator.organization.name
        }
        caption += '. '
        caption += `<a href="${item.license}">CC BY-NC-SA 4.0</a>.`
        return caption
      },
      onClick(index) {
        this.$refs.photoswipe.open(index)
      }
    },
    watch: {
      '$route.params.taxon'() {
        this.thumbnails = []
        this.getImages()
      }
    },
    created() {
      window.addEventListener('scroll', this.onScroll)
    },
    destroyed() {
      window.removeEventListener('scroll', this.onScroll)
    }
  }
</script>

<style></style>
