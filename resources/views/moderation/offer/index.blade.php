@extends("template")

@section("title", "All Offers")

@section("content")
    @include("moderation.components.header")

    <div class="container">
        <div class="table table-responsive">
            <table class="table">
                <tr>
                    <th>Category</th>
                    <th>Visible until</th>
                    <th>Reviewed</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th>Reports</th>
                    <th>Action</th>
                </tr>

                @foreach($offers as $offer)
                    <tr>
                        <td>{{$offer->category->name}}</td>
                        <td>{{$offer->visible_until->format("d.m.Y")}}</td>
                        @if($offer->reviewed === null)
                            <td>
                                <span class="badge bg-warning">no</span>
                            </td>
                        @else
                            <td>
                                <span class="badge bg-success">yes - {{$offer->reviewed->format("d.m.Y, H:i")}}</span>
                            </td>
                        @endif
                        <td>{{$offer->created_at->format("d.m.Y, H:i")}}</td>
                        <td>{{$offer->updated_at->format("d.m.Y, H:i")}}</td>
                        <td>{{count($offer->reports)}}</td>
                        <td>
                            <a href="#">Edit</a>
                            <a href="#">Show Reports</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
