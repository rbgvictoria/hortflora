<template>
    <div class="section classification">
        <div v-if="classification" class="classification ancestors">
            <div v-for="item in classification">
                <span class="taxon-rank">{{ item.rank }}</span>
                <span v-html="item.spacer"></span>
                <router-link v-if="item.rank !== 'Life'" :to="{ name: 'taxa', params: { taxon: item.id } }">
                    <span class="currentname" v-html="item.formattedName"></span>
                </router-link>
                <span v-else class="currentname" v-html="item.formattedName"></span>
            </div>
        </div>
        <div v-if="classification" class="cl-separator-higher">
            <span class="glyphicon glyphicon-triangle-top"></span>
            Higher taxa
        </div>
        <div class="section currentname">
            <div>
                <span class="taxon-rank">{{ current.rank }}</span>
                <span v-html="current.spacer"></span>
                <router-link :to="{ name: 'taxa', params: { taxon: current.id } }">
                    <span class="currentname" v-html="current.formattedName"></span>
                </router-link>
            </div>
        </div>
        <div v-if="children" class="cl-separator-subordinate">
            <span class="glyphicon glyphicon-triangle-bottom"></span>
            Subordinate taxa
        </div>
        <div v-if="children" class="classification children">
            <div v-for="item in children">
                <span class="taxon-rank">{{ item.rank }}</span>
                <span v-html="item.spacer"></span>
                <router-link :to="{ name: 'taxa', params: { taxon: item.id } }">
                    <span class="currentname" v-html="item.formattedName"></span>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import { formatName } from '../helpers/name_helper';

    export default {
        name: 'ClassificationTab',
        computed: {
            classification() {
                let cl = this.$store.state.taxon.classification.data;
                return cl.map((item, index) => {
                    let indent = '<span class="indent"></span>';
                    return {
                        id: item.id,
                        rank: item.taxonRank.label,
                        formattedName: formatName(item, true),
                        spacer: indent.repeat(index)
                    };
                });
            },
            current() {
                let taxon = this.$store.state.taxon;
                let indent = '<span class="indent"></span>';
                return {
                    id: taxon.id,
                    rank: taxon.taxonRank.label,
                    formattedName: formatName(taxon, true),
                    spacer: indent.repeat(this.classification.length)
                };
            },
            children() {
                if (typeof this.$store.state.taxon.children === 'undefined') {
                    return false;
                }
                let ch = this.$store.state.taxon.children.data;
                return ch.map((item, index) => {
                    let indent = '<span class="indent"></span>';
                    return {
                        id: item.id,
                        rank: item.taxonRank.label,
                        formattedName: formatName(item, true),
                        spacer: indent.repeat(this.classification.length + 1)
                    };
                });

            }
        }
    }
</script>
