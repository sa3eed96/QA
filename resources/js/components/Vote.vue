<template>
    <div>
        <a  title="Useful" @click.prevent="voteUp"
            class="vote-up" :class="classes">
            <i class="fas fa-caret-up fa-2x"></i>
        </a>
        <span class="votes-count">{{count}}</span>
        <a  title="not useful" @click.prevent="voteDown"
            class="vote-up" :class="classes">
            <i class="fas fa-caret-down fa-2x"></i>
        </a>
    </div>
</template>

<script>
export default {
    props: ['name', 'model'],
    computed:{
        classes(){
            return this.signedIn? '' : 'off'
        }
    },
    data(){
        return{
            count: this.model.votes_count || 0,
            id: this.model.id
        };
    },
    methods: {
        voteUp(){
            this._vote(1);
        },
        voteDown(){
            this._vote(-1);
        },
        _vote(vote){
            if(!this.signedIn){
                this.$toast.warning('Please Login or Register','Error',{
                    timeout: 3000,
                    position: 'bottomLeft'
                });
                return;
            }
            axios.post(`/${this.name}/${this.id}/vote`, { vote }).then(res=>{
                this.$toast.success(res.data.message, "Success",{
                    timeout: 3000,
                    position: 'bottomLeft'
                });
                this.count = res.data.votesCount;
            });
        }
    }
}
</script>