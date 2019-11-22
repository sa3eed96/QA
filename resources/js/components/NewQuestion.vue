<template>
    <form @submit.prevent="createQuestion">
        <div class="form-group">
            <label for="title">Question Title</label>
            <input type="text" id="title" v-model="title" class="form-control" required>
        </div>
        <div class="form-row mb-1">
            <div class="col">
                <input type="text" v-model="tagsInput" class="form-control" placeholder="add tag">
            </div>
            <div class="col">
                <button class="btn btn-outline-success" type="button" @click="addTags">add</button>
            </div>
        </div>
        <div class="d-flex mb-2">
            <div v-for="tag in this.tags" v-bind:key="tag" class="ml-1 rounded p-1 bg-info">
                    {{tag}}
            </div>
        </div>
        <div class="form-group">
            <label for="body">What is the Question</label>
            <textarea name="body" id="body" v-model="body" class="form-control" rows="10"></textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-lg btn-outline-primary">Create</button>
        </div> 
    </form>
</template>

<script>
export default {
    data(){
        return{
            title: '',
            body:'',
            tags: [],
            tagsInput: ''
        };
    },
    methods: {
        createQuestion(){
            axios.post(`/question`,{
                body: this.body,
                title: this.title,
                tags: this.tags.join()
            }).then(({data})=>{
                window.location.href = `/question/${data.slug}`;
            });
        },
        addTags(){
            const tag = this.tagsInput.toLowerCase();
            if(this.tags.indexOf(tag) === -1){
                this.tags.push(tag);
            }
            this.tagsInput = '';
        }
    }
}
</script>