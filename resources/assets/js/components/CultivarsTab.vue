<template>
    <div class="section cultivars">
        <div class="cultivar-group">
            <div v-for="cultivar in cultivars">
                <p class="currentname italics" v-html="cultivar.formattedName"></p>
                <div class="description" v-html="cultivar.description"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import { formatName } from '../helpers/name_helper';

    export default {
        name: 'CultivarsTab',
        computed: {
            cultivars() {
                if (typeof this.$store.state.taxon.cultivars === 'undefined') {
                    return false;
                }
                let cult = this.$store.state.taxon.cultivars.data;
                return cult.map((item, index) => {
                    return {
                        formattedName: formatName(item),
                        description: item.currentTreatment.currentVersion.text
                    };
                });
            }
        }
    }
</script>
