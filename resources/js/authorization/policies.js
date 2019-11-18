export default{
    modify(user, model){
        return model.user.id === user.id;
    },

    accept(user, answer){
        return user.id === answer.question.user_id;
    },
}