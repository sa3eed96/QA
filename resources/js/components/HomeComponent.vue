<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Popular Tags</div>
                    <div class="card-body">
                        <a v-for="(tag,index) in tags" v-bind:key="index" :href="getUrl(index)" class="text-dark btn btn-outline-primary m-1 p-1">
                            {{tag.tag}}
                            <div class="font-weight-light d-inline">
                                x{{tag.count}}
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            tags: []
        };
    },
    created(){
        axios.get('/tags').then(({ data })=>{
            this.tags.push(...data.tags);
        });
    },
    methods:{
        getUrl(index){
            return `/question?tags=${this.tags[index].tag}`;
        }
    }
}
</script>