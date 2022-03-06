@extends("template")

@section("title", "Edit offer")

@section("content")
    @include("moderation.components.header")

    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-8">
                <dl>
                    <dt>Name</dt>
                    <dd>{{$offer->name}}</dd>

                    <dt>Category</dt>
                    <dd>{{$offer->category->name}}</dd>

                    <dt>Description</dt>
                    <dd>{{$offer->description}}</dd>

                    <dt>Contact</dt>
                    <dd>{{$offer->contact}}</dd>

                    <dt>Visible until</dt>
                    <dd>{{$offer->visible_until->format("d.m.Y")}}</dd>
                </dl>
                <hr>
                <b>Action</b>
                <p class="text-info">Info: If you want to edit, please create a new offer.</p>
                <form>
                    @csrf
                    @method("PATCH")

                    <select class="form-select">

                    </select>
                </form>
            </div>
        </div>
    </div>
@endsection
