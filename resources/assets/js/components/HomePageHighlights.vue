<template>
  <div class="highlights">
    <h2>Highlights</h2>
    <div v-for="highlight in highlights" class="highlight">
      <div class="highlight-image">
        <router-link :to="{ name: 'taxa', params: { taxon: highlight.taxon } }">
          <img :src="'/images/home/' + highlight.icon" alt="" class="img-responsive"/>
        </router-link>
      </div>
      <div class="highlight-text">
        <router-link :to="{ name: 'taxa', params: { taxon: highlight.taxon } }">
          <i>{{ highlight.name }}</i>
        </router-link>{{ highlight.text }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['highlights']
}
</script>
