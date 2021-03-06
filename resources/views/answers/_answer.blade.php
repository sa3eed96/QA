<answer :answer="{{ $answer }}" inline-template>
    <div class="media post">
        <div class="d-felx flex-column vote-controls">
            <vote :model="{{$answer}}" name="answers"></vote>
            <accept :answer="{{$answer}}"></accept>
        </div>
        <div class="media-body">
            <form v-if="editing" @submit.prevent="update">
                <div class="form-group">
                    <textarea v-model="body" class="form-control" rows="10" required></textarea>
                </div>
                <button class="btn btn-primary" @click="editing=false">Update</button>
                <button class="btn btn-outline-secondary" type="button" @click="editing=false">Cancel</button>
            </form>
            <div v-else>
                <div v-html="bodyHtml"></div>
                <div class="row">
                    <div class="col-4">
                        <div class="ml-auto">
                            @can('update', $answer)
                                <a @click.prevent="editing=true" class="btn btn-sm btn-outline-info">Edit</a>
                            @endcan
                            @can ('delete', $answer)
                            <button @click="destroy" class="btn btn-outline-danger btn-sm">Delete</button>
                            @endcan
                        </div>
                    </div>
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div class="float-right">
                            <user-info :model = "{{ $answer }}" label="Answerd"></user-info>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</answer>