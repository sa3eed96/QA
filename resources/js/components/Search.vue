<template>
    <form class="form-inline" @submit.prevent="search">
        <input class="form-control mr-sm-2" v-model="query" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" >Search</button>
    </form>
</template>

<script>
export default {
    data(){
        return{
            query: ' '
        };
    },
    methods:{
        search(){
            window.location.href = `/question?${this.buildQuery()}`;
        },
        buildQuery(){
            let constructedQuery = '';
            const lowerCaseQuery = this.query.toLowerCase();
            const tags = lowerCaseQuery.match(/(?<=\[)[a-z]+(?=\])/gmi);
            if(tags)
                constructedQuery += `tags=${tags.join()}&`;
            const sentence = lowerCaseQuery.match(/(?<=( |\]|))[a-z]+(?=( |\[|))/gmi);
            if(sentence)
                constructedQuery+= `sentence=${sentence.join(' ')}`;
            return constructedQuery;
        }
    }
}
</script>