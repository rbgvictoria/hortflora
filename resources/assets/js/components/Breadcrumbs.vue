<template>
    <ol class="breadcrumb pull-left" v-if="breadcrumbs">
        <li v-for="crumb in breadcrumbs">
            <router-link
                :to="{name: 'taxa', params: {taxon: crumb.id}}"
                v-html="crumb.name.scientificName"
            ></router-link>
        </li>
        <siblings-select></siblings-select>
        <children-select></children-select>
    </ol>
</template>

<script>
    import SiblingsSelect from './SiblingsSelect.vue';
    import ChildrenSelect from './ChildrenSelect.vue';

    const displayRanks = [
        'kingdom',
        'phylum',
        'class',
        'order',
        'family',
        'genus',
        'species'
    ];

    export default {
        computed: {
            breadcrumbs() {
                const classification = this.$store.state.taxon.classification.data;
                return classification.filter((item) => {
                    return displayRanks.indexOf(item.taxonRank.name) > -1;
                });
            }
        },
        components: {
            SiblingsSelect,
            ChildrenSelect
        }
    }
</script>

<style>
    .breadcrumb>li>a {
        font-weight: normal;
    }
</style>
