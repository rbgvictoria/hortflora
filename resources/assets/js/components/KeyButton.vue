<template>
  <div>
    <router-link :to="{ name: 'keys', params: { key: tokey.id } }" class="btn btn-primary">
      {{ tokey.title }}
    </router-link>
  </div>
</template>

<script>
export default {
  props: ['tokey']
}
</script>
