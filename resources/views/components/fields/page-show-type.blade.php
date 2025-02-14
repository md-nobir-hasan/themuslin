@php  $value = ($value)?? ''; @endphp
<div class="form-group">
    <label for="{{$name}}">{{$title}}</label>
    <select name="{{$name}}"  class="form-control">
        <option @if($value === 'everyone') selected @endif value="everyone">{{__('Everyone')}}</option>
        <option @if($value === 'user') selected @endif value="user">{{__('User')}}</option>
    </select>
</div>