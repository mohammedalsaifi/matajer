@if($errors->all())
<div class="alert alert-danger">
    <h3>Error Occured!</h3>
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@enderror

<div class="mb-3">
    <x-form.input label="Category Name" name="name" class="form-control" :value="$category->name" />
</div>
<div class="mb-3">
    <label class="form-label">Category Parent</label>
    <select name="parent_id" class="form-control">
        <option value="">Primary Category</option>
        @foreach($parents as $parent)
        <option value="{{$parent->id}}" class="form-control" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{$parent->name}}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Category Description</label>
    <textarea name="description" class="form-control" placeholder="Category Description">{{ old('description', $category->description) }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Category Image</label>
    @if ($category->image)
    <br>
    <img src="{{ asset('storage/' . $category->image) }}" alt="YET!" height="70px">
    <br>
    <br>
    @endif
    <input type="file" name="image" class="form-control" accept="image/*">
</div>
<div class="mb-3">
    <label class="form-label">Category Status</label>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="status" value="active" @checked(old('status', $category->status) == 'active')>
        <label class="form-check-label">
            Active
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="status" value="inactive" @checked(old('status', $category->status) == 'inactive')>
        <label class="form-check-label">
            Inactive
        </label>
    </div>
</div>
<div>
    <button type="submit" class="btn btn-primary form-control">{{ $button_lable ?? 'Save'}}</button>
</div>
