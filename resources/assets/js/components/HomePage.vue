<template>
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="welcome">
          <span>HortFlora</span> is a current guide to the cultivated plants of south-eastern Australia.
          With plant profiles, identification tools and richly illustrated, the Royal Botanic Gardens
          Victoria provides this resource free-of-charge for farmers, foresters, arborists, botanists,
          garden and landscape designers, students, amateur gardeners, authors, journalists, and indeed
          anyone with an interest in cultivated plants growing in, but not restricted to, Victoria,
          South Australia, Tasmania, New South Wales and south-eastern Queensland.
        </div>

        <div class="home-page-button-nav">
          <a href="https://hortflora.rbg.vic.gov.au/search" class="btn btn-primary btn-lg btn-block">Search</a>
        </div>

        <highlights :highlights="highlights"></highlights>

      </div>
      <div class="col-md-6">
        <carousel :id="carouselId" :slides="slides"></carousel>
      </div>
    </div>
  </div>
</template>

<script>
import Highlights from './HomePageHighlights'
import Carousel from './widgets/Carousel'

export default {
  components: {
    Highlights,
    Carousel
  },
  data() {
    return {
      highlights: [
        {
          taxon: 'ad97b9fc-5340-11e7-b82b-005056b0018f',
          name: 'Banksia coccinea',
          icon: 'bankscoc.jpg',
          text: `, Scarlet Banksia, a shrub or small
              tree from southern Western Australia, is an important Australian
              cutflower.`
        },
        {
          taxon: 'ad8b014e-5340-11e7-b82b-005056b0018f',
          name: 'Cedrus deodara',
          icon: 'cedrudio.jpg',
          text: `, Deodar, grows naturally in the
              Himalayas, and features in many older Australian private and
              public gardens.`
        },
        {
          taxon: 'ad8df7fa-5340-11e7-b82b-005056b0018f',
          name: 'Quercus robur',
          icon: 'quercrob.jpg',
          text: `, the English Oak, is historically one
              of the most widely planted Oaks in south-eastern Australia.`
        }
      ],
      carouselId: 'carousel-home-page',
      slides: [
        {
          img: 'https://data.rbg.vic.gov.au/cip/preview/image/library/132035?maxsize=600',
          caption: `<i>Mespilus germanica</i><br/>Artist: Dianne Emery. All
              rights reserved.<br/>Source: State Botanical Collection, Royal
              Botanic Gardens Victoria`
        },
        {
          img: 'https://data.rbg.vic.gov.au/cip/preview/image/library/132036?maxsize=600',
          caption: `<i>Mespilus germanica</i><br/>Artist: Dianne Emery. All
              rights reserved.<br/>Source: State Botanical Collection, Royal
              Botanic Gardens Victoria`
        },
        {
          img: 'https://data.rbg.vic.gov.au/cip/preview/image/library/132037?maxsize=600',
          caption: `<i>Camellia japonica</i> &apos;Dr Tinsely&apos;<br/>Artist:
              Malcolm Hobday. All rights reserved.<br/>Source: State Botanical
              Collection, Royal Botanic Gardens Victoria`
        },
        {
          img: 'https://data.rbg.vic.gov.au/cip/preview/image/library/132039?maxsize=600',
          caption: `<i>Prostanthera melissifolia</i><br/>Artist: Anita Barley.
              All rights reserved.<br/>Source: State Botanical Collection, Royal
              Botanic Gardens Victoria`
        },
        {
          img: 'https://data.rbg.vic.gov.au/cip/preview/image/library/132041?maxsize=600',
          caption: `<i>Eucalyptus leucoxylon</i><br/>Artist: Pam McDiarmid. All
              rights reserved.<br/>Source: State Botanical Collection, Royal
              Botanic Gardens Victoria`
        },
        {
          img: 'https://data.rbg.vic.gov.au/cip/preview/image/library/132034?maxsize=600',
          caption: `<i>Eucalyptus erythrocorys</i><br/>Artist: Sandra Sanger.
              All rights reserved.<br/>Source: State Botanical Collection, Royal
              Botanic Gardens Victoria`
        }
      ]
    }
  }

}
</script>
