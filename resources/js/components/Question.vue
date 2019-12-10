<template>
    <div class="col-sm-12 col-md-10 justify-content-center">
        <div class="card">
            <form v-if="editing" class="card-body" @submit.prevent="update">
                <div class="card-title">
                    <input type="text" class="form-control form-control-lg" v-model="title">
                </div>
                <hr>

                <div class="media">
                    <div class="media-body">
                        <div class="form-row mb-1">
                            <div class="col">
                                <input type="text" v-model="tagsInput" class="form-control" placeholder="add tag">
                            </div>
                            <div class="col">
                                <button class="btn btn-outline-success" type="button" @click="addTags">add</button>
                            </div>
                        </div>
                        <div class="d-block mb-2" v-if="this.tags.length > 0 || this.newTags.length > 0">
                            Click to Remove
                        </div>
                        <div class="d-flex mb-4">
                            <div class="d-block">
                                <div class="tag-edit mb-1 ml-1 rounded p-1 bg-info" v-for="(tag, index) in this.tags" v-bind:key="tag.id" @click="removeTag(index,tag)" title="remove">
                                    {{tag.tag}}
                                </div>
                                <div class="tag-edit mb-1 ml-1 rounded p-1 bg-warning" v-for="(tag, index) in this.newTags" v-bind:key="index" @click="removeNewTag(index)" title="remove">
                                    {{tag}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea v-model="body" class="form-control" rows="10" required></textarea>
                        </div>
                        <div class="thumbnails">
                            <p v-if="images.length > 0"> Click an Image to Remove</p>
                            <img v-for="(img,index) in images" v-bind:key="index" :src="img.image" @click="removeImage(index,img)" class="img-edit rounded float-left img-thumbnail mr-1" title="Remove">
                        </div>
                        <div class="d-block">
                            <br>
                            <br>
                            <br>
                        </div>
                        <button class="btn btn-primary" type="submit">Update</button>
                        <button class="btn btn-outline-secondary" type="button" @click="cancelEdit()">Cancel</button>
                    </div>
                </div>
            </form>
            <div v-else class="card-body">
                <div class="card-title">
                    <div class="d-flex align-items-center">
                        <h1> {{ title }} </h1>
                        <div class="ml-auto">
                            <a class="btn btn-outline-secondary" href="/question">Back</a>
                        </div>
                    </div>
                    <div class="d-flex">
                            <div class="ml-1 rounded p-1 bg-info" v-for="tag in this.tags" v-bind:key="tag.id">
                                {{tag.tag}}
                            </div>
                    </div>
                </div>
                <hr>

                <div class="media">
                    <div class="d-felx flex-column vote-controls">
                        <vote :model="question" name="questions"></vote>
                        <favourite :question="question"></favourite>
                    </div>
                    <div class="media-body">
                        <div v-html="bodyHtml"></div>
                        <div class="text-center mb-4">
                            <img v-for="(img,index) in images" v-bind:key="index" :src="img.image" class="img-fluid mb-4">
                        </div>
                        <div class="ml-auto">
                            <a id="questionEditButton" v-if="authorize('modify', question)" @click.prevent="edit()" class="btn btn-sm btn-outline-info">Edit</a>
                            <button v-if="authorize('modify', question)" @click="destroy" class="btn btn-outline-danger btn-sm">Delete</button>
                        </div>
                        <div class="float-right">
                            <user-info :model="question" label="Asked"></user-info>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['question'],
    data(){
        return{
            title: this.question.title,
            body: this.question.body,
            tags: this.question.tags,
            bodyHtml: this.question.body_html,
            editing: false,
            id: this.question.id,
            beforeEdit: {},
            images: [],
            removedTags: [],
            removedImages: [],
            tagsInput: '',
            newTags:[]
        };
    },
    created(){
        axios.get(`/question/${this.question.id}/images`).then(({data}) => {
            data.images.forEach(img =>{
                this.images.push({id:img.id,image:`data:image\jpeg;base64, ${img.image}`});
            })
        });
    },
    methods:{
        edit(){
            this.beforeEdit = {
                title: this.title,
                body: this.body
            },
            this.editing=true;
        },
        update(){
            axios.put(`/question/${this.id}`,{
                title: this.title,
                body: this.body,
                removedTags: this.removedTags.length > 0 ? this.removedTags.map(tag => tag.id) : null,
                removedImages: this.removedImages.length > 0 ? this.removedImages.map(img => img.id) : null,
                newTags: this.newTags.length > 0 ? this.newTags : null
            }).then(({data}) =>{
                this.bodyHtml = data.body_html;
                this.$toast.success(data.message,'Success',{timeout: 3000});
                this.tags = data.tags;
                this.removedTags = [];
                this.newTags = [];
                this.editing=false;
            }).catch(({response})=>{
                this.$toast.error(response.data.message,'Error',{timeout: 3000});
            });
        },
        destroy(){
            if(confirm('Are You Sure?')){
                axios.delete(`/question/${this.id}`).then(({data})=>{
                    this.$toast.success(data.message, 'Success',{timeout: 2000});
                    setTimeout(()=>window.location.href = "/question",3000); 
                }).catch(err=>{
                this.$toast.error(err.response.data.message, 'Error',{timeout: 3000}); 
                })  ;
            }
        },
        removeTag(index, tag){
            this.removedTags.push(tag);
            this.tags.splice(index, 1);
        },
        removeImage(index, image){
            this.removedImages.push(image);
            this.images.splice(index, 1);
        },
        cancelEdit(){
            this.title = this.beforeEdit.title;
            this.body = this.beforeEdit.body;
            this.editing = false;
            this.removedTags.forEach(tag => this.tags.push(tag));
            this.removedTags = [];
            this.removedImages.forEach(img => this.images.push(img));
            this.removedImages = [];
            this.newTags = [];
        },
        addTags(){
            const tags = this.tagsInput.toLowerCase().split(' ');
            tags.forEach(tag => {
                if(this.tags.indexOf(tag) === -1 && this.newTags.indexOf(tag) === -1 && !tag.match(/[!_()\[\]\/\\\|^$\*\.@#]/) && tag.length > 1)
                    this.newTags.push(tag);
            });
            this.tagsInput = '';
        },
        removeNewTag(index){
            this.newTags.splice(index, 1);
        }
    }
}
</script>


<style scoped>

.img-edit{
  height:75px;  
  width:75px;
}

.img-edit:hover{
  height:85px;  
  width:85px;
  cursor:pointer;
}

.tag-edit{
    cursor: pointer;
}

#questionEditButton{
    cursor: pointer;
}

</style>