<template>
    <div class="mt-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h3>Add Answer</h3>
                </div>
                <hr>
                <form @submit.prevent="createAnswer">
                    <div class="form-group">
                        <textarea class="form-control" name="body" rows="7" v-model="body" required></textarea>
                    </div>
                    <upload-image @imagesAdded="addImages"></upload-image>
                    <br>
                    <div class="form-group">
                        <button type="submit" :disabled="isInvalid" class="btn btn-lg btn-outline-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import UploadImage from './UploadImage.vue';
export default {
    props: ['questionId'],
    data(){
        return{
            body: '',
            images: null
        };
    },
    methods: {
        createAnswer(){
            const formData = new FormData();
            formData.append('body', this.body);
            if(this.images){
                for(let i=0; i< this.images.length;++i){
                    formData.append(`${i}`, this.images[i]);
                }
            }
            axios.post(`/question/${this.questionId}/answer`, formData,{
                 headers: {
                        'Content-Type': 'multipart/form-data'
                    }
            }).then(({data}) =>{
                this.$toast.success(data.message,'Success');
                this.body = '';
                this.images = null;
                this.$emit('answerCreated', data.answer);
            }).catch(err => {
                this.$toast.error(err.response.data.message,'Error');
            });
        },
        addImages(images){
            this.images = images;
        }
    },
    computed: {
        isInvalid(){
            return !this.signedIn || this.body.length < 10;
        }
    },
    components: { UploadImage }
}
</script>