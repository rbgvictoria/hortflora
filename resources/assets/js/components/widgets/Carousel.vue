<template>
  <div :id="id" class="carousel" data-ride="carousel">
    <div class="carousel-inner" role="listbox">
      <div v-for="(item, index) in slides" :class="{item: true, active: index === 0}">
        <img :src="item.img" alt="" />
        <div v-if="item.caption" class="carousel-caption" v-html="item.caption"></div>
      </div>
    </div>

    <a class="left carousel-control" href="#carousel-home-page" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-home-page" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>

    <ol class="carousel-indicators">
      <li v-for="(item, index) in slides" :data-target="'#' + id" :data-slide-to="index" :class="{active: index === 0}"></li>
    </ol>
  </div>

</template>

<script>
export default {
  props: ['id', 'slides']
}
</script>
