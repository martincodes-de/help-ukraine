@extends("template")

@section("title", "Edit offer")

@section("content")
    @include("moderation.components.header")

    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-8">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <b>Action failed!</b>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session("success"))
                    <div class="alert alert-success">
                        {{session("success")}}
                    </div>
                @endif

                <form method="post">
                    @csrf
                    @method("PATCH")
                    <div class="mb-2">
                        @isset($offer->reviewed)
                            <span class="badge bg-success">Reviewed {{$offer->reviewed->format("d.m.Y, H:i")}} (public)</span>
                        @else
                            <span class="badge bg-dark">Not reviewed (not public)</span>
                        @endisset
                    </div>
                    <div class="mb-2">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="{{$offer->name}}" class="form-control" required>
                    </div>

                    <!-- Only for the map view -->
                    <div class="d-none">
                        <div class="mb-2">
                            <label for="lat">Lat</label>
                            <input type="text" id="lat" name="lat" value="{{$offer->lat}}" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label for="lng">Lng</label>
                            <input type="text" id="lng" name="lng" value="{{$offer->lng}}" class="form-control">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="offer_category_id">Category</label>
                        <select id="offer_category_id" name="offer_category_id" class="form-select" required>
                            <option value="{{$offer->category->id}}">current: {{$offer->category->name}}</option>
                            @foreach($offerCategories as $category)
                                <option value="{{$category->id}}">change to: {{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="description">Category</label>
                        <textarea id="description" name="description"
                                  class="form-control" required>{{$offer->description}}</textarea>
                    </div>
                    <div class="mb-2">
                        <label for="contact">Contact</label>
                        <textarea id="contact" name="contact"
                                  class="form-control" required>{{$offer->contact}}</textarea>
                    </div>
                    <div class="mb-2">
                        <label for="visible_until">Visible until</label>
                        <input type="date" id="visible_until" name="visible_until"
                               class="form-control" value="{{$offer->visible_until->format("Y-m-d")}}" required />
                    </div>
                    <hr>
                    <div class="mb-2">
                        <label for="moderation_notice">Moderation Notice</label>
                        <textarea id="moderation_notice" name="moderation_notice"
                                  class="form-control">{{$offer->moderation_notice}}</textarea>
                    </div>
                    <div class="mb-2">
                        <label for="action">Action</label>
                        <select id="action" name="action" class="form-select" required>
                            <option selected disabled>Choose action</option>
                            <option value="update">Update</option>
                            <option value="toggleReviewedStatus">Toggle reviewed status</option>
                        </select>
                        <button class="mt-1 btn btn-secondary">Perform selected action</button>
                    </div>
                </form>
            </div>

            <div class="col-sm-12 col-md-4">
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
                      integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
                      crossorigin=""/>
                <moderation-map></moderation-map>
            </div>
        </div>
    </div>
@endsection
