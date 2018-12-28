<template>
    <li v-if="children.length">
        <select
            name="children"
            id="nav-childrens"
            class="form-control input-sm"
            v-model="selected"
            @change="changeRoute"
        >
            <option value="select-child">Select child...</option>
            <option
                v-for="option in children"
                :key="option.id"
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
                selected: 'select-child'
            }
        },
        computed: {
            children() {
                return this.$store.state.taxon.children ? this.$store.state.taxon.children.data : [];
            }
        },
        methods: {
            changeRoute() {
                this.$router.push({name: 'taxa', params: {taxon: this.selected}});
            },
            getInitialValue() {
                return 'select-child'
            }
        },
        watch: {
            '$route.params.taxon'() {
                this.selected = this.getInitialValue()
            }
        }
    }
</script>
