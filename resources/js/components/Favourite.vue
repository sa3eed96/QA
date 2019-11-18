<template>
    <a  title="Bookmark" 
        :class="classes" @click.prevent="toggle">
        <i class="fas fa-star fa-lg"></i>
        <span class="favourite-count">{{ count }}</span>
    </a>
</template>

<script>
export default {
    props: ['question'],
    data(){
        return {
            isFavourited: this.question.is_favourited,
            count: this.question.favourites_count,
            id: this.question.id
        };
    },
    computed: {
        classes(){
            return[
                'favourite', 'mt-2',
                !this.signedIn ? 'off': (this.isFavourited ? 'favourited':  '')
            ];
        }
    },
    methods: {
        toggle(){
            if(!this.signedIn){
                this.$toast.warning("Please Login or Register","Error",{
                    timeout: 3000,
                    position: 'bottomLeft'
                });
                return;
            }
            this.isFavourited? this.destroy() : this.create();
        },
        destroy(){
            axios.delete(`/questions/${this.id}/favourites`).then(res=>{
                this.count--;
                this.isFavourited = false;
            }).catch(err=>{});
        },
        create(){
            axios.post(`/questions/${this.id}/favourites`).then(res=>{
                this.count++;
                this.isFavourited = true;
            }).catch(err=>{});
        }
    }
}
</script>