@csrf
<div class="form-group">
    <label for="title">Question Title</label>
    <input type="text" name="title" id="title" value="{{old('title', $question->title)}}" class="form-control {{$errors->has('title')? 'is-invalid':''}}">
    @if($errors->has('title'))
        <div class="invalid-feedback">
            <strong>{{$errors->first('title')}}</strong>
        </div>
    @endif
</div>
<div class="form-group">
    <label for="body">What is the Question</label>
    <textarea name="body" id="body" class="form-control {{$errors->has('body')? 'is-invalid':''}}" rows="10">{{old('body', $question->body)}}</textarea>
    @if($errors->has('body'))
        <div class="invalid-feedback">
            <strong>{{$errors->first('body')}}</strong>
        </div>
    @endif
</div>
<div class="form-group">
    <button type="submit" class="btn btn-lg btn-outline-primary">{{$buttonText  }}</button>
</div>