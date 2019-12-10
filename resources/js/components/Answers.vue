<template>
    <div class="col-sm-12 col-md-10 offset-md-2 ">
        <h2 class="mt-2">{{ title }}</h2>
        <div class="mt-4" v-if="userAnswers.length > 0">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h2>Your Answers</h2>
                    </div>
                    <hr>
                    <answer @deleted="removeUserAnswer(index)" v-for="(answer, index) in userAnswers" :answer="answer" :key="answer.id"></answer>
                </div>
            </div>
        </div>

        <div class="mt-4" v-if="answers.length > 0">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h2>Answers</h2>
                    </div>
                    <hr>
                    <answer @deleted="remove(index)" v-for="(answer, index) in answers" :answer="answer" :key="answer.id"></answer>
                    <div v-if="nextUrl" class="text-center mt-3">
                        <button @click.prevent="fetch(nextUrl)" class="btn btn-outline-secondary">Load more answers</button>
                    </div>
                </div>
            </div>
        </div>
        <new-answer v-if="signedIn" :question-id="question.id" @answerCreated="addAnswer"></new-answer>
    </div>
</template>

<script>
import Answer from './Answer.vue';
import NewAnswer from './NewAnswer.vue';
export default {
    props: ['question'],
    data(){
        return {
            questionId: this.question.id,
            count: this.question.answers_count,
            answers: [],
            userAnswers: [],
            nextUrl: null,
            temp: []
        };
    },
    created(){
        this.fetch(`/question/${this.questionId}/answer`);
    },
    methods: {
        fetch(uri){
            axios.get(uri).then(({data}) => {
                this.temp.push(...data.data);
                let best;
                let userAns = [];
                [...data.data].forEach(ans=>{
                    if(this.question.best_answer_id === ans.id){
                        best = ans;
                    }
                    else if(window.Auth.signedIn && ans.user_id === window.Auth.user.id){
                        this.userAnswers.push(ans);
                    }
                    else
                        this.answers.push(ans);
                });
                if(best)
                    this.answers.unshift(best);
                this.nextUrl = data.next_page_url;
            });
        },
        remove(index){
            this.answers.splice(index, 1);
            this.count--;
        },
        removeUserAnswer(index){
            this.userAnswers.splice(index, 1);
            this.count--;
        },
        addAnswer(answer){
             this.userAnswers.push(answer);
             this.count++;
        }
    },
    computed: {
        title(){
            return `${this.count} ${this.count>1? 'Answers': 'Answer'}`;
        }
    },
    components: { Answer,  NewAnswer }
}
</script>