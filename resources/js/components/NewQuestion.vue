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
        <upload-image @imagesAdded="addImages"></upload-image>
        <br>
        <div class="form-group">
            <button class="btn btn-lg btn-outline-primary">Create</button>
        </div> 
    </form>
</template>

<script>
import UploadImage from './UploadImage.vue';
export default {
    data(){
        return{
            title: '',
            body:'',
            tags: [],
            tagsInput: '',
            images: null
        };
    },
    methods: {
        createQuestion(){
            const formData = new FormData();
            formData.append('body', this.body);
            formData.append('title', this.title);
            formData.append('tags', this.tags.join());
            if(this.images){
                for(let i=0; i< this.images.length;++i){
                    formData.append(`${i}`, this.images[i]);
                }
            }
            console.log(formData.get('title'));
            axios.post(`/question`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
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
        },
        addImages(images){
            this.images = images;
        }
    },
    components: { UploadImage }
}
</script>