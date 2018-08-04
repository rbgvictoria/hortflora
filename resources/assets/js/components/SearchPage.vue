<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1>Search</h1>
            </div> <!-- /.col- -->

            <div class="col-md-4" id="left-side-panel">
                <search-form></search-form>
                <filter-queries></filter-queries>
                <facet-fields></facet-fields>
            </div> <!-- /.col-md-8 -->

            <div class="col-md-8" id="right-main-panel">
                <search-result></search-result>
            </div>

        </div> <!-- /.row -->
    </div> <!-- /.container -->


</template>

<script>
    import SearchForm from './SearchForm';
    import FilterQueries from './FilterQueries';
    import FacetFields from './FacetFields';
    import SearchResult from './SearchResult';

    export default {
        methods: {
            openModal() {
                this.$refs.imagemodal.modalOpen = true;
            }
        },
        components: {
            SearchForm,
            FilterQueries,
            FacetFields,
            SearchResult
        }
    }
</script>
