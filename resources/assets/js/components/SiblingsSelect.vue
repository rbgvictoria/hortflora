<template>
    <li v-if="siblings.length">
        <select
            name="siblings"
            id="nav-siblings"
            class="form-control input-sm"
            v-model="selected"
            @change="changeRoute"
        >
            <option
                v-for="option in siblings"
                :value="option.id"
            >
                {{ option.name.scientificName }}
            </option>
        </select>
    </li>
</template>

<script>
    export default {
        data() {
            return {
                selected: this.$route.params.taxon
            }
        },
        computed: {
            siblings() {
                return this.$store.state.taxon.siblings.data;
            }
        },
        methods: {
            changeRoute() {
                this.$router.push({name: 'taxa', params: {taxon: this.selected}});
            }
        },
        watch: {
            '$route.params.taxon'() {
                this.selected = this.$route.params.taxon
            }
        }
    }
</script>
