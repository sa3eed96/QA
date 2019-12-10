<template>
    <form @submit.prevent="createQuestion">
        <div class="form-group">
            <label for="title">Question Title</label>
            <input type="text" id="title" v-model="title" class="form-control" required>
            <div v-if="errors.title" class="alert alert-danger mb-1">
              {{ errors.title[0] }}
            </div>
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
            <div v-for="(tag, index) in this.tags" v-bind:key="index" id="tag" class="ml-1 rounded p-1 bg-info" title="remove" @click="removeTag(index)">
                    {{tag}}
            </div>
        </div>
        <div class="form-group">
            <label for="body">What is the Question</label>
            <textarea name="body" id="body" v-model="body" class="form-control" rows="10"></textarea>
            <div v-if="errors.body" class="alert alert-danger mb-1">
              {{errors.body[0]}}
            </div>
        </div>
        <upload-image @imagesAdded="addImages"></upload-image>
        <div v-if="errors.images" class="alert alert-danger mb-1">
              {{errors.images[0]}}
        </div>
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
            images: [],
            errors: []
        };
    },
    methods: {
        createQuestion(){
            const formData = new FormData();
            formData.append('body', this.body);
            formData.append('title', this.title);
            formData.append('tags', this.tags.length > 0 ? this.tags : null);
            if(this.images){
                for(let i=0; i< this.images.length;++i){
                    formData.append(`images[${i}]`, this.images[i]);
                }
            }
            axios.post(`/question`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
            }).then(({data})=>{
               window.location.href = `/question/${data.slug}`;
            }).catch(err=>{
                this.errors = err.response.data.errors;
            });
        },
        addTags(){
            const tags = this.tagsInput.toLowerCase().split(' ');
            tags.forEach(tag => {
                if(this.tags.indexOf(tag) === -1 && !tag.match(/[!_()\[\]\/\\\|^$\*\.@#]/) && tag.length > 1)
                    this.tags.push(tag);
            });
            this.tagsInput = '';
        },
        addImages(images){
            this.images = images;
        },
        removeTag(index){
            this.tags.splice(index,1);
        }
    },
    components: { UploadImage }
}
</script>

<style scoped>
    #tag{
        cursor: pointer;
    }
</style>