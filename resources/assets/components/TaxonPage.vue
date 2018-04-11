<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {{ taxon.name.scientificName + " " + taxon.name.scientificNameAuthorship }}
            </div>
        </div>
    </div>
</template>

<script>
    let taxon_id = window.hortflora_taxon_id;

    export default {
        data() {
            return {
                taxon: {},
                taxon_id: taxon_id
            };
        },
        mounted() {
            this.getTaxon();
        },
        methods: {
            getTaxon() {
                axios.get('/api/taxa/' + taxon_id).then(
                    response => {
                        this.taxon = response.data;
                    }
                );
            }
        }
    }
</script>
