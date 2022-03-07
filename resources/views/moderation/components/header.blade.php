<div class="container mt-2">
    <h5>Moderationtool</h5>
    <div class="btn-group btn-group-sm gx-2" role="group">
        <a href="{{route("home")}}" target="_blank" class="btn btn-success">Homepage</a>
        <a href="{{route("map")}}" target="_blank" class="btn btn-success">Public Map</a>
        <a href="{{route("moderation-all-offers")}}" class="btn btn-primary">All Offers</a>
    </div>
    <form class="mt-1" method="post">
        @csrf
        <button class="btn btn-secondary btn-sm">Logout</button>
    </form>
    <hr>
</div>
